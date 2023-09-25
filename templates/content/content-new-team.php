<?php
/**
 * Template New Team
 */

?>

<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title"><?php esc_html_e( 'New Team', 'hackathon' ); ?></h1>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">

			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'list' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Team Info', 'hackathon' ); ?></h3>
			</div>

			<div class="hms-widget-content">

				<form class="hackathon-new-team-form">

					<input type="hidden" name="action" value="hackathon_new_team">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( hms_get_url( 'edit-team' ) ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<table class="form-table hackathon-form-table">
						<tbody>
							<tr>
								<th><label><?php esc_html_e( 'Team logo', 'hackathon' ); ?></label></th>
								<td>
									<div class="hackathon-image-field">
										<input type="hidden" name="team_logo" value="">
										<div class="hackathon-image-figure">
										<?php
											$upload_class = '';
											$remove_class = ' hidden';
										?>
										</div>
										<button type="button" class="button hackathon-remove-image<?php echo esc_attr( $remove_class ); ?>" data-input="team_logo"><?php esc_html_e( 'Remove logo', 'hackathon' ); ?></button>
										<button type="button" class="button hackathon-upload-image<?php echo esc_attr( $upload_class ); ?>" data-title="<?php esc_attr_e( 'Select logo', 'hackathon' ); ?>" data-button="<?php esc_attr_e( 'Select', 'hackathon' ); ?>" data-input="team_logo"><?php esc_html_e( 'Add logo', 'hackathon' ); ?></button>
									</div>
								</td>
							</tr>
							<tr>
								<th><label for="team_name"><?php esc_html_e( 'Team name', 'hackathon' ); ?></label></th>
								<td>
									<input type="text" name="team_name" class="regular-text" id="team_name" value="">
								</td>
							</tr>
							<tr>
								<th><label form="team_chat"><?php esc_html_e( 'Link to the team\'s chat', 'hackathon' ); ?></label></th>
								<td><input type="text" name="team_chat" class="regular-text" id="team_chat" value=""></td>
							</tr>
						</tbody>
					</table>

					<p class="submit">
						<button class="hms-button" type="submit"><?php esc_html_e( 'Add team', 'hackathon' ); ?></button>
					</p>

				</form>

			</div>
		</div>

	</div>

</div>
