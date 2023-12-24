<?php
/**
 * Template Options Main
 */

$subpage = 'main';
if ( get_query_var( 'hms_subsubpage' ) ) {
	$subpage = get_query_var( 'hms_subsubpage' );
}

$teamplate_name = 'content/options/options-' . $subpage . '.php';

hms_load_template( $teamplate_name );
