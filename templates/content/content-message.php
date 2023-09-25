<?php
/**
 * Template Message
 */

$message_id = null;
if ( get_query_var( 'hms_subsubpage' ) ) {
	$message_id = get_query_var( 'hms_subsubpage' );
}

if ( ! $message_id ) {
	?>

<div class="hackathon-header">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'This message does not exist on the site.', 'hackathon' ); ?></h1>
	<a href="<?php echo esc_url( hms_get_url( 'messages' ) ); ?>" class="page-title-action"><?php esc_html_e( 'Go to messages', 'hackathon' ); ?></a>
</div>

<?php } else {

do_action( 'hackathon_message_page', $message_id, get_current_user_id() );

?>

<header class="hms-header">
	<div class="hms-header-content">

		<div class="hms-header-content-inner">
			<h1 class="hms-header-title">
				<?php echo esc_html( get_the_title( $message_id ) ); ?>
				<a href="<?php echo esc_url( hms_get_url( 'edit-message/' . $message_id ) );?>" class="hms-header-edit" title="<?php esc_attr_e( 'Edit message', 'hackathon' ); ?>">
					<?php hms_icon( 'pencil' ); ?>
				</a>
			</h1>
		</div>

	</div>
</header>

<div class="hms-body">

	<div class="hms-content">

		<div class="hms-widget">

			<div class="hms-widget-heading">
				<div class="hms-widget-icon">
					<?php hms_icon( 'dashboard' ); ?>
				</div>
				<h3 class="hms-widget-title"><?php esc_html_e( 'Published on:', 'hackathon' ); ?> <?php echo get_the_date( 'F j, Y - H:i'); ?></h3>
			</div>

			<div class="hms-widget-content">

				<div class="hms-entry-content">
					<?php
						$content_post = get_post( $message_id );
						$content = $content_post->post_content;
						$content = apply_filters('the_content', $content);
						$content = str_replace(']]>', ']]&gt;', $content);
						echo wp_kses_post( $content );
					?>
				</div>

			</div>
		</div>

	</div>

</div>

<?php }
