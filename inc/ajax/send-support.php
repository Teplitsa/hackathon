<?php
/**
 * Send support email
 */

function hms_ajax_hackathon_send_support() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( ! isset( $_POST['message_title'] ) || ( isset( $_POST['message_title'] ) && empty( $_POST['message_title'] ) ) ) {
		$data['message']= esc_html__( 'Message title cannot be empty', 'hackathon' );
		wp_send_json_error( $data );
	}

	if ( ! isset( $_POST['message_content'] ) || ( isset( $_POST['message_content'] ) && empty( $_POST['message_content'] ) ) ) {
		$data['message']= esc_html__( 'Message content cannot be empty', 'hackathon' );
		wp_send_json_error( $data );
	}

	if ( isset( $_POST['user_id'] ) && ! empty( $_POST['user_id'] ) ) {

		$user_id         = sanitize_text_field( $_POST['user_id'] );
		$sitename        = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		$message_title   = sanitize_text_field( $_POST['message_title'] );
		$message_content = sanitize_textarea_field( $_POST['message_content'] );
		$email           = get_user_option( 'user_email', $user_id );
		$telegram        = get_user_option( 'telegram', $user_id );
		$username        = get_user_option( 'user_login', $user_id );
		$page_url        = isset( $_POST['page_url'] ) ? sanitize_text_field( $_POST['page_url'] ) : '';

		$content = __(
'Howdy,

Username: ###USERNAME###

Email: ###EMAIL###

Telegram: ###TELEGRAM###

Title: ###MESSAGETITLE###

Message:
###MESSAGECONTENT###

From page: ###PAGEURL###'
, 'hackathon' );

		$subject = sprintf( __( 'Support request from [%s]', 'hackathon' ), $username );

		$content = str_replace( '###USERNAME###', $username, $content );
		$content = str_replace( '###EMAIL###', $email, $content );
		$content = str_replace( '###TELEGRAM###', $telegram, $content );
		$content = str_replace( '###MESSAGETITLE###', $message_title, $content );
		$content = str_replace( '###MESSAGECONTENT###', $message_content, $content );
		$content = str_replace( '###PAGEURL###', $page_url, $content );

		$email_sent = wp_mail( get_option( 'admin_email' ), $subject, $content );

		if ( $email_sent ) {
			$data['message']= esc_html__( 'Support request sent successfully', 'hackathon' );
			wp_send_json_success( $data );
		} else {
			$data['message']= esc_html__( 'Something went wrong.', 'hackathon' );
			wp_send_json_error( $data );
		}

	}

	$data['message']= esc_html__( 'Something went wrong.', 'hackathon' );
	wp_send_json_error( $data );

}
add_action( 'wp_ajax_hackathon_send_support', 'hms_ajax_hackathon_send_support' );
