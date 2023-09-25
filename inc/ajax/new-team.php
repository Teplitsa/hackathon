<?php
/**
 * New team
 */

function hms_ajax_new_team() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['team_name'] ) && $_POST['team_name'] ) {

		$team_data = array(
			'post_type'    => 'hms_team',
			'post_title'   => sanitize_text_field( $_POST['team_name'] ),
			'post_status'  => 'publish',
		);

		$team_id = wp_insert_post( $team_data );
		hms_add_team_nonce( $team_id );

		hms_send_email_new_team( $team_id );

		if ( isset( $_POST['team_chat'] ) && $_POST['team_chat'] ) {
			update_post_meta( $team_id, '_team_chat', sanitize_text_field( $_POST['team_chat'] ) );
		}

		if ( isset( $_POST['team_logo'] ) && $_POST['team_logo'] ) {
			update_post_meta( $team_id, '_team_logo', sanitize_text_field( $_POST['team_logo'] ) );
		}

		$data['team_id'] = $team_id;

		wp_send_json_success( $data );
	} else {
		$data['message'] = esc_html__( 'Team name can\'t be empty', 'hackathon' );
		wp_send_json_error( $data );
	}

}
add_action( 'wp_ajax_hackathon_new_team', 'hms_ajax_new_team' );
