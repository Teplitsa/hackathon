<?php
/**
 * Update team
 */
function hms_ajax_update_message() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['post_id'] ) && $_POST['post_id'] ) {
		$post_id = sanitize_text_field( $_POST['post_id'] );

		$post_data = array(
			'ID' => $post_id,
		);

		if ( isset( $_POST['post_title'] ) && $_POST['post_title'] && $_POST['post_title'] !== get_post_field( 'post_title', $post_id ) ) {
			$post_data['post_title'] = sanitize_text_field( $_POST['post_title'] );
		}

		if ( isset( $_POST['post_content'] ) && $_POST['post_content'] && $_POST['post_content'] !== get_post_field( 'post_content', $post_id ) ) {
			$post_data['post_content'] = wp_kses_post( $_POST['post_content'] );
		}

		wp_update_post( wp_slash( $post_data ) );

		$data['message'] = esc_html__( 'Message successfully updated', 'hackathon' );

		wp_send_json_success( $data );
	}

	wp_send_json_error( $data );
}
add_action( 'wp_ajax_hackathon_update_message', 'hms_ajax_update_message' );
