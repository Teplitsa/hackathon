<?php
/**
 * Admin Menu
 */

/**
 * Add Admin Menu
 */
function hms_admin_menu() {
	add_menu_page(
		esc_html__( 'Hackathon Management System', 'hackathon' ),
		esc_html__( 'HMS', 'hackathon' ),
		'manage_options',
		hms_get_url(),
		false,
		hms_get_menu_icon()
	);
}
add_action( 'admin_menu', 'hms_admin_menu' );

/**
 * Add Hackathon url to the Toolbar.
 */
function hms_add_nodes( $wp_admin_bar ) {
	if ( current_user_can( 'manage_options' ) ) {
		$title = '<span class="ab-icon" aria-hidden="true">' . hms_get_menu_icon( false ) . '</span><span class="ab-label">' . esc_html__( 'HMS', 'hackathon' ) . '</span>';
		$wp_admin_bar->add_menu(
			array(
				'id'    => 'hms',
				'href'  => hms_get_url(),
				'title' => $title,
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-forms',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_form' ),
				'title'  => __( 'Forms', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-materials',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_material' ),
				'title'  => __( 'Materials', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-messages',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_message' ),
				'title'  => __( 'Messages', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-point-messages',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_point_message' ),
				'title'  => __( 'Checkpoint Messages', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-requests',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_request' ),
				'title'  => __( 'Requests', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-teams',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_team' ),
				'title'  => __( 'Teams', 'hackathon' ),
			)
		);

		$wp_admin_bar->add_menu(
			array(
				'id'     => 'hms-logs',
				'parent' => 'hms',
				'href'   => admin_url( 'edit.php?post_type=hms_log' ),
				'title'  => __( 'Logs', 'hackathon' ),
			)
		);
	}
}
add_action( 'admin_bar_menu', 'hms_add_nodes', 80 );

/**
 * Admin Page
 */
require_once HMS_PATH . 'inc/admin-page.php';
