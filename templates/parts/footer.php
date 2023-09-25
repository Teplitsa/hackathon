<?php
/**
 * Content Footer
 */

$footer_agreement = sprintf( esc_html__( 'Plugin Service Level Agreement %s', 'hackathon' ), '«<a href="' . esc_url( hms_get_teplitsa_url() ) . '" target="_blank">HMS</a>»' );
$footer_developer = sprintf( esc_html__( 'Developed by %s', 'hackathon' ), '<a href="https://te-st.org" target="_blank">' . __( 'Teplitsa of social technologies', 'hackathon' ) . '</a>' );

?>

<footer class="hms-footer">
	<div class="hms-footer-developer">
		<a href="https://hms.te-st.org">
			<img src="<?php echo esc_url( HMS_URL ); ?>assets/images/teplitsa-logo.svg" alt="">
		</a>
	</div>
	<ul class="hms-footer-menu">
		<li>
			<a href="<?php echo esc_url( hms_get_docs_url() ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'hackathon' ); ?></a>
		</li>
		<li>
			<a href="<?php echo esc_url( hms_get_support_url() ); ?>" target="_blank"><?php esc_html_e( 'Developer Chat', 'hackathon' ); ?></a>
		</li>
	</ul>
	<div class="hms-footer-copyright">
		<span>
			<?php echo hms_kses( $footer_agreement ); ?><br>
			<?php echo hms_kses( $footer_developer ); ?>
		</span>
	</div>
</footer>
