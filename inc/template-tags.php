<?php
/**
 * Template Tags
 */

/**
 * Get default logo url
 */
function hms_get_default_logo_url(){
	$logo_url = HMS_URL . 'assets/images/logo.svg';
	return apply_filters( 'hms_get_default_logo_url', $logo_url );
}

/**
 * Get logo url
 */
function hms_get_logo_url(){
	$logo_url = hms_get_default_logo_url();
	if ( wp_get_attachment_image_url( hms_option( 'event_logo' ) ) ) {
		$logo_url = wp_get_attachment_image_url( hms_option( 'event_logo' ), 'large' );
	}
	return apply_filters( 'hms_get_logo_url', $logo_url );
}

/**
 * Get logo img
 */
function hms_get_logo(){
	$logo_url = hms_get_logo_url();
	$logo     = '<img src="' . esc_url( $logo_url ) . '" class="hms-logo" alt="' . esc_attr( get_bloginfo() ) . '">';
	return apply_filters( 'hms_get_logo', $logo, $logo_url );
}

/**
 * Logo img
 */
function hms_logo(){
	echo hms_kses( hms_get_logo() );
}

/**
 * Button
 * 
 * @param string $type outline.
 */
function hms_button( $text = null, $url = null, $type = '', $attr = array(), $args = array() ) {
	$classes          = array( 'hms-button' );
	if ( $type ) {
		$classes[] = 'hms-button-' . $type;
	}
	if ( array_key_exists( 'class', $attr ) ) {
		$classes[] = $attr['class'];
		unset( $attr['class'] );
	}
	$class = implode( ' ', array_unique( array_filter( $classes ) ) );

	$html_attr = 'class="' . esc_attr( $class ) . '"';

	if ( $attr ) {
		foreach ( $attr as $attr_name => $attr_value ) {
			$html_attr .= ' ' . $attr_name . '="' . $attr_value . '"';
		}
	}

	?>
	<a href="<?php echo esc_url( $url ); ?>" <?php echo wp_kses_post( $html_attr ); ?>><?php echo esc_html( $text ); ?></a>
	<?php
}

/**
 * Page Header
 */
function hms_page_header( $title = '' ){
	if ( ! $title ) {
		$title = hms_page_title( '', false );
	}
	?>
	<header class="hms-header">
		<div class="hms-header-content">
			<div class="hms-header-content-inner">
				<h1 class="hms-header-title">
					<?php echo esc_html( $title ); ?>
				</h1>
				<?php if ( hms_get_page_subtitle() ) { ?>
					<div class="hms-header-desc">
						<?php hms_page_subtitle(); ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</header>
	<?php
}