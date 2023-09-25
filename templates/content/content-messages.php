<?php
/**
 * Template Messages
 */

?>

<header class="hms-header">
	<div class="hms-header-content">

		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?>
			</h1>
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
				<h3 class="hms-widget-title"><?php esc_html_e( 'Messages', 'hackathon' ); ?> (<?php echo esc_html( hms_get_messages_count() ); ?>)</h3>
				<?php if ( hms_is_administrator() || hms_is_mentor() ) { ?>
					<div class="hms-widget-actions">
						<a href="<?php hms_new_message_url(); ?>" class="hms-button"><?php esc_html_e( 'Add message', 'hackathon' ); ?></a>
					</div>
				<?php } ?>
			</div>
			<div class="hms-widget-content">
				<?php hms_list_messages(); ?>
			</div>
		</div>
	</div>

</div>
