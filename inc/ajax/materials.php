<?php
/**
 * Materials
 */

/**
 * Initial Presentation
 */
function hms_ajax_initial_presentation() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_POST, 'sanitize_text_field' );
	$fields = $data;

	$data['message'] = esc_html__( 'Fill in required fields', 'hackathon' );

	if ( ! isset( $_POST['post_title'] ) || ! $_POST['post_title'] ) {
		$data['field']   = 'post_title';
		wp_send_json_error( $data );
	}

	$exclude_fields = array(
		'action',
		'nonce',
		'post_id',
		'redirect_to',
		'post_title',
		'_wp_http_referer',
	);

	foreach( $fields as $key => $field ) {
		if ( in_array( $key, $exclude_fields ) ) {
			unset($fields[ $key ]);
		}
	}

	foreach( $fields as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			$data['field'] = $key;
			wp_send_json_error( $data );
		}
	}

	$post_title         = sanitize_text_field( $_POST['post_title'] );
	$team_id            = sanitize_text_field( $_POST['post_id'] );

	$form_fields = get_post_meta( hms_get_option('prezentationform'), '_form_fields' , true );

	$meta_fields =  array();
	foreach( $fields as $key => $field ) {
		$meta_fields[ $key ]['value'] = $field;
		$meta_fields[ $key ]['label'] = $form_fields[ $key ]['label'];
	}

	$post_data = array(
		'post_type'    => 'hms_material',
		'post_title'   => $post_title,
		'post_status'  => 'publish',
		'meta_input'   => array(
			'_fields' => $meta_fields,
			'team_id' => $team_id,
			'type'    => 'initial_presentation',
		),
	);

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if ( is_wp_error( $post_id ) ){

		$data['message'] = $post_id->get_error_message();
		wp_send_json_error( $data );

	} else {

		//hms_send_email_new_team( $team_id, $post_id );
		hms_insert_log_material( $post_id, $team_id );
		$data['post_id'] = $post_id;
		$data['message'] = esc_html__( 'Project presentation successfully published', 'hackathon' );
		wp_send_json_success( $data );
	}

}
add_action( 'wp_ajax_hackathon_initial_presentation', 'hms_ajax_initial_presentation' );

/**
 * Checkpoint report
 */
function hms_ajax_checkpoint_report() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_POST, 'sanitize_text_field' );

	$fields = $data;

	$data['message'] = esc_html__( 'Fill in required fields', 'hackathon' );

	$exclude_fields = array(
		'action',
		'nonce',
		'post_id',
		'form_id',
		'redirect_to',
		'post_title',
		'_wp_http_referer',
	);

	foreach( $fields as $key => $field ) {
		if ( in_array( $key, $exclude_fields ) ) {
			unset($fields[ $key ]);
		}
	}

	$post_title = sanitize_text_field( $_POST['post_title'] );
	$team_id    = sanitize_text_field( $_POST['post_id'] );
	$form_id    = sanitize_text_field( $_POST['form_id'] );

	foreach( $fields as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			$data['field'] = $key;
			wp_send_json_error( $data );
		}
	}

	$form_fields = get_post_meta( $form_id, '_form_fields' , true );

	$meta_fields =  array();
	foreach( $fields as $key => $field ) {
		$meta_fields[ $key ]['value'] = $field;
		$meta_fields[ $key ]['label'] = $form_fields[ $key ]['label'];
	}

	$post_data = array(
		'post_type'    => 'hms_material',
		'post_title'   => $post_title,
		'post_status'  => 'publish',
		'meta_input'   => array(
			'_fields' => $meta_fields,
			'team_id' => $team_id,
			'form_id' => $form_id,
			'type'    => 'checkpoint_report',
		),
	);

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if ( is_wp_error( $post_id ) ){

		$data['message'] = $post_id->get_error_message();
		wp_send_json_error( $data );

	} else {

		//hms_send_email_new_team( $team_id, $post_id );
		hms_insert_log_material( $post_id, $team_id );
		$data['post_id'] = $post_id;
		$data['message'] = esc_html__( 'Checkpoint successfully published', 'hackathon' );
		wp_send_json_success( $data );
	}

}
add_action( 'wp_ajax_hackathon_checkpoint_report', 'hms_ajax_checkpoint_report' );

/**
 * Final presentation
 */
function hms_ajax_final_presentation() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_POST, 'sanitize_text_field' );

	$fields = $data;

	$data['message'] = esc_html__( 'Fill in required fields', 'hackathon' );

	$exclude_fields = array(
		'action',
		'nonce',
		'post_id',
		'redirect_to',
		'post_title',
		'_wp_http_referer',
		'final_files',
	);

	foreach( $fields as $key => $field ) {
		if ( in_array( $key, $exclude_fields ) ) {
			unset($fields[ $key ]);
		}
	}

	foreach( $fields as $key => $field ) {
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			$data['field'] = $key;
			wp_send_json_error( $data );
		}
	}

	if ( ! isset( $_POST['final_files'] ) || ! $_POST['final_files'] ) {
		$data['field']   = 'final_files';
		$data['message'] = esc_html__( 'Please upload presentation', 'hackathon' );
		wp_send_json_error( $data );
	}

	$form_fields = get_post_meta( hms_get_option('finalform'), '_form_fields' , true );

	$meta_fields =  array();
	foreach( $fields as $key => $field ) {
		$meta_fields[ $key ]['value'] = $field;
		$meta_fields[ $key ]['label'] = $form_fields[ $key ]['label'];
	}

	$post_title        = sanitize_text_field( $_POST['post_title'] );
	$final_files       = sanitize_text_field( $_POST['final_files'] );
	$team_id           = sanitize_text_field( $_POST['post_id'] );

	$post_data = array(
		'post_type'    => 'hms_material',
		'post_title'   => $post_title,
		'post_status'  => 'publish',
		'meta_input'   => array(
			'_fields' => $meta_fields,
			'final_files'       => $final_files,
			'team_id' => $team_id,
			'type'    => 'final_presentation',
		),
	);

	$post_id = wp_insert_post( wp_slash( $post_data ) );

	if ( is_wp_error( $post_id ) ){

		$data['message'] = $post_id->get_error_message();
		wp_send_json_error( $data );

	} else {

		hms_send_email_final_solution( $team_id, $post_id );
		hms_insert_log_material( $post_id, $team_id );
		$data['post_id'] = $post_id;
		$data['message'] = esc_html__( 'Final presentation successfully published', 'hackathon' );
		wp_send_json_success( $data );
	}

}
add_action( 'wp_ajax_hackathon_final_presentation', 'hms_ajax_final_presentation' );
