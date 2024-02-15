<?php
/**
 * Register User
 */
function hms_register_user() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$request = map_deep( $_REQUEST, 'sanitize_text_field' );

	// First name
	$error_message = false;

	if ( ! $error_message ) {
		$field_name = 'first_name';
		if ( isset( $_POST[ $field_name ] ) && hms_option_form( 'registerform', $field_name, 'required', true ) ) {
			if ( empty( $_POST[ $field_name ] ) ) {
				$error_message = esc_html__( 'First Name cannot be empty', 'hackathon' );
			} elseif ( ! sanitize_text_field( $_POST[ $field_name ] ) ) {
				$error_message = esc_html__( 'Invalid characters used in First Name', 'hackathon' );
			} else {
				$error_message = false;
			}
		}
	}

	// Last name
	if ( ! $error_message ) {
		$field_name = 'last_name';
		if ( isset( $_POST[ $field_name ] ) && hms_option_form( 'registerform', $field_name, 'required', true ) ) {
			if ( empty( $_POST[ $field_name ] ) ) {
				$error_message = esc_html__( 'Last Name cannot be empty', 'hackathon' );
			} elseif ( ! sanitize_text_field( $_POST[ $field_name ] ) ) {
				$error_message = esc_html__( 'Invalid characters used in Last Name', 'hackathon' );
			} else {
				$error_message = false;
			}
		}
	}

	// Email
	if ( ! $error_message ) {
		$field_name = 'email';
		if ( ! isset( $_POST[ $field_name ] ) || ( isset( $_POST[ $field_name ] ) && empty( $_POST[ $field_name ] ) ) ) {
			$error_message = esc_html__( 'Email cannot be empty', 'hackathon' );
		} elseif ( ! is_email( sanitize_email( $_POST[ $field_name ] ) ) ) {
			$error_message = esc_html__( 'Invalid email', 'hackathon' );
		} elseif ( email_exists( sanitize_email( $_POST[ $field_name ] ) ) ) {
			$error_message = esc_html__( 'User with this email address already exists', 'hackathon' );
		} else {
			$field_name    = '';
			$error_message = false;
		}
	}

	$form_id     = isset( $_POST['form_id'] ) ? $_POST['form_id'] : '';
	$form_fields = get_post_meta( $form_id, '_form_fields', true );

	$unset_fields = array(
		'first_name',
		'last_name',
		'email',
		'event_privacy_agreement',
	);

	if ( $form_fields && is_array( $form_fields ) ) {
		foreach ( $unset_fields as $field ) {
			if ( isset( $form_fields[ $field ] ) ) {
				unset( $form_fields[ $field ] );
			}
		}
		foreach ( $form_fields as $key => $field ) {
			if ( isset( $_POST[ $key ] ) && isset( $field['required'] ) ) {
				if ( ! $error_message ) {
					$field_name = $key;
					if ( $key === 'phone' ) {
						if ( empty( $_POST[ $key ] ) ) {
							$error_message = esc_html__( 'Phone cannot be empty', 'hackathon' );
						} elseif ( ! hms_is_phone( sanitize_text_field( $_POST[ $key ] ) ) ) {
							$error_message = esc_html__( 'Invalid phone number', 'hackathon' );
						} else {
							$error_message = false;
						}
					} elseif ( $key === 'city' ) {
						if ( empty( $_POST[ $field_name ] ) ) {
							$error_message = esc_html__( 'City cannot be empty', 'hackathon' );
						} else {
							$error_message = false;
						}
					} elseif ( $key === 'telegram' ) {
						if ( empty( $_POST[ $field_name ] ) ) {
							$error_message = esc_html__( 'Telegram cannot be empty', 'hackathon' );
						} else {
							$error_message = false;
						}
					} elseif ( $key === 'project_name' ) {
						if ( empty( $_POST[ $field_name ] ) ) {
							$error_message = esc_html__( 'Project name cannot be empty', 'hackathon' );
						} else {
							$error_message = false;
						}
					} elseif ( empty( $_POST[ $key ] ) ) {
							$error_message = esc_html__( 'Fill in required fields', 'hackathon' );
					} else {
						$error_message = false;
					}
				}
			}
		}
	}

	if ( ! $error_message ) {
		$field_name = 'event_privacy_agreement';
		if ( ! isset( $_POST[ $field_name ] ) ) {
			$error_message = esc_html__( 'You must agree to the processing of personal data', 'hackathon' );
		} else {
			$field_name    = '';
			$error_message = false;
		}
	}

	if ( $error_message ) {
		$data = array(
			'message' => $error_message,
			'name'    => $field_name,
			'request' => $request,
		);
		wp_send_json_error( $data );
	} else {

		$role         = isset( $_POST['user_type'] ) ? sanitize_text_field( $_POST['user_type'] ) : '';
		$user_email   = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
		$first_name   = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
		$last_name    = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
		$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
		$telegram     = isset( $_POST['telegram'] ) ? sanitize_text_field( $_POST['telegram'] ) : '';
		$city         = isset( $_POST['city'] ) ? sanitize_text_field( $_POST['city'] ) : '';
		$project_name = isset( $_POST['project_name'] ) ? sanitize_text_field( $_POST['project_name'] ) : '';
		$team_name    = isset( $_POST['team_name'] ) ? sanitize_text_field( $_POST['team_name'] ) : $project_name;

		// Here custom fields

		// Generate unique user login
		$user_exists = true;
		$check_limit = 0;

		while ( $user_exists != false && $check_limit <= 5 ) {
			$user_login  = wp_generate_password( 8, false );
			$user_exists = username_exists( $user_login );
			$check_limit++;
		}

		$userdata         = array(
			'user_login'           => sanitize_text_field( $user_login ),
			'user_pass'            => null,
			'user_email'           => $user_email,
			'first_name'           => $first_name,
			'last_name'            => $last_name,
			'role'                 => $role,
			'show_admin_bar_front' => false,
			'meta_input'           => array(
				'phone'    => $phone,
				'telegram' => $telegram,
				'city'     => $city,
			),
		);

		$user_id = wp_insert_user( $userdata );

		if ( is_wp_error( $user_id ) ) {
			$data = array(
				'message' => $user_id->get_error_message(),
				'request' => $request,
			);
			wp_send_json_error( $data );
		} else {

			// Notification
			wp_send_new_user_notifications( $user_id, 'admin' );
			hms_send_email_new_user( $user_id );

			// Insert log
			hms_insert_log_user_registered( $user_id );

			$data = array(
				'request' => $request,
			);

			$status = 'received';
			if ( $role !== 'hackathon_participant' ) {
				$status = 'approved';
			}

			$meta_input = array(
				'user_id'      => $user_id,
				'role'         => $role,
				'user_login'   => $user_login,
				'user_email'   => $user_email,
				'first_name'   => $first_name,
				'last_name'    => $last_name,
				'phone'        => $phone,
				'telegram'     => $telegram,
				'city'         => $city,
				'team_name'    => $team_name,
				'project_name' => $project_name,
				'status'       => $status,
			);

			if ( $form_fields && $form_id ) {
				foreach ( $form_fields as $key => $field ) {
					if ( in_array( $key, array( 'phone', 'telegram', 'city', 'project_name' ) ) ) {
					} elseif ( isset( $_POST[ $key ] ) ) {
						if ( isset( $field['type'] ) && $field['type'] == 'textarea' ) {
							$meta_input['custom'][ $key ]['value'] = sanitize_textarea_field( $_POST[ $key ] );
						} else {
							$meta_input['custom'][ $key ]['value'] = sanitize_text_field( $_POST[ $key ] );
						}
						$meta_input['custom'][ $key ]['label'] = sanitize_text_field( $field['label'] );
					}
				}
			}

			$request_data = array(
				'post_type'    => 'hms_request',
				'post_title'   => esc_html__( 'Request by:', 'hackathon' ) . ' ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ),
				'post_content' => wp_json_encode( $data ),
				'post_status'  => 'publish',
				'post_author'  => $user_id,
				'meta_input'   => $meta_input,
			);

			$request_id = wp_insert_post( wp_slash( $request_data ) );

			if ( is_wp_error( $request_id ) ) {
				$data['message'] = $request_id->get_error_message();
				wp_send_json_error( $data );
			} else {

				if ( $project_name ) {

					$post_status = 'draft';
					if ( $role == 'hackathon_mentor' ) {
						$post_status = 'publish';
					}
					$team_data = array(
						'post_type'   => 'hms_team',
						'post_title'  => $project_name,
						'post_status' => $post_status,
					);

					$team_id = wp_insert_post( $team_data );
					hms_add_team_nonce( $team_id );

					hms_add_team_user( $team_id, $user_id );

					update_post_meta( $request_id, '_team_id', $team_id );

					$data['team_id'] = $team_id;
				}

				$data['post_id'] = $request_id;
				wp_send_json_success( $data );
			}
		}
	}
}
add_action( 'wp_ajax_nopriv_hackathon_register_user', 'hms_register_user' );
