<?php
/**
 * Template New Message
 */

?>

<header class="hms-header">
	<div class="hms-header-content">

		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php esc_html_e( 'New Message', 'hackathon' ); ?>
			</h1>
		</div>

	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">
			<div class="hms-widget-content">

				<form class="hackathon-insert-message">

					<input type="hidden" name="action" value="hms_insert_message">
					<input type="hidden" name="redirect_to" value="<?php hms_url( 'messages' ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<table class="form-table hackathon-form-table">
						<tbody>
							<tr>
								<th><label for="post_title"><?php esc_html_e( 'Message title', 'hackathon' ); ?></label></th>
								<td>
									<input type="text" name="post_title" class="regular-text" id="post_title" value="">
								</td>
							</tr>
							<tr>
								<th><label for="post_content"><?php esc_html_e( 'Message content', 'hackathon' ); ?></label></th>
								<td>
									<?php hms_editor( '', 'post_content' ); ?>
								</td>
							</tr>
							<tr>
								<th><label><?php esc_html_e( 'Sending type', 'hackathon' ); ?></label></th>
								<td>
									<fieldset>
										<label>
											<input name="transport[]" type="checkbox" value="mail"><?php esc_html_e( 'Email', 'hackathon' ); ?>
										</label><br>
										<label>
											<input name="transport[]" type="checkbox" value="message"><?php esc_html_e( 'Message', 'hackathon' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<th><label><?php esc_html_e( 'Send to', 'hackathon' ); ?></label></th>
								<td>
									<fieldset>
										<label>
											<input type="checkbox" checked="checked" value="all"><?php esc_html_e( 'All', 'hackathon' ); ?>
										</label><br>
										<label>
											<input name="role[]" type="checkbox" checked="checked" value="mentor"><?php esc_html_e( 'Mentors', 'hackathon' ); ?>
										</label><br>
										<label>
											<input name="role[]" type="checkbox" checked="checked" value="participant"><?php esc_html_e( 'Participants', 'hackathon' ); ?>
										</label><br>
										<label>
											<input name="role[]" type="checkbox" checked="checked" value="jury"><?php esc_html_e( 'Jury', 'hackathon' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>

							<tr>
								<th></th>
								<td>
									<button class="hms-button" type="button" data-action="submit" data-target=".hackathon-insert-message"><?php esc_html_e( 'Publish', 'hackathon' ); ?></button>
									<span class="spinner"></span>
								</td>
							</tr>

						</tbody>
					</table>

				</form>

			</div>
		</div>

	</div>

</div>
