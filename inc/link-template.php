<?php
/**
 * Link Template Functions
 */

/**
 * Get dashboard slug
 */
function hms_dashboard_slug() {
	return apply_filters( 'hms_dashboard_slug', 'hms' );
}

/**
 * Get url
 */
function hms_get_url( $slug = '', $args = array() ) {

	if ( 'dashboard' === $slug ) {
		$slug = '';
	}

	$url = home_url( trailingslashit( '/' . hms_dashboard_slug() . '/' . $slug ) );

	if ( $args && is_array( $args ) ) {
		$url = add_query_arg( $args, $url );
	}

	return apply_filters( 'hms_get_url', $url, $slug, $args );
}

/**
 * Display url
 */
function hms_url( $slug = '', $args = array() ) {
	$url = hms_get_url( $slug, $args );
	echo esc_url( $url );
}

/**
 * Get admin url
 */
function hms_get_admin_url( $path = '' ) {
	$url = admin_url( $path );
	return apply_filters( 'hms_admin_url', $url, $path );
}

/**
 * Display admin url
 */
function hms_admin_url( $path = '' ) {
	$url = hms_get_admin_url( $path );
	echo esc_url( $url );
}

/**
 * Get user url
 */
function hms_get_edit_user_url( $user_id = null ) {

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	$slug = 'edit-user/' . $user_id;

	if ( 'hackathon_participant' === hms_get_user_role() ) {
		$slug = 'edit-user';
	}
	$url = hms_get_url( $slug );

	return apply_filters( 'hms_get_edit_user_url', $url, $user_id );
}

/**
 * Get user request url
 */
function hms_get_user_request_url( $user_id = null ) {

	$slug = 'request';

	$args = array(
		'post_type'      => 'hms_request',
		'author'         => $user_id,
		'posts_per_page' => 1,
	);

	$user_requests = get_posts( $args );

	if ( $user_requests ) {
		$slug .= '/' . $user_requests[0]->ID;
	}

	$url = hms_get_url( $slug );

	return apply_filters( 'hms_get_user_request_url', $url, $user_id );
}

/**
 * Get team url
 */
function hms_get_team_url( $team_id = null ) {
	$slug = 'team';

	if ( $team_id && 'hackathon_participant' !== hms_get_user_role() ) {
		$slug = $slug . '/' . $team_id;
	}

	$url = hms_get_url( $slug );

	return apply_filters( 'hms_get_team_url', $url, $team_id );
}

/**
 * Get teams url
 */
function hms_get_teams_url() {
	$slug = 'teams';
	$url  = hms_get_url( $slug );

	return apply_filters( 'hms_get_teams_url', $url );
}

/**
 * Teams url
 */
function hms_teams_url() {
	echo esc_url( hms_get_teams_url() );
}

/**
 * Get new team url
 */
function hms_get_new_team_url() {
	$slug = 'new-team';
	$url  = hms_get_url( $slug );

	return apply_filters( 'hms_get_new_team_url', $url );
}

/**
 * New team url
 */
function hms_new_team_url() {
	echo esc_url( hms_get_new_team_url() );
}

/**
 * Get materials url
 */
function hms_get_materials_url() {
	$slug = 'materials';
	$url  = hms_get_url( $slug );

	return apply_filters( 'hms_get_materials_url', $url );
}

/**
 * Materials url
 */
function hms_materials_url() {
	echo esc_url( hms_get_materials_url() );
}

/**
 * Get messages url
 */
function hms_get_messages_url() {
	$slug = 'messages';
	$url  = hms_get_url( $slug );

	return apply_filters( 'hms_get_messages_url', $url );
}

/**
 * Messages url
 */
function hms_messages_url() {
	echo esc_url( hms_get_messages_url() );
}

/**
 * Get new message url
 */
function hms_get_new_message_url() {
	$slug = 'new-message';
	$url  = hms_get_url( $slug );

	return apply_filters( 'hms_get_new_message_url', $url );
}

/**
 * New message url
 */
function hms_new_message_url() {
	echo esc_url( hms_get_new_message_url() );
}

/**
 * Is Hackathon page
 */
function hms_pages() {
	$pages = array(
		'logs',
		'users',
		'user',
		'edit-user',
		'teams',
		'team',
		'new-team',
		'edit-team',
		'lostpassword',
		'register',
		'options',
		'messages',
		'message',
		'new-message',
		'edit-message',
		'requests',
		'request',
		'projects',
		'support',
		'materials',
		'new-material',
		'material',
		'edit-material',
		'forms',
		'form',
	);
	return apply_filters( 'hms_pages', $pages );
}

/**
 * Is Hackathon page
 */
function hms_is_page() {
	$slug = hms_dashboard_slug();
	if ( get_query_var( $slug . '_page' ) ) {
		return true;
	}
	return false;
}

/**
 * Add rewrite rule
 */
function hms_add_rewrite_rules() {
	$slug = hms_dashboard_slug();

	add_rewrite_rule( $slug . '/?$', 'index.php?' . $slug . '_page=1', 'top' );
	add_rewrite_rule( '^' . $slug . '/([^/]*)/([^/]*)?', 'index.php?' . $slug . '_page=1&' . $slug . '_subpage=$matches[1]&' . $slug . '_subsubpage=$matches[2]', 'top' );
	add_rewrite_rule( '^' . $slug . '/([^/]*)/?', 'index.php?' . $slug . '_page=1&' . $slug . '_subpage=$matches[1]', 'top' );

	add_rewrite_tag( '%' . $slug . '_page%', '([^&]+)' );
	add_rewrite_tag( '%' . $slug . '_subpage%', '([^&]+)' );
	add_rewrite_tag( '%' . $slug . '_subsubpage%', '([^&]+)' );
}
add_action( 'init', 'hms_add_rewrite_rules' );

/**
 * Set global query vars.
 */
function hms_set_query_vars() {
	if ( is_user_logged_in() ) {
		set_query_var( 'current_user_id', get_current_user_id() );
	}
}
add_action( 'template_redirect', 'hms_set_query_vars' );

/**
 * Get support url
 */
function hms_get_support_url() {
	$support_url = 'https://t.me/hmsdev';
	return apply_filters( 'hms_support_url', $support_url );
}

/**
 * Get documentation url
 */
function hms_get_docs_url() {
	$docs_url = 'https://hms.te-st.org/docs/';
	return apply_filters( 'hms_docs_url', $docs_url );
}

/**
 * Get teplitsa url
 */
function hms_get_teplitsa_url() {
	$teplitsa_url = 'https://te-st.org';
	return apply_filters( 'hms_teplitsa_url', $teplitsa_url );
}
