<?php
/**
 * Template Edit Message
 */

if ( get_query_var( 'hms_subsubpage' ) ) {
	$message_id = get_query_var( 'hms_subsubpage' );
}

$message = get_post( $message_id );

if ( ! $message ) {
	?>

<div class="hackathon-header">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'This message does not exist on the site.', 'hackathon' ); ?></h1>
	<a href="<?php echo esc_url( hms_get_url( 'messages' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Go to messages', 'hackathon' ); ?></a>
</div>

	<?php
} else {

	$transport = get_post_meta( $message_id, '_transport', true ) ? get_post_meta( $message_id, '_transport', true ) : array();
	$role      = get_post_meta( $message_id, '_role', true ) ? get_post_meta( $message_id, '_role', true ) : array();

	?>

<header class="hms-header">
	<div class="hms-header-content">

		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php esc_html_e( 'Edit message', 'hackathon' ); ?>
			</h1>
		</div>

	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">
			<div class="hms-widget-content">

				<form class="hackathon-update-message">

					<input type="hidden" name="action" value="hackathon_update_message">
					<input type="hidden" name="post_id" value="<?php echo esc_attr( $message_id ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<table class="form-table hackathon-form-table">
						<tbody>
							<tr>
								<th><label for="post_title"><?php esc_html_e( 'Message title', 'hackathon' ); ?></label></th>
								<td>
									<input type="text" name="post_title" class="regular-text" id="post_title" value="<?php echo esc_attr( get_the_title( $message_id ) ); ?>">
								</td>
							</tr>
							<tr>
								<th><label for="post_content"><?php esc_html_e( 'Message content', 'hackathon' ); ?></label></th>
								<td>
									<?php hms_editor( get_post_field( 'post_content', $message_id ), 'post_content' ); ?>
								</td>
							</tr>
							<tr>
								<th><label for="deadline_access"><?php esc_html_e( 'Sending type', 'hackathon' ); ?></label></th>
								<td>
									<fieldset>
										<label>
											<input type="checkbox" disabled <?php checked( in_array( 'mail', $transport ) ); ?>><?php esc_html_e( 'Email', 'hackathon' ); ?>
										</label><br>
										<label>
											<input type="checkbox" disabled <?php checked( in_array( 'message', $transport ) ); ?>><?php esc_html_e( 'Message', 'hackathon' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<th><label for="deadline_access"><?php esc_html_e( 'To whom it was sent', 'hackathon' ); ?></label></th>
								<td>
									<fieldset>
										<label>
											<input type="checkbox" disabled <?php checked( in_array( 'mentor', $role ) ); ?>><?php esc_html_e( 'Mentors', 'hackathon' ); ?>
										</label><br>
										<label>
											<input type="checkbox" disabled <?php checked( in_array( 'participant', $role ) ); ?>><?php esc_html_e( 'Participants', 'hackathon' ); ?>
										</label><br>
										<label>
											<input type="checkbox" disabled <?php checked( in_array( 'jury', $role ) ); ?>><?php esc_html_e( 'Jury', 'hackathon' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>

							<tr>
								<th></th>
								<td>
									<fieldset>
										<button class="hms-button" type="button" data-action="submit" data-target=".hackathon-update-message"><?php esc_html_e( 'Update message', 'hackathon' ); ?></button>
									</fieldset>
								</td>
							</tr>

						</tbody>
					</table>

				</form>

			</div>
		</div>

	</div>

</div>

	<?php
}
