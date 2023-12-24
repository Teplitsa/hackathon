<?php
/**
 * Update Options
 */
function hms_update_options() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'wp_kses_post' );

	if ( ! isset( $_POST['action'] ) && 'hms_update_options' === $_POST['action'] ) {
		wp_die();
	} else {
		$options = $data;
		unset( $options['action'] );
		unset( $options['nonce'] );

		if ( isset( $_POST['option_page'] ) && 'main' === $_POST['option_page'] ) {
			if ( ! isset( $_POST['deadline_access'] ) ) {
				hms_delete_option( 'deadline_access' );
			}
		}

		if ( $options ) {
			unset( $options['option_page'] );
			foreach ( $options as $name => $value ) {
				$type = 'text';
				if ( 'mail' === $_POST['option_page'] ) {
					$type = 'textarea';
				}
				if ( 'event_contacts' === $name ) {
					$type = 'html';
				}
				if ( $value ) {
					hms_update_option( $name, $value, $type );
				} else {
					hms_delete_option( $name );
				}
			}
			$data['message'] = esc_html__( 'Options updated successfully', 'hackathon' );
		}
	}

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hms_update_options', 'hms_update_options' );
