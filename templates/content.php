<?php
/**
 * Content
 */

do_action( 'hms_before_load_content' );

$subpage    = get_query_var( 'hms_subpage' );
$subsubpage = get_query_var( 'hms_subsubpage' );

if ( 'hackathon_participant' === hms_get_user_role() && get_query_var( 'hms_subsubpage' ) ) {
	if (
		( 'edit-user' === $subpage || 'user' === $subpage ) ||
		( 'team' === $subpage && false === strpos( $subsubpage, 'invitation' ) )
		) {
		wp_redirect( hms_get_url( $subpage ) );
		exit;
	}
}

if ( hms_is_jury() ) {
	if ( 'edit-user' === $subpage ) {
		wp_redirect( hms_get_url( 'user' ) );
		exit;
	}
}

if ( ! hms_is_user_approved() ) {
	if ( $subpage && 'support' !== $subpage ) {
		wp_redirect( hms_get_url() );
		exit;
	}
}

if ( ! hms_is_administrator() ) {
	if ( 'new-team' === $subpage ) {
		wp_redirect( hms_get_url() );
		exit;
	}
}

hms_load_template( 'header.php' );

hms_load_template( 'navbar.php' );
?>

<?php hms_load_template( 'navbar.php' ); ?>

<div class="hms-main">

	<?php hms_load_template( 'sidebar.php' ); ?>

	<div class="hms-wrap">

		<?php hms_load_content_template(); ?>

		<?php hms_load_template( 'parts/footer.php' ); ?>

	</div><!-- .hms-wrap -->

</div><!-- .hms-main -->

<?php
hms_load_template( 'footer.php' );
