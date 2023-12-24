<?php
/**
 * Template Forms
 */

$form_type = get_query_var( 'hms_subsubpage' );

hms_create_default_forms();

hms_page_header();

if ( ! $form_type ) {

	?>

	<?php hms_forms_menu(); ?>

	<div class="hms-body">

		<div class="hms-content">
			<?php hms_card_forms(); ?>

			<div class="hms-cards-footer">
				<?php hms_button( esc_html__( 'Add new form', 'hackathon' ), '#', 'outline', array( 'data-modal' => '.hms-modal-new-form' ) ); ?>
			</div>
		</div>

	</div>

	<div class="hms-modal hms-modal-new-form">
		<div class="hms-modal-body">
			<div class="hms-widget">

				<div class="hms-widget-heading">
					<h3 class="hms-widget-title"><?php esc_html_e( 'New form', 'hackathon' ); ?></h3>
					<div class="hms-widget-actions">
						<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
					</div>
				</div>

				<div class="hms-widget-content">

					<form class="hms-field-form" name="add-new-form">
						<input type="hidden" name="action" value="hms_new_form">
						<?php wp_nonce_field( 'hms-nonce', 'nonce' ); ?>
						<input type="hidden" name="form_type" value="default">

						<div class="hms-field-list-wrap">

							<div class="hms-field-object">
								<div class="hms-field">
									<input type="text" name="name" class="hms-field-input" value="" placeholder="<?php esc_attr_e( 'Form name', 'hackathon' ); ?>">
								</div>
								<div class="hms-field">
									<input type="text" name="slug" class="hms-field-input" value="" placeholder="<?php esc_attr_e( 'form-slug', 'hackathon' ); ?>">
								</div>
							</div>

							<div class="hms-field-list-footer">
								<?php hms_button( esc_html__( 'Cancel' ), '#', 'link', array( 'class' => 'hms-modal-close' ) ); ?>
								<?php hms_button( esc_html__( 'Create' ), '#', 'primary', array( 'class' => 'hms-create-new-form' ) ); ?>
							</div>

						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<?php
} else {

	?>

	<?php hms_forms_menu(); ?>

	<div class="hms-body">

		<div class="hms-content">
			<?php hms_card_forms( array( 'type' => 'intrasystem' ) ); ?>

			<div class="hms-cards-footer">
				<?php hms_button( esc_html__( 'Add checkpoint form', 'hackathon' ), '#', 'outline', array( 'data-modal' => '.hms-modal-new-form' ) ); ?>
			</div>
		</div>

	</div>

	<div class="hms-modal hms-modal-new-form">
		<div class="hms-modal-body">
			<div class="hms-widget">

				<div class="hms-widget-heading">
					<h3 class="hms-widget-title"><?php esc_html_e( 'New checkpoint form', 'hackathon' ); ?></h3>
					<div class="hms-widget-actions">
						<span class="hms-modal-close"><?php hms_icon( 'close' ); ?></span>
					</div>
				</div>

				<div class="hms-widget-content">

					<form class="hms-field-form" name="add-new-form">
						<input type="hidden" name="action" value="hms_new_intrasystem_form">
						<?php wp_nonce_field( 'hms-nonce', 'nonce' ); ?>
						<input type="hidden" name="form_type" value="intrasystem">

						<div class="hms-field-list-wrap">

							<div class="hms-field-object">
								<div class="hms-field">
									<input type="text" name="name" class="hms-field-input" value="" placeholder="<?php esc_attr_e( 'Form name', 'hackathon' ); ?>">
								</div>
							</div>

							<div class="hms-field-list-footer">
								<?php hms_button( esc_html__( 'Cancel' ), '#', 'link', array( 'class' => 'hms-modal-close' ) ); ?>
								<?php hms_button( esc_html__( 'Create' ), '#', 'primary', array( 'class' => 'hms-create-new-form' ) ); ?>
							</div>

						</div>
					</form>

				</div>
			</div>
		</div>
	</div>

	<?php
}
