<?php
/**
 * Forms
 */

/**
 * Form input
 */
function hms_input_attributes( $attributes = array() ) {

	$defaults = array(
		'type'  => 'text',
		'name'  => '',
		'value' => '',
		'id'    => '',
	);

	$attributes = wp_parse_args( (array) $attributes, $defaults );

	$html_attributes = '';

	foreach( $attributes as $attr => $value ) {
		if ( $value ) {
			$html_attributes .= ' ' . $attr . '="' . $value . '"';
		}
	}

	return trim( $html_attributes );

}

/**
 * Form input
 */
function hms_input( $attributes = array(), $options = array() ) {
	$input = '<input ' . hms_input_attributes( $attributes ) . '>';
	if ( 'textarea' === $attributes['type'] ) {
		$value = '';
		if ( isset( $attributes['value'] ) ){
			$value = $attributes['value'];
			unset( $attributes['value'] );
		}
		$input = '<textarea ' . hms_input_attributes( $attributes ) . '>' . $value . '</textarea>';
	} else if ( 'select' === $attributes['type'] ) {
		$html_options = '';
		if ( is_array( $options ) && $options ) {
			foreach( $options as $option => $value ) {
				$html_options .= '<option value="' . $option . '">' . $value . '</option>';
			}
		}
		$input = '<select ' . hms_input_attributes( $attributes ) . '>' . $html_options . '</select>';
	} else if ( 'html' === $attributes['type'] ) {
		$value = '';
		if ( isset( $attributes['html'] ) ){
			$value = $attributes['html'];
		}
		$input = $value;
	}
	return $input;
}

/**
 * Form input
 */
function hms_input_hidden( $name = '', $value = '' ) {
	return hms_input(
		array(
			'type'  => 'hidden',
			'name'  => $name,
			'value' => $value,
		)
	);
}

/**
 * Get dashboard slug
 */
function hms_field( $args = array() ) {

	$defaults = array(
		'name'        => '',
		'label_value' => '',
		'attributes'  => array(),
		'options'     => array(),
		'label'       => '',
		'invalid'     => '',
		'description' => '',
	);

	$args = wp_parse_args( (array) $args, $defaults );
	$args['attributes']['id']   = $args['name'];
	$args['attributes']['name'] = $args['name'];

	$attributes = $args['attributes'];
	$options    = $args['options'];

	if ( 'html' === $attributes['type'] && isset( $args['html'] ) ) {
		$input = '<span class="custom-html">' . wp_unslash( $args['html'] ) . '</span>';
	} else {
		$input = hms_input( $attributes, $options );
	}

	if ( $args['label_value'] ) {
		$args['label'] = $args['label_value'];
	}

	$before_field = '';
	if ( $args['label'] ) {
		$required = '';
		if ( isset( $args['attributes']['required'] ) && 'required' === $args['attributes']['required'] ) {
			$required = ' <span class="hms-error-color">*</span>';
		}
		$before_field = '<label class="hms-form-label" for="' . $attributes['name'] . '">' . $args['label'] . $required . '</label>';
	}

	$after_field = '';

	if ( $args['description'] ) {
		$after_field = '<div class="hms-form-text">' . wp_unslash( nl2br( $args['description'] ) ) . '</div>';
	}

	$field = $before_field . $input . $after_field;

	return $field;
}

/**
 * Is Phone
 */
function hms_is_phone( $phone ) {
	return preg_match('/^[0-9]+$/', $phone );
}

/**
 * Default strings
 */
function hms_default_strings() {
	$strings = array(
		'agreement_description' => esc_html__( 'I agree to process my personal data to the organizer of the hms.', 'hackathon' ),
	);
	return apply_filters( 'hms_default_srings', $strings );
}

/**
 * Get string
 */
function hms_get_string( $slug ) {
	$string = false;
	$strings = hms_default_strings();
	if ( $slug && array_key_exists( $slug, hms_default_strings() ) ) {
		$string = $strings[ $slug ];
	}
	return $string;
}

/**
 * Editor
 */
function hms_editor( $content = '', $name = '', $args = array(), $mailtags = array() ){
	if ( ! $name ) {
		return;
	}

	if ( $content === $name ) {
		$default = '';
		$notifications = hms_email_notifications();
		if ( isset( $notifications[ $name ] ) ) {
			$default = $notifications[ $name ]['body'];
		}
		$content = html_entity_decode( stripslashes( hms_get_option( $name, $default ) ) );
	}

	$editor_id = 'hackathon_' . $name;

	$tinymce = array(
		'body_class'    => 'hms-editor-body',
		'content_css'   => HMS_URL . 'assets/css/style-editor.css?ver=1.0.0',
		'block_formats' => esc_html__( 'Paragraph', 'hackathon' ) . '=p;' . esc_html__( 'Heading', 'hackathon' ) . '=h2',
		'toolbar1'      => 'formatselect,bold,bullist,numlist,link,hmsmailtags',
		'toolbar2'      => '',
		'mailtags'      =>  implode( ',', apply_filters( 'hms_mailtags', $mailtags, $editor_id ) ),
	);

	$defaults  = array(
		'wpautop'          => 1,
		'media_buttons'    => false,
		'textarea_name'    => $name,
		'textarea_rows'    => 10,
		'quicktags'        => false,
		'drag_drop_upload' => false,
		'tinymce'          => $tinymce,
	);

	$settings = wp_parse_args( (array) $args, $defaults );

	wp_editor( $content, $editor_id, $settings );
}

function hms_add_mailtags( $mailtags, $editor_id ){

	if ( 'hackathon_mail_register' === $editor_id || 'hackathon_mail_reset' === $editor_id ) {
		$mailtags = array(
			'site_name',
			'user_login',
			'password_reset_link',
		);
	}

	if ( 'hackathon_mail_status_processing' === $editor_id
		|| 'hackathon_mail_status_approved' === $editor_id
		|| 'hackathon_mail_status_rejected' === $editor_id
		|| 'hackathon_mail_status_cancelled' === $editor_id ) {
		$mailtags = array(
			'site_name',
			'user_login',
			'user_status',
			'password_reset_link',
		);
	}

	if ( 'hackathon_mail_new_team' === $editor_id ) {
		$mailtags = array(
			'site_name',
			'team_name',
			'team_url',
		);
	}

	if ( 'hackathon_mail_team_form' === $editor_id ) {
		$mailtags = array(
			'site_name',
			'team_name',
			'team_url',
			'material_type',
		);
	}

	return $mailtags;
}
add_filter('hms_mailtags', 'hms_add_mailtags', 10, 2 );

/* Create default form */
function hms_create_default_forms() {
	if ( ! hms_get_option( 'defaultform' ) ) {
		$form_data = array(
			'post_type'   => 'hms_form',
			'post_title'  => sanitize_text_field( esc_html__( 'Default Register Form', 'hackathon' ) ),
			'post_status' => 'private',
			'meta_input'  => array(
				'_form_slug' => 'register',
				'_form_type' => 'default',
			),
		);

		$form_id = wp_insert_post( $form_data );

		hms_update_option( 'defaultform', $form_id );
	}

	if ( ! hms_get_option( 'prezentationform' ) ) {

		$form_fields = array(
			'custom_0' => array(
				'label' => esc_html__( 'Problem being solved', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_1' => array(
				'label' => esc_html__( 'Solution that is proposed', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_2' => array(
				'label' => esc_html__( 'Technologies and project data', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_3' => array(
				'label' => esc_html__( 'What is already there?', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_4' => array(
				'label' => esc_html__( 'What is planned to be done during the hackathon', 'hackathon' ),
				'type' => 'textarea',
			),
		);

		$form_data = array(
			'post_type'   => 'hms_form',
			'post_title'  => sanitize_text_field( esc_html__( 'Project name', 'hackathon' ) ),
			'post_status' => 'private',
			'meta_input'  => array(
				'_form_slug'   => 'prezentation',
				'_form_type'   => 'intrasystem',
				'_form_fields' => $form_fields,
			),
		);

		$form_id = wp_insert_post( $form_data );

		hms_update_option( 'prezentationform', $form_id );
	}

	if ( ! hms_get_option( 'finalform' ) ) {

		$form_fields = array(
			'custom_0' => array(
				'label' => esc_html__( 'Description of the final project', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_1' => array(
				'label' => esc_html__( 'Link to source code', 'hackathon' ),
				'type' => 'text',
			),
			'custom_2' => array(
				'label' => esc_html__( 'Link to other materials', 'hackathon' ),
				'type' => 'textarea',
			),
			'custom_3' => array(
				'label' => esc_html__( 'Presentation', 'hackathon' ),
				'type' => 'image',
			),
		);

		$form_data = array(
			'post_type'   => 'hms_form',
			'post_title'  => sanitize_text_field( esc_html__( 'Final Solution', 'hackathon' ) ),
			'post_status' => 'private',
			'meta_input'  => array(
				'_form_slug'   => 'final',
				'_form_type'   => 'intrasystem',
				'_form_fields' => $form_fields,
			),
		);

		$form_id = wp_insert_post( $form_data );

		hms_update_option( 'finalform', $form_id );
	}
}

/* Clear defaultform option */
function hms_after_delete_form( $form_id, $form ){
	if ( $form->post_type == 'hms_form' && $form_id == hms_get_option( 'defaultform' ) ) {
		hms_delete_option( 'defaultform' );
		hms_delete_option( 'prezentationform' );
		hms_delete_option( 'finalform' );
	}
}
add_action( 'after_delete_post', 'hms_after_delete_form', 10, 2 );
