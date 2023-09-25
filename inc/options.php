<?php
/**
 * Options
 */

/**
 * Option name
 */
function hms_option_name(){
	$option_name = 'hms_options';
	return apply_filters( 'hms_option_name', $option_name );
}

/**
 * Get all options
 */
function hms_get_options(){
	$option_name = hms_option_name();
	$options     = get_option( $option_name );
	return (array) $options;
}

/**
 * Get option
 */
function hms_get_option( $option = '', $default = false ){
	$options = hms_get_options();
	$value   = $default;
	if ( isset( $options[ $option ] ) ) {
		$value = $options[ $option ];
	}
	return $value;
}

/**
 * Get option
 */
function hms_option( $option = '', $default = false ){
	$options = hms_get_options();
	$value   = $default;
	if ( isset( $options[ $option ] ) ) {
		$value = $options[ $option ];
	}
	return $value;
}

/**
 * Update option
 */
function hms_update_option( $option = '', $value = '', $type = 'text' ){
	if ( $value ) {
		$options            = hms_get_options();
		$option_name        = hms_option_name();
		$options[ $option ] = $value;

		update_option( $option_name, $options );
	}
}

/**
 * Delete option
 */
function hms_delete_option( $option = '' ){
	$options     = hms_get_options();
	$option_name = hms_option_name();
	if ( isset( $options[ $option ] ) ) {
		unset( $options[ $option ] );
		update_option( $option_name, $options );
	}
}

function hms_option_form( $option_name = '', $group = '', $field = '', $default = false ){
	$option = false;
	$form_option = hms_option( $option_name );
	if( $form_option ) {
		if ( isset( $form_option[ $group ] ) && isset( $form_option[ $group ][ $field ] ) ) {
			$option = $form_option[ $group ][ $field ];
		}
	} else {
		$option = $default;
	}
	return $option;
}


if( wp_doing_ajax() ){

	// Update options.
	require_once HMS_PATH . 'inc/ajax/update-options.php';

	// Send Password Reset.
	require_once HMS_PATH . 'inc/ajax/send-password-reset.php';

}
