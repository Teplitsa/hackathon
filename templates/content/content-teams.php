<?php
/**
 * Template Teams
 */

$page    = get_query_var( 'hms_subpage' );
$subpage = get_query_var( 'hms_subsubpage' );

$csv_args = array( 'get_csv' => $page );

$page_slug = $page . '/';
if ( $subpage ) {
	$page_slug .= $subpage . '/';
}

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?> (<?php echo esc_html( hms_get_teams_count() ); ?>)
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

<div class="hms-body">

	<div class="hms-content">

		<?php hms_card_teams(); ?>

		<?php if ( hms_is_administrator() ) { ?>
			<div class="hms-cards-footer">
				<a href="<?php hms_new_team_url(); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'Add team', 'hackathon' ); ?></a>
			</div>
		<?php } ?>

	</div>

</div>
