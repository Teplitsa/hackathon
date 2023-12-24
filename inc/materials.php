<?php
/**
 * Materials
 */

/**
 * Register a custom post type material
 */
function hms_register_post_type_material() {
	$labels = array(
		'name'               => __( 'Materials', 'hackathon' ),
		'singular_name'      => __( 'Material', 'hackathon' ),
		'menu_name'          => __( 'Materials', 'hackathon' ),
		'name_admin_bar'     => __( 'Material', 'hackathon' ),
		'add_new'            => __( 'Add New', 'hackathon' ),
		'add_new_item'       => __( 'Add New Material', 'hackathon' ),
		'new_item'           => __( 'New Material', 'hackathon' ),
		'edit_item'          => __( 'Edit Material', 'hackathon' ),
		'view_item'          => __( 'View Material', 'hackathon' ),
		'all_items'          => __( 'All Materials', 'hackathon' ),
		'search_items'       => __( 'Search Material', 'hackathon' ),
		'not_found'          => __( 'No Material found.', 'hackathon' ),
		'not_found_in_trash' => __( 'No materials found in Trash.', 'hackathon' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'hms_material', $args );
}

add_action( 'init', 'hms_register_post_type_material' );

/**
 * Ajax request actions
 */
if ( wp_doing_ajax() ) {
	require_once HMS_PATH . 'inc/ajax/materials.php';
}

/**
 * Matrial types
 */
function hms_get_material_type_name( $type = '' ) {
	if ( ! $type ) {
		return false;
	}
	$material_types = array(
		'initial_presentation' => __( 'Kick-off presentation', 'hackathon' ),
		'checkpoint_report'    => __( 'Checkpoint materials', 'hackathon' ),
		'final_presentation'   => __( 'Final Solution', 'hackathon' ),
	);

	$name = '';
	if ( array_key_exists( $type, $material_types ) ) {
		$name = $material_types[ $type ];
	}

	return $name;
}

/**
 * Get checkpoint name
 */
function hms_get_checkpoint_name( $checkpoint_id = null ) {
	if ( ! $checkpoint_id ) {
		return;
	}
	return get_the_title( $checkpoint_id );
}

/**
 * Get materials
 */
function hms_get_materials( $args = array() ) {
	$defaults = array(
		'post_type'      => 'hms_material',
		'posts_per_page' => -1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );
	$materials   = get_posts( $parsed_args );

	return $materials;
}

/**
 * Materials Card
 */
function hms_card_materials( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_material',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$output = '';

	$query = new WP_Query( $parsed_args );

	if ( $query->have_posts() ) {
		$output .= '<div class="hms-cards">';
		while ( $query->have_posts() ) {
			$query->the_post();

			$team_id = get_post_meta( get_the_ID(), 'team_id', true );
			$user_id = get_post_field( 'post_author', get_the_ID() );
			$type    = get_post_meta( get_the_ID(), 'type', true );

			$output .= '<div class="hms-card">
					<div class="hms-card-content">

						<div class="hms-card-line">
							<div class="hms-card-line-item">
								<div class="hms-card-label">
									' . get_the_date( 'F j, Y - H:i:s' ) . '
								</div>
							</div>
						</div>

						<h4 class="hms-card-title">
							<a href="' . esc_url( hms_get_url( 'material/' . get_the_ID() ) ) . '">' . get_the_title() . '</a>
						</h4>

						<div class="hms-card-line">
							<div class="hms-card-line-item">
								<div class="hms-card-label">
									' . esc_html__( 'Team', 'hackathon' ) . ':
								</div>
								<div class="hms-card-value">
									<a href="' . hms_get_url( 'team/' . $team_id ) . '">' . get_the_title( $team_id ) . '</a>
								</div>
							</div>
							<div class="hms-card-line-item">
								<div class="hms-card-label">
									' . esc_html__( 'Author', 'hackathon' ) . ':
								</div>
								<div class="hms-card-value">
									<a href="' . hms_get_url( 'user/' . $user_id ) . '">' . esc_html( get_user_option( 'user_login', $user_id ) ) . '</a>
								</div>
							</div>
						</div>
					</div>

					<div class="hms-card-info">
						<div class="hms-card-info-item">
							<div class="hms-card-label">
								' . hms_get_material_type_name( $type ) . '
							</div>
						</div>
					</div>
				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No materials', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_card_materials', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}

/**
 * Teams List
 */
function hms_list_materials( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_material',
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

			$team_id = get_post_meta( get_the_ID(), 'team_id', true );
			$user_id = get_post_field( 'post_author', get_the_ID() );
			$type    = get_post_meta( get_the_ID(), 'type', true );

			$output .= '<div class="hms-list-item">
					<div class="hms-list-content">
						<h4 class="hms-list-title">
							<a href="' . esc_url( hms_get_url( 'material/' . get_the_ID() ) ) . '">' . hms_get_material_type_name( $type ) . '</a>
						</h4>

						<div class="hms-list-line">
							<div class="hms-list-line-item">
								<div class="hms-list-label">
									' . esc_html__( 'Team', 'hackathon' ) . ':
								</div>
								<div class="hms-list-value">
									<a href="' . hms_get_url( 'team/' . $team_id ) . '">' . get_the_title( $team_id ) . '</a>
								</div>
							</div>
							<div class="hms-list-line-item">
								<div class="hms-list-label">
									' . esc_html__( 'Author', 'hackathon' ) . ':
								</div>
								<div class="hms-list-value">
									' . esc_html( get_user_option( 'user_login', $user_id ) ) . '
								</div>
							</div>

						</div>
					</div>
				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No materials', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_list_materials', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}
