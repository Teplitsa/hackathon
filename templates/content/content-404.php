<?php
/**
 * Template 404
 */

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?>
			</h1>
			<?php if ( hms_get_option( 'event_desc' ) ) { ?>
				<div class="hms-header-desc">
					<?php echo nl2br( hms_kses( hms_get_option( 'event_desc' ) ) ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</header>
