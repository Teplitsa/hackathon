<?php
/**
 * CSV
 */

/**
 * Create CSV file
 */
function hms_create_csv_file( $create_data, $file = null, $col_delimiter = ';', $row_delimiter = "\r\n" ) {

	if ( ! is_array( $create_data ) ) {
		return false;
	}

	if ( $file && ! is_dir( dirname( $file ) ) ) {
		return false;
	}

	$CSV_str = '';

	foreach ( $create_data as $row ) {
		$cols = array();

		foreach ( $row as $col_val ) {

			if ( $col_val && preg_match( '/[",;\r\n]/', $col_val ) ) {

				if ( $row_delimiter === "\r\n" ) {
					$col_val = str_replace( array( "\r\n", "\r" ), array( '\n', '' ), $col_val );
				} elseif ( $row_delimiter === "\n" ) {
					$col_val = str_replace( array( "\n", "\r\r" ), '\r', $col_val );
				}

				$col_val = str_replace( '"', '""', $col_val );
				$col_val = '"' . $col_val . '"';
			}

			$cols[] = sanitize_textarea_field( $col_val );
		}

		$CSV_str .= implode( $col_delimiter, $cols ) . $row_delimiter;
	}

	$CSV_str = rtrim( $CSV_str, $row_delimiter );

	if ( $file ) {

		$CSV_str = chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) . $CSV_str;

		$done = file_put_contents( $file, $CSV_str );

		return $done ? $CSV_str : false;
	}

	return $CSV_str;
}

/**
 * Get csv users
 */
function hms_get_csv_users( $role = 'all', $args = array() ) {

	if ( $role !== 'all' ) {
		$args['role__in'] = 'hackathon_' . $role;
	}

	$csv_users = array();

	$users = hms_get_users( $args );

	$exclude = array();

	if ( isset( $args['status'] ) && $args['status'] ) {

		foreach ( $users as $user ) {
			if ( $args['status'] !== hms_get_user_status( $user->ID ) ) {
				$exclude[] = $user->ID;
			}
		}
	}

	if ( $exclude ) {
		$args['exclude'] = $exclude;
	}

	$users = hms_get_users( $args );

	if ( $users ) {
		foreach ( $users as $user ) {
			$user_id   = $user->ID;
			$teams_arr = array();
			if ( hms_get_user_teams( $user_id ) ) {
				foreach ( hms_get_user_teams( $user_id ) as $team ) {
					$team_id               = $team->ID;
					$teams_arr[ $team_id ] = $team->post_title;
				}
			}
			$teams                 = implode( ',', $teams_arr );
			$csv_users[ $user_id ] = array(
				__( 'Email', 'hackathon' )      => $user->user_email,
				__( 'First Name', 'hackathon' ) => $user->first_name,
				__( 'Last Name', 'hackathon' )  => $user->last_name,
				__( 'Role', 'hackathon' )       => hms_get_user_role_name( $user_id ),
				__( 'Team', 'hackathon' )       => $teams,
			);
		}
		$csv_users_head = $csv_users;
		$csv_users_head = array_shift( $csv_users_head );
		$csv_users_head = array_keys( $csv_users_head );
		array_unshift( $csv_users, $csv_users_head );
	}

	return $csv_users;
}

/**
 * Get csv requests
 */
function hms_get_csv_requests( $args = array() ) {

	$default_args = array(
		'post_type'      => 'hms_request',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $default_args );

	$query = new WP_Query( $parsed_args );

	$requests = array();

	if ( $query->have_posts() ) {
		$labels        = array();
		$custom_labels = array();

		while ( $query->have_posts() ) {
			$query->the_post();

			$request_id              = get_the_ID();
			$status                  = get_post_meta( $request_id, 'status', true );
			$status_title            = isset( hms_request_statuses()[ $status ] ) ? hms_request_statuses()[ $status ]['title'] : __( 'Undefined', 'hackathon' );
			$role                    = hms_get_role_name( get_post_meta( $request_id, 'role', true ) );
			$requests[ $request_id ] = array(
				__( 'Request Status', 'hackathon' ) => sanitize_text_field( $status_title ),
				__( 'Role', 'hackathon' )           => $role,
				__( 'Username', 'hackathon' )       => get_post_meta( $request_id, 'user_login', true ),
				__( 'Email', 'hackathon' )          => get_post_meta( $request_id, 'user_email', true ),
				__( 'First Name', 'hackathon' )     => get_post_meta( $request_id, 'first_name', true ),
				__( 'Last Name', 'hackathon' )      => get_post_meta( $request_id, 'last_name', true ),
			);

			if ( get_post_meta( $request_id, 'phone', true ) ) {
				$labels['phone'] = __( 'Phone', 'hackathon' );
			}

			if ( get_post_meta( $request_id, 'telegram', true ) ) {
				$labels['telegram'] = __( 'Telegram', 'hackathon' );
			}

			if ( get_post_meta( $request_id, 'city', true ) ) {
				$labels['city'] = __( 'City', 'hackathon' );
			}

			if ( get_post_meta( $request_id, 'project_name', true ) ) {
				$labels['project_name'] = __( 'Project name', 'hackathon' );
			}

			if ( get_post_meta( $request_id, 'custom', true ) ) {
				foreach ( get_post_meta( $request_id, 'custom', true ) as $field ) {
					if ( isset( $field['label'] ) && $field['label'] ) {
						$label                   = $field['label'];
						$custom_labels[ $label ] = $label;
					}
				}
			}
		}

		while ( $query->have_posts() ) {
			$query->the_post();

			$request_id = get_the_ID();

			foreach ( $labels as $slug => $label ) {
				$value = '';
				if ( get_post_meta( $request_id, $slug, true ) ) {
					$value = get_post_meta( $request_id, $slug, true );
				}
				$requests[ $request_id ][ $label ] = $value;
			}

			foreach ( $custom_labels as $label => $item ) {
				$value = '';

				if ( get_post_meta( $request_id, 'custom', true ) ) {
					foreach ( get_post_meta( $request_id, 'custom', true ) as $field ) {
						if ( isset( $field['label'] ) && $field['label'] ) {
							$field_label = $field['label'];
							if ( $field_label == $label && isset( $field['value'] ) && $field['value'] ) {
								$value = $field['value'];
							}
						}
					}
				}

				$requests[ $request_id ][ $label ] = $value;
			}
		}

		$requests_head = $requests;
		$requests_head = array_shift( $requests_head );
		$requests_head = array_keys( $requests_head );
		array_unshift( $requests, $requests_head );

	}

	wp_reset_postdata();

	return $requests;
}

/**
 * Get csv requests
 */
function hms_get_csv_materials( $args = array() ) {

	$default_args = array(
		'post_type'      => 'hms_material',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $default_args );

	$query = new WP_Query( $parsed_args );

	$materials = array();

	if ( $query->have_posts() ) {

		while ( $query->have_posts() ) {
			$query->the_post();

			$material_id = get_the_ID();
			$type        = get_post_meta( $material_id, 'type', true );
			$team_id     = get_post_meta( $material_id, 'team_id', true );
			$user_id     = get_post_field( 'post_author', $material_id );

			$fields        = get_post_meta( $material_id, '_fields', true );
			$materials_str = '';
			foreach ( $fields as $key => $field ) {
				$materials_str .= $field['label'] . ': ' . $field['value'] . "\n";
			}

			if ( 'final_presentation' === $type ) {
				$materials_str .= esc_html__( 'Presentation', 'hackathon' ) . ': ' . "\n";

				$files = explode( ',', get_post_meta( $material_id, 'final_files', true ) );
				if ( $files ) {
					foreach ( $files as $file_id ) {
						$file           = get_attached_file( $file_id );
						$file_basename  = wp_basename( $file );
						$file_url       = wp_get_attachment_url( $file_id );
						$materials_str .= $file_url . "\n";
					}
				}
			}

			$materials[ $material_id ] = array(
				__( 'Date', 'hackathon' )      => get_the_date( 'F j, Y - H:i:s' ),
				__( 'Title', 'hackathon' )     => get_the_title(),
				__( 'Type', 'hackathon' )      => hms_get_material_type_name( $type ),
				__( 'Team', 'hackathon' )      => get_the_title( $team_id ),
				__( 'Author', 'hackathon' )    => get_user_option( 'display_name', $user_id ),
				__( 'Materials', 'hackathon' ) => $materials_str,
			);
		}

		$materials_head = $materials;
		$materials_head = array_shift( $materials_head );
		$materials_head = array_keys( $materials_head );
		array_unshift( $materials, $materials_head );

	}

	wp_reset_postdata();

	return $materials;
}

/**
 * Get csv teams
 */
function hms_get_csv_teams( $args = array() ) {

	$default_args = array(
		'post_type'      => 'hms_team',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $default_args );

	$query = new WP_Query( $parsed_args );

	$teams = array();

	if ( $query->have_posts() ) {
		$participants = '';
		$mentors      = '';
		while ( $query->have_posts() ) {
			$query->the_post();

			$team_id = get_the_ID();

			$team_users = (array) hms_get_team_users( $team_id );

			$participants = '';

			if ( $team_users ) {
				$hms_args  = array(
					'include'  => $team_users,
					'role__in' => array( 'hackathon_participant' ),
					'orderby'  => 'include',
				);
				$hms_users = get_users( $hms_args );

				if ( $hms_users ) {
					foreach ( $hms_users as $user ) {
						$participants .= $user->display_name . ' ' . $user->user_email . "\n";
					}
				}
			}

			$team_users = (array) hms_get_team_users( $team_id );

			$mentors = '';

			if ( $team_users ) {
				$hms_args  = array(
					'include'  => $team_users,
					'role__in' => array( 'hackathon_mentor' ),
					'orderby'  => 'include',
				);
				$hms_users = get_users( $hms_args );

				if ( $hms_users ) {
					foreach ( $hms_users as $user ) {
						$mentors .= $user->display_name . ' ' . $user->user_email . "\n";
					}
				}
			}

			$teams[ $team_id ] = array(
				__( 'Title', 'hackathon' )                => get_the_title(),
				__( 'List of participants', 'hackathon' ) => $participants,
				__( 'List of mentors', 'hackathon' )      => $mentors,
			);
		}

		$teams_head = $teams;
		$teams_head = array_shift( $teams_head );
		$teams_head = array_keys( $teams_head );
		array_unshift( $teams, $teams_head );

	}

	wp_reset_postdata();

	return $teams;
}

/**
 * Export CSV
 */
function hms_before_load_content() {
	if ( isset( $_GET['get_csv'] ) ) {

		$content_type    = get_query_var( 'hms_subpage' );
		$content_subtype = get_query_var( 'hms_subsubpage' );

		$args = array();
		if ( isset( $_GET['search'] ) ) {
			$args['search'] = $_GET['search'];
			$args['s']      = $_GET['search'];
		}

		if ( isset( $_GET['s'] ) ) {
			$args['s'] = $_GET['s'];
		}

		if ( isset( $_GET['status'] ) ) {
			$args['status'] = $_GET['status'];
		}

		if ( isset( $_GET['meta_key'] ) ) {
			$args['meta_key'] = $_GET['meta_key'];

			if ( isset( $_GET['meta_value'] ) ) {
				$args['meta_value'] = $_GET['meta_value'];
			}
		}

		$file_name = 'hms-' . $content_type . '-' . gmdate( 'd.m.Y-' ) . time() . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $file_name . '"' );

		$csv_file = '';

		if ( isset( $_GET['get_csv'] ) == $content_type ) {

			$upload_dir = (object) wp_upload_dir();

			$upload_dir_path = '';

			if ( ! $upload_dir->error ) {
				$upload_dir_path = $upload_dir->path . '/';
			}

			if ( $content_type === 'users' ) {
				$role = 'all';
				if ( $content_subtype ) {
					$role = $content_subtype;
				}
				$csv_file = hms_create_csv_file( hms_get_csv_users( $role, $args ), $upload_dir_path . $file_name, ';' );
			} elseif ( $content_type === 'requests' ) {
				$csv_file = hms_create_csv_file( hms_get_csv_requests( $args ), $upload_dir_path . $file_name, ';' );
			} elseif ( $content_type === 'materials' ) {
				$csv_file = hms_create_csv_file( hms_get_csv_materials( $args ), $upload_dir_path . $file_name, ';' );
			} elseif ( $content_type === 'teams' ) {
				$csv_file = hms_create_csv_file( hms_get_csv_teams( $args ), $upload_dir_path . $file_name, ';' );
			}

			echo $csv_file;
		}
		exit();
	}
}
add_action( 'hms_before_load_content', 'hms_before_load_content' );
