<?php
/**
 * Support
 */

if( wp_doing_ajax() ){

	// Send support email.
	require_once HMS_PATH . 'inc/ajax/send-support.php';

}
