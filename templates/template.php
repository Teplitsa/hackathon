<?php
/**
 * Template
 */

$is_subpage = get_query_var( 'hms_subpage' );

if ( ! is_user_logged_in() && ! in_array( $is_subpage, hms_get_forms_slugs() ) ) {
	wp_redirect( wp_login_url( hms_get_url() ) );
	exit;
}

if ( is_user_logged_in() && ! isset( $_GET['preview'] ) ) {

	$exclude_pages = array();

	if ( hms_is_participant() ) {
		$exclude_pages = array(
			'logs',
			'users',
			'teams',
			'new-team',
			'edit-team',
			'options',
			'new-message',
			'edit-message',
			'requests',
			'forms',
		);
	}

	if ( hms_is_administrator() ) {
		if ( isset( $_GET['login_as'] ) && get_current_user_id() != $_GET['login_as'] ) {
			$user_id = sanitize_text_field( $_GET['login_as'] );
			$user = get_user_by( 'id', $user_id ); 
			if ( $user ) {
				wp_set_current_user( $user_id, $user->user_login );
				wp_set_auth_cookie( $user_id );
				do_action( 'wp_login', $user->user_login, $user );
				wp_redirect( hms_get_url() );
				exit;
			}
		}
	}

	if ( in_array( get_query_var( 'hms_subpage' ), $exclude_pages ) ) {
		wp_redirect( hms_get_url() );
		exit;
	}
	load_template( HMS_PATH . '/templates/content.php' );

} else if ( in_array( $is_subpage, hms_get_forms_slugs() ) ) {
	load_template( HMS_PATH . '/templates/register.php' );
}
