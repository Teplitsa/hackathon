<?php
/**
 * Template Users
 */

$page    = get_query_var( 'hms_subpage' );
$subpage = get_query_var( 'hms_subsubpage' );

$csv_args = array( 'get_csv' => $page );

$args = array();
if ( $subpage ) {
	$args = array(
		'role__in' => 'hackathon_' . $subpage,
	);
}

if ( isset( $_GET['order'] ) ) {
	$args['order'] = $_GET['order'];
}

if ( isset( $_GET['orderby'] ) ) {
	$args['orderby'] = $_GET['orderby'];
}

if ( isset( $_GET['search'] ) ) {
	$args['search']     = $_GET['search'];
	$csv_args['search'] = $args['search'];
}

if ( isset( $_GET['status'] ) ) {
	$args['status']     = $_GET['status'];
	$csv_args['status'] = $_GET['status'];
}

if ( isset( $_GET['team'] ) ) {
	$args['team'] = $_GET['team'];
}

$args = apply_filters( 'hms_page_users_args', $args );

$page_slug = $page . '/';
if ( $subpage ) {
	$page_slug .= $subpage . '/';
}

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?>
			</h1>
			<?php if ( hms_get_page_subtitle() ) { ?>
				<div class="hms-header-desc">
					<?php hms_page_subtitle(); ?>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="hms-header-action">
		<?php if ( hms_is_administrator() ) { ?>
			<a href="<?php hms_url( $page_slug . '/', $csv_args ); ?>" target="_blank" class="hms-header-action-button">
				<?php esc_html_e( 'Download .csv', 'hackathon' ); ?> <?php hms_icon( 'download' ); ?>
			</a>
		<?php } ?>
	</div>
</header>

<?php hms_users_menu(); ?>

<?php hms_users_filter(); ?>

<div class="hms-body">

	<div class="hms-content">
		<?php hms_card_users( $args ); ?>
	</div>

</div>
