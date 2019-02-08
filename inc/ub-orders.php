<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action('init', 'ub_utility_manager_orders_custom_post');

function ub_utility_manager_orders_custom_post(){
	$labels = array(
			'name' => _x('Orders', 'Order name', 'ub-utility-manager'),
			'singular_name' => _x('Order', 'Order type singular name', 'ub-utility-manager'),
			'add_new' => _x('Add New', 'Order', 'ub-utility-manager'),
			'add_new_item' => __('Add New Order', 'ub-utility-manager'),
			'edit_item' => __('Edit Order', 'ub-utility-manager'),
			'new_item' => __('New Order', 'ub-utility-manager'),
			'view_item' => __('View Order', 'ub-utility-manager'),
			'search_items' => __('Search Order', 'ub-utility-manager'),
			'not_found' => __('No Order Found', 'ub-utility-manager'),
			'not_found_in_trash' => __('No Order Found in Trash', 'ub-utility-manager'),
			'parent_item_colon' => ''
	);

	register_post_type('ub_order', array('labels' => $labels,
					'public' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'publicly_queryable' => true,
					'query_var' => true,
					'exclude_from_search' => true,
					'rewrite' => array('slug' => 'order'),
					'show_in_nav_menus' => false,
					'menu_icon' => 'dashicons-cart',
					'supports' => array('title', 'author')
			)
	);

	register_post_status( 'new', array(
		'label'                     => _x( 'New', 'post status label', 'ub-utility-manager' ),
		'public'                    => true,
		'label_count'               => _n_noop( 'New <span class="count">(%s)</span>', 'New <span class="count">(%s)</span>', 'ub-utility-manager' ),
		'post_type'                 => array( 'ub_order' ), // Define one or more post types the status can be applied to.
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-yes',
	) );

	register_post_status( 'complete', array(
		'label'                     => _x( 'Complete', 'post status label', 'ub-utility-manager' ),
		'public'                    => true,
		'label_count'               => _n_noop( 'Complete <span class="count">(%s)</span>', 'Complete <span class="count">(%s)</span>', 'ub-utility-manager' ),
		'post_type'                 => array( 'ub_order' ), // Define one or more post types the status can be applied to.
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-yes',
	) );

	$labels_order_type = array(
			'name'                       => _x( 'Order Type', 'taxonomy general name' ),
			'singular_name'              => _x( 'Order Type', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Order Type' ),
			'popular_items'              => __( 'Popular Order Type' ),
			'all_items'                  => __( 'All Order Types' ),
			'parent_item'                => __( 'Parent Order Type' ),
			'parent_item_colon'          => __( 'Parent Order Type:' ),
			'edit_item'                  => __( 'Edit Order Type' ),
			'update_item'                => __( 'Update Order Type' ),
			'add_new_item'               => __( 'Add New Order Type' ),
			'new_item_name'              => __( 'New Order Type Name' ),
			'separate_items_with_commas' => __( 'Separate Order Types with commas' ),
			'add_or_remove_items'        => __( 'Add or remove Order Type' ),
			'choose_from_most_used'      => __( 'Choose from the most used Order Types' ),
			'not_found'                  => __( 'No Order Types found.' ),
			'menu_name'                  => __( 'Type' ),
	);

	$args_order_type = array(
			'hierarchical'          => true,
			'labels'                => $labels_order_type,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'order-type' ),
	);
	register_taxonomy( 'ub_order_type', 'ub_order', $args_order_type );


}

add_filter( 'enter_title_here', 'ub_order_register_enter_title' );
function ub_order_register_enter_title( $input ) {
    if ( 'ub_order' === get_post_type() ) {
        return __( 'Order Name', 'ub-utility-manager' );
    }

    return $input;
}


add_action( 'cmb2_admin_init', 'ub_order_register_custom_metabox' );
function ub_order_register_custom_metabox() {
	$prefix = '_ubo_';

	$cmb_order_details = new_cmb2_box( array(
		'id'            => $prefix . 'order_details',
		'title'         => esc_html__( 'Order Details', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_order' ), // Post type
	) );

	$cmb_order_details->add_field( array(
		'name'       => esc_html__( 'Created:', 'ub-utility-manager' ),
		'id'         => $prefix . 'created',
		'type'       => 'text',
	) );
	$cmb_order_details->add_field( array(
		'name'       => esc_html__( 'Status:', 'ub-utility-manager' ),
		'id'         => $prefix . 'status',
		'type'       => 'text',
	) );
	$cmb_order_details->add_field( array(
		'name'       => esc_html__( 'Order Created By:', 'ub-utility-manager' ),
		'id'         => $prefix . 'order_by',
		'type'       => 'text',
	) );

	$cmb_order_items = new_cmb2_box( array(
		'id'            => $prefix . 'order_items',
		'title'         => esc_html__( 'Order Items', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_order' ), // Post type
	) );



}
