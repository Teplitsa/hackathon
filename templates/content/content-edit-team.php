<?php
/**
 * Template New Team
 */

$current_user_id = get_current_user_id();
$team_id         = -1;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$team_id = get_query_var( 'hms_subsubpage' );
}

$team = get_post( $team_id );

if ( ! $team ) {
	?>

<div class="hackathon-header">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'This team does not exist on the site.', 'hackathon' ); ?></h1>
	<a href="<?php echo esc_url( hms_get_url( 'teams' ) ); ?>" class="button"><?php esc_html_e( 'Go to teams', 'hackathon' ); ?></a>
</div>

	<?php
} else {

	$team_status = get_post_meta( $team_id, '_team_status', true );
	?>

<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title">
			<?php esc_html_e( 'Edit Team', 'hackathon' ); ?>
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

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'dashboard' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Team Info', 'hackathon' ); ?></h3>
			</div>

			<div class="hms-widget-content">

				<form class="hackathon-update-team-form" >

					<input type="hidden" name="action" value="hackathon_update_team">
					<input type="hidden" name="redirect_to" value="<?php echo esc_attr( hms_get_url( 'team' ) ); ?>">
					<input type="hidden" name="post_id" value="<?php echo esc_attr( $team_id ); ?>">
					<?php wp_nonce_field( 'hackathon-nonce', 'nonce' ); ?>

					<table class="form-table hackathon-form-table">
						<tbody>
							<tr>
								<th><label><?php esc_html_e( 'Team logo', 'hackathon' ); ?></label></th>
								<td>
									<div class="hackathon-image-field">
										<input type="hidden" name="team_logo" value="<?php echo get_post_meta( $team_id, '_team_logo', true ); ?>">
										<div class="hackathon-image-figure">
										<?php
											$upload_class = '';
											$remove_class = '';
										?>
										<?php if ( wp_get_attachment_image( get_post_meta( $team_id, '_team_logo', true ) ) ) { ?>
											<?php hms_team_logo( $team_id ); ?>
											<?php $upload_class = ' hidden'; ?>
											<?php
										} else {
											$remove_class = ' hidden';
										}
										?>
										</div>
										<button type="button" class="button hackathon-remove-image<?php echo esc_attr( $remove_class ); ?>" data-input="team_logo"><?php esc_html_e( 'Remove logo', 'hackathon' ); ?></button>
										<button type="button" class="button hackathon-upload-image<?php echo esc_attr( $upload_class ); ?>" data-title="<?php esc_attr_e( 'Select logo', 'hackathon' ); ?>" data-button="<?php esc_attr_e( 'Select', 'hackathon' ); ?>" data-input="team_logo"><?php esc_html_e( 'Add logo', 'hackathon' ); ?></button>
									</div>
								</td>
							</tr>
							<tr>
								<th><label for="team_name"><?php esc_html_e( 'Team name', 'hackathon' ); ?></label></th>
								<td>
									<input type="text" name="team_name" class="regular-text" id="team_name" value="<?php echo esc_attr( get_the_title( $team_id ) ); ?>">
								</td>
							</tr>

							<?php if ( get_post_meta( $team_id, '_team_titles' ) ) { ?>
								<tr>
									<th><label for="team_name"><?php esc_html_e( 'Team name history', 'hackathon' ); ?></label></th>
									<td>
										<?php foreach ( get_post_meta( $team_id, '_team_titles' ) as $item ) { ?>
											<?php echo esc_html( $item ); ?><br>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>

							<tr>
								<th><label form="team_chat"><?php esc_html_e( 'Link to the team\'s chat', 'hackathon' ); ?></label></th>
								<td><input type="text" name="team_chat" class="regular-text" id="team_chat" value="<?php echo get_post_meta( $team_id, '_team_chat', true ); ?>"></td>
							</tr>
						</tbody>
					</table>

					<p class="submit">
						<button class="hms-button" type="submit"><?php esc_html_e( 'Update team', 'hackathon' ); ?></button>
						<a class="hms-button hms-button-link" href="<?php hms_url( 'team/' . $team_id . '/' ); ?>"><?php esc_html_e( 'Go to team', 'hackathon' ); ?></a>
					</p>

				</form>

			</div>
		</div>

	</div>

</div>

	<?php
}
