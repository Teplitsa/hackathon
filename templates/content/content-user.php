<?php
/**
 * Template User
 */

$user_id = get_current_user_id();
if ( get_query_var( 'hms_subsubpage' ) ) {
	$user_id = get_query_var( 'hms_subsubpage' );
}

?>

<?php if ( ! hms_user_exists( $user_id ) || ! user_can( $user_id, 'hackathon' ) ) { ?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title"><?php esc_html_e( 'This user does not exist on the site.', 'hackathon' ); ?></h1>
		</div>
	</div>
</header>

<?php } else { ?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php hms_page_title(); ?>
				<?php if ( hms_is_administrator() ) { ?>
					<a href="<?php echo esc_url( hms_get_url('edit-user/' . $user_id . '/') );?>" class="hms-header-edit" title="<?php esc_attr_e( 'Edit profile', 'hackathon' ); ?>">
						<?php hms_icon( 'pencil' ); ?>
					</a>
				<?php } else if ( ! current_user_can( 'jury' ) && $user_id == get_current_user_id() ) { ?>
					<a href="<?php echo esc_url( hms_get_url('edit-user') );?>" class="hms-header-edit" title="<?php esc_attr_e( 'Edit profile', 'hackathon' ); ?>">
						<?php hms_icon( 'pencil' ); ?>
					</a>
				<?php } ?>
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

				<div class="hms-table">

					<?php if ( ! hms_is_administrator( $user_id ) ) {
						$user_status = hms_get_user_status( $user_id );
						$request_id  = hms_get_user_request_id( $user_id );
						?>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Status', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<div class="hms-card-status">
									<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $user_status ); ?>"></span>
									<div class="hms-card-label">
										<?php echo hms_get_request_status( $user_status ); ?>
									</div>
								</div>
							</div>
						</div>

					<?php } ?>

					<?php if ( ! current_user_can( 'jury' ) ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Avatar', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<?php hms_user_gravatar( $user_id, 200 ); ?>
							</div>
						</div>
					<?php } ?>

					<?php if ( ! current_user_can( 'jury' ) && ! current_user_can( 'participant' ) ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Role', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value"><?php echo hms_get_user_role_name( $user_id ); ?></span>
							</div>
						</div>
					<?php } ?>

					<?php if ( ! current_user_can( 'jury' ) ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Biographical Info', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value"><?php echo wpautop( esc_textarea( get_user_option( 'description', $user_id ) ) ); ?></span>
							</div>
						</div>
					<?php } ?>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Username', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value"><?php echo esc_html( get_user_option( 'user_login', $user_id ) ); ?></span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Email', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value"><?php echo esc_html( get_user_option( 'user_email', $user_id ) ); ?></span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'First Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value"><?php echo esc_html( get_user_option( 'first_name', $user_id ) ); ?></span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Last Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value"><?php echo esc_html( get_user_option( 'last_name', $user_id ) ); ?></span>
						</div>
					</div>

					<?php if ( ! hms_is_jury() ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value"><?php echo esc_html( get_user_option( 'telegram', $user_id ) ); ?></span>
							</div>
						</div>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Phone', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value"><?php echo esc_html( get_user_option( 'phone', $user_id ) ); ?></span>
							</div>
						</div>
						<?php if ( get_user_option( 'city', $user_id ) ) { ?>
							<div class="hms-table-row">
								<div class="hms-table-col">
									<label class="hms-table-label"><?php esc_html_e( 'City', 'hackathon' ); ?></label>
								</div>
								<div class="hms-table-col">
									<span class="hms-table-value"><?php echo esc_html( get_user_option( 'city', $user_id ) ); ?></span>
								</div>
							</div>
						<?php } ?>
					<?php } ?>

				</div>

			</div>
		</div>

		<?php if ( ! hms_is_administrator( $user_id ) && ! hms_is_jury( $user_id ) && hms_is_user_approved( $user_id ) ) { ?>

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
						if ( $teams ) { ?>
							<div class="hms-cards">
							<?php
							foreach( $teams as $team ) {
								$team_id = $team->ID;
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
												<?php hms_icon('team'); ?>
											</div>
											<div class="hms-card-label">
												<?php echo sprintf(
													esc_html( _n( '%s User', '%s Users', $users_count, 'hackathon' ) ),
													esc_html( $users_count )
												); ?>
											</div>
										</div>
									</div>
								</div>

							<?php } ?>
						</div>
					<?php } else { ?>

						<?php if ( 'hackathon_participant' === hms_get_user_role( $user_id ) ) { ?>
							<strong><?php esc_html_e( 'No team', 'hackathon' ); ?></strong>
						<?php } else { ?>
							<strong><?php esc_html_e( 'No teams', 'hackathon' ); ?></strong>
						<?php } ?>

					<?php } ?>

				</div>
			</div>
		<?php } ?>

	</div>

</div>

<?php }
