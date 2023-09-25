<?php
/**
 * Requests
 */

/**
 * Register a custom post type request
 */
function hms_register_post_type_request() {
	$labels = array(
		'name'                  => __( 'Requests', 'hackathon' ),
		'singular_name'         => __( 'Request', 'hackathon' ),
		'menu_name'             => __( 'Requests', 'hackathon' ),
		'name_admin_bar'        => __( 'Request', 'hackathon' ),
		'add_new'               => __( 'Add New', 'hackathon' ),
		'add_new_item'          => __( 'Add New Request', 'hackathon' ),
		'new_item'              => __( 'New Request', 'hackathon' ),
		'edit_item'             => __( 'Edit Request', 'hackathon' ),
		'view_item'             => __( 'View Request', 'hackathon' ),
		'all_items'             => __( 'All Requests', 'hackathon' ),
		'search_items'          => __( 'Search Requests', 'hackathon' ),
		'not_found'             => __( 'No Request found.', 'hackathon' ),
		'not_found_in_trash'    => __( 'No requests found in Trash.', 'hackathon' ),
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
		'supports'           => array( 'title', 'editor', 'author' ),
		'show_in_rest'       => true,
	);

	register_post_type( 'hms_request', $args );
}

add_action( 'init', 'hms_register_post_type_request' );

/**
 * Request statuses
 */
function hms_request_statuses(){
	$statuses = array(
		'received' => array(
			'title' => __( 'Application accepted', 'hackathon' ),
			'label'  => __( 'Accepted', 'hackathon' ),
		),
		'processing'    => array(
			'title' => __( 'Application processing', 'hackathon' ),
			'label'  => __( 'Processing', 'hackathon' ),
		),
		'approved'    => array(
			'title' => __( 'Application approved', 'hackathon' ),
			'label' => __( 'Approved', 'hackathon' ),
		),
		'waitinglist'    => array(
			'title' => __( 'Application to standby', 'hackathon' ),
			'label' => __( 'In standby', 'hackathon' ),
		),
		'rejected'    => array(
			'title' => __( 'Application rejected', 'hackathon' ),
			'label' => __( 'Rejected', 'hackathon' ),
		),
		'cancelled'    => array(
			'title' => __( 'Team cancelled', 'hackathon' ),
			'label' => __( 'Cancelled', 'hackathon' ),
		),
	);

	return $statuses;
}

/**
 * Request statuses
 */
function hms_get_request_status( $slug = '' ){
	$status = '';
	$statuses = hms_request_statuses();
	if ( isset( $statuses[ $slug ] ) ) {
		$status = $statuses[ $slug ]['title'];
	}

	return $status;
}

/**
 * Ajax request actions
 */
if( wp_doing_ajax() ){
	require_once HMS_PATH . 'inc/ajax/update-request.php';
}

/**
 * Get requests
 */
function hms_get_requests( $args = array() ) {

	$default_args = array(
		'post_type'      => 'hms_request',
		'posts_per_page' => -1,
	);

	$parsed_args = wp_parse_args( $args, $default_args );

	$requests = get_posts( $parsed_args );
	return $requests;
}

/**
 * Get requests count
 */
function hms_get_requests_count( $status = '' ) {
	$args = array();
	if ( $status ) {
		$args = array(
			'meta_key'       => 'status',
			'meta_value'     => $status,
		);
	}
	$requests = hms_get_requests( $args );
	return count( $requests );
}

/**
 * Requests Menu
 */
function hms_requests_menu(){
	$page    = get_query_var( 'hms_subpage' );
	$subpage = get_query_var( 'hms_subsubpage' );

	$statuses = hms_request_statuses();
	$all      = array(
		'label' => __( 'All', 'hackathon' ),
		'slug' => '',
	);

	array_unshift( $statuses, $all );

	?>
	<ul class="hms-submenu">
		<?php
		$count = count( $statuses );
		$i = 0;
		foreach ( $statuses as $status_slug => $status ) {
			$i++;
			$item_class = 'hms-submenu-item';
			$slug       = 'requests';
			if ( $status_slug ) {
				$slug .= '/' . $status_slug;
			}
			if ( $subpage === $status_slug || ( 0 === $status_slug && ! $subpage ) ) {
				$item_class .= ' active';
			}
			$name = $status['label'];
			?>
			<li class="<?php echo esc_attr( $item_class ); ?>">
				<a href="<?php hms_url( $slug ); ?>">
					<?php if ( $i > 1 ) {?>
						<span class="hms-card-status-icon hms-card-status-<?php echo esc_attr( $status_slug );?>"></span>
					<?php } ?>
					<span class="hms-card-status-label"><?php echo esc_html( $name ); ?></span>
					<span class="count">(<?php echo esc_html( hms_get_requests_count( $status_slug ) ); ?>)</span></a>
			</li>
		<?php } ?>

	</ul>
	<?php
}

/**
 * Requests Filter
 */
function hms_requests_filter(){

	$order   = isset( $_GET['order'] ) ? $_GET['order'] : 'desc';
	$orderby = isset( $_GET['orderby'] ) ? $_GET['orderby'] : '';
	$search  = isset( $_GET['search'] ) ? $_GET['search'] : '';
	//$status  = isset( $_GET['status'] ) ? $_GET['status'] : '';
	//$team    = isset( $_GET['team'] ) ? $_GET['team'] : '';
	// $filter_active = '';
	// if ( $status || $team ) {
	// 	$filter_active = ' active';
	// }
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

	</form>
	<?php

}

/**
 * Requests Card
 */
function hms_card_requests( $args = array() ) {

	$default_args = array(
		'post_type'      => 'hms_request',
		'posts_per_page' => -1,
		'echo'           => 1,
	);

	$parsed_args = wp_parse_args( $args, $default_args );

	$query = new WP_Query( $parsed_args );

	$output = '';

	if ( $query->have_posts() ) {
		$output .= '<div class="hms-cards hms-cards-requests">';
		while ( $query->have_posts() ) {
			$query->the_post();

			$name         = get_post_meta( get_the_ID(), 'first_name', true ) . ' ' . get_post_meta( get_the_ID(), 'last_name', true );
			$author_id    = get_post_field( 'post_author', get_the_ID() );
			$this_status  = get_post_meta( get_the_ID(), 'status', true );
			$status_class = $this_status;

			$card_info_html = '';

			if ( hms_is_administrator() ) {

				$status_dropdown = '';
				foreach ( hms_request_statuses() as $slug => $status ) {
					$status_dropdown .= '<div class="hms-card-status" data-request-id="' . get_the_ID() . '" data-request-status="' . esc_attr( $slug ) . '">
						<span class="hms-card-status-icon hms-card-status-' . esc_attr( $slug ) . '"></span>
						<div class="hms-card-label">
							' . esc_html( $status['title'] ) . '
						</div>
					</div>';
				}

				$card_info_html = '<div class="hms-card-info">
				<div class="hms-card-status-dropdown">
						<div class="hms-card-status">
							<span class="hms-card-status-icon hms-card-status-' . esc_attr( $this_status ) . '"></span>
							<div class="hms-card-label">
								' . hms_get_request_status( $this_status ) . '
							</div>
							<span class="hms-card-status-toggle">
								' . hms_get_icon('down') . '
							</span>
						</div>
						<div class="hms-card-status-popover">
							<div class="hms-card-status-menu">
								' . $status_dropdown . '
							</div>
						</div>
					</div>
				</div>';
			} else {
				$card_info_html = '<div class="hms-card-info">
					<div class="hms-card-status">
						<span class="hms-card-status-icon hms-card-status-' . esc_attr( $status_class ) . '"></span>
						<div class="hms-card-label">
							' . hms_get_request_status( $this_status ) . '
						</div>
					</div>
				</div>';
			}

			$bottom_line = '';
			/* Posible bottom line code:
			$bottom_line = '<div class="hms-card-line hms-card-line-normal">
				<div class="hms-card-line-item">
					<div class="hms-card-label">...</div>
				</div>
			</div>';
			*/

			$output .= '<div class="hms-card status-' . esc_attr( $status_class ) . '">';

				$output .= '<div class="hms-card-content">
					<div class="hms-card-line hms-card-line-small">
						<div class="hms-card-line-item">
							<div class="hms-card-label">
								' . esc_html( get_the_date( 'F j Y H:i') ) . '
							</div>
						</div>
						<div class="hms-card-line-item">
							<div class="hms-card-label">
								' . esc_html( hms_get_role_name( get_post_meta( get_the_ID(), 'role', true ) ) ) . '
							</div>
						</div>
						<div class="hms-card-line-item">
							<div class="hms-card-value">
								' . make_clickable( esc_html( hms_get_user_email( $author_id ) ) ) . '
							</div>
						</div>
					</div>

					<h4 class="hms-card-title">
						<a href="' . esc_url( hms_get_url( 'request/' . get_the_ID() ) ) . '">' . esc_html( $name ) . '</a>
					</h4>

					' . $bottom_line . '

				</div>

				' . $card_info_html;

			$output .= '</div>';
		}
		$output .= '</div>';

	} else {
		$output .= esc_html__( 'No requests', 'hackathon' );
	}
	wp_reset_postdata();

	$html = apply_filters( 'hms_card_requests', $output, $parsed_args, $query );

	if ( $parsed_args['echo'] ) {
		echo hms_kses( $html );
	} else {
		return $html;
	}

}
