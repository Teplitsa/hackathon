<?php
/**
 * Form Actions
 */
function hms_ajax_new_form() {

	check_ajax_referer( 'hms-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['name'] ) && $_POST['name'] ) {

		if ( isset( $_POST['slug'] ) && $_POST['slug'] ) {

			$form_type = $_POST['form_type'];

			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => -1,
				'meta_key'       => '_form_slug',
				'meta_value'     => sanitize_title( $_POST['slug'] ),
				'post_status'    => 'any',
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				$data['message'] = esc_html__( 'A form with this url already exists!', 'hackathon' );
				wp_send_json_error( $data );
			}

			if ( in_array( $_POST['slug'], hms_pages() ) ) {
				$data['message'] = esc_html__( 'This url is already busy.', 'hackathon' );
				wp_send_json_error( $data );
			}

			wp_reset_postdata();

			$form_data = array(
				'post_type'   => 'hms_form',
				'post_title'  => sanitize_text_field( $_POST['name'] ),
				'post_status' => 'publish',
				'meta_input'  => array(
					'_form_slug' => sanitize_title( trim( $_POST['slug'] ) ),
					'_form_type' => sanitize_title( $_POST['form_type'] ),
				),
			);

			if ( $form_type == 'intrasystem' ) {
				unset( $form_arr['meta_input']['_form_slug'] );
			}

			$form_id = wp_insert_post( $form_data );

			$data['redirect_to'] = hms_get_url( 'form/' . $form_id . '/' );
			wp_send_json_success( $data );

		} else {
			$data['message'] = esc_html__( 'Form url cannot be empty', 'hackathon' );
			wp_send_json_error( $data );
		}
	} else {
		$data['message'] = esc_html__( 'Form name cannot be empty', 'hackathon' );
		wp_send_json_error( $data );
	}
}
add_action( 'wp_ajax_hms_new_form', 'hms_ajax_new_form' );

/**
 * Delete form
 */
function hms_ajax_delete_form() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['form_id'] ) && $_POST['form_id'] ) {
		wp_delete_post( $_POST['form_id'] );

		$data['message'] = esc_html__( 'The form was successfully deleted', 'hackathon' );
		wp_send_json_success( $data );
	}

	$data['message'] = esc_html__( 'Unknown error, write to the site admin', 'hackathon' );
	wp_send_json_error( $data );
}
add_action( 'wp_ajax_hms_delete_form', 'hms_ajax_delete_form' );

/**
 * Update form
 */
function hms_ajax_update_form() {

	check_ajax_referer( 'hms-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['form_id'] ) && $_POST['form_id'] ) {

		$data['validate'] = false;
		$form_id          = $_POST['form_id'];
		$form_title       = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$form_slug        = isset( $_POST['slug'] ) ? sanitize_text_field( $_POST['slug'] ) : '';
		$form_role        = isset( $_POST['role'] ) ? sanitize_text_field( $_POST['role'] ) : 'hackathon_participant';
		$form_fields      = isset( $_POST['field'] ) ? $_POST['field'] : array();
		$form_type        = get_post_meta( $form_id, '_form_type', true );

		foreach ( $form_fields as $field ) {
			if ( isset( $field['label'] ) && empty( trim( $field['label'] ) ) ) {
				$data['message']  = esc_html__( 'Please fill in the required fields.', 'hackathon' );
				$data['validate'] = true;
				wp_send_json_error( $data );
			}
		}

		if ( isset( $form_fields['custom'] ) && is_array( $form_fields['custom'] ) ) {
			foreach ( $form_fields['custom'] as $key => $field ) {
				if ( empty( $field['label'] ) ) {
					unset( $form_fields['custom'][ $key ] );
				}
			}
		}

		$form_arr = array(
			'ID'         => $form_id,
			'post_title' => $form_title,
			'meta_input' => array(
				'_form_slug'   => $form_slug,
				'_form_role'   => $form_role,
				'_form_fields' => $form_fields,
			),
		);

		if ( $form_type !== 'intrasystem' ) {

			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'meta_key'       => '_form_slug',
				'meta_value'     => $form_slug,
				'post__not_in'   => array( $form_id ),
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				$data['message'] = esc_html__( 'A form with this url already exists!', 'hackathon' );
				wp_send_json_error( $data );
			}
		} else {
			unset( $form_arr['meta_input']['_form_slug'] );
		}

		wp_update_post( wp_slash( $form_arr ) );

		wp_send_json_success( $data );
	}

	$data['message'] = esc_html__( 'Unknown error, write to the site admin', 'hackathon' );
	wp_send_json_error( $data );
}
add_action( 'wp_ajax_hms_update_form', 'hms_ajax_update_form' );

/**
 * Add new field
 */
function hms_ajax_add_new_field() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	$order   = isset( $_POST['order'] ) && ! empty( $_POST['order'] ) ? $_POST['order'] : 0;
	$form_id = isset( $_POST['form_id'] ) && ! empty( $_POST['form_id'] ) ? $_POST['form_id'] : '';

	ob_start();

	hms_field_object( $order, array(), $form_id );

	$html = ob_get_clean();

	$data['html'] = $html;
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hms_add_new_field', 'hms_ajax_add_new_field' );

/**
 * Replace field
 */
function hms_ajax_replace_field() {

	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	$order = isset( $_POST['order'] ) && ! empty( $_POST['order'] ) ? $_POST['order'] : 0;
	$field = isset( $_POST['field'] ) ? $_POST['field'] : array();

	ob_start();

	hms_field_object( $order, $field );

	$html = ob_get_clean();

	$data['html'] = $html;
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hms_replace_field', 'hms_ajax_replace_field' );

/**
 * Add Option
 */
function hms_ajax_add_option() {
	check_ajax_referer( 'hackathon-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	$index = isset( $_POST['index'] ) && ! empty( $_POST['index'] ) ? $_POST['index'] : 0;

	ob_start();

	?>

	<div class="hms-select-option">
		<div class="hms-select-drag">
			<?php hms_icon( 'drag' ); ?>
		</div>
		<input type="text" name="field[custom_<?php echo esc_attr( $index ); ?>][options][]" data-type="options" class="hms-field-input" value="">
		<div class="hms-select-option-remove">
			<?php hms_icon( 'close-thin' ); ?>
		</div>
	</div>

	<?php

	$html = ob_get_clean();

	$data['html'] = $html;
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_hms_add_option', 'hms_ajax_add_option' );

/* Intrasystem forms */
function hms_ajax_new_intrasystem_form() {

	check_ajax_referer( 'hms-nonce', 'nonce' );

	$data = map_deep( $_REQUEST, 'sanitize_text_field' );

	if ( isset( $_POST['name'] ) && $_POST['name'] ) {

		$form_data = array(
			'post_type'   => 'hms_form',
			'post_title'  => sanitize_text_field( $_POST['name'] ),
			'post_status' => 'publish',
			'meta_input'  => array(
				'_form_type' => sanitize_title( $_POST['form_type'] ),
			),
		);

		$form_id = wp_insert_post( $form_data );

		$data['redirect_to'] = hms_get_url( 'form/' . $form_id . '/' );
		wp_send_json_success( $data );

	} else {
		$data['message'] = esc_html__( 'Form name cannot be empty', 'hackathon' );
		wp_send_json_error( $data );
	}
}
add_action( 'wp_ajax_hms_new_intrasystem_form', 'hms_ajax_new_intrasystem_form' );
