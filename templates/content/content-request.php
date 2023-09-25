<?php
/**
 * Template Request
 */

$request_id = null;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$request_id = get_query_var( 'hms_subsubpage' );
}

$request = null;
if ( $request_id ) {
	$request = get_post( $request_id );
}

if ( ! $request || 'hms_request' !== $request->post_type ) {

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php esc_html_e( 'This request does not exist on the site.', 'hackathon' ); ?>
			</h1>
			<?php if ( hms_get_page_subtitle() ) { ?>
				<div class="hms-header-desc">
					<?php hms_page_subtitle(); ?>
				</div>
			<?php } ?>
		</div>
	</div>
</header>

<?php } else {

?>

<header class="hms-header">
	<div class="hms-header-content">
		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php echo esc_html( get_the_title( $request_id ) ); ?>
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
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'applications' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Request details', 'hackathon' ); ?></h3>
			</div>

			<div class="hms-widget-content">
				<div class="hms-table">

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Request Status', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">

							<?php $request_status = get_post_meta( $request_id, 'status', true );?>

							<?php if ( hms_is_administrator() && hms_is_participant( $request->post_author ) ) { ?>
								<div class="hms-card-status-dropdown">
									<div class="hms-card-status">
										<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $request_status ); ?>"></span>
										<div class="hms-card-label">
											<?php echo hms_get_request_status( $request_status ); ?>
										</div>
										<span class="hms-card-status-toggle">
											<?php hms_icon('down'); ?>
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

								<a href="<?php hms_url('user/' . $request->post_author ); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'View user', 'hackathon' ); ?></a>

							<?php } else { ?>

								<div class="hms-card-status">
									<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $request_status ); ?>"></span>
									<div class="hms-card-label">
										<?php echo hms_get_request_status( $request_status ); ?>
									</div>
								</div>

								<?php if ( hms_is_administrator() ) { ?>
									<a href="<?php hms_url('user/' . $request->post_author ); ?>" class="hms-button hms-button-outline"><?php esc_html_e( 'View user', 'hackathon' ); ?></a>
								<?php } ?>

							<?php } ?>

						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Role', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( hms_get_role_name( get_post_meta( $request_id, 'role', true ) ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Username', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'user_login', true ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Email', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'user_email', true ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'First Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'first_name', true ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Last Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'last_name', true ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Phone', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'phone', true ) ); ?>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<label class="hms-table-label"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></label>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<?php echo esc_html( get_post_meta( $request_id, 'telegram', true ) ); ?>
							</span>
						</div>
					</div>

					<?php if ( get_post_meta( $request_id, 'city', true ) ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'City', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value">
									<?php echo esc_html( get_post_meta( $request_id, 'city', true ) ); ?>
								</span>
							</div>
						</div>
					<?php } ?>

					<?php
					$project_name = get_post_meta( $request_id, 'project_name', true );
					if ( $project_name ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<label class="hms-table-label"><?php esc_html_e( 'Project name', 'hackathon' ); ?></label>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value">
									<?php echo esc_html( get_post_meta( $request_id, 'project_name', true ) ); // team_name ?>
								</span>
							</div>
						</div>
					<?php } ?>

					<?php if ( get_post_meta( $request_id, 'custom', true ) ) { ?>
						<?php foreach( get_post_meta( $request_id, 'custom', true ) as $field ) { ?>
							<div class="hms-table-row">
								<div class="hms-table-col">
									<label class="hms-table-label"><?php echo esc_html( $field['label'] ); ?></label>
								</div>
								<div class="hms-table-col">
									<span class="hms-table-value">
										<?php echo esc_html( $field['value'] ); ?>
									</span>
								</div>
							</div>
						<?php } ?>
					<?php } ?>

				</div>
			</div>
		</div>
	</div>
</div>

<?php
}
