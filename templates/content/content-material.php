<?php
/**
 * Template Material
 */

$material_id = null;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$material_id = get_query_var( 'hms_subsubpage' );
}

if ( ! $material_id ) {
	?>

<div class="hackathon-header">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'This material does not exist on the site.', 'hackathon' ); ?></h1>
	<a href="<?php echo esc_url( hms_get_url( 'materials' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Go to materials', 'hackathon' ); ?></a>
</div>

<?php } else {

$type    = get_post_meta( $material_id, 'type', true );
$team_id = get_post_meta( $material_id, 'team_id', true );
$user_id = get_post_field( 'post_author', $material_id );
$fields  = get_post_meta( $material_id, '_fields', true );

?>
<header class="hms-header">
	<div class="hms-header-content">
		<h1 class="hms-header-title">
			<?php echo esc_html( get_the_title( $material_id ) ); ?>
		</h1>
	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">
			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'materials' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Published on:', 'hackathon' ); ?> <?php echo get_the_date( 'F j, Y - H:i'); ?></h3>
			</div>

			<div class="hms-widget-content">

				<div class="hms-table">

					<div class="hms-table-row">
						<div class="hms-table-col">
							<span class="hms-table-label"><?php esc_html_e( 'Material type', 'hackathon' ); ?></span>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value"><?php echo wpautop( hms_get_material_type_name( $type ) ); ?></span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<span class="hms-table-label"><?php esc_html_e( 'Team', 'hackathon' ); ?></span>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<a href="<?php hms_url('team/' . $team_id ); ?>"><?php echo get_the_title( get_post_meta( $material_id, 'team_id', true ) );?></a>
							</span>
						</div>
					</div>

					<div class="hms-table-row">
						<div class="hms-table-col">
							<span class="hms-table-label"><?php esc_html_e( 'Author', 'hackathon' ); ?></span>
						</div>
						<div class="hms-table-col">
							<span class="hms-table-value">
								<a href="<?php hms_url( 'user/' . $user_id ); ?>"><?php echo get_user_option( 'user_login', $user_id ); ?></a>
							</span>
						</div>
					</div>

					<?php foreach ( $fields as $key => $field ) { ?>
						<div class="hms-table-row">
							<div class="hms-table-col">
								<span class="hms-table-label"><?php echo esc_html( $field['label'] ); ?></span>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value">
									<?php echo wpautop( esc_html( $field['value'] ) ); ?>
								</span>
							</div>
						</div>
					<?php } ?>

					<?php if( 'initial_presentation' === $type ) { ?>

					<?php } else if( 'checkpoint_report' === $type ) { ?>

					<?php } else if ( 'final_presentation' === $type ) { ?>

						<div class="hms-table-row">
							<div class="hms-table-col">
								<span class="hms-table-label"><?php esc_html_e( 'Presentation', 'hackathon' ); ?></span>
							</div>
							<div class="hms-table-col">
								<span class="hms-table-value">
									<div class="hms-list-files">
										<?php
										$files = explode( ',', get_post_meta( $material_id, 'final_files', true ) );
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
								</span>
							</div>
						</div>

					<?php } ?>

				</div>

			</div>
		</div>

	</div>

</div>

<?php }
