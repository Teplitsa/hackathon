<?php
/**
 * Template New Message
 */

$user_id = get_current_user_id();

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php esc_html_e( 'Support', 'hackathon' ); ?>
			</h1>
			<?php if ( hms_get_page_subtitle() ) { ?>
				<div class="hms-header-desc">
					<?php hms_page_subtitle(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'messages' ); ?>
				</div>
				<h3 class="hms-widget-title">
					<?php esc_html_e( 'Support Message', 'hackathon' ); ?>
				</h3>
			</div>

			<div class="hms-widget-content">

				<form class="hackathon-send-support" name="supportform">

					<input type="hidden" name="action" value="hackathon_send_support">
					<input type="hidden" name="page_url" value="<?php hms_url( 'support' ); ?>">
					<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<div class="hms-table">
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="message_title"><?php esc_html_e( 'Message title', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="message_title" class="regular-text" id="message_title" value="">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="message_content"><?php esc_html_e( 'Message content', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<textarea name="message_content" id="message_content" class="regular-text" rows="10"></textarea>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
							</div>
							<div class="hms-table-col">
								<button class="hms-button" type="submit"><?php esc_html_e( 'Send', 'hackathon' ); ?></button>
								<span class="spinner"></span>
							</div>
						</div>
					</div>

				</form>

			</div>
		</div>

	</div>

</div>
