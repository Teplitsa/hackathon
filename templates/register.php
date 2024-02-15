<?php
/**
 * Template Login
 */

$form_slug      = get_query_var( 'hms_subpage' );
$forms_slugs    = hms_get_forms_slugs();
$form_id        = array_search( $form_slug, $forms_slugs );
$form_role      = get_post_meta( $form_id, '_form_role', true );
$form_fields    = get_post_meta( $form_id, '_form_fields', true );
$disable_button = isset( $_GET['preview'] );
$form_title     = apply_filters( 'hms_register_form_title', __( 'Sign up for the hackathon', 'hackathon' ), $form_slug );

hms_load_template( 'header.php' );

?>

<main class="hms-login">

	<div class="hms-login-container">

		<div class="hms-login-figure">
			<?php hms_logo(); ?>
		</div>

		<div class="hms-login-title">
			<h1><?php echo esc_html( $form_title ); ?></h1>
		</div>

			<?php if ( isset( $_GET['checkemail'] ) && 'registered' === $_GET['checkemail'] ) { ?>
				<p class="message">
				<?php
				echo apply_filters(
					'hms_checkemail_registered_message',
					sprintf(
					/* translators: %s: Link to the login page. */
						__( 'Registration complete. Please check your email, then visit the <a href="%s">login page</a>.' ),
						hms_get_url()
					),
					$form_slug
				);
				?>
					</p>
			<?php } else { ?>

				<div class="hms-login-desc"><span class="hms-error-color">*</span> <?php esc_html_e( 'All fields are required.', 'hackathon' ); ?></div>

				<form name="registerform" novalidate class="hms-form">

					<?php
						$user_type = 'hackathon_participant';
						if ( $form_role ) {
							$user_type = $form_role;
						}

						echo hms_input_hidden( 'action', 'hackathon_register_user' );
						echo hms_input_hidden( 'redirect_to', esc_attr( hms_get_url( $form_slug, array( 'checkemail' => 'registered' ) ) ) );
						echo hms_input_hidden( 'user_type', $user_type );
						echo hms_input_hidden( 'form_id', $form_id );

						wp_nonce_field( 'hackathon-nonce', 'nonce' );
					?>

					<?php
						/** First name field */
						$first_name_label       = isset( $form_fields['first_name']['label'] ) && ! empty( $form_fields['first_name']['label'] ) ? $form_fields['first_name']['label'] : esc_html__( 'First Name', 'hackathon' );
						$first_name_description = isset( $form_fields['first_name']['description'] ) ? $form_fields['first_name']['description'] : '';
					?>
					<div class="hms-form-row">
						<label class="hms-form-label" for="first_name"><?php echo esc_html( $first_name_label ); ?> <span class="hms-error-color">*</span></label><input type="text" name="first_name" id="first_name" class="input" required="required">
						<?php if ( $first_name_description ) { ?>
							<div class="hms-form-text"><?php echo esc_html( $first_name_description ); ?></div>
						<?php } ?>
					</div>

					<?php
						/** Last name field */
						$last_name_label       = isset( $form_fields['last_name']['label'] ) && ! empty( $form_fields['last_name']['label'] ) ? $form_fields['last_name']['label'] : esc_html__( 'Last Name', 'hackathon' );
						$last_name_description = isset( $form_fields['last_name']['description'] ) ? $form_fields['last_name']['description'] : '';
					?>
					<div class="hms-form-row">
						<label class="hms-form-label" for="last_name"><?php echo esc_html( $last_name_label ); ?> <span class="hms-error-color">*</span></label><input type="text" name="last_name" id="last_name" class="input" required="required">
						<?php if ( $last_name_description ) { ?>
							<div class="hms-form-text"><?php echo esc_html( $last_name_description ); ?></div>
						<?php } ?>
					</div>

					<?php
						/** Email field */
						$email_label       = isset( $form_fields['email']['label'] ) && ! empty( $form_fields['email']['label'] ) ? $form_fields['email']['label'] : esc_html__( 'Email', 'hackathon' );
						$email_description = isset( $form_fields['email']['description'] ) ? $form_fields['email']['description'] : '';
					?>
					<div class="hms-form-row">
						<label class="hms-form-label" for="email"><?php echo esc_html( $email_label ); ?> <span class="hms-error-color">*</span></label><input type="text" name="email" id="email" class="input" required="required">
						<?php if ( $email_description ) { ?>
							<div class="hms-form-text"><?php echo esc_html( $email_description ); ?></div>
						<?php } ?>
					</div>

					<?php
						/** Agree field */
						$agree_description = isset( $form_fields['event_privacy_agreement']['description'] ) && ! empty( $form_fields['event_privacy_agreement']['description'] ) ? $form_fields['event_privacy_agreement']['description'] : esc_html__( 'I agree to process my personal data to the organizer of the hms.', 'hackathon' );

						$unset_fields = array(
							'first_name',
							'last_name',
							'email',
							'event_privacy_agreement',
						);

						if ( $form_fields && is_array( $form_fields ) ) {
							foreach ( $unset_fields as $field ) {
								if ( isset( $form_fields[ $field ] ) ) {
									unset( $form_fields[ $field ] );
								}
							}
							foreach ( $form_fields as $key => $field ) {
								hms_front_field( $key, $field );
							}
						}

						?>
					<div class="hms-form-row">
						<fieldset>
							<label for="event_privacy_agreement" class="hms-form-label-agreement"><input name="event_privacy_agreement" type="checkbox" id="event_privacy_agreement" value="true" required="required"> <span><?php echo esc_html( $agree_description ); ?></span></label>
						</fieldset>
					</div>

					<?php do_action( 'hms_register_form_before_submit', $form_slug ); ?>

					<div class="hms-form-row">
						<input type="submit" name="wp-submit" id="wp-submit" class="hms-form-button" value="<?php esc_attr_e( 'Register', 'hackathon' ); ?>" <?php disabled( true, $disable_button ); ?>>
						<span class="spinner"></span>
					</div>

				</form>

				<p id="nav">
					<a href="<?php echo esc_url( wp_login_url( hms_get_url() ) ); ?>"><?php _e( 'Log in', 'hackathon' ); ?></a>
					|
					<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'hackathon' ); ?></a>
				</p>
			<?php } ?>
	</div>

</main>

<?php
hms_load_template( 'footer.php' );
