<?php
/**
 * Delete Team
 */
function hms_delete_team() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	$data = array(
		'request'     => $request,
		'redirect_to' => hms_get_url( 'teams' ),
		'confirm'     => isset( $_POST['confirm'] ) && $_POST['confirm'] ? (bool) sanitize_text_field( $_POST['confirm'] ) : false,
	);

	if ( $data['confirm'] ) {
		$data['message'] = esc_html__( 'Do you really want to delete this?', 'hackathon' );
		$data['str']     = array(
			'yes' => esc_html__( 'Yes', 'hackathon' ),
			'no'  => esc_html__( 'No', 'hackathon' ),
		);
		wp_send_json_error( $data );
	}

	$result = wp_delete_post( sanitize_text_field( $_POST['id'] ) );

	if ( $result ) {
		wp_send_json_success( $data );
	} else {
		wp_send_json_error( $data );
	}
}
add_action( 'wp_ajax_hackathon_delete-team', 'hms_delete_team' );
