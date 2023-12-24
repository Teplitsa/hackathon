<?php
/**
 * Functions
 */
function hms_global_vars( $vars = array() ) {
	$vars = array(
		'user_id' => get_current_user_id(),
	);
	return apply_filters( 'hms_global_vars', $vars );
}

function hms_max_teams() {
	$max_teams = (int) hms_option( 'max_teams', 5 );
	return apply_filters( 'hms_max_teams', $max_teams );
}

function hms_max_participants() {
	$max_participants = (int) hms_option( 'max_participants', 6 );
	return apply_filters( 'hms_max_participants', $max_participants );
}

function hms_register_url() {
	return hms_get_url( 'register' );
}
add_filter( 'register_url', 'hms_register_url' );

/**
 * Pages content
 */
function hms_pages_content() {
	$content = array(
		'dashboard' => array(
			'page_title' => esc_html__( 'Hackathon Dashboard', 'hackathon' ),
			'menu_title' => esc_html__( 'Dashboard', 'hackathon' ),
			'icon'       => 'dashboard',
			'children'   => array(
				'dashboard' => array(
					'page_title' => esc_html__( 'Hackathon Dashboard', 'hackathon' ),
					'menu_title' => esc_html__( 'Main', 'hackathon' ),
					'caps'       => array(
						'administrator',
					),
				),
				'logs'      => array(
					'page_title' => esc_html__( 'Event log', 'hackathon' ),
					'menu_title' => esc_html__( 'Logs', 'hackathon' ),
					'caps'       => array(
						'administrator',
					),
				),
			),
		),
		'user'      => array(
			'page_title' => esc_html__( 'Profile', 'hackathon' ),
			'menu_title' => esc_html__( 'Profile', 'hackathon' ),
			'icon'       => 'profile',
		),
		'users'     => array(
			'page_title'    => esc_html__( 'Users', 'hackathon' ),
			'menu_title'    => esc_html__( 'Users', 'hackathon' ),
			'page_subtitle' => 'default',
			'icon'          => 'participants',
			'caps'          => array(
				'administrator',
				'hackathon_mentor',
			),
		),
		'requests'  => array(
			'page_title' => esc_html__( 'Requests', 'hackathon' ),
			'menu_title' => esc_html__( 'Requests', 'hackathon' ),
			'icon'       => 'applications',
			'caps'       => array(
				'administrator',
			),
		),
		'teams'     => array(
			'page_title' => esc_html__( 'Teams', 'hackathon' ),
			'menu_title' => esc_html__( 'Teams', 'hackathon' ),
			'icon'       => 'team',
			'caps'       => array(
				'administrator',
				'hackathon_mentor',
			),
		),
		'team'      => array(
			'page_title' => esc_html__( 'My Team', 'hackathon' ),
			'menu_title' => esc_html__( 'My Team', 'hackathon' ),
			'icon'       => 'team',
			'caps'       => array(
				'hackathon_participant',
			),
		),
		'projects'  => array(
			'page_title' => esc_html__( 'Projects', 'hackathon' ),
			'menu_title' => esc_html__( 'Projects', 'hackathon' ),
			'icon'       => 'project',
		),
		'materials' => array(
			'page_title' => esc_html__( 'Materials', 'hackathon' ),
			'menu_title' => esc_html__( 'Materials', 'hackathon' ),
			'icon'       => 'materials',
			'caps'       => array(
				'administrator',
				'hackathon_mentor',
				'hackathon_jury',
			),
		),
		'messages'  => array(
			'page_title' => esc_html__( 'Messages', 'hackathon' ),
			'menu_title' => esc_html__( 'Messages', 'hackathon' ),
			'icon'       => 'messages',
			'caps'       => array(
				'administrator',
				'hackathon_mentor',
				'hackathon_participant',
			),
		),
		'forms'     => array(
			'page_title' => esc_html__( 'Form List', 'hackathon' ),
			'menu_title' => esc_html__( 'Form List', 'hackathon' ),
			'icon'       => 'list-forms',
			'caps'       => array(
				'administrator',
			),
			'children'   => array(
				'forms'  => array(
					'page_title' => esc_html__( 'Public Form List', 'hackathon' ),
					'menu_title' => esc_html__( 'Public Form List', 'hackathon' ),
				),
				'inside' => array(
					'page_title' => esc_html__( 'Intrasystem Form List', 'hackathon' ),
					'menu_title' => esc_html__( 'Intrasystem Form List', 'hackathon' ),
					'parent'     => 'forms',
				),
			),
		),
		'options'   => array(
			'page_title' => esc_html__( 'Main Options', 'hackathon' ),
			'menu_title' => esc_html__( 'Options', 'hackathon' ),
			'icon'       => 'settings',
			'separator'  => true,
			'caps'       => array(
				'administrator',
			),
			'children'   => array(
				'options'       => array(
					'page_title' => esc_html__( 'Options', 'hackathon' ),
					'menu_title' => esc_html__( 'Main Options', 'hackathon' ),
				),
				'mentors-teams' => array(
					'page_title' => esc_html__( 'Mentors and Teams', 'hackathon' ),
					'menu_title' => esc_html__( 'Mentors and Teams', 'hackathon' ),
					'parent'     => 'options',
				),
				'mail'          => array(
					'page_title' => esc_html__( 'Email templates', 'hackathon' ),
					'menu_title' => esc_html__( 'Email templates', 'hackathon' ),
					'parent'     => 'options',
				),
			),
		),
	);
	return apply_filters( 'hms_pages_content', $content );
}

/**
 * Filter Menu items
 */
function hms_filter_pages_content( $content ) {
	if ( ! hms_get_user_teams( get_current_user_id() ) ) {
		unset( $content['team'] );
	}
	if ( ! hms_is_user_approved() ) {
		unset( $content['user'] );
		unset( $content['users'] );
		unset( $content['team'] );
		unset( $content['teams'] );
		unset( $content['projects'] );
		unset( $content['materials'] );
	}
	return $content;
}
add_filter( 'hms_pages_content', 'hms_filter_pages_content' );

/**
 * Page title
 */
function hms_page_title( $page = '', $echo = true ) {

	$page_title = null;

	$pages = hms_pages_content();

	$page_slug    = get_query_var( 'hms_subpage' );
	$subpage_slug = get_query_var( 'hms_subsubpage' );

	if ( ! $page ) {
		if ( $subpage_slug ) {
			$page = $subpage_slug;
		} elseif ( $page_slug ) {
			$page = $page_slug;
		} else {
			$page = 'dashboard';
		}
	}

	foreach ( $pages as $slug => $item ) {
		if ( $slug === $page ) {
			if ( isset( $item['page_title'] ) && $item['page_title'] ) {
				$page_title = $item['page_title'];
			}
		}
		if ( isset( $item['children'] ) && is_array( $item['children'] ) && $item['children'] && ! $page_title ) {
			foreach ( $item['children'] as $childslug => $child ) {

				if ( $childslug === $page ) {
					$page_title = $child['page_title'];
				}
			}
		}
	}

	if ( 'user' === $page_slug ) {
		$page_title = esc_html__( 'My profile', 'hackathon' );
		if ( get_query_var( 'hms_subsubpage' ) && get_current_user_id() !== (int) get_query_var( 'hms_subsubpage' ) ) {
			$page_title = hms_get_user_name( get_query_var( 'hms_subsubpage' ) );
		}
	}

	if ( 'edit-user' === $page_slug ) {
		$page_title = esc_html__( 'Edit my profile', 'hackathon' );
		if ( get_query_var( 'hms_subsubpage' ) && get_current_user_id() !== (int) get_query_var( 'hms_subsubpage' ) ) {
			$page_title = sprintf( __( 'Edit user: %s', 'hackathon' ), hms_get_user_name( get_query_var( 'hms_subsubpage' ) ) );
		}
	}

	if ( 'users' === $page_slug ) {
		$page_title = esc_html__( 'Users', 'hackathon' );
	}

	if ( 'requests' === $page_slug ) {
		$page_title = esc_html__( 'Requests', 'hackathon' );

		$requests = hms_request_statuses();
		if ( $subpage_slug && isset( $requests[ $subpage_slug ] ) ) {
			$page_title = $requests[ $subpage_slug ]['title'];
		}
	}

	if ( 'form' === $page_slug ) {
		$page_title = esc_html__( 'Edit form', 'hackathon' );
	}

	if ( ! $page_title ) {
		$page_title = esc_html__( '404', 'hackathon' );
	}

	if ( $echo ) {
		echo esc_html( $page_title );
	} else {
		return $page_title;
	}
}

/**
 * Get page subtitle
 */
function hms_get_page_subtitle( $page = '' ) {

	$page_subtitle = null;

	$pages = hms_pages_content();

	$page_slug    = get_query_var( 'hms_subpage' );
	$subpage_slug = get_query_var( 'hms_subsubpage' );

	$page_subtitle_default = __( 'Hackathon', 'hackathon' ) . ' «' . hms_option( 'event_name', get_bloginfo() ) . '»';

	if ( ! $page ) {
		if ( $subpage_slug ) {
			$page = $subpage_slug;
		} elseif ( $page_slug ) {
			$page = $page_slug;
		} else {
			$page = 'dashboard';
		}
	}

	foreach ( $pages as $slug => $item ) {
		if ( $slug === $page ) {
			if ( isset( $item['page_subtitle'] ) && $item['page_subtitle'] ) {
				$page_subtitle = $item['page_subtitle'];
			}
		}
		if ( isset( $item['children'] ) && is_array( $item['children'] ) && $item['children'] && ! $page_subtitle ) {
			foreach ( $item['children'] as $childslug => $child ) {

				if ( $childslug === $page ) {
					if ( isset( $child['page_subtitle'] ) ) {
						$page_subtitle = $child['page_subtitle'];
					}
				}
			}
		}
		if ( 'default' === $page_subtitle ) {
			$page_subtitle = $page_subtitle_default;
		}
	}

	if (
		'edit-user' === $page_slug ||
		'user' === $page_slug ||
		'requests' === $page_slug ||
		'request' === $page_slug ||
		'teams' === $page_slug
		) {
		$page_subtitle = $page_subtitle_default;
	}

	if ( in_array( $page_slug, array( 'form', 'forms' ), true ) ) {
		$page_subtitle = esc_html__( 'Create any form using the constructor', 'hackathon' );
	}

	return $page_subtitle;
}

/**
 * Page subtitle
 */
function hms_page_subtitle() {
	echo esc_html( hms_get_page_subtitle() );
}

/**
 * Date Format
 */
function hms_date_format() {
	$date_format = 'j F, Y';

	return apply_filters( 'hms_date_format', $date_format );
}

/**
 * Time Format
 */
function hms_time_format() {
	$time_format = 'H:i';

	return apply_filters( 'hms_time_format', $time_format );
}

/**
 * DateTime Format
 */
function hms_datetime_format() {
	$datetime_format = hms_date_format() . ' - ' . hms_time_format();

	return apply_filters( 'hms_datetime_format', $datetime_format );
}

/**
 * Date Format JS
 */
function hms_js_date_format() {
	// Convert the PHP date format into JS format.
	$js_date_format = str_replace(
		array(
			'd',
			'j',
			'l',
			'z', // Day.
			'F',
			'M',
			'n',
			'm', // Month.
			'Y',
			'y', // Year.
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
		hms_date_format()
	);

	return $js_date_format;
}

/**
 * Time Format JS
 */
function hms_js_time_format() {
	// Convert the PHP time format into JS format.
	$js_time_format = str_replace(
		array(
			'H',
			'i',
		),
		array(
			'HH',
			'mm',
		),
		hms_time_format()
	);

	return $js_time_format;
}

/**
 * Date
 */
function hms_date( $value, $format = null ) {

	if ( ! $format ) {
		$format = hms_datetime_format();
	}

	if ( ! $value ) {
		return $value;
	}

	$unixtimestamp = 0;

	if ( is_numeric( $value ) && strlen( $value ) !== 8 ) {
		$unixtimestamp = $value;
	} else {
		$unixtimestamp = strtotime( $value );
	}

	return date_i18n( $format, $unixtimestamp );
}

/**
 * Date
 */
function hms_get_gtm() {
	$current_offset = get_option( 'gmt_offset' );
	$tzstring       = get_option( 'timezone_string' );

	$check_zone_info = true;

	if ( false !== strpos( $tzstring, 'Etc/GMT' ) ) {
		$tzstring = '';
	}

	if ( empty( $tzstring ) ) {
		$check_zone_info = false;
		if ( 0 == $current_offset ) {
			$tzstring = 'GMT+0';
		} elseif ( $current_offset < 0 ) {
			$tzstring = 'GMT' . $current_offset;
		} else {
			$tzstring = 'GMT+' . $current_offset;
		}
	}
	return $tzstring;
}

/**
 * Is registration closed
 */
function hms_is_registration_closed() {

	$deadline_request = hms_get_option( 'deadline_request' );
	if ( ! $deadline_request ) {
		return false;
	}

	$time_zone             = wp_timezone_string();
	$current_timestamp     = current_time( 'timestamp', $time_zone );
	$event_start_timestamp = strtotime( $deadline_request . ' ' . $time_zone );

	if ( $event_start_timestamp <= $current_timestamp ) {
		return true;
	}

	return false;
}

/**
 * Filters the HTML that is allowed for a given context.
 *
 * @param array  $tags    Tags by.
 * @param string $context Context name.
 */
function hms_kses_allowed_html( $tags, $context ) {

	if ( 'content' === $context ) {
		$tags = array(
			'div'      => array(
				'class'  => true,
				'data-*' => true,
			),
			'span'     => array(
				'class' => true,
			),
			'p'        => array(),
			'br'       => array(),
			'a'        => array(
				'href'   => true,
				'target' => true,
				'class'  => true,
				'data-*' => true,
			),
			'h4'       => array(
				'class' => array(),
			),
			'svg'      => array(
				'class' => true,
			),
			'use'      => array(
				'xlink:href' => true,
			),
			'img'      => array(
				'src'   => true,
				'class' => true,
				'alt'   => true,
			),
			'button'   => array(
				'class'  => true,
				'type'   => true,
				'title'  => true,
				'data-*' => true,
			),
			'label'    => array(
				'class' => true,
				'for'   => true,
			),
			'select'   => array(
				'class'  => true,
				'name'   => true,
				'data-*' => true,
			),
			'input'    => array(
				'class'       => true,
				'type'        => true,
				'name'        => true,
				'value'       => true,
				'placeholder' => true,
				'data-*'      => true,
			),
			'textarea' => array(
				'class'       => true,
				'type'        => true,
				'value'       => true,
				'placeholder' => true,
				'data-*'      => true,
			),
			'option'   => array(
				'value'    => true,
				'requred'  => true,
				'selected' => true,
			),
		);
	}

	if ( 'common' === $context ) {
		$tags = wp_kses_allowed_html( 'post' );
	}

	return $tags;
}
add_filter( 'wp_kses_allowed_html', 'hms_kses_allowed_html', 10, 2 );

/**
 * HMS Kses
 */
function hms_kses( $content ) {
	return wp_kses( $content, 'content' );
}

/**
 * Add cutom button TinyMCE
 */
function hms_mce_buttons( $buttons ) {
	array_push( $buttons, 'separator', 'hmsmail' );
	return $buttons;
}
add_filter( 'mce_buttons', 'hms_mce_buttons' );

/**
 * Load the TinyMCE js
 */
function hms_mce_external_plugins( $plugin_array ) {
	$plugin_array['hmsmail'] = HMS_URL . 'assets/js/tinymce.js';
	return $plugin_array;
}
add_filter( 'mce_external_plugins', 'hms_mce_external_plugins' );
