<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


add_action('init', 'ub_utility_manager_property_custom_post');

function ub_utility_manager_property_custom_post(){
	$labels = array(
			'name' => _x('Properties', 'Property name', 'ub-utility-manager'),
			'singular_name' => _x('Property', 'Property type singular name', 'ub-utility-manager'),
			'add_new' => _x('Add New', 'Property', 'ub-utility-manager'),
			'add_new_item' => __('Add New Property', 'ub-utility-manager'),
			'edit_item' => __('Edit Property', 'ub-utility-manager'),
			'new_item' => __('New Property', 'ub-utility-manager'),
			'view_item' => __('View Property', 'ub-utility-manager'),
			'search_items' => __('Search Property', 'ub-utility-manager'),
			'not_found' => __('No Property Found', 'ub-utility-manager'),
			'not_found_in_trash' => __('No Property Found in Trash', 'ub-utility-manager'),
			'parent_item_colon' => ''
	);

	register_post_type('ub_property', array('labels' => $labels,
					'public' => true,
					'show_ui' => true,
					'show_in_menu' => true,
					'capability_type' => 'post',
					'hierarchical' => false,
					'publicly_queryable' => true,
					'query_var' => true,
					'exclude_from_search' => false,
					'rewrite' => array('slug' => 'property'),
					'taxonomies' => array('property_category'),
					'show_in_nav_menus' => false,
					'menu_icon' => 'dashicons-admin-home',
					'supports' => array('title', 'page-attributes')
			)
	);

	$labels_property_category = array(
			'name'                       => _x( 'Property Category', 'taxonomy general name' ),
			'singular_name'              => _x( 'Property Category', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Property Category' ),
			'popular_items'              => __( 'Popular Property Category' ),
			'all_items'                  => __( 'All Property Categories' ),
			'parent_item'                => __( 'Parent Property Category' ),
			'parent_item_colon'          => __( 'Parent Property Category:' ),
			'edit_item'                  => __( 'Edit Property Category' ),
			'update_item'                => __( 'Update Property Category' ),
			'add_new_item'               => __( 'Add New Property Category' ),
			'new_item_name'              => __( 'New Property Category Name' ),
			'separate_items_with_commas' => __( 'Separate Property Categories with commas' ),
			'add_or_remove_items'        => __( 'Add or remove Property category' ),
			'choose_from_most_used'      => __( 'Choose from the most used Property Categories' ),
			'not_found'                  => __( 'No Property Categories found.' ),
			'menu_name'                  => __( 'Category' ),
	);

	$args_property_category = array(
			'hierarchical'          => true,
			'labels'                => $labels_property_category,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'property-category' ),
	);
	register_taxonomy( 'ub_property_category', 'ub_property', $args_property_category );

}

add_filter( 'enter_title_here', 'ub_property_register_enter_title' );
function ub_property_register_enter_title( $input ) {
    if ( 'ub_property' === get_post_type() ) {
        return __( 'Owner Name', 'ub-utility-manager' );
    }

    return $input;
}


add_action( 'cmb2_admin_init', 'ub_property_register_custom_metabox' );
function ub_property_register_custom_metabox() {
	$prefix = '_ubp_';

	$cmb_demo = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Test Metabox', 'cmb2' ),
		'object_types'  => array( 'ub_property' ), // Post type
	) );

	$cmb_demo->add_field( array(
		'name'       => esc_html__( 'Test Text', 'cmb2' ),
		'desc'       => esc_html__( 'field description (optional)', 'cmb2' ),
		'id'         => $prefix . 'text',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', // function should return a bool value
		// 'sanitization_cb' => 'my_custom_sanitization', // custom sanitization callback parameter
		// 'escape_cb'       => 'my_custom_escaping',  // custom escaping callback parameter
		// 'on_front'        => false, // Optionally designate a field to wp-admin only
		// 'repeatable'      => true,
		// 'column'          => true, // Display field value in the admin post-listing columns
	) );

	$cmb_demo->add_field( array(
		'name' => esc_html__( 'Test Text Small', 'cmb2' ),
		'desc' => esc_html__( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'textsmall',
		'type' => 'text_small',
		// 'repeatable' => true,
		// 'column' => array(
		// 	'name'     => esc_html__( 'Column Title', 'cmb2' ), // Set the admin column title
		// 	'position' => 2, // Set as the second column.
		// );
		// 'display_cb' => 'yourprefix_display_text_small_column', // Output the display of the column values through a callback.
	) );


}
