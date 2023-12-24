<?php
/**
 * Insert Message
 */
function hms_ajax_chat_message() {

	check_ajax_referer( 'hms-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	$checkpoint_id = isset( $_POST['checkpoint_id'] ) ? $_POST['checkpoint_id'] : '';
	$team_id       = isset( $_POST['team_id'] ) ? $_POST['team_id'] : '';

	if ( isset( $_POST['message'] ) && $_POST['message'] ) {
		$message    = isset( $_POST['message'] ) ? $_POST['message'] : '';
		$message_id = hms_insert_point_message( $checkpoint_id, $team_id, $message );

		hms_insert_point_email( $checkpoint_id, $team_id, $message, $message_id );
	}

	$data['count'] = hms_get_point_messages_count( $checkpoint_id, $team_id );
	$data['html']  = hms_get_point_messages( $checkpoint_id, $team_id );

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hms_chat_message', 'hms_ajax_chat_message' );
