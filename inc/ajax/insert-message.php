<?php
/**
 * Insert Message
 */
function hms_ajax_insert_message() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['post_title'] ) && $_POST['post_title'] ) {
		if ( isset( $_POST['post_content'] ) && $_POST['post_content'] ) {
			$transport    = isset( $_POST['transport'] ) ? map_deep( $_POST['transport'], 'sanitize_text_field' ) : array();
			$role         = isset( $_POST['role'] ) ? map_deep( $_POST['role'], 'sanitize_text_field' ) : array();
			$post_title   = sanitize_text_field( $_POST['post_title'] );
			$post_content = sanitize_text_field( $_POST['post_content'] );

			hms_insert_message( $_POST['post_title'], $_POST['post_content'], $transport, $role, $data );
		}
		$data['message'] = esc_html__( 'Content can\'t be empty', 'hackathon' );
		wp_send_json_error( $data );
	} else {
		$data['message'] = esc_html__( 'Title can\'t be empty', 'hackathon' );
		wp_send_json_error( $data );
	}
}
add_action( 'wp_ajax_hms_insert_message', 'hms_ajax_insert_message' );
