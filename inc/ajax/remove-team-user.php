<?php
/**
 * Remove Team User
 */

function hms_ajax_remove_team_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	$data = array(
		'request' => $request,
	);

	if ( isset( $_POST['team_id'] ) && isset( $_POST['user_id'] ) ) {
		$team_id = sanitize_text_field( $_POST['team_id'] );
		$user_id = sanitize_text_field( $_POST['user_id'] );
		hms_remove_team_user( $team_id, $user_id );
		$data['message'] = sprintf( __( 'User successfully removed from team <br><strong>%s</strong>', 'hackathon' ), get_the_title( $team_id ) );
		wp_send_json_success( $data );
	}

	wp_send_json_error( $data );

}
add_action( 'wp_ajax_hms_remove_team_user', 'hms_ajax_remove_team_user' );

