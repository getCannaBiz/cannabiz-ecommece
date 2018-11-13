<?php
// Register Custom Post Type
function wpd_orders() {

	$labels = array(
		'name'                  => _x( 'Orders', 'Post Type General Name', 'wpd-ecommerce' ),
		'singular_name'         => _x( 'Order', 'Post Type Singular Name', 'wpd-ecommerce' ),
		'menu_name'             => __( 'Orders', 'wpd-ecommerce' ),
		'name_admin_bar'        => __( 'Order', 'wpd-ecommerce' ),
		'archives'              => __( 'Order Archives', 'wpd-ecommerce' ),
		'attributes'            => __( 'Order Attributes', 'wpd-ecommerce' ),
		'parent_item_colon'     => __( 'Parent Order:', 'wpd-ecommerce' ),
		'all_items'             => __( 'All Orders', 'wpd-ecommerce' ),
		'add_new_item'          => __( 'Add New Order', 'wpd-ecommerce' ),
		'add_new'               => __( 'Add New', 'wpd-ecommerce' ),
		'new_item'              => __( 'New Order', 'wpd-ecommerce' ),
		'edit_item'             => __( 'Edit Order', 'wpd-ecommerce' ),
		'update_item'           => __( 'Update Order', 'wpd-ecommerce' ),
		'view_item'             => __( 'View Order', 'wpd-ecommerce' ),
		'view_items'            => __( 'View Orders', 'wpd-ecommerce' ),
		'search_items'          => __( 'Search Order', 'wpd-ecommerce' ),
		'not_found'             => __( 'Not found', 'wpd-ecommerce' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'wpd-ecommerce' ),
		'featured_image'        => __( 'Featured Image', 'wpd-ecommerce' ),
		'set_featured_image'    => __( 'Set featured image', 'wpd-ecommerce' ),
		'remove_featured_image' => __( 'Remove featured image', 'wpd-ecommerce' ),
		'use_featured_image'    => __( 'Use as featured image', 'wpd-ecommerce' ),
		'insert_into_item'      => __( 'Insert into order', 'wpd-ecommerce' ),
		'uploaded_to_this_item' => __( 'Uploaded to this order', 'wpd-ecommerce' ),
		'items_list'            => __( 'Orders list', 'wpd-ecommerce' ),
		'items_list_navigation' => __( 'Orders list navigation', 'wpd-ecommerce' ),
		'filter_items_list'     => __( 'Filter orders list', 'wpd-ecommerce' ),
	);
	$rewrite = array(
		'slug'       => 'order',
		'with_front' => true,
		'pages'      => true,
		'feeds'      => true,
	);
	$capabilities = array(
		'edit_post'          => 'edit_post',
		'read_post'          => 'read_post',
		'delete_post'        => 'delete_post',
		'edit_posts'         => 'edit_posts',
		'edit_others_posts'  => 'edit_others_posts',
		'publish_posts'      => 'publish_posts',
		'read_private_posts' => 'read_private_posts',
	);
	$args = array(
		'label'               => __( 'Order', 'wpd-ecommerce' ),
		'description'         => __( 'View your store\'s order history', 'wpd-ecommerce' ),
		'labels'              => $labels,
		'supports'            => false,
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'menu_icon'           => 'dashicons-cart',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'orders',
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capabilities'        => $capabilities,
		'show_in_rest'        => true,
	);
	register_post_type( 'wpd_orders', $args );

}
add_action( 'init', 'wpd_orders', 0 );

// Adds Orders admin submenu link.
function wpd_admin_menu_orders() {
	add_submenu_page( 'wpd-settings', 'WP Dispensary\'s eCommerce orders', 'Orders', 'manage_options', 'edit.php?post_type=wpd_orders', null );
}
add_action( 'admin_menu', 'wpd_admin_menu_orders', 10 );

/**
 * Disable Quick Edit links for Orders admin page.
 */
function wpd_ecommerce_disable_quick_edit( $actions = array(), $post = null ) {

    // Abort if the post type is not "Orders"
    if ( ! is_post_type_archive( 'wpd_orders' ) ) {
        return $actions;
    }

    // Remove the Quick Edit link
    if ( isset( $actions['inline hide-if-no-js'] ) ) {
        unset( $actions['inline hide-if-no-js'] );
    }

    // Return the set of links without Quick Edit
    return $actions;

}
add_filter( 'post_row_actions', 'wpd_ecommerce_disable_quick_edit', 10, 2 );
add_filter( 'page_row_actions', 'wpd_ecommerce_disable_quick_edit', 10, 2 );
