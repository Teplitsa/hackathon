<?php
/**
 * Admin Forms
 *
 * @package hackathon
 */

/**
 * Register a custom post type form
 */
function hms_register_post_type_form() {
	$labels = array(
		'name'               => __( 'Forms', 'hackathon' ),
		'singular_name'      => __( 'Forms', 'hackathon' ),
		'menu_name'          => __( 'Forms', 'hackathon' ),
		'name_admin_bar'     => __( 'Forms', 'hackathon' ),
		'add_new'            => __( 'Add Form', 'hackathon' ),
		'add_new_item'       => __( 'Add New Form', 'hackathon' ),
		'new_item'           => __( 'New Form', 'hackathon' ),
		'edit_item'          => __( 'Edit Form', 'hackathon' ),
		'view_item'          => __( 'View Form', 'hackathon' ),
		'all_items'          => __( 'All Forms', 'hackathon' ),
		'search_items'       => __( 'Search Form', 'hackathon' ),
		'not_found'          => __( 'No Form found.', 'hackathon' ),
		'not_found_in_trash' => __( 'No Forms found in Trash.', 'hackathon' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => true,
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'hms_form', $args );
}
add_action( 'init', 'hms_register_post_type_form' );

/**
 * Forms menu
 */
function hms_forms_menu() {
	$page    = get_query_var( 'hms_subpage' );
	$subpage = get_query_var( 'hms_subsubpage' );

	$public_forms = get_posts(
		array(
			'post_type'      => 'hms_form',
			'posts_per_page' => -1,
			'post_status'    => 'eny',
			'meta_query'     => array(
				array(
					'key'   => '_form_type',
					'value' => 'default',
				),
			),
		)
	);

	$intrasystem_forms = get_posts(
		array(
			'post_type'      => 'hms_form',
			'posts_per_page' => -1,
			'post_status'    => 'eny',
			'meta_query'     => array(
				array(
					'key'   => '_form_type',
					'value' => 'intrasystem',
				),
			),
		)
	);

	$filter_items = array(
		array(
			'name'  => __( 'Public', 'hackathon' ),
			'slug'  => '',
			'count' => count( $public_forms ),
		),
		array(
			'name'  => __( 'Intrasystem', 'hackathon' ),
			'slug'  => 'inside',
			'count' => count( $intrasystem_forms ),
		),
	);

	?>
	<ul class="hms-submenu">
		<?php
		$count = count( $filter_items );
		foreach ( $filter_items as $item ) {
			$item_class = 'hms-submenu-item';
			$slug       = 'forms';
			if ( $item['slug'] ) {
				$slug .= '/' . $item['slug'];
			}
			if ( $subpage === $item['slug'] ) {
				$item_class .= ' active';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<a href="<?php hms_url( $slug ); ?>"><?php echo esc_html( $item['name'] ); ?>
					<span class="count">(<?php echo esc_html( $item['count'] ); ?>)</span>
				</a>
			</li>
		<?php } ?>
	</ul>
	<?php
}

/**
 * Forms Card
 *
 * @param array $args form args.
 */
function hms_card_forms( $args = '' ) {

	$defaults = array(
		'echo' => 1,
		'type' => 'default',
	);

	$parsed_args = wp_parse_args( (array) $args, $defaults );

	ob_start();

	if ( 'intrasystem' === $parsed_args['type'] ) {
		?>

		<div class="hms-cards hms-cards-form">

			<?php
			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => 1,
				'post_status'    => 'private',
				'meta_key'       => '_form_slug',
				'meta_value'     => 'prezentation',
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				while ( $forms_query->have_posts() ) {
					$forms_query->the_post();
					$form_slug = get_post_meta( get_the_ID(), '_form_slug', true );
					?>
					<div class="hms-card hms-form-<?php echo esc_attr( $form_slug ); ?>">

						<div class="hms-card-content">
							<h4 class="hms-card-title">
								<a href="<?php hms_url( 'form/' . get_the_ID() . '/' ); ?>"><?php esc_html_e( 'Kick-off presentation', 'hackathon' ); ?> <?php hms_icon( 'sticky' ); ?></a>
							</h4>
						</div>

						<div class="hms-card-actions">
							<button class="hms-card-action-toggle" type="button" title="<?php esc_attr_e( 'Toggle menu', 'hackathon' ); ?>">
								<?php hms_icon( 'menu-vertical' ); ?>
							</button>
							<div class="hms-card-action-popover">
								<div class="hms-card-action-menu">
									<a href="<?php hms_url( 'form/' . get_the_ID() . '/' ); ?>"><?php esc_html_e( 'Edit', 'hackathon' ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}

			wp_reset_postdata();
			?>

			<?php
			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => 1,
				'post_status'    => 'private',
				'meta_key'       => '_form_slug',
				'meta_value'     => 'final',
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				while ( $forms_query->have_posts() ) {
					$forms_query->the_post();
					$form_slug = get_post_meta( get_the_ID(), '_form_slug', true );
					?>
					<div class="hms-card hms-form-<?php echo esc_attr( $form_slug ); ?>">

						<div class="hms-card-content">
							<h4 class="hms-card-title">
								<a href="<?php hms_url( 'form/' . get_the_ID() . '/' ); ?>"><?php echo esc_html( get_post_field( 'post_title' ) ); ?> <?php hms_icon( 'sticky' ); ?></a>
							</h4>
						</div>

						<div class="hms-card-actions">
							<button class="hms-card-action-toggle" type="button" title="<?php esc_attr_e( 'Toggle menu', 'hackathon' ); ?>">
								<?php hms_icon( 'menu-vertical' ); ?>
							</button>
							<div class="hms-card-action-popover">
								<div class="hms-card-action-menu">
									<a href="<?php hms_url( 'form/' . get_the_ID() . '/' ); ?>"><?php esc_html_e( 'Edit', 'hackathon' ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}

			wp_reset_postdata();
			?>

		</div>

		<div class="hms-cards hms-cards-form">

			<div class="hms-card-separator"></div>

			<?php
			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'meta_key'       => '_form_type',
				'meta_value'     => 'intrasystem',
				'order'          => 'asc',
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				while ( $forms_query->have_posts() ) {
					$forms_query->the_post();
					$form_slug = get_post_meta( get_the_ID(), '_form_slug', true );
					?>
					<div class="hms-card hms-form-<?php echo esc_attr( $form_slug ); ?>">
						<div class="hms-card-content">
							<h4 class="hms-card-title">
								<a href="<?php hms_url( 'form/' . get_the_ID() ); ?>"><?php the_title(); ?></a>
							</h4>
						</div>

						<div class="hms-card-actions">
							<button class="hms-card-action-toggle" type="button" title="<?php esc_attr_e( 'Toggle menu', 'hackathon' ); ?>">
								<?php hms_icon( 'menu-vertical' ); ?>
							</button>
							<div class="hms-card-action-popover">
								<div class="hms-card-action-menu">
									<a href="<?php hms_url( 'form/' . get_the_ID() ); ?>"><?php esc_html_e( 'Edit', 'hackathon' ); ?></a>
									<a href="#" class="hms-form-delete" data-id="<?php the_ID(); ?>"><?php esc_html_e( 'Delete', 'hackathon' ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}
			wp_reset_postdata();
			?>

		</div>

	<?php } else { ?>

		<div class="hms-cards hms-cards-form">
			<div class="hms-card">

				<div class="hms-card-content">
					<h4 class="hms-card-title">
						<a href="<?php hms_url( 'form' ); ?>"><?php esc_html_e( 'Default Register Form', 'hackathon' ); ?></a>
					</h4>
				</div>

				<div class="hms-card-actions">
					<button class="hms-card-action-toggle" type="button" title="<?php esc_attr_e( 'Toggle menu', 'hackathon' ); ?>">
						<?php hms_icon( 'menu-vertical' ); ?>
					</button>
					<div class="hms-card-action-popover">
						<div class="hms-card-action-menu">
							<a href="<?php hms_url( 'form' ); ?>"><?php esc_html_e( 'Edit', 'hackathon' ); ?></a>
							<a href="<?php hms_url( 'register', array( 'preview' => 'true' ) ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'hackathon' ); ?></a>
						</div>
					</div>
				</div>

			</div>

			<?php
			$forms_args = array(
				'post_type'      => 'hms_form',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'meta_key'       => '_form_type',
				'meta_value'     => 'default',
			);

			$forms_query = new WP_Query( $forms_args );

			if ( $forms_query->have_posts() ) {
				while ( $forms_query->have_posts() ) {
					$forms_query->the_post();
					$form_slug = get_post_meta( get_the_ID(), '_form_slug', true );
					?>
					<div class="hms-card hms-form-<?php echo esc_attr( $form_slug ); ?>">
						<div class="hms-card-content">
							<h4 class="hms-card-title">
								<a href="<?php hms_url( 'form/' . get_the_ID() ); ?>"><?php the_title(); ?></a>
							</h4>
						</div>

						<div class="hms-card-actions">
							<button class="hms-card-action-toggle" type="button" title="<?php esc_attr_e( 'Toggle menu', 'hackathon' ); ?>">
								<?php hms_icon( 'menu-vertical' ); ?>
							</button>
							<div class="hms-card-action-popover">
								<div class="hms-card-action-menu">
									<a href="<?php hms_url( 'form/' . get_the_ID() ); ?>"><?php esc_html_e( 'Edit', 'hackathon' ); ?></a>
									<a href="<?php hms_url( $form_slug, array( 'preview' => 'true' ) ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'hackathon' ); ?></a>
									<?php /* <a href="">Duplicate</a> */ ?>
									<a href="#" class="hms-form-delete" data-id="<?php the_ID(); ?>"><?php esc_html_e( 'Delete', 'hackathon' ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
			}

			wp_reset_postdata();
			?>

		</div>
		<?php
	}

	$output = ob_get_clean();

	$html = apply_filters( 'hms_card_forms', $output, $parsed_args );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}

/**
 * Hms field
 */
function hms_field_object( $key = 0, $field = array(), $form_id = null ) {
	$name_label            = 'field[' . $key . '][label]';
	$name_description      = 'field[' . $key . '][description]';
	$name_required         = 'field[' . $key . '][required]';
	$name_dependency       = 'field[' . $key . '][dependency]';
	$name_rule_field       = 'field[' . $key . '][rule_field]';
	$name_rule_value       = 'field[' . $key . '][rule_value]';
	$name_type             = 'field[' . $key . '][type]';
	$name_placeholder      = 'field[' . $key . '][placeholder]';
	$name_placeholder_text = 'field[' . $key . '][placeholder_text]';

	$value_label            = isset( $field['label'] ) ? wp_unslash( $field['label'] ) : '';
	$value_description      = isset( $field['description'] ) ? wp_unslash( $field['description'] ) : '';
	$value_type             = isset( $field['type'] ) ? wp_unslash( $field['type'] ) : '';
	$value_required         = isset( $field['required'] ) ? wp_unslash( $field['required'] ) : '';
	$value_dependency       = isset( $field['dependency'] ) ? wp_unslash( $field['dependency'] ) : '';
	$value_rule_field       = isset( $field['rule_field'] ) ? wp_unslash( $field['rule_field'] ) : '';
	$value_rule_value       = isset( $field['rule_value'] ) ? wp_unslash( $field['rule_value'] ) : '';
	$value_options          = isset( $field['options'] ) ? wp_unslash( $field['options'] ) : array();
	$value_placeholder      = isset( $field['placeholder'] ) ? wp_unslash( $field['placeholder'] ) : '';
	$value_placeholder_text = isset( $field['placeholder_text'] ) ? wp_unslash( $field['placeholder_text'] ) : '';

	if ( 'project_name' === $value_type ) {
		$name_rule_field = 'field[project_name][rule_field]';
		$name_rule_value = 'field[project_name][rule_value]';
	}

	$form_fields = (array) get_post_meta( $form_id, '_form_fields', true );

	$unset_fields = array(
		'first_name',
		'last_name',
		'email',
		'phone',
		'telegram',
		'event_privacy_agreement',
	);

	foreach ( $form_fields as $name => $field ) {
		if ( in_array( $name, $unset_fields ) ) {
			unset( $form_fields[ $name ] );
		}
	}

	$dependency_show = '';
	if ( $value_dependency == 'true' ) {
		$dependency_show = ' hms-show';
	}

	$field_conditional_option = '';
	foreach ( $form_fields as $name => $field ) {
		if ( isset( $field['label'] ) && $field['label'] && $value_label != $field['label'] ) {
			$field_conditional_option .= '<option value="' . esc_attr( $name ) . '" ' . selected( $name, $value_rule_field, false ) . '>' . esc_html( $field['label'] ) . '</option>';
		}
	}

	$field_conditional_logic = '<div class="hms-field-conditional-logic' . esc_attr( $dependency_show ) . '">
		<div class="hms-field-conditional-logic__inner">
			<label class="hms-field-label">' . esc_html__( 'Show this field if', 'hackathon' ) . '</label>
			<div class="hms-field hms-field-flex">
				<select class="hms-field-input" name="' . esc_attr( $name_rule_field ) . '" data-type="rule_field" data-selected="' . esc_attr( $value_rule_field ) . '">
					' . hms_kses( $field_conditional_option ) . '
				</select>
				<span class="hms-field-label">' . esc_html__( 'equal', 'hackathon' ) . '</span>
				<input type="text" name="' . esc_attr( $name_rule_value ) . '" data-type="rule_value" class="hms-field-input" value="' . esc_attr( $value_rule_value ) . '">
			</div>
			' . /*
	<div class="hms-field">
				<a href="#" class="hms-field-add-rule">' . esc_html__( 'Add rule +', 'hackathon' ) . '</a>
			</div> */ '
		</div>
	</div>';

	if ( $value_type === 'phone' ) {
		?>
		<div class="hms-field-object">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="field[phone][label]" data-type="label" data-name="phone" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'Label', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="field[phone][type]" data-type="type">
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select"><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone" <?php selected( true, true ); ?>><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name"><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field">
				<input type="text" name="field[phone][description]" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>
			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch">
						<input type="checkbox" name="field[phone][required]" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} elseif ( $value_type === 'telegram' ) {
		?>
		<div class="hms-field-object">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="field[telegram][label]" data-type="label" data-name="telegram" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'Telegram', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="field[telegram][type]" data-type="type">
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select"><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram" <?php selected( true, true ); ?>><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name"><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field">
				<input type="text" name="field[telegram][description]" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>
			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch">
						<input type="checkbox" name="field[telegram][required]" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} elseif ( $value_type === 'city' ) {
		?>
		<div class="hms-field-object">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="field[city][label]" data-type="label" data-name="city" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'City', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="field[city][type]" data-type="type">
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select"><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city" <?php selected( true, true ); ?>><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name"><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field">
				<input type="text" name="field[city][description]" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>
			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch">
						<input type="checkbox" name="field[city][required]" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} elseif ( $value_type === 'project_name' ) {
		?>
		<div class="hms-field-object">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="field[project_name][label]" data-type="label" data-name="project_name" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'Project Name', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="field[project_name][type]" data-type="type">
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select"><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name" <?php selected( true, true ); ?>><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field">
				<input type="text" name="field[project_name][description]" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>

			<?php echo hms_kses( $field_conditional_logic ); ?>

			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch hms-field-switch-conditional-logic">
						<input type="checkbox" name="<?php echo esc_attr( $name_dependency ); ?>" data-type="dependency" <?php checked( 'true', $value_dependency ); ?> value="true">
						<span><?php esc_html_e( 'Conditional Logic', 'hackathon' ); ?><span></span></span>
					</label>
					<label class="hms-field-switch">
						<input type="checkbox" name="field[project_name][required]" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} elseif ( $value_type === 'textarea' || $value_type === 'text' ) {
		?>
		<div class="hms-field-object hms-is-sortable">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="<?php echo esc_attr( $name_label ); ?>" data-type="label" data-name="<?php echo esc_attr( $key ); ?>" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'Label', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="<?php echo esc_attr( $name_type ); ?>" data-type="type">
					<option value="text" <?php selected( 'text', $value_type ); ?>><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea" <?php selected( 'textarea', $value_type ); ?>><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select" <?php selected( 'select', $value_type ); ?>><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone" <?php selected( 'phone', $value_type ); ?>><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram" <?php selected( 'telegram', $value_type ); ?>><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city" <?php selected( 'city', $value_type ); ?>><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name" <?php selected( 'project_name', $value_type ); ?>><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field">
				<input type="text" name="<?php echo esc_attr( $name_description ); ?>" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>

			<?php echo hms_kses( $field_conditional_logic ); ?>

			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch hms-field-switch-conditional-logic">
						<input type="checkbox" name="<?php echo esc_attr( $name_dependency ); ?>" data-type="dependency" <?php checked( 'true', $value_dependency ); ?> value="true">
						<span><?php esc_html_e( 'Conditional Logic', 'hackathon' ); ?><span></span></span>
					</label>
					<label class="hms-field-switch">
						<input type="checkbox" name="<?php echo esc_attr( $name_required ); ?>" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} elseif ( $value_type === 'select' ) {
		$placeholder_visible  = ' hms-field-hidden';
		$placeholder_disabled = true;
		if ( $value_placeholder ) {
			$placeholder_visible  = '';
			$placeholder_disabled = false;
		}
		?>
		<div class="hms-field-object hms-is-sortable">
			<div class="hms-field-drag">
				<?php hms_icon( 'drag' ); ?>
			</div>
			<div class="hms-field hms-field-flex">
				<input type="text" name="<?php echo esc_attr( $name_label ); ?>" data-type="label" data-name="<?php echo esc_attr( $key ); ?>" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>" placeholder="<?php esc_attr_e( 'Label', 'hackathon' ); ?>">
				<select class="hms-field-input hms-field-select-type" name="<?php echo esc_attr( $name_type ); ?>" data-type="type">
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select" <?php selected( true, true ); ?>><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name"><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
				</select>
			</div>
			<div class="hms-field<?php echo esc_attr( $placeholder_visible ); ?>">
				<input type="text" name="<?php echo esc_attr( $name_placeholder_text ); ?>" data-type="placeholder_text" class="hms-field-input" value="<?php echo esc_attr( $value_placeholder_text ); ?>" placeholder="<?php esc_attr_e( 'Placeholder', 'hackathon' ); ?>" <?php disabled( true, $placeholder_disabled ); ?>>
			</div>
			<div class="hms-field">
				<div class="hms-select-options">
					<?php foreach ( $value_options as $option ) { ?>
						<div class="hms-select-option">
							<div class="hms-select-drag">
								<?php hms_icon( 'drag' ); ?>
							</div>
							<input type="text" name="field[<?php echo esc_attr( $key ); ?>][options][]" data-type="options" class="hms-field-input" value="<?php echo esc_attr( $option ); ?>">
							<div class="hms-select-option-remove">
								<?php hms_icon( 'close-thin' ); ?>
							</div>
						</div>
					<?php } ?>
				</div>
				<a href="#" class="hms-select-add-option"><?php esc_html_e( 'Add option +', 'hackathon' ); ?></a>
			</div>
			<div class="hms-field">
				<input type="text" name="<?php echo esc_attr( $name_description ); ?>" data-type="description" class="hms-field-input" value="<?php echo esc_attr( $value_description ); ?>" placeholder="<?php esc_attr_e( 'Description', 'hackathon' ); ?>">
			</div>

			<?php echo hms_kses( $field_conditional_logic ); ?>

			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
				<div class="hms-field-additional-actions">
					<label class="hms-field-switch hms-field-switch-conditional-logic">
						<input type="checkbox" name="<?php echo esc_attr( $name_dependency ); ?>" data-type="dependency" <?php checked( 'true', $value_dependency ); ?> value="true">
						<span><?php esc_html_e( 'Conditional Logic', 'hackathon' ); ?><span></span></span>
					</label>
					<label class="hms-field-switch hms-field-switch-placeholder">
						<input type="checkbox" name="<?php echo esc_attr( $name_placeholder ); ?>" data-type="placeholder" <?php checked( 'true', $value_placeholder ); ?> value="true">
						<span><?php esc_html_e( 'Placeholder', 'hackathon' ); ?><span></span></span>
					</label>
					<label class="hms-field-switch">
						<input type="checkbox" name="<?php echo esc_attr( $name_required ); ?>" data-type="required" <?php checked( 'true', $value_required ); ?> value="true">
						<span><?php esc_html_e( 'Required', 'hackathon' ); ?><span></span></span>
					</label>
				</div>
			</div>
		</div>
		<?php
	} else {
		?>
		<div class="hms-field-object hms-is-sortable">
			<div class="hms-field hms-field-flex">
				<input type="hidden" name="<?php echo esc_attr( $name_label ); ?>" data-type="label" data-name="<?php echo esc_attr( $key ); ?>" class="hms-field-input" value="<?php echo esc_attr( $value_label ); ?>">
				<select class="hms-field-input hms-field-select-type" name="<?php echo esc_attr( $name_type ); ?>" data-type="type">
					<option value="" disabled selected="selected"><?php esc_html_e( 'Select field type', 'hackathon' ); ?></option>
					<option value="text"><?php esc_html_e( 'Text', 'hackathon' ); ?></option>
					<option value="textarea"><?php esc_html_e( 'Text Area', 'hackathon' ); ?></option>
					<option value="select"><?php esc_html_e( 'Select', 'hackathon' ); ?></option>
					<option value="phone"><?php esc_html_e( 'Phone', 'hackathon' ); ?></option>
					<option value="telegram"><?php esc_html_e( 'Telegram', 'hackathon' ); ?></option>
					<option value="city"><?php esc_html_e( 'City', 'hackathon' ); ?></option>
					<option value="project_name"><?php esc_html_e( 'Project Name', 'hackathon' ); ?></option>
					<?php
					/*
					<option value="checkbox"><?php esc_html_e( 'Checkbox', 'hackathon' ); ?></option>
					<option value="file" <?php selected( 'file', $value_type ); ?>><?php esc_html_e( 'File', 'hackathon' ); ?></option>  */
					?>
				</select>
			</div>

			<div class="hms-field-footer">
				<div class="hms-field-actions">
					<a href="#" class="hms-field-remove"><?php hms_icon( 'delete' ); ?></a>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Hms fields
 */
function hms_fields_object( $form_id = null ) {
	if ( ! $form_id ) {
		return;
	}

	$form_fields = get_post_meta( $form_id, '_form_fields', true );

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
			hms_field_object( $key, $field, $form_id );
		}
	}
}

/**
 * Get all forms slugs
 */
function hms_get_forms_slugs() {

	$slugs = array();

	$forms_args = array(
		'post_type'      => 'hms_form',
		'posts_per_page' => -1,
		'post_status'    => 'any',
	);

	$forms_query = new WP_Query( $forms_args );

	if ( $forms_query->have_posts() ) {
		while ( $forms_query->have_posts() ) {
			$forms_query->the_post();
			$form_slug = get_post_meta( get_the_ID(), '_form_slug', true );
			$form_id   = get_the_ID();
			if ( $form_slug ) {
				$slugs[ $form_id ] = $form_slug;
			}
		}
	}
	wp_reset_postdata();

	return $slugs;
}

/**
 * Hms fron field
 */
function hms_front_field( $key = 0, $field = array() ) {
	$type             = isset( $field['type'] ) ? $field['type'] : 'text';
	$label            = isset( $field['label'] ) ? $field['label'] : '';
	$description      = isset( $field['description'] ) ? $field['description'] : '';
	$required         = isset( $field['required'] ) ? 'required="required"' : '';
	$options          = isset( $field['options'] ) ? wp_unslash( $field['options'] ) : array();
	$placeholder      = isset( $field['placeholder'] ) ? $field['placeholder'] : false;
	$placeholder_text = isset( $field['placeholder_text'] ) ? $field['placeholder_text'] : false;
	$dependency       = isset( $field['dependency'] ) ? $field['dependency'] : false;
	$rule_field       = isset( $field['rule_field'] ) ? $field['rule_field'] : '';
	$rule_value       = isset( $field['rule_value'] ) ? $field['rule_value'] : '';

	$form_row_class = 'hms-form-row';
	$disabled       = false;

	if ( $dependency && $rule_value ) {
		$disabled        = true;
		$form_row_class .= ' hidden';
	}

	$required_star = '';
	if ( $required ) {
		$required_star = '<span class="hms-error-color">*</span>';
	}

	if ( ! $label ) {
		return;
	}

	if ( in_array( $type, array( 'text', 'project_name' ) ) ) {
		?>
		<div class="<?php echo esc_attr( $form_row_class ); ?>">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label><input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="input" <?php echo wp_kses_post( $required ); ?> <?php disabled( true, $disabled ); ?>>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	} elseif ( $type === 'textarea' ) {
		?>
		<div class="<?php echo esc_attr( $form_row_class ); ?>">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label>
			<textarea name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="input" <?php echo wp_kses_post( $required ); ?> rows="5" <?php disabled( true, $disabled ); ?>></textarea>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	} elseif ( $type === 'phone' ) {
		?>
		<div class="hms-form-row">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label><input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="input" <?php echo wp_kses_post( $required ); ?>>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	} elseif ( $type === 'telegram' ) {
		?>
		<div class="hms-form-row">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label><input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="input" <?php echo wp_kses_post( $required ); ?>>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	} elseif ( $type === 'city' ) {
		?>
		<div class="hms-form-row">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label><input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="input" <?php echo wp_kses_post( $required ); ?>>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	} elseif ( $type === 'select' ) {
		?>
		<div class="<?php echo esc_attr( $form_row_class ); ?>">
			<label class="hms-form-label" for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?> <?php echo wp_kses_post( $required_star ); ?></label>
			<select type="select" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" class="select" <?php echo wp_kses_post( $required ); ?> <?php disabled( true, $disabled ); ?>>
				<?php if ( $placeholder ) { ?>
					<option value=""><?php echo esc_html( $placeholder_text ); ?></option>
				<?php } ?>
				<?php foreach ( $options as $option ) { ?>
					<option value="<?php echo esc_attr( $option ); ?>"><?php echo esc_html( $option ); ?></option>
				<?php } ?>
			</select>
			<?php if ( $description ) { ?>
				<div class="hms-form-text"><?php echo esc_html( $description ); ?></div>
			<?php } ?>
		</div>
		<?php
	}}

/**
 * Ajax request actions
 */
if ( wp_doing_ajax() ) {
	require_once HMS_PATH . 'inc/ajax/form-actions.php';
}
