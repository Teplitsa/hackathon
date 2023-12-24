<?php
/**
 * Messages
 */

/**
 * Register post type message
 */
function hms_register_post_type_message() {
	$labels = array(
		'name'               => __( 'Messages', 'hackathon' ),
		'singular_name'      => __( 'Message', 'hackathon' ),
		'menu_name'          => __( 'Messages', 'hackathon' ),
		'name_admin_bar'     => __( 'Message', 'hackathon' ),
		'add_new'            => __( 'Add New', 'hackathon' ),
		'add_new_item'       => __( 'Add New Message', 'hackathon' ),
		'new_item'           => __( 'New Message', 'hackathon' ),
		'edit_item'          => __( 'Edit Message', 'hackathon' ),
		'view_item'          => __( 'View Message', 'hackathon' ),
		'all_items'          => __( 'All Messages', 'hackathon' ),
		'search_items'       => __( 'Search Messages', 'hackathon' ),
		'not_found'          => __( 'No Message found.', 'hackathon' ),
		'not_found_in_trash' => __( 'No Message found in Trash.', 'hackathon' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => false,
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	);

	register_post_type( 'hms_message', $args );
}
add_action( 'init', 'hms_register_post_type_message' );

/**
 * Ajax message actions
 */
if ( wp_doing_ajax() ) {
	require_once HMS_PATH . 'inc/ajax/insert-message.php';
	require_once HMS_PATH . 'inc/ajax/update-message.php';
}

/**
 * Insert message
 *
 * @example hms_insert_message( 'Title', 'Content', array('mail','message'), array('mentor') );
 */
function hms_insert_message( $title = null, $content = null, $transport = array(), $role = array(), $data = array() ) {

	$post_data = array(
		'post_type'    => 'hms_message',
		'post_title'   => sanitize_text_field( $title ),
		'post_content' => wp_kses_post( $content ),
		'post_status'  => 'publish',
		'meta_input'   => array(
			'_transport' => $transport,
			'_role'      => $role,
		),
	);

	$data = array_merge( $data, $post_data );

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if ( is_wp_error( $post_id ) ) {

		$data['message'] = $post_id->get_error_message();
		ob_start();
			wp_send_json_error( $data );
			$output = ob_get_contents();
		ob_end_clean();

		return $output;
	} else {

		if ( in_array( 'mail', $transport ) ) {

			$emails             = array();
			$mentor_emails      = array();
			$participant_emails = array();
			$jury_emails        = array();

			if ( is_array( $role ) && $role ) {
				if ( in_array( 'mentor', $role ) ) {
					$mentor_emails = hms_get_mentors_email();
				}
				if ( in_array( 'participant', $role ) ) {
					$participant_emails = hms_get_participants_email();
				}
				if ( in_array( 'jury', $role ) ) {
					$jury_emails = hms_get_jury_email();
				}
			}

			$emails = array_merge( $mentor_emails, $participant_emails, $jury_emails );

			if ( $emails ) {
				$to = $emails;

				$subject = esc_html( $title );
				$message = wp_strip_all_tags( $content );

				foreach ( $emails as $i => $to ) {
					wp_mail( $to, $subject, $message );
					if ( $i > 0 && $i % 10 == 0 ) {
						sleep( 0.1 );
					}
				}
			}
		}

		if ( in_array( 'message', $transport ) ) {
			$messages             = get_option( 'hackathon_messages' ) ? get_option( 'hackathon_messages' ) : array();
			$messages[ $post_id ] = $role;
			update_option( 'hackathon_messages', $messages );
		}

		$data['post_id'] = $post_id;
		$data['message'] = esc_html__( 'The message was successfully created', 'hackathon' );
		$data['reload']  = 4000;

		ob_start();
			wp_send_json_success( $data );
			$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}

/**
 * Get messages
 */
function hms_get_messages( $args = array() ) {
	$defaults = array(
		'post_type'      => 'hms_message',
		'posts_per_page' => -1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );
	$teams       = get_posts( $parsed_args );

	return $teams;
}

/**
 * Get messages count
 */
function hms_get_messages_count() {
	$count = hms_get_messages();
	return count( $count );
}

/**
 * Message List
 */
function hms_list_messages( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_message',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$output = '';

	$query = new WP_Query( $parsed_args );

	if ( $query->have_posts() ) {
		$output .= '<div class="hms-list">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$users_count = hms_get_team_users_count( get_the_ID() );

			$output .= '<div class="hms-list-item">
					<div class="hms-list-content">
						<div class="hms-list-line">
							<div class="hms-list-line-item">
								<div class="hms-list-label">
									' . get_the_date( 'F j, Y - H:i:s' ) . '
								</div>
							</div>
						</div>

						<h4 class="hms-list-title">
							<a href="' . esc_url( hms_get_url( 'message/' . get_the_ID() ) ) . '">' . get_the_title() . '</a>
						</h4>

						<div class="hms-list-text">
							' . wpautop( get_the_content() ) . '
						</div>
					</div>
				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No messages', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_list_messages', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}
