<?php
/**
 * Login User
 */
function hms_login_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	$data = array(
		'request' => $request,
	);

	$log = sanitize_text_field( $_POST['log'] );

	$user = get_user_by( 'login', $log );

	if ( $user && ! user_can( $user->ID, 'hackathon' ) ) {
		$data['message'] = esc_html__( 'This user type does not have access to the hackathon', 'hackathon' );
		wp_send_json_error( $data );
	}

	$user = wp_signon();

	if ( is_wp_error( $user ) ) {
		$message         = $user->get_error_message();
		$data['message'] = str_replace( wp_lostpassword_url(), hms_get_url( 'lostpassword' ), $message );
		wp_send_json_error( $data );
	}

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_nopriv_hackathon_login', 'hms_login_user' );
