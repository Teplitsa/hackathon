<?php
/**
 * Widget Info
 */

if ( ! hms_is_administrator() ) {
	?>
	<div class="hms-widget half-width">
		<div class="hms-widget-heading">
			<div class="hms-widget-icon">
				<?php hms_icon( 'participant' ); ?>
			</div>
			<h3 class="hms-widget-title"><?php esc_html_e( 'Participants', 'hackathon' ); ?> (<?php echo esc_html( hms_get_users_count() ); ?>)</h3>
		</div>
	</div>

	<div class="hms-widget half-width">
		<div class="hms-widget-heading">
			<div class="hms-widget-icon">
				<?php hms_icon( 'team' ); ?>
			</div>
			<h3 class="hms-widget-title"><?php esc_html_e( 'Teams', 'hackathon' ); ?> (<?php echo esc_html( hms_get_teams_count() ); ?>)</h3>
		</div>
	</div>
	<?php
}
