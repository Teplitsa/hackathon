<?php
/**
 * Update request
 */

function hms_ajax_request_status() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['request_id'] ) && $_POST['request_id'] ) {
		$request_id     = sanitize_text_field( $_POST['request_id'] );
		$request_status = sanitize_text_field( $_POST['status'] );

		update_post_meta( $request_id, 'status', $request_status );

		$statuses = hms_request_statuses();
		$status   = esc_html__( 'Status not defined', 'hackathon' );
		if ( isset( $statuses[ $request_status ] ) ) {
			$status = esc_html( $statuses[ $request_status ]['title'] );
		}

		$author_id = get_post_field( 'post_author', $request_id );

		if ( 'processing' === $request_status || 'approved' === $request_status || 'rejected' === $request_status || 'cancelled' === $request_status ) {
			hms_send_email_status( $author_id, $request_status );
		}

		$team_id = get_post_meta( $request_id, '_team_id', true );
		if ( $team_id && 'approved' === $request_status ) {
			$team_data = array(
				'ID' => $team_id,
				'post_status' => 'publish'
			);
			wp_update_post( $team_data );
		}

		$data['message'] = esc_html__( 'Request status successfully changed to:', 'hackathon' ) . '<br><strong>' . esc_html( $status ) . '</strong>';

		wp_send_json_success( $data );
	}

	wp_send_json_error( $data );

}
add_action( 'wp_ajax_hms_request_status', 'hms_ajax_request_status' );
