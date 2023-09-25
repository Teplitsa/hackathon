<?php
/**
 * Update team
 */

function hms_ajax_update_team() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['post_id'] ) && $_POST['post_id'] ) {
		$team_id = sanitize_text_field( $_POST['post_id'] );

		$team_data = array(
			'ID' => $team_id,
		);

		if ( isset( $_POST['team_name'] ) && $_POST['team_name'] && $_POST['team_name'] !== get_post_field( 'post_title', $team_id )  ) {
			$team_data['post_title'] = sanitize_text_field( $_POST['team_name'] );
			if ( get_the_title( $team_id ) !== $team_data['post_title'] ) {
				add_post_meta( $team_id, '_team_titles', get_the_title( $team_id ) );
			}
		}

		if ( isset( $_POST['team_status'] ) && $_POST['team_status'] && $_POST['team_status'] !== get_post_meta( $team_id, '_team_status', true ) ) {
			$team_data['meta_input']['_team_status'] = sanitize_text_field( $_POST['team_status'] );
		}

		if ( isset( $_POST['team_chat'] ) && $_POST['team_chat'] && $_POST['team_chat'] !== get_post_meta( $team_id, '_team_chat', true ) ) {
			$team_data['meta_input']['_team_chat'] = sanitize_text_field( $_POST['team_chat'] );
		}

		if ( isset( $_POST['team_logo'] ) && $_POST['team_logo'] && $_POST['team_logo'] !== get_post_meta( $team_id, '_team_logo', true ) ) {
			$team_data['meta_input']['_team_logo'] = sanitize_text_field( $_POST['team_logo'] );
		} else {
			$team_data['meta_input']['_team_logo'] = '';
		}

		wp_update_post( wp_slash( $team_data ) );

		$data['message'] = esc_html__( 'Team successfully updated', 'hackathon' );

		wp_send_json_success( $data );
	}

	wp_send_json_error( $data );

}
add_action( 'wp_ajax_hackathon_update_team', 'hms_ajax_update_team' );
