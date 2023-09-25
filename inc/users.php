<?php
/**
 * Users
 */

/**
 * Get user
 */
function hms_get_userdata( $user_id = null ){
	return get_userdata( $user_id );
}

/**
 * Get user email
 */
function hms_get_user_email( $user_id = null ){
	$user_data = hms_get_userdata( $user_id );
	if ( ! $user_data ) {
		return false;
	}
	return $user_data->user_email;
}

/**
 * Get user login
 */
function hms_get_user_login( $user_id = null ){
	$user_data = hms_get_userdata( $user_id );
	if ( ! $user_data ) {
		return false;
	}
	return $user_data->user_login;
}

/**
 * Get user role
 */
function hms_get_user_role( $user_id = null ) {
	if ( $user_id ) {
		$user_meta  = get_userdata( $user_id );
		if ( $user_meta ) {
			if ( $user_meta->roles ) {
				$user_roles = $user_meta->roles;
				$user_role   = $user_roles[0];
			} else {
				return false;
			}
		} else {
			return false;
		}
	} else {
		$current_user = wp_get_current_user();
		$user_roles   = $current_user->roles;
		$user_role    = array_shift( $user_roles );
	}
	return $user_role;
}

/**
 * Get current user role
 */
function hms_get_current_user_role() {
	$user_id   = get_current_user_id();
	$user_role = hms_get_user_role( $user_id );
	return $user_role;
}

/**
 * Get role name
 */
function hms_get_role_name( $role = '' ) {
	if ( ! $role ) {
		return false;
	}
	$roles     = wp_roles()->roles;
	$role_name = null;
	if ( isset( $roles[ $role ] ) ) {
		$role_name = $roles[ $role ]['name'];
	}

	$role_name = translate( translate_user_role( $role_name ), 'hackathon' );
	return $role_name;
}

/**
 * Get user role name
 */
function hms_get_user_role_name( $user_id = null ) {
	$user_role = hms_get_user_role( $user_id );
	if ( ! $user_role ) {
		return false;
	}
	$role_name = translate( hms_get_role_name( $user_role ), 'hackathon' );
	return $role_name;
}

/**
 * Get user name
 */
function hms_get_user_name( $user_id = null ) {
	$user_info = get_userdata( $user_id );
	return $user_info->display_name;
}

/**
 * Is user admin role
 */
function hms_is_administrator( $user_id = '' ) {
	$role = hms_get_user_role( $user_id );
	if ( 'administrator' === $role ) {
		return true;
	}
	return false;
}

/**
 * Is user mentor role
 */
function hms_is_mentor( $user_id = '' ) {
	$role = hms_get_user_role( $user_id );
	if ( 'hackathon_mentor' === $role ) {
		return true;
	}
	return false;
}

/**
 * Is user participant role
 */
function hms_is_participant( $user_id = '' ) {
	$role = hms_get_user_role( $user_id );
	if ( 'hackathon_participant' === $role ) {
		return true;
	}
	return false;
}

/**
 * Is user jury role
 */
function hms_is_jury( $user_id = '' ) {
	$role = hms_get_user_role( $user_id );
	if ( 'hackathon_jury' === $role ) {
		return true;
	}
	return false;
}

/**
 * Check if user exists by ID
 */
function hms_user_exists( $user_id = null ) {
	$user_exists = get_user_by( 'ID', $user_id );
	if ( $user_exists ) {
		return true;
	}
	return false;
}

/**
 * Check if user approved
 */
function hms_is_user_approved( $user_id = '' ){
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	if ( hms_is_administrator( $user_id ) ) {
		return true;
	}

	if ( hms_is_mentor( $user_id ) ) {
		return true;
	}

	if ( hms_is_jury( $user_id ) ) {
		return true;
	}

	$args = array(
		'post_type'      => 'hms_request',
		'author'         =>  $user_id,
		'posts_per_page' => 1
	);
	$request = get_posts( $args );

	if ( ! $request ) {
		return false;
	}

	$request_id = $request[0]->ID;

	$status = get_post_meta( $request_id, 'status', true );
	if ( 'approved' === $status ){
		return true;
	};

	return false;
}

/**
 * Get user status
 */
function hms_get_user_status( $user_id = '' ){

	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}

	$status = 'received';

	$args = array(
		'post_type'      => 'hms_request',
		'author'         =>  $user_id,
		'posts_per_page' => 1
	);
	$request = get_posts( $args );

	if ( ! $request ) {
		return $status;
	}

	$request_id = $request[0]->ID;

	$status = get_post_meta( $request_id, 'status', true );

	return $status;
}

/**
 * Get user status
 */
function hms_get_user_request_id( $user_id = '' ){

	if ( ! $user_id ) {
		return false;
	}

	$args = array(
		'post_type'      => 'hms_request',
		'author'         =>  $user_id,
		'posts_per_page' => 1
	);
	$request = get_posts( $args );

	if ( ! $request ) {
		return false;
	}

	$request_id = $request[0]->ID;

	return $request_id;
}

/**
 * Get user teams
 */
function hms_get_user_teams( $user_id = null, $fields = 'all' ) {
	$teams_args = array(
		'post_type'   => 'hms_team',
		'fields'      => $fields,
		'meta_query'  => array(
			array(
				'key'     => '_team_users',
				'value'   => $user_id,
				'compare' => 'IN',
			),
		),
	);

	$teams = get_posts( $teams_args );

	return $teams;
}

/**
 * Get users
 * 
 * $args = array( 'role' => 'hackathon_mentor' ) // 'hackathon_participant'
 */
function hms_get_users( $args = array(), $return = 'all' ) {
	$defaults = array(
		'role__in' => array( 'hackathon_mentor', 'hackathon_participant', 'hackathon_jury'),
		'orderby'  => 'registered',
		'order'    => 'DESC',
	);

	if( isset($args['search'] ) ) {
		$args['search'] = '*' . $args['search'] . '*';
		$args['search_columns'] = array( 'display_name', 'user_email', 'user_nicename', 'user_login', 'ID' );
	}

	$parsed_args = wp_parse_args( $args, $defaults );

	$users = get_users( $parsed_args );
	if ( 'email' === $return ) {
		$emails = array();
		foreach ( $users as $user ) {
			$emails[] = $user->user_email;
		}
		$users = $emails;
	}
	return $users;
}

/**
 * Get users email
 * 
 * $args = array( 'role' => 'hackathon_mentor' ) // 'hackathon_participant'
 */
function hms_get_users_email( $role = '' ) {
	$emails = hms_get_users( array( 'role' => $role ), 'email' );

	return $emails;
}

/**
 * Get mentors email
 */
function hms_get_mentors_email() {
	$emails = hms_get_users_email( 'hackathon_mentor' );

	return $emails;
}

/**
 * Get participants email
 */
function hms_get_participants_email() {
	$emails = hms_get_users_email( 'hackathon_participant' );

	return $emails;
}

/**
 * Get jury email
 */
function hms_get_jury_email() {
	$emails = hms_get_users_email( 'hackathon_jury' );

	return $emails;
}

/**
 * Get administrators email
 */
function hms_get_administrators_email() {
	$emails = hms_get_users_email( 'administrator' );

	return $emails;
}

/**
 * Get users
 */
function hms_get_users_count( $role = '' ) {
	$args = array();
	if ( $role ) {
		$args = array(
			'role__in' => 'hackathon_' . $role,
		);
	}
	$count = hms_get_users( $args );
	return count( $count );
}

/**
 * Get user gravatar
 */
function hms_get_user_gravatar( $user_id = null, $size = 200 ) {
	$gravatar = get_avatar( $user_id, $size, '', '', array( 'class' => 'hackathon-avatar' ) );
	if ( get_user_option( 'custom_avatar', $user_id ) ) {
		$gravatar = '<img src="' . wp_get_attachment_image_url( get_user_option( 'custom_avatar', $user_id ), array( $size, $size ) ) . '" class="hackathon-avatar hackathon-avatar-custom" alt="">';
	}
	return $gravatar;
}

/**
 * Display user gravatar
 */
function hms_user_gravatar( $user_id = null, $size = 200 ) {
	echo wp_kses_post( hms_get_user_gravatar( $user_id, $size ) );
}

/**
 * Get user avatar
 */
function hms_get_avatar( $size = 200 ){
	$user_id       = get_query_var( 'current_user_id' );
	$custom_avatar = get_user_option( 'custom_avatar', $user_id );

	if ( $custom_avatar && wp_get_attachment_url( $custom_avatar ) ) {
		$avatar_url = wp_get_attachment_url( $custom_avatar, array( $size, $size ) );
	} else {
		$avatar_url = get_avatar_url( $user_id, array( 'size' => $size ) );
	}
	return '<img src="' . esc_url( $avatar_url ) . '" class="hms-avatar-image" alt="">';
}

/**
 * Display user avatar
 */
function hms_avatar( $size = 200 ){
	$avatar = hms_get_avatar( $size );
	echo wp_kses( $avatar, 'content' );
}

/**
 * User ajax events
 */
if( wp_doing_ajax() ){

	// Register user.
	require_once HMS_PATH . 'inc/ajax/register-user.php';

	// Update user.
	require_once HMS_PATH . 'inc/ajax/update-user.php';

	// Delete user.
	require_once HMS_PATH . 'inc/ajax/delete-user.php';

	// Login user.
	require_once HMS_PATH . 'inc/ajax/login-user.php';

	// Lostpassword user.
	require_once HMS_PATH . 'inc/ajax/lostpassword-user.php';

}

/**
 * On activate plugin
 */
function hms_add_roles() {

	add_role(
		'hackathon_mentor',
		'Mentor',
		array(
			'hackathon'    => true,
			'mentor'       => true,
			'upload_files' => true,
		)
	);

	add_role(
		'hackathon_participant',
		'Participant',
		array(
			'hackathon'    => true,
			'participant'  => true,
			'upload_files' => true,
		)
	);

	add_role(
		'hackathon_jury',
		'Jury',
		array(
			'hackathon' => true,
			'jury'      => true,
		)
	);

	$administrator = get_role( 'administrator' );
	$administrator->add_cap( 'hackathon' );
	$administrator->add_cap( 'manage_hackathon' );

	hms_add_rewrite_rules();

	flush_rewrite_rules();

}
register_activation_hook( HMS_FILE, 'hms_add_roles' );

/**
 * On deactivate plugin
 */
function hms_remove_roles(){
	remove_role( 'hackathon_mentor' );
	remove_role( 'hackathon_participant' );
	remove_role( 'hackathon_jury' );
}
register_deactivation_hook( HMS_FILE, 'hms_remove_roles' );

/**
 * Add custom fields to login form
 */
function hms_login_form_top( $form_top, $args ){
	if( isset( $args['hackathon_fields'] ) && $args['hackathon_fields'] ) {
		$additional_fields = "\n" . '<input type="hidden" name="action" value="hackathon_login">';
		$additional_fields .= "\n" . wp_nonce_field( 'hackathon-nonce', 'nonce', false, false );
		$additional_fields .= "\n" . $form_top;
		return $additional_fields;
	}
	return $form_top;
}
add_filter( 'login_form_top', 'hms_login_form_top', 10, 2 );

/**
 * Redirect after login
 */
function hms_wp_login( $user_login, $user ){
	if ( ! isset( $_REQUEST['action'] ) ) {
		if ( ! user_can( $user, 'manage_options') && user_can( $user, 'hackathon' ) ) {
			wp_redirect( hms_get_url() );
			exit;
		}
	}
}
add_action( 'wp_login', 'hms_wp_login', 10, 2 );

/**
 * Remove admin bar
 */
function hms_remove_admin_bar() {
	if ( ! current_user_can( 'manage_options') && current_user_can( 'hackathon' ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'hms_remove_admin_bar' );

/**
 * Disable admin bar
 */
function hms_disable_admin_bar() {
	if ( is_user_logged_in() && hms_is_page() ) {
		add_filter('show_admin_bar', '__return_false');
	}
}
add_action( 'template_redirect', 'hms_disable_admin_bar' );

/**
 * Restrict Media Library Access to User’s Own Uploads in WordPress
 */
function hms_ajax_query_attachments_args( $query ) {
	$user_id = get_current_user_id();
	if ( 'hackathon_participant' === hms_get_user_role() ) {
		$query['author'] = $user_id;
	}
	return $query;
}
add_filter( 'ajax_query_attachments_args', 'hms_ajax_query_attachments_args' );

/**
 * Users List
 */
function hms_list_users( $args = array(), $status = true ) {
	$defaults = array(
		'echo' => 1,
	);

	$parsed_args = wp_parse_args( (array)$args, $defaults );

	$users = hms_get_users( $parsed_args );

	$output = '';

	if ( $users ) {
		$output .= '<div class="hms-list">';
		foreach ( $users as $user ) {

			$team_html = '<div class="hms-list-line-item">
				<div class="hms-list-label">
				' . esc_html__( 'No team', 'hackathon' ) . '
				</div>
			</div>';

			$star       = '';
			$user_teams  = hms_get_user_teams( $user->ID );
			$user_status = hms_get_user_status( $user->ID );

			if ( $user_teams ) {
				$team = $user_teams[0];
				if ( $user->ID === hms_get_team_captain( $team->ID ) ) {
					$star = '<img src="' . esc_url( HMS_URL ) . 'assets/images/star.svg" alt="' . esc_attr__( 'Captain', 'hackathon' ) . '" title="' . esc_attr__( 'Captain', 'hackathon' ) . '">';
				}
				$team_html = '<div class="hms-list-line-item">
					<div class="hms-list-label">
						' . esc_html__( 'Team', 'hackathon' ) . ':
					</div>
					<div class="hms-list-value">
						<a href="' . esc_url( hms_get_url( 'team/' . $team->ID ) ) . '">' . esc_html( $team->post_title ) . '</a>
					</div>
				</div>';
			}
			
			$status_html = '';
			if ( $status ) {
				$status_html = '<div class="hms-list-line-item">
					<div class="hms-list-value">
						<span class="hms-card-status-icon hms-card-status-' . esc_attr( $user_status ) . '"></span>
							' . hms_get_request_status( $user_status ) . '
						</div>
					</div>';
			}

			$output .= '<div class="hms-list-item">
				<a href="' . esc_url( hms_get_url( 'user/' . $user->ID . '/' ) ) . '" class="hms-list-figure"> ' . get_avatar( $user->ID, 50, '', '', array( 'class' => 'hms-list-image' ) ) . '</a>

				<div class="hms-list-content">
						<h4 class="hms-list-title">
							<a href="' . esc_url( hms_get_url( 'user/' . $user->ID . '/' ) ) . '">' . esc_html( $user->display_name ) . '</a>
							' . $star . '
						</h4>

						<div class="hms-list-line">
							' . $team_html . '
							<div class="hms-list-line-item">
								<div class="hms-list-label">
									' . esc_html__( 'Role', 'hackathon' ) . ':
								</div>
								<div class="hms-list-value">
									' . esc_html( hms_get_user_role_name( $user->ID ) ) . '
								</div>
							</div>
							' . $status_html . '
						</div>


					</div>
				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No members', 'hackathon' );
	}

	$html = apply_filters( 'hms_list_users', $output, $parsed_args, $users );

	if ( $parsed_args['echo'] ) {
		echo wp_kses_post( $html );
	} else {
		return $html;
	}

}

/**
 * Users Card
 */
function hms_card_users( $args = array() ) {
	$defaults = array(
		'echo' => 1,
	);

	$page = get_query_var( 'hms_subpage' );

	$parsed_args = wp_parse_args( (array)$args, $defaults );

	$users = hms_get_users( $parsed_args );

	$exclude = array();

	if ( array_key_exists( 'status', $parsed_args ) && $parsed_args['status'] ) {

		foreach ( $users as $user ) {
			if ( $parsed_args['status'] !== hms_get_user_status( $user->ID ) ) {
				$exclude[] = $user->ID;
			}
		}
	}

	if ( array_key_exists( 'team', $parsed_args ) && $parsed_args['team'] ) {
		foreach ( $users as $user ) {
			if ( 'true' === $parsed_args['team'] ) {
				if ( ! hms_get_user_teams( $user->ID ) ) {
					$exclude[] = $user->ID;
				}
			}
			if ( 'false' === $parsed_args['team'] ) {
				if ( hms_get_user_teams( $user->ID ) ) {
					$exclude[] = $user->ID;
				}
			}
		}
	}

	if ( $exclude ) {
		$parsed_args['exclude'] = $exclude;
		$users = hms_get_users( $parsed_args );
	}

	$output = '';

	if ( $users ) {
		$output .= '<div class="hms-cards">';
		foreach ( $users as $user ) {

			$user_id     = $user->ID;
			$user_status = hms_get_user_status( $user_id );
			$star        = '';
			$team_html   = '';
			

			if ( ! hms_is_jury( $user->ID ) ) {
				$team_html .= '<div class="hms-card-line-item">
					<div class="hms-card-label">
					' . esc_html__( 'No team', 'hackathon' ) . '
					</div>
				</div>';

				$user_teams = hms_get_user_teams( $user->ID );
				if ( $user_teams ) {
					$team = $user_teams[0];
					if ( $user->ID === hms_get_team_captain( $team->ID ) ) {
						$star = '<img src="' . esc_url( HMS_URL ) . 'assets/images/star.svg" alt="' . esc_attr__( 'Captain', 'hackathon' ) . '" title="' . esc_attr__( 'Captain', 'hackathon' ) . '">';
					}
					$team_count_html = '';
					if ( hms_is_mentor( $user->ID ) ) {
						$team_count_html = '&nbsp;&nbsp;(' . count( $user_teams ) . ')';
					}
					$team_html = '<div class="hms-card-line-item">
						<div class="hms-card-value">
							<a href="' . esc_url( hms_get_url( 'team/' . $team->ID ) ) . '">' . esc_html( $team->post_title ) . '</a>' . $team_count_html . '
						</div>
					</div>';
				}
			}

			$actions = '';

			if ( hms_is_administrator() || hms_is_mentor() ) {

				$action_edit    = '<a href="' . esc_url( hms_get_url( 'edit-user/' . $user_id ) ) . '">' . esc_html__( 'Edit', 'hackathon' ) . '</a>';
				$action_request = '<a href="' . esc_url( hms_get_user_request_url( $user_id ) ) . '">' . esc_html__( 'View request', 'hackathon' ) . '</a>';
				$action_login   = '<a href="' . esc_url( hms_get_url( '', array( 'login_as' => $user_id ) ) ) . '">' . esc_html__( 'Login as', 'hackathon' ) . '</a>';
				$action_delete  = '<a href="#" data-user-action="delete" data-user-id="' . esc_attr( $user_id ) . '">' . esc_html__( 'Delete', 'hackathon' ) . '</a>';

				if ( $user_id === get_current_user_id() || hms_is_mentor() ) {
					$action_delete = '';
				}

				if ( hms_is_administrator( $user_id ) ) {
					$action_request = '';
				}

				if ( hms_is_mentor() ) {
					$action_edit  = '<a href="' . esc_url( hms_get_url( 'user/' . $user_id ) ) . '">' . esc_html__( 'View profile', 'hackathon' ) . '</a>';
					$action_login = '';
				}

				$actions = $action_edit . $action_login . $action_request.  $action_delete;
			}

			$actions_html = '
			<div class="hms-card-actions">
				<button class="hms-card-action-toggle" type="button" title="' . esc_attr__( 'Toggle menu', 'hackathon' ) . '">
					' . hms_get_icon( 'menu-vertical' ) . '
				</button>
				<div class="hms-card-action-popover">
					<div class="hms-card-action-menu">
						' . $actions . '
					</div>
				</div>
			</div>';

			$status_html = '';
			if ( hms_is_participant( $user_id ) ) {
				$status_html = '<div class="hms-card-status">
					<span class="hms-card-status-icon hms-card-status-' . esc_attr( $user_status ) . '"></span>
					<div class="hms-card-label">
						' . hms_get_request_status( $user_status ) . '
					</div>
				</div>';
			}

			$output .= '<div class="hms-card">
				<a href="' . esc_url( hms_get_url( 'user/' . $user->ID . '/' ) ) . '" class="hms-card-figure"> ' . get_avatar( $user->ID, 50, '', '', array( 'class' => 'hms-card-image' ) ) . '</a>

					<div class="hms-card-content">
						<h4 class="hms-card-title">
							<a href="' . esc_url( hms_get_url( 'user/' . $user->ID . '/' ) ) . '">' . esc_html( $user->display_name ) . '</a>
							' . $star . '
						</h4>

						<div class="hms-card-line">
							' . $team_html . '
							<div class="hms-card-line-item">
								<div class="hms-card-label">
									' . esc_html( hms_get_user_role_name( $user->ID ) ) . '
								</div>
							</div>
							<div class="hms-card-line-item">
								<div class="hms-card-value">
									' . make_clickable( esc_html( hms_get_user_email( $user->ID ) ) ) . '
								</div>
							</div>
							' . apply_filters( 'hms_card_line_user', '', $user->ID, $page ) . '
						</div>
					</div>

					<div class="hms-card-info">
						' . $status_html . '
						' . $actions_html . '
					</div>

				</div>';
		}
		$output .= '</div>';
	} else {
		$output .= esc_html__( 'No members', 'hackathon' );
	}

	$html = apply_filters( 'hms_card_users', $output, $parsed_args, $users );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}
}

/**
 * Users menu
 */
function hms_users_menu(){
	$page    = get_query_var( 'hms_subpage' );
	$subpage = get_query_var( 'hms_subsubpage' );

	$filter_items = array(
		array(
			'name' => __( 'All', 'hackathon' ),
			'slug' => '',
		),
		array(
			'name' => __( 'Mentors', 'hackathon' ),
			'slug' => 'mentor',
		),
		array(
			'name' => __( 'Participants', 'hackathon' ),
			'slug' => 'participant',
		),
		array(
			'name' => __( 'Jury', 'hackathon' ),
			'slug' => 'jury',
		),
	);

	do_action( 'hms_before_filter_users' );

	?>
	<ul class="hms-submenu">
		<?php
		$count = count( $filter_items );
		$i = 0;
		foreach ( $filter_items as $item ) {
			$item_class = 'hms-submenu-item';
			$slug = 'users';
			if ( $item['slug'] ) {
				$slug .= '/' . $item['slug'];
			}
			if ( $subpage === $item['slug'] ) {
				$item_class .= ' active';
			}
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<a href="<?php hms_url( $slug ); ?>"><?php echo esc_html( $item['name'] ); ?> <span class="count">(<?php echo esc_html( hms_get_users_count( $item['slug'] ) ); ?>)</span></a>
			</li>
		<?php } ?>
	</ul>
	<?php

	do_action( 'hms_after_filter_users' );
}

/**
 * Users Filter
 */
function hms_users_filter(){

	$order   = isset( $_GET['order'] ) ? $_GET['order'] : 'desc';
	$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
	$search  = isset( $_GET['search'] ) ? $_GET['search'] : '';
	$status  = isset( $_GET['status'] ) ? $_GET['status'] : '';
	$team    = isset( $_GET['team'] ) ? $_GET['team'] : '';
	$filter_active = '';
	if ( $status || $team ) {
		$filter_active = ' active';
	}
	?>
	<form class="hms-filter" method="get">
		<input type="hidden" name="order" value="<?php echo esc_attr( $order ); ?>">
		<div class="hms-filter-top">
			<div class="hms-filter-item hms-filter-item-search">
				<input name="search" type="seach" class="hms-filter-field hms-filter-search" value="<?php echo esc_attr( $search );?>" placeholder="<?php esc_attr_e( 'User name or email', 'hackathon' ); ?>">
				<button class="hms-filter-search-button" type="submit">
					<?php hms_icon( 'search' );?>
				</button>
			</div>
			<div class="hms-filter-item">
				<a href="#" class="hms-filter-button">
					<?php hms_icon( 'filter' );?>
				</a>
			</div>

			<div class="hms-filter-item hms-filter-item-order">
				<a href="#" class="hms-filter-order order-<?php echo esc_attr( $order ); ?>">
					<?php hms_icon( 'down' );?>
					<?php hms_icon( 'down' );?>
				</a>
				<select name="orderby" class="hms-filter-field hms-filter-select">
					<option value="registered" <?php selected( $orderby, 'registered' ); ?>><?php esc_html_e( 'By Date', 'hackathon' ); ?></option>
					<option value="display_name" <?php selected( $orderby, 'display_name' ); ?>><?php esc_html_e( 'By Name', 'hackathon' ); ?></option>
				</select>
			</div>
		</div>

		<div class="hms-filter-bottom<?php echo esc_attr( $filter_active ); ?>">
			<div class="hms-filter-bottom-inner">
				<div class="hms-filter-item">
					<select name="status" class="hms-filter-field hms-filter-select">
						<option value=""><?php esc_html_e( 'All status types', 'hackathon' ); ?></option>
						<?php foreach( hms_request_statuses() as $value => $item ) { ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $status, $value ); ?> ><?php echo esc_html( $item['label'] ); ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="hms-filter-item">
					<select name="team" class="hms-filter-field hms-filter-select">
						<option value=""><?php esc_html_e( 'Select Team', 'hackathon' ); ?></option>
						<option value="false" <?php selected( $team, 'false' ); ?>><?php esc_html_e( 'No Team', 'hackathon' ); ?></option>
						<option value="true" <?php selected( $team, 'true' ); ?>><?php esc_html_e( 'Has Team', 'hackathon' ); ?></option>
					</select>
				</div>
			</div>
		</div>

	</form>
	<?php

}
