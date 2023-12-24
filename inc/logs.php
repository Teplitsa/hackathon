<?php
/**
 * Logs
 */

/**
 * Register a post type log
 */
function hms_register_post_type_log() {
	$labels = array(
		'name'               => __( 'Logs', 'hackathon' ),
		'singular_name'      => __( 'Log', 'hackathon' ),
		'menu_name'          => __( 'Logs', 'hackathon' ),
		'name_admin_bar'     => __( 'Log', 'hackathon' ),
		'add_new'            => __( 'Add Log', 'hackathon' ),
		'add_new_item'       => __( 'Add New Log', 'hackathon' ),
		'new_item'           => __( 'New Log', 'hackathon' ),
		'edit_item'          => __( 'Edit Log', 'hackathon' ),
		'view_item'          => __( 'View Log', 'hackathon' ),
		'all_items'          => __( 'All Logs', 'hackathon' ),
		'search_items'       => __( 'Search Logs', 'hackathon' ),
		'not_found'          => __( 'No Log found.', 'hackathon' ),
		'not_found_in_trash' => __( 'No Log found in Trash.', 'hackathon' ),
	);

	$args = array(
		'labels'       => $labels,
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => false,
		'supports'     => array( 'title', 'editor' ),
		'show_in_rest' => true,
	);

	register_post_type( 'hms_log', $args );
}
add_action( 'init', 'hms_register_post_type_log' );

/**
 * Insert log
 *
 * Log types: user_deleted, change_user_status, team_user_added, team_material_added, deadline
 */
function hms_insert_log( $data = array() ) {

	$title      = isset( $data['title'] ) ? $data['title'] : '';
	$meta_input = isset( $data['meta_input'] ) ? $data['meta_input'] : array();

	$post_data = array(
		'post_type'   => 'hms_log',
		'post_status' => 'publish',
		'post_title'  => sanitize_text_field( $title ),
		'meta_input'  => $meta_input,
	);

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	return $post_id;
}

/**
 * Insert log user_registered.
 */
function hms_insert_log_user_registered( $user_id ) {
	$role       = hms_get_user_role_name( $user_id );
	$user_email = get_user_option( 'user_email', $user_id );
	$user_login = get_user_option( 'user_login', $user_id );
	$first_name = get_user_option( 'first_name', $user_id );
	$last_name  = get_user_option( 'last_name', $user_id );
	$user_name  = $first_name . ' ' . $last_name;
	$log_title  = wp_sprintf( __( 'User registration %s', 'hackathon' ), $user_name );

	$data = array(
		'title'      => $log_title,
		'meta_input' => array(
			'_action'         => 'user_registered',
			'_user_id'        => sanitize_text_field( $user_id ),
			'_user_email'     => sanitize_text_field( $user_email ),
			'_user_name'      => sanitize_text_field( $user_name ),
			'_user_role_name' => sanitize_text_field( $role ),
			'_user_login'     => sanitize_text_field( $user_login ),
		),
	);

	hms_insert_log( $data );
}

/**
 * Insert log user_deleted.
 */
function hms_insert_log_user_deleted( $user_id ) {

	$role       = hms_get_user_role_name( $user_id );
	$user_email = get_user_option( 'user_email', $user_id );
	$user_login = get_user_option( 'user_login', $user_id );
	$first_name = get_user_option( 'first_name', $user_id );
	$last_name  = get_user_option( 'last_name', $user_id );
	$user_name  = trim( $first_name . ' ' . $last_name );
	if ( ! $user_name ) {
		$user_name = $user_login;
	}
	$log_title = wp_sprintf( __( 'The user (%s) was deleted.', 'hackathon' ), $user_name );

	$data = array(
		'title'      => $log_title,
		'meta_input' => array(
			'_action'         => 'user_deleted',
			'_user_id'        => sanitize_text_field( $user_id ),
			'_user_email'     => sanitize_text_field( $user_email ),
			'_user_name'      => sanitize_text_field( $user_name ),
			'_user_role_name' => sanitize_text_field( $role ),
			'_user_login'     => sanitize_text_field( $user_login ),
		),
	);

	hms_insert_log( $data );
}

/**
 * Insert log add material
 */
function hms_insert_log_material( $material_id, $team_id ) {
	$material_name = get_the_title( $material_id );
	$log_title     = wp_sprintf( __( 'The team submitted the form: %s', 'hackathon' ), $material_name );

	$data = array(
		'title'      => $log_title,
		'meta_input' => array(
			'_action'      => 'add_material',
			'_team_id'     => get_the_title( $team_id ),
			'_material_id' => $material_id,
		),
	);

	hms_insert_log( $data );
}

/**
 * Logs List
 */
function hms_list_logs( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_log',
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
			$log_type    = get_post_meta( get_the_ID(), '_action', true );
			$line_bottom = '';

			if ( 'user_registered' === $log_type ) {
				$line_bottom = '<div class="hms-list-line">
						<div class="hms-list-line-item">
							<div class="hms-list-label">
								' . __( 'Role', 'hackathon' ) . ':
							</div>
							<div class="hms-list-value">
								' . esc_html( get_post_meta( get_the_ID(), '_user_role_name', true ) ) . '
							</div>
						</div>
					</div>';
			}

			if ( 'add_material' === $log_type ) {
				$material_id   = get_post_meta( get_the_ID(), '_material_id', true );
				$material_type = get_post_meta( $material_id, 'type', true );
				$material_name = hms_get_material_type_name( $material_type );

				$line_bottom = '<div class="hms-list-line">
						<div class="hms-list-line-item">
							<div class="hms-list-label">
								' . __( 'Team', 'hackathon' ) . ':
							</div>
							<div class="hms-list-value">
								' . esc_html( get_post_meta( get_the_ID(), '_team_id', true ) ) . '
							</div>
						</div>
						<div class="hms-list-line-item">
							<div class="hms-list-label">
								' . __( 'Material type', 'hackathon' ) . ':
							</div>
							<div class="hms-list-value">
								' . esc_html( $material_name ) . '
							</div>
						</div>
					</div>';
			}

			$output .= '<div class="hms-list-item" data-text="' . esc_attr( get_post_meta( get_the_ID(), '_action', true ) ) . '">
					<div class="hms-list-content">
						<div class="hms-list-line">
							<div class="hms-list-line-item">
								<div class="hms-list-label">
									' . get_the_date( 'F j, Y - H:i:s' ) . '
								</div>
							</div>
						</div>

						<h4 class="hms-list-title">
							' . get_the_title() . '
						</h4>

						' . $line_bottom . '

					</div>
				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'Log list is empty', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_list_logs', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}

/**
 * Logs Card
 */
function hms_card_logs( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_log',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$output = '';

	$query = new WP_Query( $parsed_args );

	$output .= '<div class="hms-cards">';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$log_type    = get_post_meta( get_the_ID(), '_action', true );
			$line_bottom = '';

			if ( 'user_registered' === $log_type ) {
				$line_bottom = '<div class="hms-card-line">
					<div class="hms-card-line-item">
						<div class="hms-card-label">
							' . __( 'Role', 'hackathon' ) . ':
						</div>
						<div class="hms-card-value">
							' . get_post_meta( get_the_ID(), '_user_role_name', true ) . '
						</div>
					</div>
				</div>';
			}

			if ( 'add_material' === $log_type ) {
				$material_id   = get_post_meta( get_the_ID(), '_material_id', true );
				$material_type = get_post_meta( $material_id, 'type', true );
				$material_name = hms_get_material_type_name( $material_type );

				$line_bottom = '<div class="hms-card-line">
					<div class="hms-card-line-item">
						<div class="hms-card-label">
							' . __( 'Team', 'hackathon' ) . ':
						</div>
						<div class="hms-card-value">
							' . esc_html( get_post_meta( get_the_ID(), '_team_id', true ) ) . '
						</div>
					</div>
					<div class="hms-card-line-item">
						<div class="hms-card-label">
							' . __( 'Material type', 'hackathon' ) . ':
						</div>
						<div class="hms-card-value">
							' . esc_html( $material_name ) . '
						</div>
					</div>
				</div>';
			}

			$output .= '<div class="hms-card" data-text="' . get_post_meta( get_the_ID(), '_action', true ) . '">
				<div class="hms-card-content">
					<div class="hms-list-line">
						<div class="hms-list-line-item">
							<div class="hms-list-label">
								' . get_the_date( 'F j, Y - H:i:s' ) . '
							</div>
						</div>
					</div>

					<h4 class="hms-card-title">
						' . get_the_title() . '
					</h4>

					' . $line_bottom . '

				</div>

			</div>';
		}
	} else {
		$output .= '<div class="hms-card">' . esc_html__( 'Log list is empty', 'hackathon' ) . '</div>';
	}
	$output .= '</div>';
	wp_reset_postdata();

	$html = apply_filters( 'hms_card_logs', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}
