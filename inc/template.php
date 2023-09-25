<?php
/**
 * Template
 */

/**
 * Load Template Path
 */
function hms_page_templates_path(){
	$templates_path = HMS_PATH . 'templates/';
	return apply_filters( 'hms_page_template_path', $templates_path );
}

/**
 * Load Template
 */
function hms_load_template( $template = '' ){
	$template_path = hms_page_templates_path() . $template;
	if ( ! file_exists( $template_path ) ) {
		return;
	}
	load_template( $template_path, true, hms_global_vars() );
}

/**
 * Load Template content
 */
function hms_load_content_template(){
	$template_name = '404';
	$page          = get_query_var( 'hms_page' );
	$subpage       = get_query_var( 'hms_subpage' );
	if ( $subpage && in_array( $subpage, hms_pages() ) ) {
		$template_name = $subpage;
	} else if ( hms_is_page() && ! $subpage ){
		$template_name = 'dashboard';
	}
	$templates_path = hms_page_templates_path();
	$template       = $templates_path . 'content/content-' . $template_name . '.php';
	if ( ! file_exists( $template ) ) {
		$template_name = '404';
	}
	$template_name = 'content-' . $template_name . '.php';

	hms_load_template( 'content/' . $template_name );
}

/**
 * Add custom templates
 */
function hms_template_include( $template ) {
	if ( hms_is_page() ) {
		return hms_page_templates_path() . 'template.php';
	}
	return $template;
}
add_filter( 'template_include', 'hms_template_include' );
