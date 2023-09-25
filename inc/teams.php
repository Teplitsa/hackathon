<?php
/**
 * Teams
 */

/**
 * Register a custom post type team
 */
function hms_register_post_type_team() {
	$labels = array(
		'name'                  => __( 'Teams', 'hackathon' ),
		'singular_name'         => __( 'Team', 'hackathon' ),
		'menu_name'             => __( 'Teams', 'hackathon' ),
		'name_admin_bar'        => __( 'Team', 'hackathon' ),
		'add_new'               => __( 'Add New', 'hackathon' ),
		'add_new_item'          => __( 'Add New Team', 'hackathon' ),
		'new_item'              => __( 'New Team', 'hackathon' ),
		'edit_item'             => __( 'Edit Team', 'hackathon' ),
		'view_item'             => __( 'View Team', 'hackathon' ),
		'all_items'             => __( 'All Teams', 'hackathon' ),
		'search_items'          => __( 'Search Teams', 'hackathon' ),
		'not_found'             => __( 'No Team found.', 'hackathon' ),
		'not_found_in_trash'    => __( 'No teams found in Trash.', 'hackathon' ),
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
		'supports'           => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'hms_team', $args );
}
add_action( 'init', 'hms_register_post_type_team' );

/**
 * Get teams
 */
function hms_get_teams( $args = array() ) {
	$defaults = array(
		'post_type'      => 'hms_team',
		'posts_per_page' => -1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );
	$teams       = get_posts( $parsed_args );

	return $teams;
}

/**
 * Get team name
 */
function hms_get_team_name( $team_id = null ) {
	if ( ! $team_id ) {
		return;
	}
	return get_the_title( $team_id );
}

/**
 * Get teams count
 */
function hms_get_teams_count(){
	$count = hms_get_teams();
	return count( $count );
}

function hms_get_team_users( $team_id = null, $role = '' ){
	$users = get_post_meta( $team_id, '_team_users' );
	if ( $role && $users ) {
		foreach( $users as $key => $user ) {
			if ( $role !== hms_get_user_role( $user ) ) {
				unset( $users[ $key ] );
			}
		}
	}
	return $users;
}

function hms_get_team_users_count( $team_id = null, $role = '' ){
	$users = (array) hms_get_team_users( $team_id, $role );
	return count( $users );
}

function hms_add_team_user( $team_id = null, $user_id = null ){
	if ( ! hms_user_exists( $user_id ) ) {
		return;
	}
	$all_team_users = (array) hms_get_team_users( $team_id );
	if ( ! in_array( $user_id, $all_team_users ) ) {
		add_post_meta( $team_id, '_team_users', $user_id );
		return true;
	}
	return false;
}

function hms_remove_team_user( $team_id = null, $user_id = null ){
	if ( ! hms_user_exists( $user_id ) ) {
		return;
	}
	$all_team_users = (array) hms_get_team_users( $team_id );
	if ( in_array( $user_id, $all_team_users ) ) {
		delete_post_meta( $team_id, '_team_users', $user_id );
	}
}

function hms_remove_teams_user( $user_id = null ){
	if ( ! $user_id ) {
		return;
	}
	$teams = hms_get_teams();
	foreach( $teams as $team ) {
		$team_id = $team->ID;
		$all_team_users = (array) hms_get_team_users( $team_id );
		if ( in_array( $user_id, $all_team_users ) ) {
			delete_post_meta( $team_id, '_team_users', $user_id );
		}
	}
}

function hms_get_team_captain( $team_id = '' ){
	if ( ! $team_id ) {
		return false;
	}
	$participants = hms_get_team_users( $team_id, 'hackathon_participant' );
	if ( ! $participants ) {
		return false;
	}
	return (int) current( $participants );
}

function hms_add_team_nonce( $team_id = null ){
	update_post_meta( $team_id, '_team_nonce', 'invitation-' . wp_create_nonce( 'hackathon-' . $team_id ) );
}

function hms_get_team_nonce( $team_id = null ){
	$team_nonce = get_post_meta( $team_id, '_team_nonce', true );
	return $team_nonce;
}

if( wp_doing_ajax() ){
	require_once HMS_PATH . 'inc/ajax/remove-team-user.php';
	require_once HMS_PATH . 'inc/ajax/add-team-user.php';
	require_once HMS_PATH . 'inc/ajax/new-team.php';
	require_once HMS_PATH . 'inc/ajax/update-team.php';
	require_once HMS_PATH . 'inc/ajax/delete-team.php';
}

function hms_on_team_insert( $post_ID ){
	if ( ! get_post_meta( $post_ID, '_team_nonce' ) ) {
		hms_add_team_nonce( $post_ID );
	}
}
add_action( 'save_post_hms_team', 'hms_on_team_insert' );

/**
 * Get team logo
 */
function hms_get_team_logo( $team_id = null ) {
	if ( wp_get_attachment_image( get_post_meta( $team_id, '_team_logo', true ) ) ) {
		$img = '<img src="' . esc_url( wp_get_attachment_image_url( get_post_meta( $team_id, '_team_logo', true ), 'medium' ) ) . '" alt="' . esc_attr__( 'Team logo', 'hackathon' ) . '" class="hackathon-team-logo">';
	} else {
		$title = get_the_title( $team_id );
		$img = '<span class="hms-list-figure-text">' . mb_substr( $title, 0, 1) . '</span>';
	}
	return $img;
}

/**
 * Display team logo
 */
function hms_team_logo( $team_id ) {
	echo wp_kses_post( hms_get_team_logo( $team_id ) );
}

/**
 * Teams List
 */
function hms_list_teams( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_team',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$output       = '';

	$query = new WP_Query( $parsed_args );

	if ( $query->have_posts() ) {
		$output .= '<div class="hms-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$users_count = hms_get_team_users_count( get_the_ID() );

				$output .= '<div class="hms-list-item">
					<a href="' . esc_url( hms_get_url( 'team/' . get_the_ID() ) ) . '" class="hms-list-figure">
						' . hms_get_team_logo( get_the_ID() ) . '
					</a>

					<div class="hms-list-content">
						<h4 class="hms-list-title">
							<a href="' . esc_url( hms_get_url( 'team/' . get_the_ID() ) ) . '">' . get_the_title() . '</a>
						</h4>

						<div class="hms-list-line">
							<div class="hms-list-line-item">
								<div class="hms-list-label">
								' . sprintf(
									esc_html( _n( '%s User', '%s Users', $users_count, 'hackathon' ) ),
									esc_html( $users_count )
								) . '
								</div>
							</div>
						</div>
					</div>
				</div>';
			}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No teams', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_list_teams', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}

}

/**
 * Teams Card
 */
function hms_card_teams( $args = '' ) {
	$defaults = array(
		'post_type'      => 'hms_team',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $defaults );

	$output       = '';

	$query = new WP_Query( $parsed_args );

	$current_user_id    = get_current_user_id();
	$current_user_teams = hms_get_user_teams( $current_user_id );

	$output .= '<div class="hms-cards">';
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$users_count = hms_get_team_users_count( get_the_ID() );

			$team_url      = hms_get_url( 'team/' . get_the_ID() );
			$disable_class = '';

			if ( hms_is_mentor( $current_user_id ) ) {
				$team_url = '#';
				$disable_class = ' hms-card-inactive';

				foreach ( $current_user_teams as $team ) {
					if ( $team->ID === get_the_ID() ) {
						$team_url      = hms_get_url( 'team/' . get_the_ID() );
						$disable_class = '';
					}
				}
			}

			$mentor_action_html = '';
			if ( hms_is_mentor() ) {
				if ( $disable_class ) {
					$mentor_action_html = '<div class="hms-card-actions">
						<button type="button" class="hms-button hms-button-outline hms-button-xs hms-add-team-user" data-team="' . esc_attr( get_the_ID() ) . '" data-user="' . esc_attr( $current_user_id ) . '">' . esc_html__( 'Join', 'hackathon' ) . '</button>
						</div>
					';
				} else {
					$mentor_action_html = '<div class="hms-card-actions">
						<button type="button" class="hms-button hms-button-outline hms-button-xs hms-button-delete hms-remove-team-user" data-team="' . esc_attr( get_the_ID() ) . '" data-user="' . esc_attr( $current_user_id ) . '">' . esc_html__( 'Leave', 'hackathon' ) . '</button>
					</div>';
				}
			}

			$output .= '<div class="hms-card' . esc_attr( $disable_class ) . '">
				<a href="' . esc_url( $team_url ) . '" class="hms-card-figure">
					' . hms_get_team_logo( get_the_ID() ) . '
				</a>

				<div class="hms-card-content">
					<h4 class="hms-card-title">
						<a href="' . esc_url( $team_url ) . '">' . get_the_title() . '</a>
					</h4>

					<div class="hms-card-line">
						<div class="hms-card-line-item">
							<div class="hms-card-value">
							' .  links_add_target( make_clickable( get_post_meta( get_the_ID(), '_team_chat', true ) ) ) . '
							</div>
						</div>
					</div>
				</div>

				<div class="hms-card-info">
					<div class="hms-card-info-item">
						<div class="hms-card-info-icon">
							' . hms_get_icon('team') . '
						</div>
						<div class="hms-card-label">
						' . sprintf(
							esc_html( _n( '%s User', '%s Users', $users_count, 'hackathon' ) ),
							esc_html( $users_count )
						) . '
						</div>
					</div>
					' . $mentor_action_html . '
				</div>

			</div>';
		}
	} else {
		$output .= '<div class="hms-card">' . esc_html__( 'No teams', 'hackathon' ) . '</div>';
	}
	$output .= '</div>';
	wp_reset_postdata();

	$html = apply_filters( 'hms_card_teams', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}

}
