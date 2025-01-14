<?php
/**
 * Scripts
 */

/**
 * Localizes the jQuery UI datepicker.
 */
function hms_localize_jquery_ui_datepicker() {
	global $wp_locale;

	// Convert the PHP date format into jQuery UI's format.
	$datepicker_date_format = str_replace(
		array(
			'd',
			'j',
			'l',
			'z',
			'F',
			'M',
			'n',
			'm',
			'Y',
			'y',
		),
		array(
			'dd',
			'd',
			'DD',
			'o',
			'MM',
			'M',
			'm',
			'mm',
			'yy',
			'y',
		),
		get_option( 'date_format' )
	);

	$datepicker_defaults = wp_json_encode(
		array(
			'monthNames'      => array_values( $wp_locale->month ),
			'monthNamesShort' => array_values( $wp_locale->month_abbrev ),
			'dayNames'        => array_values( $wp_locale->weekday ),
			'dayNamesShort'   => array_values( $wp_locale->weekday_abbrev ),
			'dayNamesMin'     => array_values( $wp_locale->weekday_initial ),
			'dateFormat'      => $datepicker_date_format,
			'firstDay'        => absint( get_option( 'start_of_week' ) ),
			'isRTL'           => $wp_locale->is_rtl(),
		)
	);

	wp_add_inline_script( 'hms-timepicker', "jQuery(function(jQuery){jQuery.datepicker.setDefaults({$datepicker_defaults});});" );
}

/**
 * Localize countdown
 */
function hms_localize_countdown() {

	$countdown_defaults = wp_json_encode(
		array(
			'compactLabels' => array(
				_x( 'y', 'countdown', 'hackathon' ),
				_x( 'm', 'countdown', 'hackathon' ),
				_x( 'w', 'countdown', 'hackathon' ),
				_x( 'd', 'countdown', 'hackathon' ),
			),
		)
	);

	wp_add_inline_script( 'hms-countdown', "(function( $ ){ $.countdown.setDefaults({$countdown_defaults});})( jQuery );" );
}

/**
 * Localize jQuery validation plugin
 */
function hms_localize_validate() {

	$messages = wp_json_encode(
		array(
			'required'    => esc_html__( 'This field is required.', 'hackathon' ),
			'email'       => esc_html__( 'Please enter a valid email address.', 'hackathon' ),
			'url'         => esc_html__( 'Please enter a valid URL.', 'hackathon' ),
			'date'        => esc_html__( 'Please enter a valid date.', 'hackathon' ),
			'number'      => esc_html__( 'Please enter a valid number.', 'hackathon' ),
			'digits'      => esc_html__( 'Please enter only digits.', 'hackathon' ),
			'maxlength'   => esc_html__( 'Please enter no more than {0} characters.', 'hackathon' ),
			'minlength'   => esc_html__( 'Please enter at least {0} characters.', 'hackathon' ),
			'rangelength' => esc_html__( 'Please enter a value between {0} and {1} characters long.', 'hackathon' ),
			'range'       => esc_html__( 'Please enter a value between {0} and {1}.', 'hackathon' ),
			'max'         => esc_html__( 'Please enter a value less than or equal to {0}.', 'hackathon' ),
			'min'         => esc_html__( 'Please enter a value greater than or equal to {0}', 'hackathon' ),
			'lettersonly' => esc_html__( 'Please enter only latin letters.', 'hackathon' ),
		)
	);

	wp_add_inline_script( 'jquery-validate', "(function( $ ){ $.extend( $.validator.messages, $messages );})( jQuery );" );
}

/**
 * Register styles
 */
function hms_register_styles() {
	global $wp_version;

	$dependencies = array(
		'hms-common',
		'hms-dashicons',
		'hms-forms',
		'hms-buttons',
		'hms-login',
		'hms-jquery-ui',
	);

	if ( is_user_logged_in() && ! isset( $_GET['preview-form'] ) ) {
		$load_url = admin_url() . 'load-styles.php';

		$admin_styles = array(
			'dashicons',
			'forms',
			'buttons',
			'admin-bar',
			'wp-components',
			'wp-widgets',
			'wp-block-editor',
			'wp-nux',
			'wp-reusable-blocks',
			'wp-editor',
			'common',
			'wp-reset-editor-styles',
			'yles',
			'wp-block-library',
			'wp-editor-classic-layout-styles',
			'wp-edit-blocks',
			'wp-edit-widgets',
			'wp-format-library',
			'admin-menu',
			'dashboard',
			'list-tables',
			'edit',
			'revisions',
			'media',
			'themes',
			'about',
			'nav-menus',
			'wp-pointer',
			'widgets',
			'site-icon',
			'l10n',
			'wp-auth-check',
			'wp-block-library-theme',
		);

		$admin_styles;

		$args = array(
			'load' => implode( ',', $admin_styles ),
		);

		$url = add_query_arg( $args, $load_url );

		wp_register_style( 'wp-style', $url, array(), $wp_version );

		$dependencies[] = 'wp-style';

		wp_enqueue_media();

	}

	$hms_wp_version = get_bloginfo('version');

	wp_register_style( 'hms-common', HMS_URL . 'assets/css/common.min.css', array(), $hms_wp_version );
	wp_register_style( 'hms-dashicons', includes_url( 'css' ) . '/dashicons.min.css', array(), $hms_wp_version );
	wp_register_style( 'hms-forms', HMS_URL . 'assets/css/forms.min.css', array(), $hms_wp_version );
	wp_register_style( 'hms-buttons', includes_url( 'css' ) . '/buttons.min.css', array(), $hms_wp_version );
	wp_register_style( 'hms-login', HMS_URL . 'assets/css/login.min.css', array(), $hms_wp_version );
	wp_register_style( 'hms-jquery-ui', HMS_URL . 'assets/css/jquery-ui.css', array(), $hms_wp_version );
	wp_register_style( 'hms-style', HMS_URL . 'assets/css/style.css', $dependencies, HMS_VER );

	if ( wp_get_attachment_image_url( hms_option( 'event_logo' ) ) ) {
		$custom_css = '.login h1 a {background-image: url(' . wp_get_attachment_image_url( hms_option( 'event_logo' ) ) . ')}';
		wp_add_inline_style( 'hms-style', apply_filters( 'hms_inline_style', $custom_css ) );
	}
}
add_action( 'template_redirect', 'hms_register_styles' );

/**
 * Login scripts
 */
function hms_login_enqueue_scripts() {
	$custom_css = '.login h1 a {background-image: url(' . esc_url( hms_get_logo_url() ) . ')}';
	wp_enqueue_style( 'hms-style', HMS_URL . 'assets/css/style.css', array( 'login' ), HMS_VER );
	wp_add_inline_style( 'hms-style', apply_filters( 'hms_inline_style', $custom_css ) );
}
add_action( 'login_enqueue_scripts', 'hms_login_enqueue_scripts' );

/**
 * Scripts
 */
function hms_register_scripts() {

	$dependencies = array( 'jquery-core' );

	$vars = array(
		'ajaxurl'     => admin_url( 'admin-ajax.php' ),
		'date_format' => hms_js_date_format(),
		'time_format' => hms_js_time_format(),
		'i18n'        => array(
			'timeText'      => esc_html__( 'Time', 'hackathon' ),
			'currentText'   => esc_html__( 'Now', 'hackathon' ),
			'closeText'     => esc_html__( 'Done', 'hackathon' ),
			'successCopy'   => esc_html__( 'Text successfully copied to clipboard!', 'hackathon' ),
			'availableTags' => esc_html__( 'Available {tags}', 'hackathon' ),
		),
	);

	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
		if ( 'edit-user' === get_query_var( 'hms_subpage' ) && get_query_var( 'hms_subsubpage' ) ) {
			$user_id = get_query_var( 'hms_subsubpage' );
		}
		$vars['nonce_reset']      = wp_create_nonce( 'reset-password-for-' . $user_id );
		$vars['nonce']            = wp_create_nonce( 'hackathon-nonce' );
		$vars['user_id']          = $user_id;
		$dependencies[]           = 'clipboard';
		$vars['avatar_url']       = get_avatar_url( $user_id, array( 'size' => '200' ) );
		$vars['default_logo_url'] = hms_get_default_logo_url();

		$dependencies = array(
			'jquery-core',
			'jquery-ui-core',
			'jquery-ui-datepicker',
			'clipboard',
			'media-editor',
			'wp-plupload',
			'media-models',
			'media-upload',
			'hms-timepicker',
			'hms-countdown',
			'jquery-validate',
		);

		function filter_media( $query ) {
			$query['author'] = get_current_user_id();
			return $query;
		}
		add_filter( 'ajax_query_attachments_args', 'filter_media' );

		wp_register_script( 'hms-timepicker', HMS_URL . 'assets/js/jquery-ui-timepicker-addon.js', array(), '1.6.3', true );
		wp_register_script( 'hms-countdown', HMS_URL . 'assets/js/jquery.countdown.min.js', array(), '2.1.0', true );
		wp_register_script( 'jquery-validate', HMS_URL . 'assets/js/jquery.validate.min.js', array(), '1.9.3', true );

		wp_enqueue_media();

		hms_localize_jquery_ui_datepicker();
		hms_localize_countdown();
		hms_localize_validate();

	}

	if ( ! is_user_logged_in() || isset( $_GET['preview-form'] ) ) {
		$form_slug = get_query_var( 'hms_subpage' );
		$form_args = array(
			'meta_key'       => '_form_slug',
			'meta_value'     => $form_slug,
			'post_type'      => 'hms_form',
			'posts_per_page' => 1,
			'post_status'    => 'any',
		);
		$form      = get_posts( $form_args );
		if ( $form ) {
			$form_id      = $form[0]->ID;
			$vars['form'] = get_post_meta( $form_id, '_form_fields', true );
		}
	}

	wp_register_script( 'hms-scripts', HMS_URL . 'assets/js/scripts.js', $dependencies, HMS_VER, true );
	wp_localize_script( 'hms-scripts', 'hms', $vars );
}
add_action( 'template_redirect', 'hms_register_scripts' );

/**
 * Enqueue styles
 */
function hms_enqueue_styles() {
	wp_print_styles( array( 'hms-style' ) );
}
add_action( 'hms_head', 'hms_enqueue_styles' );

/**
 * Enqueue scripts
 */
function hms_enqueue_scripts() {
	remove_action( 'wp_footer', 'the_block_template_skip_link' );
	wp_footer();
	wp_print_scripts( 'hms-scripts' );
}
add_action( 'hms_footer', 'hms_enqueue_scripts' );

/**
 * Admin scripts
 */
function hms_admin_enqueue_scripts() {
	wp_enqueue_style( 'hms-admin', HMS_URL . 'assets/css/admin.css', array(), HMS_VER );
}
add_action( 'admin_enqueue_scripts', 'hms_admin_enqueue_scripts' );
