<?php
/**
 * Widget Logs
 */

?>
<div class="hms-widget">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'log' ); ?>
		</div>
		<h3 class="hms-widget-title"><?php esc_html_e( 'Event log', 'hackathon' ); ?></h3>
		<div class="hms-widget-actions">
			<a href="<?php hms_url( 'logs' ); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'All logs', 'hackathon' ); ?></a>
		</div>
	</div>
	<div class="hms-widget-content">
		<?php hms_list_logs( array( 'posts_per_page' => 5 ) ); ?>
	</div>
</div>
<?php
