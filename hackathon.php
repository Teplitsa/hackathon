<?php
/**
 * Plugin Name: Hackathon Management System
 * Plugin URI: https://wordpress.org/plugins/hackathon/
 * Description: Hackathon Management System
 * Version: 1.2.5
 * Requires at least: 6.0
 * Requires PHP: 5.6
 * Author: Teplitsa. Technologies for Social Good
 * Author URI: https://te-st.org
 * License: GPL v3
 * Text Domain: hackathon
 *
 * @package hackathon
 */

/* Define Plugin Constants */
define( 'HMS_PATH', plugin_dir_path( __FILE__ ) );
define( 'HMS_URL', plugin_dir_url( __FILE__ ) );
define( 'HMS_FILE', __FILE__ );
define( 'HMS_VER', '1.2.5' );

/**
 * Plugin Init
 */
require_once HMS_PATH . 'inc/init.php';
