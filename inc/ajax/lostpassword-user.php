<?php
/**
 * Lostpassword User
 */
function hms_lostpassword_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	$data['status'] = 'success';

	$results = retrieve_password();

	if ( true === $results ) {
		wp_send_json_success( esc_html__( 'A password reset link was emailed.', 'hackathon' ) );
	} else {
		wp_send_json_error( $results->get_error_message() );
	}
}
add_action( 'wp_ajax_nopriv_hackathon_lostpassword', 'hms_lostpassword_user' );
