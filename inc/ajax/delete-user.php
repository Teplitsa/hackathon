<?php
/**
 * Login User
 */
function hms_delete_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	$data = array(
		'request'     => $request,
		'redirect_to' => hms_get_url( 'users' ),
	);

	$user_id = isset( $_POST['user_id'] ) ? sanitize_text_field( $_POST['user_id'] ) : false;

	$result = wp_delete_user( $user_id );

	if ( $result ) {

		hms_remove_teams_user( $user_id );

		hms_insert_log_user_deleted( $user_id );

		$data['message'] = esc_html__( 'User deleted successfully', 'hackathon' );

		hms_remove_teams_user( $user_id );

		wp_send_json_success( $data );
	} else {
		$data['message'] = esc_html__( 'Something went wrong', 'hackathon' );
		wp_send_json_error( $data );
	}
}
add_action( 'wp_ajax_hackathon_delete_user', 'hms_delete_user' );
