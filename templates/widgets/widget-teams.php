<?php
/**
 * Widget Teams
 */

?>
<div class="hms-widget half-width">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'team' ); ?>
		</div>
		<h3 class="hms-widget-title"><?php esc_html_e( 'Teams', 'hackathon' ); ?> (<?php echo esc_html( hms_get_teams_count() ); ?>)</h3>
		<div class="hms-widget-actions">
			<a href="<?php hms_teams_url(); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'All teams', 'hackathon' ); ?></a>
			<a href="<?php hms_new_team_url(); ?>" class="hms-button"><?php esc_html_e( 'Add', 'hackathon' ); ?></a>
		</div>
	</div>
	<div class="hms-widget-content">
		<?php hms_list_teams( array( 'posts_per_page' => 4 ) ); ?>
	</div>
</div>
<?php
