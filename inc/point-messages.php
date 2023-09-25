<?php
/**
 * Point Messages
 */

/**
 * Register post type point_message
 */
function hms_register_post_type_point_message() {
	$labels = array(
		'name'                  => __( 'Checkpoint Messages', 'hackathon' ),
		'singular_name'         => __( 'Message', 'hackathon' ),
		'menu_name'             => __( 'Messages', 'hackathon' ),
		'name_admin_bar'        => __( 'Message', 'hackathon' ),
		'add_new'               => __( 'Add New', 'hackathon' ),
		'add_new_item'          => __( 'Add New Message', 'hackathon' ),
		'new_item'              => __( 'New Message', 'hackathon' ),
		'edit_item'             => __( 'Edit Message', 'hackathon' ),
		'view_item'             => __( 'View Message', 'hackathon' ),
		'all_items'             => __( 'All Messages', 'hackathon' ),
		'search_items'          => __( 'Search Messages', 'hackathon' ),
		'not_found'             => __( 'No Message found.', 'hackathon' ),
		'not_found_in_trash'    => __( 'No Message found in Trash.', 'hackathon' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => false,
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	);

	register_post_type( 'hms_point_message', $args );
}
add_action( 'init', 'hms_register_post_type_point_message' );

/**
 * Ajax chat message actions
 */
if ( wp_doing_ajax() ){
	require_once HMS_PATH . 'inc/ajax/chat-messages.php';
}

/**
 * Insert message
 * 
 * @example hms_insert_message( 'Title', 'Content', array('mail','message'), array('mentor') );
 */
function hms_insert_point_message( $checkpoint_id = null, $team_id = null, $message = null ){

	$post_data = array(
		'post_type'    => 'hms_point_message',
		'post_title'   => sanitize_text_field( $checkpoint_id ),
		'post_content' => wp_kses_post( $message ),
		'post_status'   => 'publish',
		'meta_input'   => array(
			'_checkpoint' => $checkpoint_id,
			'_team'       => $team_id,
		),
	);

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if ( is_wp_error( $post_id ) ){
		return false;
	} else {
		wp_update_post( array(
			'ID'         => $post_id,
			'post_title' => esc_html__( 'Checkpoint message', 'hackathon' ) . ' - ' .  $post_id,
		) );
		return $post_id;
	}
}

/**
 * Send point email
 */
function hms_insert_point_email( $checkpoint_id = null, $team_id = null, $message = null, $message_id = null ){

	$team_name       = hms_get_team_name( $team_id );
	$checkpoint_name = hms_get_checkpoint_name( $checkpoint_id );
	$subject         = sprintf( __( 'Checkpoint message, team [%s]', 'hackathon' ), $team_name );
	$content         = sprintf( __(
				'Hi,

New message in the team: %1$s
From checkpoint: %2$s

Message:
%3$s

Go to message: %4$s', 'hackathon' ),
			$team_name,
			$checkpoint_name,
			$message,
			esc_url( hms_get_url( 'team/' . $team_id ) . '#msg-' . $message_id )
	);

	$users_ids    = hms_get_team_users( $team_id );
	$users_emails = array( get_option( 'admin_email' ) );

	foreach( $users_ids as $user_id ) {
		$user = get_user_by( 'id', $user_id );
		$users_emails[ $user_id ] = $user->user_email;
	}

	$current_user_id = get_current_user_id();
	unset( $users_emails[ $current_user_id ] );

	$email_sent = wp_mail( $users_emails, $subject, $content );
}

/**
 * Message List
 */
function hms_get_point_messages( $checkpoint_id = null, $team_id = null ) {
	$args = array(
		'post_type'      => 'hms_point_message',
		'posts_per_page' => -1,
		'echo'           => false,
		'order'          => 'asc',
		'meta_query'     => array(
			array(
				'key'     => '_checkpoint',
				'value'   => $checkpoint_id,
				'compare' => '=',
			),
			array(
				'key'     => '_team',
				'value'   => $team_id,
				'compare' => '=',
			),
		),
	);

	$output       = '';

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		$output .= '<div class="hms-chat-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$author_id         = get_post_field( 'post_author', get_the_ID() );
				$current_author_id = get_current_user_id();
				$author_name = get_the_author_meta( 'display_name', $author_id );
				$msg_classes = 'hms-chat-list-item';
				if ( $author_id == $current_author_id ) {
					$msg_classes .= ' hms-chat-list-item-current';
				}
				$output .= '<div class="' . esc_attr( $msg_classes ) . '" id="msg-' . get_the_ID() . '">
					<div class="hms-chat-list-heading">
						<span class="hms-chat-list-author">
						' . $author_name . '
						</span>
						<span> - </span>
						<span>' . esc_html( hms_get_user_role_name( $author_id ) ) . '</span>
					</div>

					<div class="hms-chat-list-text">
						' . wpautop( get_the_content() ) . '
					</div>

					<div class="hms-chat-list-footer">
						<span class="hms-chat-list-date">
							' . get_the_date( 'F j, Y - H:i:s') . '
						</span>
					</div>
				</div>';
			}
		$output .= '</div>';
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_get_point_messages', $output, $args, $query );

	if ( $args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}

/**
 * Message count
 */
function hms_get_point_messages_count( $checkpoint_id = null, $team_id = null ) {
	$args = array(
		'post_type'      => 'hms_point_message',
		'posts_per_page' => -1,
		'order'          => 'asc',
		'meta_query'     => array(
			array(
				'key'     => '_checkpoint',
				'value'   => $checkpoint_id,
				'compare' => '=',
			),
			array(
				'key'     => '_team',
				'value'   => $team_id,
				'compare' => '=',
			),
		),
	);

	$query = new WP_Query( $args );

	$found_posts = $query->found_posts;

	return $found_posts;
}

/**
 * Message ids
 */
function hms_get_point_messages_ids( $checkpoint_id = null, $team_id = null ) {
	$messages_args = array(
		'post_type'      => 'hms_point_message',
		'posts_per_page' => -1,
		'order'          => 'asc',
		'meta_query'     => array(
			array(
				'key'     => '_checkpoint',
				'value'   => $checkpoint_id,
				'compare' => '=',
			),
			array(
				'key'     => '_team',
				'value'   => $team_id,
				'compare' => '=',
			),
		),
	);

	$ids   = array();
	$messages_query = new WP_Query( $messages_args );

	if ( $messages_query->have_posts() ) {
		while ( $messages_query->have_posts() ) {
			$messages_query->the_post();
			$msg_post_id         = get_the_ID();
			$ids[ $msg_post_id ] = $msg_post_id;
		}
	}
	wp_reset_postdata();

	return $ids;
}


/**
 * Message new count
 */
function hms_get_point_new_messages_count( $checkpoint_id = null, $team_id = null ){
	$ids = hms_get_point_messages_ids( $checkpoint_id, $team_id );
	return count( $ids );
}
