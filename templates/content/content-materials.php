<?php
/**
 * Template Materials
 */

$page    = get_query_var( 'hms_subpage' );
$subpage = get_query_var( 'hms_subsubpage' );

$csv_args = array( 'get_csv' => $page );

$args = array();
if ( hms_is_mentor( get_current_user_id() ) ) {
	$post_ids = hms_get_user_teams( get_current_user_id(), 'ids');
	if ( ! $post_ids ) {
		$post_ids = 0;
	}
	$args['meta_key']   = 'team_id';
	$args['meta_value'] = $post_ids;
}

$page_slug = $page . '/';
if ( $subpage ) {
	$page_slug .= $subpage . '/';
}

?>

<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title">
			<?php hms_page_title(); ?>
		</h1>
	</div>

	<div class="hms-header-action">
		<?php if ( hms_is_administrator() ) { ?>
			<a href="<?php hms_url( $page_slug . '/', $csv_args ); ?>" target="_blank" class="hms-header-action-button">
				<?php esc_html_e( 'Download .csv', 'hackathon' ); ?> <?php hms_icon( 'download' ); ?>
			</a>
		<?php } ?>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">
		<?php hms_card_materials( $args ); ?>
	</div>

</div>
