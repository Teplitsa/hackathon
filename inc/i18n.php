<?php
/**
 * Internationalization
 */

function hms_i18n(){
	_x( 'Mentor', 'User role', 'hackathon' );
	_x( 'Participant', 'User role', 'hackathon' );
	_x( 'Jury', 'User role', 'hackathon' );
	__( 'Mentor', 'hackathon' );
	__( 'Participant', 'hackathon' );
	__( 'Jury', 'hackathon' );
}
add_action( 'init', 'hms_i18n' );
