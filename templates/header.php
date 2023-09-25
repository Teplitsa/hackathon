<?php
/**
 * Header Template
 */

$body_class = 'wp-core-ui login hackathon-page';

if ( is_user_logged_in() ) {
	$body_class = 'wp-admin wp-core-ui admin-bar admin-color-fresh no-customize-support hackathon-page hms-page';

	if ( isset( $_GET['preview']) ) {
		$body_class .= ' login';
	}
}

if ( get_query_var( 'hms_subpage' ) ) {
	$page_slug     = get_query_var( 'hms_subpage' );
	$subpage_class = '';
	if ( get_query_var( 'hms_subsubpage' ) ) {
		$subpage_slug      = get_query_var( 'hms_subsubpage' );
		$subpage_class .= ' hackathon-page-' . $page_slug . '-' . $subpage_slug;
	}

	$body_class .= ' hackathon-page-' . $page_slug . $subpage_class;

	if ( 'edit-user' === $page_slug ) {
		if ( ! get_query_var( 'hms_subsubpage' ) || get_current_user_id() == get_query_var( 'hms_subsubpage' ) ) {
			$body_class .= ' hackathon-page-my-profile';
		}
	}
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo esc_html( hms_option( 'event_name', get_bloginfo() ) ); ?></title>
	<?php hms_head(); ?>
</head>
<body <?php body_class( $body_class ); ?>>

	<?php do_action( 'hms_body_open' ); ?>
