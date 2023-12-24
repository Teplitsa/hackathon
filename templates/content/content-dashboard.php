<?php
/**
 * Template Dashboard
 */

?>

<?php if ( ! hms_is_user_approved() ) { ?>

<header class="hms-hero">

	<div class="hms-hero-figure">
		<?php hms_logo(); ?>
	</div>

	<div class="hms-hero-hello">
		<?php esc_html_e( 'Welcome to Hackathon Management System:', 'hackathon' ); ?>
	</div>

	<h1 class="hms-hero-title">
		<?php echo hms_option( 'event_name', get_bloginfo() ); ?>
	</h1>

	<div class="hms-hero-desc">
		<p>
		<?php esc_html_e( 'Your request has been received! Her status is:', 'hackathon' ); ?> <strong><?php echo hms_get_request_status( hms_get_user_status() ); ?></strong>
		</p>
		<p><?php esc_html_e( 'You will have access to all HMS functionality after approval and confirmation of your participation by the organizers.', 'hackathon' ); ?></p>

		<?php if ( hms_option( 'event_contacts' ) ) { ?>
			<p><strong><?php esc_html_e( 'If you have any questions, write to the organizers:', 'hackathon' ); ?></strong></p>

			<?php echo wpautop( wp_specialchars_decode( stripslashes( hms_option( 'event_contacts' ) ) ) ); ?>
			<?php if ( hms_option( 'event_chat_link' ) ) { ?>
				<p><strong><?php esc_html_e( 'Main hackathon chat:', 'hackathon' ); ?></strong><br> <?php echo links_add_target( make_clickable( hms_option( 'event_chat_link' ) ) ); ?></p>
			<?php } ?>

			<p>
				<a href="<?php hms_url( 'support' ); ?>">
					<span class="hms-adminbar-label"><?php esc_html_e( 'Support', 'hackathon' ); ?></span>
				</a>
			</p>
		<?php } ?>
	</div>

</header>

<?php } elseif ( hms_is_mentor() && ! hms_get_user_teams( get_current_user_id() ) ) { ?>

<header class="hms-hero">

	<div class="hms-hero-figure">
		<?php hms_logo(); ?>
	</div>

	<h1 class="hms-hero-title">
		<?php esc_html_e( 'You are not yet a member of any team', 'hackathon' ); ?>
	</h1>

	<div class="hms-hero-desc">
		<p><?php esc_html_e( 'It\'s time to join and invite participants to work on interesting projects', 'hackathon' ); ?></p>
		<p><a href="<?php hms_url( 'teams' ); ?>" class="hms-button"><?php esc_html_e( 'Go to teams', 'hackathon' ); ?></a></p>
	</div>

</header>

<?php } else { ?>

	<header class="hms-header">
		<div class="hms-header-content">
			<div class="hms-header-content-inner">
				<div class="hms-header-logo">
					<?php hms_logo(); ?>
				</div>
				<h1 class="hms-header-title">
					<?php printf( esc_html__( 'Welcome to %s', 'hackathon' ), hms_option( 'event_name', get_bloginfo() ) ); ?>
				</h1>
				<?php if ( hms_get_option( 'event_desc' ) ) { ?>
					<div class="hms-header-desc">
						<?php echo nl2br( hms_kses( hms_get_option( 'event_desc' ) ) ); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</header>

	<div class="hms-body">

		<div class="hms-content">

			<?php hms_load_template( 'widgets/widget-date.php' ); ?>

			<?php hms_load_template( 'widgets/widget-countdown.php' ); ?>

			<?php hms_load_template( 'widgets/widget-info.php' ); ?>

			<?php hms_load_template( 'widgets/widget-contacts.php' ); ?>

			<?php if ( hms_is_administrator() ) { ?>

				<?php hms_load_template( 'widgets/widget-teams.php' ); ?>

				<?php hms_load_template( 'widgets/widget-materials.php' ); ?>

				<?php hms_load_template( 'widgets/widget-participants.php' ); ?>

				<?php hms_load_template( 'widgets/widget-messages.php' ); ?>

				<?php hms_load_template( 'widgets/widget-logs.php' ); ?>

			<?php } ?>

		</div>

	</div>

	<?php
}
