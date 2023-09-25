<?php
/**
 * Widget Contacts
 */

$widget_width = '';
if ( hms_is_administrator() ) {
	$widget_width = ' half-width';
}

?>
	<div class="hms-widget hms-widget-contacts<?php echo esc_attr( $widget_width ); ?>">
		<div class="hms-widget-heading">
			<div class="hms-widget-icon">
				<?php hms_icon( 'contact' ); ?>
			</div>
			<h3 class="hms-widget-title"><?php esc_html_e( 'Contacts of the organizers', 'hackathon' ); ?></h3>
		</div>
		<div class="hms-widget-content">
			<?php echo wpautop( wp_specialchars_decode( stripslashes( hms_option( 'event_contacts' ) ) ) ); ?>
			<?php if ( hms_option( 'event_chat_link' ) ) { ?>
				<p><strong><?php esc_html_e( 'Main hackathon chat:', 'hackathon' ); ?></strong><br> <?php echo links_add_target( make_clickable( hms_option( 'event_chat_link' ) ) ); ?></p>
			<?php } ?>
		</div>
	</div>
<?php
