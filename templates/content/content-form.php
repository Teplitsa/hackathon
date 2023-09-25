<?php
/**
 * Template New Form
 */

$subpage = get_query_var( 'hms_subsubpage' );

$form_id     = $subpage;
if ( ! $form_id ) {
	$form_id = hms_get_option( 'defaultform' );
}
$form        = get_post( $form_id );
$form_type   = get_post_meta( $form_id, '_form_type', true );

if ( $form_type !== 'intrasystem' ) {

	$form_name   = $form->post_title;
	$form_slug   = get_post_meta( $form_id, '_form_slug', true );
	if ( ! $form_slug ) {
		$form_slug = 'register';
	}
	$form_role   = get_post_meta( $form_id, '_form_role', true );
	$form_fields = get_post_meta( $form_id, '_form_fields', true );

	$first_name_label       = isset( $form_fields['first_name']['label'] ) && ! empty( $form_fields['first_name']['label'] ) ? $form_fields['first_name']['label'] : esc_html__( 'First Name', 'hackathon' );
	$first_name_description = isset( $form_fields['first_name']['description'] ) ? $form_fields['first_name']['description'] : '';

	$last_name_label       = isset( $form_fields['last_name']['label'] ) && ! empty( $form_fields['last_name']['label'] ) ? $form_fields['last_name']['label'] : esc_html__( 'Last Name', 'hackathon' );
	$last_name_description = isset( $form_fields['last_name']['description'] ) ? $form_fields['last_name']['description'] : '';

	$email_label       = isset( $form_fields['email']['label'] ) && ! empty( $form_fields['email']['label'] ) ? $form_fields['email']['label'] : esc_html__( 'Email', 'hackathon' );
	$email_description = isset( $form_fields['email']['description'] ) ? $form_fields['email']['description'] : '';

	$agree_description = isset( $form_fields['event_privacy_agreement']['description'] ) && ! empty( $form_fields['event_privacy_agreement']['description'] ) ? $form_fields['event_privacy_agreement']['description'] : esc_html__( 'I agree to process my personal data to the organizer of the hms.', 'hackathon' );

	if ( ! $form_id ) {
		$form_name = esc_html__( 'Default Register Form', 'hackathon' );
	}

	?>

	<?php hms_page_header(); ?>

	<div class="hms-body">

		<div class="hms-content">

			<form class="hms-field-form" name="hms-form-update">
				<input type="hidden" name="action" value="hms_update_form">
				<input type="hidden" name="redirect_to" value="<?php echo esc_attr( hms_get_url( 'forms' ) ); ?>">
				<?php wp_nonce_field( 'hms-nonce', 'nonce' ); ?>
				<input type="hidden" name="form_id" value="<?php echo esc_attr( $form_id ); ?>">

				<div class="hms-field-list-wrap">
    
					<div class="hms-field-object">
						<div class="hms-field">
							<input type="text" name="name" class="hms-field-input" value="<?php echo esc_attr( $form_name ); ?>" placeholder="<?php esc_attr_e( 'Form name', 'hackathon' ); ?>">
						</div>
						<div class="hms-field">
							<input type="text" name="slug" class="hms-field-input" value="<?php echo esc_attr( $form_slug ); ?>" placeholder="<?php esc_attr_e( 'form-slug', 'hackathon' ); ?>">
						</div>
						<div class="hms-field">
							<div class="hms-field-description">
								<span class="hms-form-slug hms-clipboard" data-msg="<?php esc_attr_e( 'URL successfully copied to clipboard!', 'hackathon' ); ?>"><?php hms_url(); ?><span class="hms-form-slug-js"><?php echo esc_html( $form_slug ); ?></span>/</span> – <?php esc_html_e( 'the form will be available at this address.', 'hackathon' ); ?> | <a href="<?php hms_url( $form_slug, array( 'preview' => 'true' ) ); ?>" target="_blnk" class="hms-form-url-js"><?php esc_html_e( 'Preview', 'hackathon' ); ?></a></div>
						</div>
						<div class="hms-field">
							<select name="role" class="hms-field-input">
								<option value="hackathon_participant" <?php selected( 'hackathon_participant', $form_role ); ?>><?php esc_html_e( 'Participant', 'hackathon' ); ?></option>
								<option value="hackathon_mentor" <?php selected( 'hackathon_mentor', $form_role ); ?>><?php esc_html_e( 'Mentor', 'hackathon' ); ?></option>
								<option value="hackathon_jury" <?php selected( 'hackathon_jury', $form_role ); ?>><?php esc_html_e( 'Jury', 'hackathon' ); ?></option>
							</select>
						</div>
						<div class="hms-field">
							<div class="hms-field-description"><?php esc_html_e( 'Select user role that will be assigned during registration.', 'hackathon' ); ?></div>
						</div>
						<?php if ( $form_id != hms_get_option( 'defaultform' ) ) { ?>
							<div class="hms-field-footer">
								<div class="hms-field-actions"></div>
								<div class="hms-field-additional-actions">
									<a href="#" class="hms-button hms-button-outline hms-button-delete hms-button-xs hms-form-delete" data-id="<?php echo esc_attr( $form_id ); ?>"><?php esc_html_e( 'Delete Form', 'hackathon' ); ?></a>
								</div>
							</div>
						<?php } ?>
					</div>

					<div class="hms-field-separator"></div>

					<div class="hms-field-object">
						<div class="hms-field">
							<label class="hms-field-label"><?php esc_html_e( 'First Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-field hms-field-flex">
							<input type="text" name="field[first_name][label]" class="hms-field-input" value="<?php echo esc_attr( $first_name_label ); ?>" placeholder="<?php esc_attr_e( 'First Name', 'hackathon' ); ?>">
						</div>
						<div class="hms-field">
							<input type="text" name="field[first_name][description]" class="hms-field-input" value="<?php echo esc_attr( $first_name_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
						</div>
					</div>

					<div class="hms-field-object">
						<div class="hms-field">
							<label class="hms-field-label"><?php esc_html_e( 'Last Name', 'hackathon' ); ?></label>
						</div>
						<div class="hms-field hms-field-flex">
							<input type="text" name="field[last_name][label]" class="hms-field-input" value="<?php echo esc_attr( $last_name_label ); ?>" placeholder="<?php esc_attr_e( 'Last Name', 'hackathon' ); ?>">
						</div>
						<div class="hms-field">
							<input type="text" name="field[last_name][description]" class="hms-field-input" value="<?php echo esc_attr( $last_name_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
						</div>
					</div>

					<div class="hms-field-object">
						<div class="hms-field">
							<label class="hms-field-label"><?php esc_html_e( 'Email', 'hackathon' ); ?></label>
						</div>
						<div class="hms-field hms-field-flex">
							<input type="text" name="field[email][label]" class="hms-field-input" value="<?php echo esc_attr( $email_label ); ?>" placeholder="<?php esc_attr_e( 'Email', 'hackathon' ); ?>">
						</div>
						<div class="hms-field">
							<input type="text" name="field[email][description]" class="hms-field-input" value="<?php echo esc_attr( $email_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
						</div>
					</div>

					<div class="hms-field-separator"></div>

					<div class="hms-field-list">

						<?php hms_fields_object( $form_id ); ?>

					</div>

					<div class="hms-field-object">
						<div class="hms-field">
							<label class="hms-field-label"><?php esc_html_e( 'Agreement', 'hackathon' ); ?></label>
						</div>
						<div class="hms-field hms-field-flex">
							<textarea name="field[event_privacy_agreement][description]" class="hms-field-input" placeholder="<?php esc_attr_e( 'Agree text', 'hackathon' ); ?>" rows="3"><?php echo esc_html( $agree_description ); ?></textarea>
						</div>
					</div>

					<div class="hms-field-list-footer">
						<?php hms_button( esc_html__( 'Add field', 'hackathon' ), '#', 'outline', array( 'class' => 'hms-field-add-new' ) ); ?>
						<?php hms_button( esc_html__( 'Cancel', 'hackathon' ), hms_get_url('forms'), 'link' ); ?>
						<?php hms_button( esc_html__( 'Save', 'hackathon' ), '#', 'primary', array( 'class' => 'hms-update-form' ) ); ?>
					</div>

				</div>

			</form>
		</div>

	</div>

<?php } else {

	hms_load_template( 'content/content-form-inside.php' );

}
