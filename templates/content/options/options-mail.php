<?php
/**
 * Template Forms Options
 */

$page_slug     = get_query_var( 'hms_subpage' );
$subpage_slug  = get_query_var( 'hms_subsubpage' );
$pages         = hms_pages_content();
$option_pages  = $pages['options']['children'];
$notifications = hms_email_notifications();

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

<form class="hackathon-options-form">

	<input type="hidden" name="action" value="hms_update_options">
	<input type="hidden" name="option_page" value="mail">
	<?php wp_nonce_field( 'hackathon-nonce', 'nonce', false ); ?>

	<div class="hms-body">

		<div class="hms-content">

			<div class="hms-widget">

				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'dashboard' ); ?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Emails for Users', 'hackathon' ); ?></h3>
				</div>

				<div class="hms-widget-content">

					<table class="form-table hackathon-form-table">
						<tbody>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_register"><?php esc_html_e( 'The email that comes to the User upon new registration', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_register', 'mail_register', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_reset"><?php esc_html_e( 'The email that the User receives when recovering the password', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_reset', 'mail_reset', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>

			<div class="hms-widget">

				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'dashboard' ); ?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Emails related to the participation', 'hackathon' ); ?></h3>
				</div>

				<div class="hms-widget-content">

					<table class="form-table hackathon-form-table">
						<tbody>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_status_processing"><?php esc_html_e( 'The email that the User receives when switching the application status to «Application processing»', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_status_processing', 'mail_status_processing', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_status_approved"><?php esc_html_e( 'The email that the User receives when switching the application status to «Application approved»', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_status_approved', 'mail_status_approved', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_status_rejected"><?php esc_html_e( 'The email that the User receives when switching the application status to «Application rejectet»', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_status_rejected', 'mail_status_rejected', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<p><strong><label for="mail_status_cancelled"><?php esc_html_e( 'The email that the User receives when switching the application status to «Team cancelled»', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_status_cancelled', 'mail_status_cancelled', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>

			<div class="hms-widget">

				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'dashboard' ); ?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Email for Administrator', 'hackathon' ); ?></h3>
				</div>

				<div class="hms-widget-content">

					<table class="form-table hackathon-form-table">
						<tbody>

							<tr>
								<td>
									<fieldset>
										<p><strong><label name="mail_new_team"><?php esc_html_e( 'The email that comes to the Administrator when creating a new team', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_new_team', 'mail_new_team', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<fieldset>
										<p><strong><label name="mail_team_form"><?php esc_html_e( 'The email that comes to the Administrator when all three forms are filled in by teams', 'hackathon' ); ?></label></strong></p>
										<div class="medium-text">
											<?php hms_editor( 'mail_team_form', 'mail_team_form', array( 'textarea_rows' => 10 ) ); ?>
										</div>
									</fieldset>
								</td>
							</tr>

							<tr>
								<td>
									<button class="hms-button" type="button" data-action="submit" data-target=".hackathon-options-form"><?php esc_html_e( 'Update Options', 'hackathon' ); ?></button>
								</td>
							</tr>

						</tbody>
					</table>

				</div>
			</div>

		</div>

	</div>

</form>
