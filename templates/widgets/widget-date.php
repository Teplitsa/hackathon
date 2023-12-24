<?php
/**
 * Widget Date
 */

$time_zone             = wp_timezone_string();
$current_timestamp     = current_time( 'timestamp', $time_zone );
$event_start_timestamp = strtotime( hms_get_option( 'event_start_timestamp' ) . ' ' . $time_zone );

if ( $event_start_timestamp <= $current_timestamp ) {
	$widget_width = 'quarter';
} else {
	$widget_width = 'third';
}

?>

<div class="hms-widget <?php echo esc_attr( $widget_width ); ?>-width">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'start' ); ?>
		</div>
		<h3 class="hms-widget-title hms-widget-title__small"><?php esc_html_e( 'Hackathon start', 'hackathon' ); ?></h3>
	</div>
	<div class="hms-widget-content">
		<div class="hms-widget-info-datetime">
			<?php
			if ( hms_get_option( 'event_start_timestamp' ) ) {
				echo esc_html( sprintf( __( '%1$s on %2$s', 'hackathon' ), hms_date( hms_get_option( 'event_start_timestamp' ), 'F j' ), hms_date( hms_get_option( 'event_start_timestamp' ), 'H:i' ) ) );
			} else {
				esc_html_e( 'Date not specified.', 'hackathon' );
			}
			?>
		</div>
		<div class="hms-widget-info-timezone">
			<?php echo esc_html( hms_get_gtm() ); ?>
		</div>
	</div>
</div>

<div class="hms-widget <?php echo esc_attr( $widget_width ); ?>-width">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'end' ); ?>
		</div>
		<h3 class="hms-widget-title hms-widget-title__small"><?php esc_html_e( 'Hackathon end', 'hackathon' ); ?></h3>
	</div>
	<div class="hms-widget-content">
		<div class="hms-widget-info-datetime">
			<?php
			if ( hms_get_option( 'event_end_timestamp' ) ) {
				echo esc_html( sprintf( __( '%1$s on %2$s', 'hackathon' ), hms_date( hms_get_option( 'event_end_timestamp' ), 'F j' ), hms_date( hms_get_option( 'event_end_timestamp' ), 'H:i' ) ) );
			} else {
				esc_html_e( 'Date not specified.', 'hackathon' );
			}
			?>
		</div>
		<div class="hms-widget-info-timezone">
			<?php echo esc_html( hms_get_gtm() ); ?>
		</div>
	</div>
</div>
