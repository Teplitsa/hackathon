<?php
/**
 * Update User
 */
function hms_update_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( ! isset( $_POST['user_id'] ) ) {
		wp_die();
	} else {
		$user_id = sanitize_text_field( $_POST['user_id'] );
	}

	if ( isset( $_POST['description'] ) ) {
		update_user_meta( $user_id, 'description', sanitize_textarea_field( $_POST['description'] ), get_user_option( 'description', $user_id ) );
	}

	if ( isset( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ), get_user_option( 'first_name', $user_id ) );
	}

	if ( isset( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ), get_user_option( 'last_name', $user_id ) );
	}

	if ( isset( $_POST['first_name'] ) && isset( $_POST['last_name'] ) ) {
		$display_name = $_POST['first_name'] . ' ' . $_POST['last_name'];
		update_user_meta( $user_id, 'display_name', sanitize_text_field( $display_name ), get_user_option( 'display_name', $user_id ) );
	}

	if ( isset( $_POST['telegram'] ) ) {
		update_user_meta( $user_id, 'telegram', sanitize_text_field( $_POST['telegram'] ), get_user_option( 'telegram', $user_id ) );
	}

	if ( isset( $_POST['phone'] ) ) {
		update_user_meta( $user_id, 'phone', sanitize_text_field( $_POST['phone'] ), get_user_option( 'phone', $user_id ) );
	}

	if ( isset( $_POST['city'] ) ) {
		update_user_meta( $user_id, 'city', sanitize_text_field( $_POST['city'] ), get_user_option( 'city', $user_id ) );
	}

	if ( isset( $_POST['custom_avatar'] ) ) {
		update_user_meta( $user_id, 'custom_avatar', sanitize_text_field( $_POST['custom_avatar'] ), get_user_option( 'custom_avatar', $user_id ) );
	}

	if ( isset( $_POST['role'] ) ) {

		$hsm_new_role = sanitize_text_field( $_POST['role'] );

		$hsm_user = new WP_User( $user_id );

		$hsm_user_meta  = get_userdata( $user_id );
		$hsm_user_roles = $hsm_user_meta->roles;
		$hms_role       = $hsm_user_roles[0];

		if ( $hsm_new_role !== $hms_role ) {
			// Remove role
			$hsm_user->remove_role( $hms_role );

			// Add role
			$hsm_user->add_role( $hsm_new_role );
		}
	}

	$data = array(
		'message' => esc_html__( 'User data updated successfully', 'hackathon' ),
		'request' => $request,
	);

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hackathon_update_user', 'hms_update_user' );
