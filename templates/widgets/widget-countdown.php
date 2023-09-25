<?php
/**
 * Widget Countdown
 */

$time_zone             = wp_timezone_string();
$current_timestamp     = current_time('timestamp', $time_zone);
$event_start_timestamp = strtotime( hms_get_option( 'event_start_timestamp' ) . ' ' . $time_zone );
$event_end_timestamp   = strtotime( hms_get_option( 'event_end_timestamp' ) . ' ' . $time_zone );
$deadline_checkpoint   = strtotime( hms_get_option( 'deadline_checkpoint' ) . ' ' . $time_zone );
$deadline_presentation = strtotime( hms_get_option( 'deadline_presentation' ) . ' ' . $time_zone );

$checkpoint_timestamp = $deadline_checkpoint;

$checkpoint_expired_action = 'reload';
$checkpoint_expired_text   = '';

$widget_status_class = '';

if ( $deadline_checkpoint <= $current_timestamp ) {
	$checkpoint_timestamp = $deadline_presentation;
	$checkpoint_expired_action = 'message';
	$checkpoint_expired_text   = __( 'time is up', 'hackathon' );
}

if ( $event_start_timestamp <= $current_timestamp ) {
	$star_end_widget_title = __( 'Until the end of the hackathon left', 'hackathon' );

	$even_end_left = $event_end_timestamp - $current_timestamp;

	if ( $even_end_left < ( 60 * 60 * 10 ) && $even_end_left > ( 60 * 60 * 3 )  ) {
		$widget_status_class = ' hms-widget-warning';
	} else if ( $even_end_left <= ( 60 * 60 * 3 ) ) {
		$widget_status_class = ' hms-widget-danger';
	}

} else {
	$star_end_widget_title = __( 'Until the beginning of the hackathon left', 'hackathon' );
}

$widget_width = 'quarter';

if ( $event_start_timestamp <= $current_timestamp ) {
	$widget_width = 'quarter';
} else {
	$widget_width = 'third';
}

?>

<div class="hms-widget <?php echo esc_attr( $widget_width ); ?>-width<?php echo esc_attr( $widget_status_class ); ?>">
	<div class="hms-widget-heading">
		<div class="hms-widget-icon">
			<?php hms_icon( 'clock' ); ?>
		</div>
		<h3 class="hms-widget-title hms-widget-title__small"><?php echo esc_html( $star_end_widget_title ); ?></h3>
	</div>
	<div class="hms-widget-content">
		<div class="hms-widget-info-datetime">
			<?php if ( $event_start_timestamp <= $current_timestamp ) { ?>
				<span class="hms-countdown" data-until="<?php echo esc_attr( $event_end_timestamp ); ?>"></span>
			<?php } else { ?>
				<span class="hms-countdown" data-until="<?php echo esc_attr( $event_start_timestamp ); ?>" data-expiry="reload"></span>
			<?php } ?>
		</div>
		<div class="hms-widget-info-timezone">
			<?php echo esc_html( hms_get_gtm() );?>
		</div>
	</div>
</div>

<?php if ( $event_start_timestamp <= $current_timestamp ) { ?>

	<div class="hms-widget quarter-width">
		<div class="hms-widget-heading">
			<div class="hms-widget-icon">
				<?php hms_icon( 'check' ); ?>
			</div>
			<h3 class="hms-widget-title hms-widget-title__small"><?php esc_html_e( 'Until the next checkpoint left', 'hackathon' ); ?></h3>
		</div>
		<div class="hms-widget-content">
			<div class="hms-widget-info-datetime">
				<span class="hms-countdown" data-until="<?php echo esc_attr( $checkpoint_timestamp ); ?>" data-expiry="<?php echo esc_attr( $checkpoint_expired_action ); ?>" data-expiry-text="<?php echo esc_attr( $checkpoint_expired_text ) ; ?>"></span>
			</div>
			<div class="hms-widget-info-timezone">
				<?php echo esc_html( hms_get_gtm() );?>
			</div>
		</div>
	</div>

<?php } ?>
