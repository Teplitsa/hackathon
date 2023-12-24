<?php
/**
 * Template Mentors and Teams
 */

$page_slug    = get_query_var( 'hms_subpage' );
$subpage_slug = get_query_var( 'hms_subsubpage' );
$pages        = hms_pages_content();
$option_pages = $pages['options']['children'];

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
</header>

<ul class="hms-submenu">
	<?php
	foreach ( $option_pages as $slug => $page ) {
		$item_class = 'hms-submenu-item';
		$parent     = '';

		if ( isset( $page['parent'] ) ) {
			$parent = $page['parent'] . '/';
		}

		if ( $slug === $subpage_slug || ( ! $subpage_slug && $page_slug === $slug ) ) {
			$item_class .= ' active';
		}
		?>
		<li class="<?php echo esc_attr( $item_class ); ?>">
			<a href="<?php hms_url( $parent . $slug ); ?>"><?php echo esc_html( $page['menu_title'] ); ?></a>
		</li>
	<?php } ?>
</ul>

<form class="hms-form hms-options-form hms-form-validate">

	<input type="hidden" name="action" value="hms_update_options">
	<input type="hidden" name="option_page" value="mentors_teams">
	<?php wp_nonce_field( 'hackathon-nonce', 'nonce', false ); ?>

	<div class="hms-body">

		<div class="hms-content">

			<div class="hms-widget">
				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'dashboard' ); ?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Options', 'hackathon' ); ?></h3>
				</div>

				<div class="hms-widget-content">

					<div class="hms-table">
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="max_teams" class="hms-table-label"><?php esc_html_e( 'Maximum number of teams', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="number" min="1" max="100" name="max_teams" class="regular-text hms-form-control" id="max_teams" value="<?php echo hms_option( 'max_teams', 5 ); ?>" required>
									<p class="description"><?php esc_html_e( 'The maximum number of teams that one mentor can join at the same time', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="max_participants" class="hms-table-label"><?php esc_html_e( 'Maximum number of participants', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="number" min="1" max="100" name="max_participants" class="regular-text hms-form-control" id="max_participants" value="<?php echo hms_option( 'max_participants', 6 ); ?>" required>
									<p class="description"><?php esc_html_e( 'Maximum number of participants per team (including mentors)', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col"></div>
							<div class="hms-table-col">
								<button class="hms-button" type="submit"><?php esc_html_e( 'Update Options', 'hackathon' ); ?></button>
								<span class="spinner"></span>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>

	</div>

</form>
