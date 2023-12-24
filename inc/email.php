<?php
/**
 * Email
 */

/**
 * Send Email
 */
function hms_send_email( $email_data, $user_id = '' ) {

	$default_data = array(
		'to'      => '',
		'subject' => '',
		'message' => '',
		'headers' => '',
	);

	$data = wp_parse_args( (array) $email_data, $default_data );

	$email = $data['to'];

	if ( ! email_exists( $email ) ) {
		return;
	}

	$user = get_userdata( $user_id );

	$key        = '';
	$user_login = '';

	if ( $user ) {
		$key        = get_password_reset_key( $user );
		$user_login = $user->user_login;
	}

	$search = array(
		'{site_name}',
		'{user_login}',
		'{user_status}',
		'{password_reset_link}',
	);

	$replace = array(
		get_option( 'blogname' ),
		hms_get_user_login( $user_id ),
		hms_get_request_status( hms_get_user_status( $user_id ) ),
		network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),
	);

	$data['message'] = nl2br( make_clickable( str_replace( $search, $replace, $data['message'] ) ) );

	$data = apply_filters( 'hms_email_data', $data );

	add_filter(
		'wp_mail_content_type',
		function () {
			return 'text/html';
		}
	);

	wp_mail(
		$data['to'],
		wp_specialchars_decode( $data['subject'] ),
		$data['message'],
		$data['headers']
	);
}

/**
 * Send Email new user
 */
function hms_send_email_new_user( $user_id ) {

	if ( apply_filters( 'hms_disable_send_email_new_user', false ) === true ) {
		return false;
	}

	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_register' ) ) {
		$message = hms_option( 'mail_register' );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications['mail_register']['body'];
	}

	$email_data = array(
		'to'      => hms_get_user_email( $user_id ),
		'subject' => sprintf( __( '[%s] Login Details', 'hackathon' ), $title ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_new_user_email_data', $email_data );

	hms_send_email( $email_data, $user_id );
}

/**
 * Send Email reset
 */
function hms_send_email_reset( $user_id ) {

	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_reset' ) ) {
		$message = hms_option( 'mail_reset' );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications['mail_reset']['body'];
	}

	$email_data = array(
		'to'      => hms_get_user_email( $user_id ),
		'subject' => sprintf( __( '[%s] Reset password', 'hackathon' ), $title ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_reset_email_data', $email_data );

	hms_send_email( $email_data, $user_id );
}

/**
 * Send Email status
 */
function hms_send_email_status( $user_id, $status = '' ) {

	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_status_' . $status ) ) {
		$message = hms_option( 'mail_status_' . $status );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications[ 'mail_status_' . $status ]['body'];
	}

	$email_data = array(
		'to'      => hms_get_user_email( $user_id ),
		'subject' => sprintf( __( '[%s] Status changed', 'hackathon' ), $title ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_status_email_data', $email_data );

	hms_send_email( $email_data, $user_id );
}

/**
 * Send Email new team
 */
function hms_send_email_new_team( $team_id ) {

	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_new_team' ) ) {
		$message = hms_option( 'mail_new_team' );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications['mail_new_team']['body'];
	}

	$search = array(
		'{team_name}',
		'{team_url}',
	);

	$replace = array(
		get_the_title( $team_id ),
		hms_get_url( 'team/' . $team_id ),
	);

	$message = str_replace( $search, $replace, $message );

	$email_data = array(
		'to'      => get_option( 'admin_email' ),
		'subject' => sprintf( __( '[%s] New team created', 'hackathon' ), $title ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_new_team_email_data', $email_data );

	hms_send_email( $email_data );
}

/**
 * Send Email team form
 */
function hms_send_email_team_form( $team_id, $post_id ) {

	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_team_form' ) ) {
		$message = hms_option( 'mail_team_form' );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications['mail_team_form']['body'];
	}

	$search = array(
		'{team_name}',
		'{team_url}',
		'{material_type}',
	);

	$replace = array(
		get_the_title( $team_id ),
		hms_get_url( 'team/' . $team_id ),
		$post_id,
	);

	$message = str_replace( $search, $replace, $message );

	$email_data = array(
		'to'      => get_option( 'admin_email' ),
		'subject' => sprintf( __( '[%s] New team created', 'hackathon' ), $title ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_team_form_email_data', $email_data );

	hms_send_email( $email_data );
}

/**
 * Send Email Final solution
 */
function hms_send_email_final_solution( $team_id, $post_id ) {
	$title = hms_option( 'event_name', get_bloginfo() );

	if ( hms_option( 'mail_team_form' ) ) {
		$message = hms_option( 'mail_team_form' );
	} else {
		$notifications = hms_email_notifications();
		$message       = $notifications['mail_team_form']['body'];
	}

	$search = array(
		'{site_name}',
		'{team_name}',
		'{team_url}',
	);

	$replace = array(
		$title,
		get_the_title( $team_id ),
		hms_get_url( 'team/' . $team_id ),
	);

	$message = str_replace( $search, $replace, $message );

	$email_data = array(
		'to'      => get_option( 'admin_email' ),
		'subject' => sprintf( __( '[%1$s]: Team <%2$s> successfully submitted all forms', 'hackathon' ), $title, get_the_title( $team_id ) ),
		'message' => $message,
		'headers' => '',
	);

	$email_data = apply_filters( 'hms_team_final_solution_data', $email_data );

	hms_send_email( $email_data );
}

function hms_email_notifications() {

	$notifications = array(
		'mail_register'          => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your login:', 'hackathon' ) . ' {user_login}' . "\r\n\r\n" .
				__( 'To set your password, visit the following address:', 'hackathon' ) . "\r\n\r\n" .
				'{password_reset_link}' . "\r\n\r\n",
		),
		'mail_reset'             => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your login:', 'hackathon' ) . ' {user_login}' . "\r\n\r\n" .
				__( 'To set your password, visit the following address:', 'hackathon' ) . "\r\n\r\n" .
				'{password_reset_link}' . "\r\n\r\n",
		),
		'mail_status_processing' => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your status successfully changed to:', 'hackathon' ) . ' {user_status}',
		),
		'mail_status_approved'   => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your status successfully changed to:', 'hackathon' ) . ' {user_status}',
		),
		'mail_status_rejected'   => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your status successfully changed to:', 'hackathon' ) . ' {user_status}',
		),
		'mail_status_cancelled'  => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'Your status successfully changed to:', 'hackathon' ) . ' {user_status}',
		),
		'mail_new_team'          => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				__( 'New created team:', 'hackathon' ) . ' {team_name}' . "\r\n\r\n" .
				'{team_url}',
		),
		'mail_team_form'         => array(
			'body' => '{site_name}' . "\r\n\r\n" .
				sprintf( __( 'Team %s successfully submitted all forms.', 'hackathon' ), '{team_name}' ) . "\r\n\r\n" .
				'{team_url}',
		),
	);

	return $notifications;
}

function hms_retrieve_password_message( $message, $key, $user_login, $user ) {

	if ( user_can( $user, 'hackathon' ) ) {

		$user_id = $user->ID;

		if ( hms_option( 'mail_reset' ) ) {
			$message = hms_option( 'mail_reset' );
		} else {
			$notifications = hms_email_notifications();
			$message       = $notifications['mail_reset']['body'];
		}

		add_filter(
			'wp_mail_content_type',
			function () {
				return 'text/html';
			}
		);

		$search = array(
			'{site_name}',
			'{user_login}',
			'{password_reset_link}',
		);

		$replace = array(
			get_option( 'blogname' ),
			hms_get_user_login( $user_id ),
			network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user->user_login ), 'login' ),
		);

		$message = nl2br( make_clickable( str_replace( $search, $replace, $message ) ) );

	}

	return $message;
}
add_filter( 'retrieve_password_message', 'hms_retrieve_password_message', 10, 4 );

function hms_lostpassword_post( $errors, $user ) {
	if ( user_can( $user, 'hackathon' ) ) {
		add_filter(
			'wp_mail_content_type',
			function () {
				return 'text/html';
			}
		);
	}
}
add_action( 'lostpassword_post', 'hms_lostpassword_post', 10, 2 );
