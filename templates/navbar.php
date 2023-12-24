<?php
/**
 * Navbar
 */

?>

<nav class="hms-adminbar">

	<ul class="hms-adminbar-menu">
		<li class="hms-adminbar-toggle">
			<a href="#"><?php hms_icon( 'menu' ); ?></a>
		</li>
		<li class="hms-adminbar-dashboard">
			<?php if ( hms_is_administrator() ) { ?>
				<a href="<?php hms_admin_url(); ?>">
					<?php hms_icon( 'WordPress' ); ?>
					<span class="hms-adminbar-label"><?php esc_html_e( 'WordPress Dashboard', 'hackathon' ); ?></span>
				</a>
			<?php } else { ?>
				<a href="<?php hms_url( 'support' ); ?>">
					<?php hms_icon( 'sos' ); ?>
					<span class="hms-adminbar-label"><?php esc_html_e( 'Support', 'hackathon' ); ?></span>
				</a>
			<?php } ?>
		</li>
	</ul>

	<ul class="hms-adminbar-menu">
		<li>
			<a href="<?php echo wp_logout_url( hms_get_url() ); ?>">
				<?php hms_icon( 'migrate' ); ?>
				<span class="hms-adminbar-label"><?php esc_html_e( 'Log Out', 'hackathon' ); ?></span>
			</a>
		</li>
	</ul>

</nav>
