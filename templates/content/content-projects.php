<?php
/**
 * Template Projects
 */

?>

<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title">
			<?php hms_page_title(); ?>
		</h1>
		<?php if ( hms_get_page_subtitle() ) { ?>
			<div class="hms-header-desc">
				<?php hms_page_subtitle(); ?>
			</div>
		<?php } ?>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-cards hms-cards-accordion">
		<?php

				$args = array(
					'post_type'      => 'hms_material',
					'posts_per_page' => -1,
					'meta_key'       => 'type',
					'meta_value'     => 'initial_presentation',
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					?>
					
						<?php
						while ( $query->have_posts() ) {
							$query->the_post();

							$team_id          = get_post_meta( get_the_ID(), 'team_id', true );
							$type             = get_post_meta( get_the_ID(), 'type', true );
							$fields           = get_post_meta( get_the_ID(), '_fields', true );
							$accordion_status = '';

							if ( 0 === $query->current_post ) {
								$accordion_status = ' open';
							}
							?>

							<div class="hms-card <?php echo esc_attr( $accordion_status ); ?>">
								<div class="hms-card-content">
									<div class="hms-card-line">
										<div class="hms-card-line-item">
											<div class="hms-card-label">
												<?php echo get_the_date( 'F j, Y - H:i:s' ); ?>
											</div>
										</div>
									</div>

									<h4 class="hms-card-title">
										<?php the_title(); ?>
									</h4>

									<span class="hms-card-toggle"></span>
								</div>

								<div class="hms-card-hidden">
									<div class="hms-card-body">

										<div class="hms-material-results">
											<?php foreach ( $fields as $key => $field ) { ?>
												<div class="hms-material-item">
													<div class="hms-material-heading"><?php echo esc_html( $field['label'] ); ?></div>
													<div class="hms-material-content"><?php echo esc_html( $field['value'] ); ?></div>
												</div>
											<?php } ?>
										</div>

									</div>
								</div>
							</div>

						<?php } ?>
						
				<?php } else { ?>
					<?php esc_html_e( 'No projects', 'hackathon' ); ?>
					<?php
				}
				wp_reset_postdata();
				?>

		</div>
	</div>
</div>
