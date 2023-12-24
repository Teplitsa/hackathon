<?php
/**
 * Template Edit User
 */

$current_user_id = (int) get_current_user_id();
$user_id         = $current_user_id;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$user_id = (int) get_query_var( 'hms_subsubpage' );
}

$user_meta = get_userdata( $user_id );
if ( $user_meta ) {
	$user_roles = $user_meta->roles;
	$role       = $user_roles[0];
}
$request_id = hms_get_user_request_id( $user_id );

?>

<?php if ( ! hms_user_exists( $user_id ) || ! user_can( $user_id, 'hackathon' ) ) { ?>

<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title">
			<?php esc_html_e( 'This user does not exist on the site.', 'hackathon' ); ?>
		</h1>
	</div>
</header>

<?php } else { ?>

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

		<div class="hms-widget">

			<div class="hms-widget-content">

				<form class="hms-form hms-user-form">

					<input type="hidden" name="action" value="hackathon_update_user">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>
					<input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
					<input type="hidden" name="custom_avatar" class="form-control" value="<?php echo esc_attr( get_user_option( 'custom_avatar', $user_id ) ); ?>">

					<div class="hms-table">

						<?php
						if ( ! hms_is_administrator( $user_id ) ) {
							$user_status = hms_get_user_status( $user_id );
							if ( hms_is_administrator() && hms_is_participant( $user_id ) ) {
								?>
							<div class="hms-table-row">
								<div class="hms-table-col">
									<label class="hms-table-label"><?php esc_html_e( 'Status', 'hackathon' ); ?></label>
								</div>
								<div class="hms-table-col">

									<div class="hms-card-status-dropdown">
										<div class="hms-card-status">
											<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $user_status ); ?>"></span>
											<div class="hms-card-label">
												<?php echo hms_get_request_status( $user_status ); ?>
											</div>
											<span class="hms-card-status-toggle">
												<?php hms_icon( 'down' ); ?>
											</span>
										</div>
										<div class="hms-card-status-popover">
											<div class="hms-card-status-menu">
												<?php foreach ( hms_request_statuses() as $slug => $status ) { ?>
													<div class="hms-card-status" data-request-id="<?php echo esc_attr( $request_id ); ?>" data-request-status="<?php echo esc_attr( $slug ); ?>">
														<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $slug ); ?>"></span>
														<div class="hms-card-label">
															<?php echo esc_html( $status['title'] ); ?>
														</div>
													</div>
												<?php } ?>
											</div>
										</div>
									</div>

									<?php if ( hms_is_administrator() ) { ?>
										<a href="<?php hms_url( 'request/' . $request_id ); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'View request', 'hackathon' ); ?></a>
									<?php } ?>

								</div>
							</div>
								<?php
							}
						}
						?>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="user_avatar"><?php esc_html_e( 'Avatar', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hackathon-image-field">
									<input type="hidden" name="custom_avatar" value="<?php echo get_user_option( 'custom_avatar', $user_id ); ?>">
									<?php hms_user_gravatar( $user_id, 200 ); ?>

									<button type="button" class="hms-button hms-button-outline hms-button-small hackathon-upload-avatar" data-title="<?php esc_attr_e( 'Select avatar', 'hackathon' ); ?>" data-button="<?php esc_attr_e( 'Select', 'hackathon' ); ?>" data-input="custom_avatar"><?php esc_html_e( 'Change avatar', 'hackathon' ); ?></button>
									<button type="button" class="hms-button hms-button-outline hms-button-small hackathon-remove-avatar" data-input="custom_avatar"><?php esc_html_e( 'Return default avatar', 'hackathon' ); ?></button>

								</div>
							</div>
						</div>

						<?php if ( ! current_user_can( 'jury' ) && ! current_user_can( 'participant' ) ) { ?>

							<div class="hms-table-row">
								<div class="hms-table-col">
									<label class="hms-table-label" for="role"><?php esc_html_e( 'Role', 'hackathon' ); ?></label>
								</div>
								<div class="hms-table-col">
									<?php
									if ( 'hackathon_participant' === hms_get_user_role( $current_user_id ) || $current_user_id == $user_id ) {
										echo '<select id="role" class="select" ' . disabled( true, true, false ) . '>';
										echo '<option>' . hms_get_user_role_name( $user_id ) . '</option>';
										echo '</select>';
									} else {
										$all_roles = wp_roles()->roles;
										echo '<select id="role" class="select" name="role" ' . disabled( hms_get_user_role( $user_id ), 'administrator', false ) . '>';

										if ( 'administrator' === hms_get_user_role( $user_id ) ) {
											echo '<option>' . hms_get_user_role_name( $user_id ) . '</option>';
										} else {

											foreach ( $all_roles as $role_slug => $role_obj ) {
												if ( array_key_exists( 'hackathon', $role_obj['capabilities'] ) ) {
													if ( 'administrator' === $role_slug ) {
														continue;
													}
													echo '<option value="' . $role_slug . '" ' . selected( $role, $role_slug, false ) . '>' . translate( translate_user_role( $role_obj['name'] ), 'hackathon' ) . '</option>';
												}
											}
										}
										echo '</select>';
									}
									?>
								</div>
							</div>

						<?php } ?>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="description"><?php esc_html_e( 'Biographical Info', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<textarea class="regular-text" id="description" name="description" rows="5"><?php echo esc_textarea( get_user_option( 'description', $user_id ) ); ?></textarea>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="user_login"><?php esc_html_e( 'Username', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" class="regular-text" id="user_login" value="<?php echo esc_html( get_user_option( 'user_login', $user_id ) ); ?>" <?php disabled( true, true ); ?>> <span class="description"><?php esc_html_e( 'Usernames cannot be changed.', 'hackathon' ); ?></span>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="user_email"><?php esc_html_e( 'Email', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" class="regular-text" id="user_email" value="<?php echo esc_html( get_user_option( 'user_email', $user_id ) ); ?>" <?php disabled( true, true ); ?>> <span class="description"><?php esc_html_e( 'Email cannot be changed.', 'hackathon' ); ?></span>
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="first_name"><?php esc_html_e( 'First Name', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="first_name" class="regular-text" id="first_name" value="<?php echo esc_html( get_user_option( 'first_name', $user_id ) ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="last_name"><?php esc_html_e( 'Last Name', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="last_name" class="regular-text" id="first_name" value="<?php echo esc_html( get_user_option( 'last_name', $user_id ) ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="telegram" class="regular-text hms-form-control" id="telegram" value="<?php echo esc_html( get_user_option( 'telegram', $user_id ) ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="phone" class="regular-text hms-form-control" id="phone" value="<?php echo esc_html( get_user_option( 'phone', $user_id ) ); ?>">
							</div>
						</div>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label" for="city"><?php esc_html_e( 'City', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<input type="text" name="city" class="regular-text" id="city" value="<?php echo esc_html( get_user_option( 'city', $user_id ) ); ?>">
							</div>
						</div>

						<?php if ( ! current_user_can( 'jury' ) && ! current_user_can( 'participant' ) ) { ?>

							<div class="hms-table-row">
								<div class="hms-table-col">
									<label class="hms-table-label" for="city"><?php esc_html_e( 'Password Reset', 'hackathon' ); ?></label>
								</div>
								<div class="hms-table-col">
									<button class="hms-button hms-button-outline hms-button-small" type="button" id="hackathon-send-reset-link"><?php esc_html_e( 'Send Reset Link', 'hackathon' ); ?></button>
									<p class="description"><?php printf( esc_html__( 'Send %s a link to reset their password. This will not change their password, nor will it force a change.', 'hackathon' ), esc_html( $user_meta->data->display_name ) ); ?></p>
								</div>
							</div>

						<?php } ?>

						<div class="hms-table-row">
							<div class="hms-table-col"></div>
							<div class="hms-table-col">
								<button class="hms-button" type="submit"><?php esc_html_e( 'Update Profile', 'hackathon' ); ?></button>
								<span class="spinner"></span>
							</div>
						</div>

					</div>

				</form>

			</div>
		</div>

		<?php if ( ! hms_is_administrator( $user_id ) && ! hms_is_jury( $user_id ) ) { ?>

			<div class="hms-widget">
				<div class="hms-widget-heading">
					<div class="hms-widget-icon">
						<?php hms_icon( 'team' ); ?>
					</div>
					<h3 class="hms-widget-title">
						<?php if ( 'hackathon_participant' === hms_get_user_role( $user_id ) ) { ?>
							<?php esc_html_e( 'Team', 'hackathon' ); ?>
						<?php } else { ?>
							<?php esc_html_e( 'Teams', 'hackathon' ); ?>
						<?php } ?>
					</h3>
				</div>
				<div class="hms-widget-content">

					<?php
						$teams = hms_get_user_teams( $user_id );
					if ( $teams ) {
						?>
							<div class="hms-cards">
							<?php
							foreach ( $teams as $team ) {
								$team_id     = $team->ID;
								$users_count = hms_get_team_users_count( $team_id );
								?>

								<div class="hms-card">
									<a href="<?php echo esc_url( hms_get_url( 'team/' . $team_id ) ); ?>" class="hms-card-figure">
										<?php echo hms_get_team_logo( $team_id ); ?>
									</a>

									<div class="hms-card-content">
										<h4 class="hms-card-title">
											<a href="<?php echo esc_url( hms_get_url( 'team/' . $team_id ) ); ?>"><?php echo esc_html( $team->post_title ); ?></a>
										</h4>

										<div class="hms-card-line">
											<div class="hms-card-line-item">
												<div class="hms-card-value">
													<?php echo links_add_target( make_clickable( esc_html( get_post_meta( get_the_ID(), '_team_chat', true ) ) ) ); ?>
												</div>
											</div>
										</div>
									</div>

									<div class="hms-card-info">
										<div class="hms-card-info-item">
											<div class="hms-card-info-icon">
												<?php hms_icon( 'team' ); ?>
											</div>
											<div class="hms-card-label">
												<?php
												printf(
													esc_html( _n( '%s User', '%s Users', $users_count, 'hackathon' ) ),
													esc_html( $users_count )
												);
												?>
											</div>
										</div>

										<div class="hms-card-info-item">
											<?php if ( hms_is_mentor( $current_user_id ) || hms_is_administrator( $current_user_id ) ) { ?>
												<button type="button" class="hms-button hms-button-outline hms-button-delete hms-button-small hms-remove-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>"><?php esc_html_e( 'Remove from team', 'hackathon' ); ?></button>
											<?php } ?>
										</div>
									</div>
								</div>

							<?php } ?>
							</div>
						<?php } else { ?>

							<?php if ( 'hackathon_participant' === hms_get_user_role( $user_id ) ) { ?>
								<?php if ( $user_id === get_current_user_id() ) { ?>
									<strong><?php esc_html_e( 'You are not yet a member of any team', 'hackathon' ); ?></strong>
								<?php } else { ?>
									<strong><?php esc_html_e( 'This user is not a member of any team', 'hackathon' ); ?></strong>
								<?php } ?>
							<?php } else { ?>
								<strong><?php esc_html_e( 'No teams', 'hackathon' ); ?></strong>
							<?php } ?>

						<?php } ?>

					<?php
						$hiddenClass = '';
						$max_count   = hms_max_teams();
					if ( 'hackathon_participant' === hms_get_user_role( $user_id ) ) {
						$max_count = 1;
					}
					if ( count( $teams ) === $max_count ) {
						$hiddenClass = ' hidden';
					}
					?>

					<?php if ( hms_is_administrator() || hms_is_mentor() ) { ?>

						<p class="submit<?php echo esc_attr( $hiddenClass ); ?>">
							<button class="hms-button hackathon-open-modal" data-modal=".hms-modal-teams">
								<?php if ( hms_is_mentor() && get_current_user_id() == $user_id ) { ?>
									<?php esc_html_e( 'Join team', 'hackathon' ); ?>
								<?php } else { ?>
									<?php esc_html_e( 'Add user to team', 'hackathon' ); ?>
								<?php } ?>
							</button>
						</p>

						<div class="hms-modal hms-modal-teams">
							<div class="hms-modal-body">
								<div class="hms-widget">
									<div class="hms-widget-heading">
										<div class="hms-widget-icon">
											<?php hms_icon( 'team' ); ?>
										</div>
										<h3 class="hms-widget-title"><?php esc_html_e( 'Available Teams', 'hackathon' ); ?></h3>
										<div class="hms-widget-actions">
											<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
										</div>
									</div>

									<div class="hms-widget-content">

										<?php
											$teams = hms_get_teams();
										if ( $teams ) {
											?>
												<div class="hms-cards">
												<?php
												foreach ( $teams as $team ) {
													$team_id     = $team->ID;
													$users_count = hms_get_team_users_count( $team_id );

													if ( in_array( $team_id, hms_get_user_teams( $user_id, 'ids' ) ) ) {
														continue;
													}

													$users_count = hms_get_team_users_count( $team_id );
													if ( $users_count >= 5 ) {
														continue;
													}
													?>

													<div class="hms-card">

														<?php if ( hms_is_administrator() ) { ?>
															<a href="<?php echo esc_url( hms_get_url( 'team/' . $team_id ) ); ?>" class="hms-card-figure">
																<?php echo hms_get_team_logo( $team_id ); ?>
															</a>
															<div class="hms-card-content">
																<h4 class="hms-card-title">
																	<a href="<?php echo esc_url( hms_get_url( 'team/' . $team_id ) ); ?>"><?php echo esc_html( $team->post_title ); ?></a>
																</h4>
															</div>
														<?php } else { ?>
															<span class="hms-card-figure">
																<?php echo hms_get_team_logo( $team_id ); ?>
															</span>
															<div class="hms-card-content">
																<h4 class="hms-card-title">
																	<span><?php echo esc_html( $team->post_title ); ?></span>
																</h4>
															</div>
														<?php } ?>

														<div class="hms-card-info">
															<div class="hms-card-info-item">
																<div class="hms-card-info-icon">
																	<?php hms_icon( 'team' ); ?>
																</div>
																<div class="hms-card-label">
																	<?php
																	printf(
																		esc_html( _n( '%s User', '%s Users', $users_count, 'hackathon' ) ),
																		esc_html( $users_count )
																	);
																	?>
																</div>
															</div>

															<?php if ( hms_is_mentor( $current_user_id ) || hms_is_administrator( $current_user_id ) ) { ?>
																<div class="hms-card-info-item">
																	<button type="button" class="hms-button hms-button-outline hms-button-small hms-add-team-user" data-team="<?php echo esc_attr( $team_id ); ?>" data-user="<?php echo esc_attr( $user_id ); ?>"><?php esc_html_e( 'Add team', 'hackathon' ); ?></button>
																</div>
															<?php } ?>
														</div>
													</div>

													<?php
												}
												?>
											</div>
											<?php
										} else {
											?>
											<h3><?php esc_html_e( 'No teams', 'hackathon' ); ?></h3>
										<?php } ?>

									</div>
								</div>
							</div>
						</div>

					<?php } ?>

				</div>
			</div>
		<?php } ?>

	</div>

</div>

	<?php
}
