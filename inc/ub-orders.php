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
