<?php
/**
 * Widget Teams
 */

?>
<div class="hms-widget">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'messages' ); ?>
		</div>
		<h3 class="hms-widget-title"><?php esc_html_e( 'Messages', 'hackathon' ); ?> (<?php echo esc_html( hms_get_messages_count() ); ?>)</h3>
		<div class="hms-widget-actions">
			<a href="<?php hms_messages_url(); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'All messages', 'hackathon' ); ?></a>
			<a href="<?php hms_new_message_url(); ?>" class="hms-button"><?php esc_html_e( 'Add message', 'hackathon' ); ?></a>
		</div>
	</div>
	<div class="hms-widget-content">
		<?php hms_list_messages( array( 'posts_per_page' => 4 ) ); ?>
	</div>
</div>
<?php
