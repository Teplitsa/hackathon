<?php
/**
 * Widget Participants
 */

?>
<div class="hms-widget half-width">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'participant' ); ?>
		</div>
		<h3 class="hms-widget-title"><?php esc_html_e( 'Participants', 'hackathon' ); ?> (<?php echo esc_html( hms_get_users_count() ); ?>)</h3>
		<div class="hms-widget-actions">
			<a href="<?php hms_url( 'users' ); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'All members', 'hackathon' ); ?></a>
		</div>
	</div>
	<div class="hms-widget-content">
		<?php hms_list_users( array( 'number' => 4 ), false ); ?>
	</div>
</div>
<?php
