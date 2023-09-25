<?php
/**
 * Template Logs
 */

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?>
			</h1>
			<?php if ( hms_get_page_subtitle() ) { ?>
				<div class="hms-header-desc">
					<?php hms_page_subtitle(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">
		<?php hms_card_logs(); ?>
	</div>

</div>
