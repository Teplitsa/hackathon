<?php
/**
 * Template Options Main
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
	<input type="hidden" name="option_page" value="main">
	<?php wp_nonce_field( 'hackathon-nonce', 'nonce', false ); ?>

	<div class="hms-body">

		<div class="hms-content">

			<div class="hms-widget">

				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'dashboard' ); ?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Main options', 'hackathon' ); ?></h3>
				</div>

				<div class="hms-widget-content">

					<div class="hms-table">
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_name" class="hms-table-label"><?php esc_html_e( 'Hackathon name', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="event_name" class="regular-text hms-form-control" id="event_name" value="<?php echo esc_html( hms_get_option( 'event_name', get_bloginfo() ) ); ?>" required>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_desc" class="hms-table-label"><?php esc_html_e( 'Hackathon Description', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<textarea name="event_desc" class="large-text hms-form-control" id="event_desc" rows="8" required><?php echo html_entity_decode( stripslashes( hms_option( 'event_desc' ) ) ); ?></textarea>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_chat_link" class="hms-table-label"><?php esc_html_e( 'Link to the main hackathon chat', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="url" name="event_chat_link" class="regular-text hms-form-control" id="event_chat_link" value="<?php echo hms_option( 'event_chat_link' ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_logo" class="hms-table-label"><?php esc_html_e( 'Event logo', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hackathon-image-field">
									<input type="hidden" name="event_logo" value="<?php echo hms_option( 'event_logo' ); ?>">
									<div class="hackathon-image-figure">
										<?php hms_logo(); ?>
										<?php
											$upload_class = '';
											$remove_class = '';
										if ( wp_get_attachment_image( hms_option( 'event_logo' ) ) ) {
											$upload_class = ' hidden';
										} else {
											$remove_class = ' hidden';
										}
										?>
									</div>
									<button type="button" class="button hackathon-remove-image<?php echo esc_attr( $remove_class ); ?> hms-set-default-image" data-input="event_logo"><?php esc_html_e( 'Set default logo', 'hackathon' ); ?></button>
									<button type="button" class="button hackathon-upload-image<?php echo esc_attr( $upload_class ); ?>" data-title="<?php esc_attr_e( 'Select logo', 'hackathon' ); ?>" data-button="<?php esc_attr_e( 'Select', 'hackathon' ); ?>" data-input="event_logo"><?php esc_html_e( 'Change logo', 'hackathon' ); ?></button>
								</div>
								<p class="description"><?php esc_html_e( 'Recommended logo height 120px and width 340px, for retina height 240px and width 640px', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_contacts" class="hms-table-label"><?php esc_html_e( 'Contacts of the organizers', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<?php hms_editor( 'event_contacts', 'event_contacts', array( 'textarea_rows' => 6 ) ); ?>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_start_timestamp" class="hms-table-label"><?php esc_html_e( 'Date and time of the start of the hackathon', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" class="regular-text hms-form-control hms-date-time-picker" id="event_start_timestamp" data-range-max="event_end_timestamp" value="<?php echo hms_date( hms_option( 'event_start_timestamp' ), 'F j, Y - H:i' ); ?>" readonly="readonly">
								<input type="hidden" name="event_start_timestamp" value="<?php echo hms_option( 'event_start_timestamp' ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="event_end_timestamp" class="hms-table-label"><?php esc_html_e( 'Date and time of the end of the hackathon', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" class="regular-text hms-form-control hms-date-time-picker" id="event_end_timestamp" data-range-min="event_start_timestamp" value="<?php echo hms_date( hms_option( 'event_end_timestamp' ), 'F j, Y - H:i' ); ?>" readonly="readonly">
										<input type="hidden" name="event_end_timestamp" value="<?php echo hms_option( 'event_end_timestamp' ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-heading">
								<h3 class="hms-widget-title"><?php esc_html_e( 'Deadlines', 'hackathon' ); ?></h3>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="deadline_request" class="hms-table-label"><?php esc_html_e( 'Application deadline', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hms-date-time-group">
									<input type="text" class="regular-text hms-date-time-picker" id="deadline_request" value="<?php echo hms_date( hms_option( 'deadline_request' ), 'F j, Y - H:i' ); ?>" readonly="readonly">
									<input type="hidden" name="deadline_request" value="<?php echo hms_option( 'deadline_request' ); ?>">
								</div>
								<p class="description"><?php esc_html_e( 'After the expiration of this period, you can register on the platform only as a Specialist of an already announced team.', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="deadline_checkpoint" class="hms-table-label"><?php esc_html_e( 'Deadline for sending a checkpoint', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hms-date-time-group">
									<input type="text" class="regular-text hms-date-time-picker" id="deadline_checkpoint" value="<?php echo hms_date( hms_option( 'deadline_checkpoint' ), 'F j, Y - H:i' ); ?>" readonly="readonly">
									<input type="hidden" name="deadline_checkpoint" value="<?php echo hms_option( 'deadline_checkpoint' ); ?>">
								</div>
								<p class="description"><?php esc_html_e( 'After the time limit has expired, teams cannot submit their checkpoint.', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="deadline_presentation" class="hms-table-label"><?php esc_html_e( 'Deadline for submitting materials for the final presentation', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hms-date-time-group">
									<input type="text" class="regular-text hms-date-time-picker" id="deadline_presentation" value="<?php echo hms_date( hms_option( 'deadline_presentation' ), 'F j, Y - H:i' ); ?>" readonly="readonly">
									<input type="hidden" name="deadline_presentation" value="<?php echo hms_option( 'deadline_presentation' ); ?>">
								</div>
								<p class="description"><?php esc_html_e( 'After this time limit, teams cannot submit their final presentations.', 'hackathon' ); ?></p>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label for="deadline_access" class="hms-table-label"><?php esc_html_e( 'Upload presentations', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<fieldset>
										<label for="deadline_access">
										<input name="deadline_access" type="checkbox" id="deadline_access" value="1" <?php checked( (bool) hms_option( 'deadline_access' ), true ); ?>><?php esc_html_e( 'Open access to download presentations', 'hackathon' ); ?>
									</label>
									<p class="description"><?php esc_html_e( 'This is necessary to manually enable the ability to upload materials after the deadline.', 'hackathon' ); ?></p>
								</fieldset>
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
