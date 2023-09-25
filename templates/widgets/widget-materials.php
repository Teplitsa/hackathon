<?php
/**
 * Widget Materials
 */

?>
<div class="hms-widget half-width">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'list' ); ?>
		</div>
		<h3 class="hms-widget-title"><?php esc_html_e( 'Materials list', 'hackathon' ); ?></h3>
		<div class="hms-widget-actions">
			<a href="<?php hms_materials_url(); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'All materials', 'hackathon' ); ?></a>
		</div>
	</div>
	<div class="hms-widget-content">
		<?php hms_list_materials( array( 'posts_per_page' => 4 ) ); ?>
	</div>
</div>
<?php
