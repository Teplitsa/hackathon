<?php
/**
 * Add Team User
 */
function hms_ajax_add_team_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	$user_id = isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : '';
	$team_id = isset( $_POST['team_id'] ) ? sanitize_text_field( $_POST['team_id'] ) : '';

	$max_participants = hms_max_participants();
	$user_count       = hms_get_team_users_count( $team_id );
	$teams_count      = count( hms_get_user_teams( $user_id ) );
	$max_count        = hms_max_teams();
	if ( 'hackathon_participant' === hms_get_user_role( $user_id ) ) {
		$max_count = 1;
	}

	$data = array(
		'request'          => $request,
		'count'            => $teams_count,
		'max_count'        => $max_count,
		'max_participants' => $max_participants,
	);

	if ( $user_id && $team_id ) {

		if ( $max_participants > $user_count ) {

			if ( $max_count <= $teams_count ) {
				$data['message'] = sprintf( esc_html__( 'The maximum number of teams is %s', 'hackathon' ), $max_count );
				wp_send_json_error( $data );
			} else {
				hms_add_team_user( $team_id, $user_id );
				$data['message'] = esc_html__( 'User successfully added to the team.', 'hackathon' );
				wp_send_json_success( $data );
			}
		} else {
			$data['message'] = sprintf( esc_html__( 'Maximum number of participants is %s', 'hackathon' ), $max_participants );
			wp_send_json_error( $data );
		}

		// if ( $max_participants <= $user_count ) {
		// if ( $max_count <= $teams_count ) {
		// $data['message'] = sprintf( esc_html__( 'The maximum number of teams is %s', 'hackathon' ), $max_count );
		// wp_send_json_error( $data );
		// } else {
		// $data['message'] = sprintf( esc_html__( 'Maximum number of participants is %s', 'hackathon' ), $max_participants );
		// wp_send_json_error( $data );
		// }
		// } else {
		// hms_add_team_user( $team_id, $user_id );
		// $data['message'] = esc_html__( 'User successfully added to the team.', 'hackathon' );
		// wp_send_json_success( $data );
		// }
	}
}
add_action( 'wp_ajax_hms_add_team_user', 'hms_ajax_add_team_user' );
