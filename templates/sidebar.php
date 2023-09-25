<?php
/**
 * Sidebar
 */

$page_slug    = get_query_var( 'hms_subpage' );
$subpage_slug = get_query_var( 'hms_subsubpage' );
$user_id      = get_query_var( 'current_user_id' );

?>

<div class="hms-sidebar">
	<div class="hms-sidebar-inner">

		<div class="hms-sidebar-profile">
			<div class="hms-sidebar-avatar">
				<?php hms_avatar(); ?>
			</div>
			<h3 class="hms-profile-name"><?php echo esc_html( get_user_option( 'user_login', $user_id ) ); ?></h3>
			<div class="hms-profile-role"><?php echo esc_html( hms_get_user_role_name( $user_id ) ); ?></div>
		</div>

		<ul class="hms-menu">
			<?php foreach( hms_pages_content() as $slug => $item ) {

				if ( isset( $item['caps'] ) && ! in_array( hms_get_current_user_role(), $item['caps'] ) ) {
					continue;
				}

				$url = hms_get_url( $slug );
				if ( isset( $item['url'] ) ) {
					$url = $item['url'];
				}

				if ( hms_dashboard_slug() === $slug ) {
					$slug = '';
				}

				$children = isset( $item['children'] ) && is_array( $item['children'] ) && $item['children'] ? $item['children'] : array();
				$has_children = false;
				if ( $children ) {
					foreach( $children as $childslug => $child ) {
						if ( ! isset( $child['caps'] ) ) {
							$has_children = true;
							break;
						} else if ( in_array( hms_get_current_user_role(), $child['caps'] ) ) {
							$has_children = true;
							break;
						}
					}
				}

				$item_class = 'menu-top';
				if ( $has_children ){
					$item_class .= ' wp-has-submenu wp-menu-open';

					$has_current_submenu = false;
					if ( $slug === $page_slug ) {
						$item_class .= ' wp-has-current-submenu';
						$has_current_submenu = true;
					} else {
						foreach( $item['children'] as $childslug => $child ) {
							if ( $childslug === $page_slug ) {
								$item_class .= ' wp-has-current-submenu';
								$has_current_submenu = true;
							}
						}
					}
					if ( ! $has_current_submenu ) {
						$item_class .= ' wp-not-current-submenu';
					}

				} else {
					if ( $slug === $page_slug || array_key_exists( $page_slug, $children ) ) {
						$item_class .= ' current';
					}
					if ( $page_slug === 'edit-user' && 'user' === $slug ) {
						$item_class .= ' current';
					}

					if ( 'messages' === $slug ) {
						if ( 'message' === $page_slug || 'new-message' === $page_slug || 'edit-message' === $page_slug ) {
							$item_class .= ' current';
						}
					}

				}

				?>
				<li class="<?php echo esc_attr( $item_class ); ?>">
					<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( $item_class ); ?>">
						<div class="hms-menu-icon"><?php hms_icon( $item['icon'] ); ?></div>
						<div class="hms-menu-name"><?php echo esc_html( $item['menu_title'] ); ?></div>
						<?php if ( 'requests' === $slug && (int) hms_get_requests_count( 'received' ) > 0 ) { ?>
							<span class="hms-menu-badge"><?php echo esc_html( hms_get_requests_count( 'received' ) ); ?></span>
						<?php } ?>
					</a>
				</li>
				<?php if ( isset( $item['separator'] ) && $item['separator'] ) { ?>
					<li class="wp-not-current-submenu wp-menu-separator">
						<div class="separator"></div>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>

		<div class="hms-sidebar-separator"></div>

	</div><!-- .hms-sidebar-inner -->
</div><!-- .hms-sidebar -->
