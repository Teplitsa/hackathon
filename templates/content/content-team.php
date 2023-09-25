<?php
/**
 * Template Team
 */

$team_id = null;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$team_id = get_query_var( 'hms_subsubpage' );
}

$team = null;
if ( $team_id ) {
	$team = get_post( $team_id );
}

if ( 'hackathon_participant' === hms_get_user_role() && false === strpos( $team_id, 'invitation' ) ) {
	$team = hms_get_user_teams( get_current_user_id() );
	if ( $team ) {
		$team_id = $team[0]->ID;
	}
}

if ( ! $team ) {

	$args = array(
		'meta_query' => array(
			array(
				'key' => '_team_nonce',
				'value' => $team_id
			)
		),
		'post_type' => 'hms_team',
		'posts_per_page' => 1
	);
	$posts = get_posts( $args );

	if ( $posts ) {
		$team_id = $posts[0]->ID;
		if ( ! hms_is_administrator() ) {
			$is_team_user = hms_add_team_user( $team_id, get_current_user_id() );
		}
		?>

		<header class="hms-header">
			<div class="hms-header-content">
				<div class="hms-header-content-inner">
					<?php if ( hms_is_administrator() ) { ?>
						<h1 class="hms-header-title"><?php esc_html_e( 'Administrators can\'t be added to teams.', 'hackathon' ); ?></h1>
						<div class="hms-header-desc">
							<a href="<?php echo esc_url( hms_get_teams_url() ); ?>" class="button"><?php esc_html_e( 'Go to teams', 'hackathon' ); ?></a>
						</div>
					<?php } else if ( $is_team_user ) { ?>
						<h1 class="hms-header-title"><?php esc_html_e( 'You have been successfully added to the team:', 'hackathon' ); ?> <?php echo get_the_title( $team_id ); ?></h1>
						<div class="hms-header-desc">
							<a href="<?php echo esc_url( hms_get_team_url( $team_id ) ); ?>" class="button"><?php esc_html_e( 'Go to team', 'hackathon' ); ?></a>
						</div>
					<?php } else { ?>
						<h1 class="hms-header-title"><?php esc_html_e( 'You are already a member of the team:', 'hackathon' ); ?> <?php echo get_the_title( $team_id ); ?></h1>
						<div class="hms-header-desc">
							<a href="<?php echo esc_url( hms_get_team_url( $team_id ) ); ?>" class="button"><?php esc_html_e( 'Go to team', 'hackathon' ); ?></a>
						</div>
					<?php } ?>
				</div>
			</div>
		</header>

	<?php } else { ?>

		<header class="hms-header">
			<div class="hms-header-content">
				<div class="hms-header-content-inner">
					<h1 class="hms-header-title"><?php esc_html_e( 'This team does not exist on the site.', 'hackathon' ); ?></h1>
					<div class="hms-header-desc">
						<a href="<?php echo esc_url( hms_get_teams_url() ); ?>" class="button"><?php esc_html_e( 'Go to teams', 'hackathon' ); ?></a>
					</div>
				</div>
			</div>
		</header>

	<?php } ?>

<?php } else { ?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-image">
			<div class="hms-header-image-inner">
				<?php echo hms_get_team_logo( $team_id ); ?>
			</div>
		</div>
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php echo esc_html( get_the_title( $team_id ) ); ?>
				<?php if ( hms_is_administrator() ) { ?>
					<a href="<?php echo esc_url( hms_get_url( 'edit-team/' . $team_id ) ); ?>" class="hms-header-edit" title="<?php esc_attr_e( 'Edit team', 'hackathon' ); ?>">
						<?php hms_icon( 'pencil' ); ?>
					</a>
				<?php } ?>
			</h1>
			<?php if ( get_post_meta( $team_id, '_team_chat', true ) ) { ?>
				<div class="hms-header-line">
					<?php echo make_clickable( get_post_meta( $team_id, '_team_chat', true ) ); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'list' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Kick-off presentation', 'hackathon' ); ?></h3>

				<div class="hms-widget-actions">
					<a class="hms-widget-heading-messages" href="#" data-modal="#hms-modal-messages-0<?php echo esc_attr( $team_id ); ?>">
						<?php esc_html_e( 'Messages', 'hackathon' ); ?>
						<span><?php echo hms_get_point_messages_count( '0' . $team_id, $team_id ); ?></span>
						<?php hms_icon('messages');?>
					</a>

					<?php hms_checkpoint_modal( '0' . $team_id, $team_id ); ?>

				</div>

			</div>

			<div class="hms-widget-content">

				<?php
				$unlock_next = false;

				$args = array(
					'post_type'      => 'hms_material',
					'posts_per_page' => 1,
					'meta_query' => array(
						array(
							'key'   => 'team_id',
							'value' => $team_id,
						),
						array(
							'key'   => 'type',
							'value' => 'initial_presentation',
						),
					),
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) { $query->the_post();
					$unlock_next = true;
					?>

					<div class="hms-material-results">
						<div class="hms-material-item">
							<div class="hms-material-label"><?php esc_html_e( 'Project name', 'hackathon' ); ?></div>
							<div class="hms-material-title"><?php the_title(); ?></div>
						</div>

						<?php
							$fields = get_post_meta( get_the_ID(), '_fields', true );
							foreach( $fields as $key => $field ) { ?>
								<div class="hms-material-item">
									<div class="hms-material-heading"><?php echo esc_html( $field['label'] ); ?></div>
									<div class="hms-material-content"><?php echo wpautop( esc_html( $field['value'] ) ); ?></div>
								</div>
							<?php }
						?>
					</div>

					<?php } ?>
				<?php } else {
					$unlock_next = false;
				?>

				<form class="hms-form-materials hms-form">

					<input type="hidden" name="action" value="hackathon_initial_presentation">
					<input type="hidden" name="redirect_to" value="">
					<input type="hidden" name="post_id" value="<?php echo esc_attr( $team_id ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<?php
					$project_name = esc_html__( 'Project name', 'hackathon' );
					$form = get_posts(array(
						'post_type'      => 'hms_form',
						'meta_key'       => '_form_slug',
						'meta_value'     => 'prezentation',
						'post_status'    => 'private',
						'posts_per_page' => 1,
					));
					if ( $form ) {
						$form_id = $form[0]->ID;
						$project_name = get_post_field('post_title', $form_id );
					}
					?>
					<div class="hms-form-row">
						<label class="hms-form-label"><?php echo esc_html( $project_name ); ?></label><br>
						<input type="text" id="post_title" class="hms-form-input" name="post_title">
					</div>

					<?php
					if ( $form ) {
						$form_id = $form[0]->ID;
						$form_fields = get_post_meta( $form[0]->ID, '_form_fields', true );

						foreach( $form_fields as $key => $field ) { ?>
							<div class="hms-form-row">
								<label class="hms-form-label"><?php echo esc_html( $field['label'] ); ?></label><br>
								<?php if ( $field['type'] == 'textarea' ) { ?>
									<textarea class="hms-form-input" name="<?php echo esc_attr( $key ); ?>" rows="5"></textarea>
								<?php } else if ( $field['type'] == 'text' ) { ?>
									<input type="text" class="hms-form-input" name="<?php echo esc_attr( $key ); ?>">
								<?php } ?>
							</div>
						<?php }
					}
					?>

					<div class="hms-form-row">
						<button class="hms-button" type="submit"><?php esc_html_e( 'Publish initial presentation', 'hackathon' ); ?></button>
					</div>
				</form>

				<?php }
				wp_reset_postdata();

				?>

			</div>
		</div>

		<?php
		$forms_args = array(
			'post_type'      => 'hms_form',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'meta_key'       => '_form_type',
			'meta_value'     => 'intrasystem',
			'order'          => 'asc',
		);

		$forms_query = new WP_Query( $forms_args );

		if ( $forms_query->have_posts() ) {
			while ( $forms_query->have_posts() ) { $forms_query->the_post();
				$post_id = get_the_ID();
				$form_fields = get_post_meta( $post_id, '_form_fields', true );
				if ( $form_fields ) { ?>
					<div class="hms-widget">
						<div class="hms-widget-heading">
							<div class="hms-widget-icon">
								<?php hms_icon( 'check' ); ?>
							</div>
							<h3 class="hms-widget-title"><?php the_title(); ?></h3>

							<div class="hms-widget-actions">
								<a class="hms-widget-heading-messages" href="#" data-modal="#hms-modal-messages-<?php echo esc_attr( $post_id ); ?>">
									<?php esc_html_e( 'Messages', 'hackathon' ); ?>
									<span><?php echo hms_get_point_messages_count( $post_id, $team_id ); ?></span>
									<?php hms_icon('messages');?>
								</a>
								<?php hms_checkpoint_modal( $post_id, $team_id ); ?>

							</div>

						</div>

						<div class="hms-widget-content">

						<?php
							$material_args = array(
								'post_type'      => 'hms_material',
								'posts_per_page' => 1,
								'meta_query' => array(
									array(
										'key'   => 'team_id',
										'value' => $team_id,
									),
									array(
										'key'   => 'type',
										'value' => 'checkpoint_report',
									),
									array(
										'key'   => 'form_id',
										'value' => get_the_ID(),
									),
								),
							);

							$material_query = new WP_Query( $material_args );

							$time_zone             = wp_timezone_string();
							$current_timestamp     = current_time('timestamp', $time_zone);
							$deadline_checkpoint   = strtotime( hms_get_option( 'deadline_checkpoint' ) . ' ' . $time_zone );

							if ( $unlock_next ) {

								if ( $material_query->have_posts() ) {
									while ( $material_query->have_posts() ) { $material_query->the_post();
									$unlock_next = true;
									?>
									<div class="hms-material-results">
										<?php
										$material_fields = get_post_meta( get_the_ID(), '_fields', true );
										foreach( $material_fields as $key => $field ){ ?>
											<div class="hms-material-item">
												<div class="hms-material-heading"><?php echo esc_html( $field['label'] ); ?></div>
												<div class="hms-material-content"><?php echo wpautop( esc_html( $field['value'] ) ); ?></div>
											</div>
										<?php } ?>
									</div>

									<?php } ?>
								<?php } else { ?>

									<?php 

									if ( $deadline_checkpoint <= $current_timestamp ) {
										esc_html_e( 'Сheckpoint submission time has expired', 'hackathon' );
									} else {

										?>
										<form class="hms-form-materials hms-form">

											<input type="hidden" name="action" value="hackathon_checkpoint_report">
											<input type="hidden" name="redirect_to" value="">
											<input type="hidden" name="post_id" value="<?php echo esc_attr( $team_id ); ?>">
											<input type="hidden" name="form_id" value="<?php echo esc_attr( get_the_ID() ); ?>">
											<input type="hidden" name="post_title" value="<?php the_title(); ?>">
											<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

											<?php foreach( $form_fields as $key => $field ) { ?>
												<div class="hms-form-row">
													<label class="hms-form-label"><?php echo esc_html( $field['label'] ); ?></label><br>
													<?php if ( $field['type'] == 'textarea' ) { ?>
														<textarea class="hms-form-input" name="<?php echo esc_attr( $key ); ?>" rows="5"></textarea>
													<?php } else if ( $field['type'] == 'text' ) { ?>
														<input type="text" class="hms-form-input" name="<?php echo esc_attr( $key ); ?>">
													<?php } else if ( $field['type'] == 'select') { ?>
														<select class="hms-form-input" name="<?php echo esc_attr( $key ); ?>">
															<?php foreach( $field['options'] as $option ) { ?>
																<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $option ); ?></option>
															<?php } ?>
														</select>
													<?php } ?>
												</div>
											<?php } ?>

											<div class="hms-form-row">
												<button class="hms-button" type="submit"><?php esc_html_e( 'Send checkpoint report', 'hackathon' ); ?></button>
											</div>

										</form>
									<?php } ?>
								<?php $unlock_next = false; ?>
							<?php } ?>
							<?php wp_reset_postdata(); ?>
						<?php } else { ?>
							<?php esc_html_e( 'To submit this step, please complete the previous step.', 'hackathon' ); ?>
						<?php } ?>
					</div>

					<div class="hms-checkpoint-messages">
						<input type="text" name="post_title" class="regular-text" id="post_title" value="">
					</div>

				</div>
				<?php
				}
			}
		}

		wp_reset_postdata();
		?>

		<div class="hms-widget">

			<?php

			$final_name = esc_html__( 'Final Solution', 'hackathon' );
			$final_form = get_posts(array(
				'post_type'      => 'hms_form',
				'meta_key'       => '_form_slug',
				'meta_value'     => 'final',
				'post_status'    => 'private',
				'posts_per_page' => 1,
			));
			if ( $final_form ) {
				$form_id = $final_form[0]->ID;
				$final_name = get_post_field('post_title', $form_id );
			}
			?>

			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'list' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php echo esc_html( $final_name ); ?></h3>

				<div class="hms-widget-actions">
					<a class="hms-widget-heading-messages" href="#" data-modal="#hms-modal-messages-00<?php echo esc_attr( $team_id ); ?>">
						<?php esc_html_e( 'Messages', 'hackathon' ); ?>
						<span><?php echo hms_get_point_messages_count( '00' . $team_id, $team_id ); ?></span>
						<?php hms_icon('messages');?>
					</a>

					<?php hms_checkpoint_modal( '00' . $team_id, $team_id ); ?>

				</div>

			</div>

			<div class="hms-widget-content">

				<?php
				if ( $unlock_next ) {
					
				$args = array(
					'post_type'      => 'hms_material',
					'posts_per_page' => 1,
					'meta_query' => array(
						array(
							'key'   => 'team_id',
							'value' => $team_id,
						),
						array(
							'key'   => 'type',
							'value' => 'final_presentation',
						),
					),
				);

				$query = new WP_Query( $args );

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) { $query->the_post();
						$final_fields = get_post_meta( get_the_ID(), '_fields', true );
						$final_files  = get_post_meta( get_the_ID(), 'final_fields', true );
													$unlock_next = true;
					?>

					<div class="hms-material-results">

						<?php foreach( $final_fields as $key => $field ) { ?>
							<div class="hms-material-item">
								<div class="hms-material-heading"><?php echo esc_html( $field['label'] ); ?></div>
								<div class="hms-material-content"><?php echo wpautop( esc_html( $field['value'] ) ); ?></div>
							</div>
						<?php } ?>

						<div class="hms-material-item">
							<div class="hms-material-heading"><?php esc_html_e( 'Presentation', 'hackathon' ); ?></div>
							<div class="hms-list-files">
								<?php
									$files = explode( ',', get_post_meta( get_the_ID(), 'final_files', true ) );
									foreach( $files as $file_id ) {
										$file          = get_attached_file( $file_id );
										$file_basename = wp_basename( $file );
										$file_url      = wp_get_attachment_url( $file_id );
										$filetype      = wp_check_filetype( $file_url );
										$file_ext      = $filetype['ext'];
										?>
										<div class="hms-file-item hms-file-id-<?php echo esc_attr( $file_id ); ?> hms-file-format-<?php echo esc_attr( $file_ext ); ?>">
											<div class="hms-file-icon"><?php echo esc_html( $file_ext ); ?></div>
											<a href="<?php echo esc_url( $file_url ); ?>" target="_blank">
												<?php echo esc_html( $file_basename ); ?>
											</a>
										</div>
										<?php
									}
								?>
							</div>
						</div>
					</div>

					<?php } ?>
				<?php } else { ?>

					<?php


					$time_zone             = wp_timezone_string();
					$deadline_presentation = strtotime( hms_get_option( 'deadline_presentation' ) . ' ' . $time_zone );

					$deadline_access = hms_get_option( 'deadline_access' );

					$current_timestamp     = current_time('timestamp', $time_zone);

					if ( $deadline_presentation <= $current_timestamp && ! $deadline_access ) {
						esc_html_e( 'The time for submitting the final soluition has expired', 'hackathon' );
					} else {

					?>

						<form class="hms-form-materials hms-form">

							<input type="hidden" name="action" value="hackathon_final_presentation">
							<input type="hidden" name="redirect_to" value="">
							<input type="hidden" name="post_id" value="<?php echo esc_attr( $team_id ); ?>">
							<input type="hidden" name="post_title" value="<?php esc_attr_e( 'Final Solution', 'hackathon' ); ?>">
							<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

							<?php
							if ( $final_form ) {
								$form_id = $final_form[0]->ID;
								$form_fields = get_post_meta( $final_form[0]->ID, '_form_fields', true );
								foreach( $form_fields as $key => $field ) {
									if ( isset( $field['type'] ) ) {?>
									<div class="hms-form-row">
										<label class="hms-form-label"><?php echo esc_html( $field['label'] ); ?></label><br>
										<?php if ( $field['type'] == 'textarea' ) { ?>
											<textarea class="hms-form-input" name="<?php echo esc_attr( $key ); ?>" rows="5"></textarea>
										<?php } else if ( $field['type'] == 'text' ) { ?>
											<input type="text" class="hms-form-input" name="<?php echo esc_attr( $key ); ?>">
										<?php } else if ( $field['type'] == 'select') { ?>
											<select class="hms-form-input" name="<?php echo esc_attr( $key ); ?>">
												<?php foreach( $field['options'] as $option ) { ?>
													<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_attr( $option ); ?></option>
												<?php } ?>
											</select>
										<?php } ?>
									</div>
								<?php
									}
								}
							}
							?>

							<div class="hms-form-row">
								<label class="hms-form-label"><?php esc_html_e( 'Presentation', 'hackathon' ); ?></label><br>
								<div class="hms-form-files">
									<input type="hidden" class="medium-text" name="final_files">
									<div class="hackathon-files-list hakcathon-cards"></div>
									<button type="button" class="hms-button hms-button-outline hackathon-upload-files" data-title="<?php esc_attr_e( 'Select files', 'hackathon' ); ?>" data-button="<?php esc_attr_e( 'Select', 'hackathon' ); ?>" data-input="final_files"><?php esc_html_e( 'Add files', 'hackathon' ); ?></button>
								</div>
							</div>

							<div class="hms-form-row hms-form-row-submit">
								<button class="hms-button" type="submit"><?php esc_html_e( 'Send final presentation', 'hackathon' ); ?></button>
							</div>

						</form>
					<?php } ?>
				<?php }
				wp_reset_postdata();

				?>

			<?php } else { ?>
				<?php esc_html_e( 'To submit this step, please complete the previous step.', 'hackathon' ); ?> 
			<?php } ?>
			</div>
		</div>

	</div>

	<div class="hms-inner-sidebar">

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'participants' ); ?>
				</div>

				<h3 class="hms-widget-title"><?php esc_html_e( 'List of participants', 'hackathon' ); ?> (<?php echo hms_get_team_users_count( $team_id, 'hackathon_participant' ); ?>)</h3>
			</div>

			<div class="hms-widget-content">

				<div class="hms-list">
					<?php
					$team_users = (array) hms_get_team_users( $team_id );
					if ( $team_users ) {
						$hms_args = array(
							'include'  => $team_users,
							'role__in' => array( 'hackathon_participant' ),
							'orderby'  => 'include',
						);
						$hms_users = get_users( $hms_args );

						if ( $hms_users ) {
							foreach ( $hms_users as $user ) {
								$user_url = '#';
								if ( hms_is_administrator() || hms_is_mentor() ) {
									$user_url = hms_get_url( 'user/' . $user->ID . '/' );
								}
								$star = '';
								if ( $user->ID === hms_get_team_captain( $team_id ) ) {
									$star = '<img src="' . esc_url( HMS_URL ) . 'assets/images/star.svg" alt="' . esc_attr__( 'Captain', 'hackathon' ) . '" title="' . esc_attr__( 'Captain', 'hackathon' ) . '">';
								}
								?>
								<div class="hms-list-item">
									<a href="<?php echo esc_url( $user_url ); ?>" class="hms-list-figure">
										<?php echo get_avatar( $user->ID, 50, '', '', array( 'class' => 'hms-list-image' ) ); ?>
									</a>
									<div class="hms-list-content">
										<h4 class="hms-list-title">
											<a href="<?php echo esc_url( $user_url ); ?>"><?php echo esc_html( $user->user_login ); ?></a>
											<?php echo hms_kses( $star ); ?>
										</h4>
										<div class="hms-list-line">
											<div class="hms-list-line-item">
												<div class="hms-list-value">
													<?php echo make_clickable( esc_html( hms_get_user_email( $user->ID ) ) ); ?>
												</div>
											</div>
										</div>
									</div>
									<?php if ( hms_is_administrator() ) { ?>
										<div class="hackathon-card-info">
											<button type="button" class="button button-small button-delete hms-remove-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user->ID ); ?>"><?php esc_html_e( 'Delete', 'hackathon' ); ?></button>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } else { ?>
							<strong><?php esc_html_e( 'No mentors', 'hackathon' ); ?></strong>
						<?php } ?>
					<?php } else { ?>
						<strong><?php esc_html_e( 'There are no users in this team.', 'hackathon' ); ?></strong>
					<?php } ?>
				</div>

				<?php if ( hms_is_administrator( get_current_user_id() ) ) { ?>

					<div class="hms-widget-submit">
						<button class="hms-button hms-button-small" data-modal=".hms-modal-participants"><?php esc_html_e( 'Add user to team', 'hackathon' ); ?></button>
					</div>

					<div class="hms-modal hms-modal-participants">
						<div class="hms-modal-body">
							<div class="hms-widget">

								<div class="hms-widget-heading">
									<div class="hms-widget-icon">
										<?php hms_icon( 'team' ); ?>
									</div>
									<h3 class="hms-widget-title"><?php esc_html_e( 'Users', 'hackathon' ); ?></h3>
									<div class="hms-widget-actions">
										<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
									</div>
								</div>

								<div class="hms-widget-content">

									<div class="hackathon-cards hackathon-cards-users">
									<?php
										$hms_args = array(
											'role__in' => array( 'hackathon_participant' ),
										);
										if ( $team_users ) {
											$hms_args['exclude'] = $team_users;
										}
										$hms_users = get_users( $hms_args );

										$user_count = 0;
										foreach ( $hms_users as $user ) {
											$user_id = $user->ID;
											$has_user_teams = hms_get_user_teams( $user_id );
											if ( $has_user_teams ) {
												continue;
											}
											$user_count++;
											?>
											<a href="#" class="hackathon-card hms-add-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>">
												<?php echo get_avatar( $user_id, 32, '', '', array( 'class' => 'hackathon-card-image' ) ); ?>
												<div class="hackathon-card-content">
													<h4 class="hackathon-card-title"><?php echo esc_html( $user->display_name ); ?> <small>( <?php echo esc_html( $user->user_login ); ?> )</small></h4>
												</div>
												
											</a>
									<?php }

									if ( ! $user_count ) {
										esc_html_e( 'No members available', 'hackathon' );
									}
									?>
									</div>

								</div>
							</div>
						</div>
					</div>

				<?php } ?>

				<div class="hms-widget-footer">
					<h3 class="hms-widget-title hms-widget-title__small"><?php esc_html_e( 'Link to invite members', 'hackathon' ); ?></h3>
					<button class="button-link hms-clipboard" data-msg="<?php esc_attr_e( 'Invitation link successfully copied to clipboard!', 'hackathon' ); ?>" title="<?php esc_attr_e( 'Click to copy', 'hackathon' ); ?>"><?php echo hms_get_url( 'team/' . hms_get_team_nonce( $team_id ) ); ?></button>
				</div>

			</div>
		</div>

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'cube' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'List of mentors', 'hackathon' ); ?> (<?php echo hms_get_team_users_count( $team_id, 'hackathon_mentor' ); ?>)</h3>
			</div>
			<div class="hms-widget-content">

				<div class="hms-list">
					<?php
					$team_users = (array) hms_get_team_users( $team_id );
					if ( $team_users ) {
						$hms_args = array(
							'include'  => $team_users,
							'role__in' => array( 'hackathon_mentor' ),
							'orderby'  => 'include',
						);

						$hms_users = get_users( $hms_args );

						if ( $hms_users) {
							foreach ( $hms_users as $user ) {
								$user_url = '#';
								if ( hms_is_administrator() || hms_is_mentor() ) {
									$user_url = hms_get_url( 'user/' . $user->ID . '/' );
								}
								?>
								<div class="hms-list-item">
									<a href="<?php echo esc_url( $user_url ); ?>" class="hms-list-figure">
										<?php echo get_avatar( $user->ID, 50, '', '', array( 'class' => 'hms-list-image' ) ); ?>
									</a>
									<div class="hms-list-content">
										<h4 class="hms-list-title">
											<a href="<?php echo esc_url( $user_url ); ?>"><?php echo esc_html( $user->user_login ); ?></a>
										</h4>
										<div class="hms-list-line">
											<div class="hms-list-line-item">
												<div class="hms-list-value">
													<?php echo make_clickable( esc_html( hms_get_user_email( $user->ID ) ) ); ?>
												</div>
											</div>
										</div>
									</div>
									<?php if ( hms_is_administrator() ) { ?>
										<div class="hackathon-card-info">
											<button type="button" class="button button-small button-delete hms-remove-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user->ID ); ?>"><?php esc_html_e( 'Delete', 'hackathon' ); ?></button>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
						<?php } else { ?>
							<strong><?php esc_html_e( 'No mentors', 'hackathon' ); ?></strong>
						<?php } ?>
					<?php } else { ?>
						<strong><?php esc_html_e( 'No mentors', 'hackathon' ); ?></strong>
					<?php } ?>
				</div>

				<?php if ( hms_is_administrator( get_current_user_id() ) ) { ?>

					<div class="hms-widget-submit">
						<button class="hms-button hms-button-small" data-modal=".hms-modal-mentors"><?php esc_html_e( 'Add mentor', 'hackathon' ); ?></button>
					</div>

					<div class="hms-modal hms-modal-mentors">
						<div class="hms-modal-body">
							<div class="hms-widget">

								<div class="hms-widget-heading">
									<div class="hms-widget-icon">
										<?php hms_icon( 'team' ); ?>
									</div>
									<h3 class="hms-widget-title"><?php esc_html_e( 'Mentors', 'hackathon' ); ?></h3>
									<div class="hms-widget-actions">
										<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
									</div>
								</div>

								<div class="hms-widget-content">

									<div class="hackathon-cards hackathon-cards-users">
									<?php
										$hms_args = array(
											'role__in' => array( 'hackathon_mentor' ),
										);
										if ( $team_users ) {
											$hms_args['exclude'] = $team_users;
										}
										$hms_users = get_users( $hms_args );

										$user_count = 0;
										foreach ( $hms_users as $user ) {
											$user_id = $user->ID;

											$teams_count = count( hms_get_user_teams( $user_id ) );
											$max_count   = hms_max_teams();
											if ( $has_user_teams && $teams_count === $max_count ) {
												continue;
											}

											$user_count++;
											?>
											<a href="#" class="hackathon-card hms-add-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>">
												<?php echo get_avatar( $user_id, 32, '', '', array( 'class' => 'hackathon-card-image' ) ); ?>
												<div class="hackathon-card-content">
													<h4 class="hackathon-card-title"><?php echo esc_html( $user->display_name ); ?> <small>( <?php echo esc_html( $user->user_login ); ?> )</small></h4>
												</div>
											</a>
									<?php }

									if ( ! $user_count ) {
										esc_html_e( 'No mentors available', 'hackathon' );
									}
									?>
									</div>

								</div>
							</div>
						</div>
					</div>

				<?php } ?>

			</div>
		</div>

	</div>

</div>

<?php
}

function hms_checkpoint_modal( $checkpoint_id = null, $team_id = null ) {
	if ( ! $checkpoint_id ) {
		$checkpoint_id = get_the_ID();
	}
	?>

	<div class="hms-modal hms-modal-chat" id="hms-modal-messages-<?php echo esc_attr( $checkpoint_id ); ?>">
		<div class="hms-modal-body">
			<div class="hms-widget">

				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon('messages');?>
					</div>
					<h3 class="hms-widget-title"><?php esc_html_e( 'Messages', 'hackathon' ); ?></h3>
					<div class="hms-widget-actions">
						<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
					</div>
				</div>

				<div class="hms-widget-content">

					<div class="hms-chat">
						<div class="hms-chat-ajax">
							<?php echo hms_get_point_messages( $checkpoint_id, $team_id ); ?>
						</div>
					</div>

				</div>

				<form class="hms-form hms-chat-form">
					<input type="hidden" name="action" value="hms_chat_message">
					<?php wp_nonce_field( 'hms-nonce', 'nonce' ); ?>
					<input type="hidden" name="checkpoint_id" value="<?php echo esc_attr( $checkpoint_id ); ?>">
					<input type="hidden" name="team_id" value="<?php echo esc_attr( $team_id ); ?>">
					<div class="hms-form-row">
						<label class="hms-form-label"><?php esc_html_e( 'Message', 'hackathon' ); ?></label><br>
							<textarea class="hms-form-input" name="message" rows="5"></textarea>
					</div>
					<div class="hms-form-row hms-form-row-submit">
						<button class="hms-button" type="submit"><?php esc_html_e( 'Send', 'hackathon' ); ?></button><span class="spinner"></span>
					</div>
				</form>

			</div>
		</div>
	</div>
	<?php
}
