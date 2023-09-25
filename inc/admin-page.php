<?php
/**
 * Admin Page
 */

/**
 * Add Admin Menu
 */
function hms_admin_page() {
	add_submenu_page(
		'options-general.php',
		esc_html__( 'Hackathon Management System', 'hackathon' ),
		esc_html__( 'HMS', 'hackathon' ),
		'manage_options',
		'hms',
		'hms_admin_page_callback'
	);

	add_action( 'admin_head', function() {
		remove_submenu_page( 'options-general.php', 'hackathon' );
	} );

}
add_action( 'admin_menu', 'hms_admin_page' );

/**
 * Admin Page Callback
 */
function hms_admin_page_callback() {
?>

<div class="wrap">
	<h1><?php echo get_admin_page_title(); ?></h1>
	<h2><?php esc_html_e( 'Auxiliary links', 'hackathon' ); ?></h2>
	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=hms_log' ) ); ?>"><?php esc_html_e( 'Edit Logs', 'hackathon' ); ?></a>
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=hms_message' ) ); ?>"><?php esc_html_e( 'Edit Messages', 'hackathon' ); ?></a>
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=hms_request' ) ); ?>"><?php esc_html_e( 'Edit Requests', 'hackathon' ); ?></a>
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=hms_team' ) ); ?>"><?php esc_html_e( 'Edit Teams', 'hackathon' ); ?></a>
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=hms_material' ) ); ?>"><?php esc_html_e( 'Edit Materials', 'hackathon' ); ?></a>
	</p>
</div>
<?php
}
