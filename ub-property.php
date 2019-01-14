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
	
	//For New Property
	$cmb_new_property = new_cmb2_box( array(
		'id'            => $prefix . 'new_property',
		'title'         => esc_html__( 'New Property', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_property' ), // Post type
	) );
	
	$cmb_new_property->add_field( array(
		'name'       => esc_html__( 'Owner\'s Phone Number', 'ub-utility-manager' ),
		'id'         => $prefix . 'owner_phone_number',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_new_property->add_field( array(
		'name' => esc_html__( 'Property Manager', 'ub-utility-manager' ),
		'desc'       => esc_html__( 'Same as Property Manager', 'ub-utility-manager' ),
		'id'   => $prefix . 'property_manager',
		'type' => 'checkbox',
	) );
	
	$cmb_new_property->add_field( array(
		'name'       => esc_html__( 'Street Address', 'ub-utility-manager' ),
		'id'         => $prefix . 'street_address',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_new_property->add_field( array(
		'name'       => esc_html__( 'City', 'ub-utility-manager' ),
		'id'         => $prefix . 'city',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_new_property->add_field( array(
		'name'             => esc_html__( 'State', 'ub-utility-manager' ),
		'id'               => $prefix . 'state',
		'type'             => 'select',
		'show_option_none' => true,
		'options'          => array(
			'state1' => esc_html__( 'State One', 'ub-utility-manager' ),
			'state2' => esc_html__( 'State Two', 'ub-utility-manager' ),
			'state3' => esc_html__( 'State Three', 'ub-utility-manager' ),
		),
	) );
	
	$cmb_new_property->add_field( array(
		'name'       => esc_html__( 'Zipcode', 'ub-utility-manager' ),
		'id'         => $prefix . 'zipcode',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	//For Utility Gas
	$cmb_utility_gas = new_cmb2_box( array(
		'id'            => $prefix . 'utility_gas',
		'title'         => esc_html__( 'Utility Gas', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_property' ), // Post type
	) );
	
	$cmb_utility_gas->add_field( array(
		'name' => esc_html__( 'Gas', 'ub-utility-manager' ),
		'desc'       => esc_html__( 'Gas', 'ub-utility-manager' ),
		'id'   => $prefix . 'gas',
		'type' => 'checkbox',
	) );
	
	$cmb_utility_gas->add_field( array(
		'name'       => esc_html__( 'Provider', 'ub-utility-manager' ),
		'id'         => $prefix . 'gas_provider',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_gas->add_field( array(
		'name'       => esc_html__( 'Name on Utilities', 'ub-utility-manager' ),
		'id'         => $prefix . 'gas_utility_name',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_gas->add_field( array(
		'name'       => esc_html__( 'Confirmation/Account Number', 'ub-utility-manager' ),
		'id'         => $prefix . 'gas_account_number',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_gas->add_field( array(
		'name' => esc_html__( 'Upload PDF', 'cmb2' ),
		'id'   => $prefix . 'gas_pdf',
		'type' => 'file',
	) );
	
	//For Utility Water
	$cmb_utility_water = new_cmb2_box( array(
		'id'            => $prefix . 'utility_water',
		'title'         => esc_html__( 'Utility Water', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_property' ), // Post type
	) );
	
	$cmb_utility_water->add_field( array(
		'name' => esc_html__( 'Water', 'ub-utility-manager' ),
		'desc'       => esc_html__( 'Water', 'ub-utility-manager' ),
		'id'   => $prefix . 'water',
		'type' => 'checkbox',
	) );
	
	$cmb_utility_water->add_field( array(
		'name'       => esc_html__( 'Provider', 'ub-utility-manager' ),
		'id'         => $prefix . 'water_provider',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_water->add_field( array(
		'name'       => esc_html__( 'Name on Utilities', 'ub-utility-manager' ),
		'id'         => $prefix . 'water_utility_name',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_water->add_field( array(
		'name'       => esc_html__( 'Confirmation/Account Number', 'ub-utility-manager' ),
		'id'         => $prefix . 'water_account_number',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_water->add_field( array(
		'name' => esc_html__( 'Upload PDF', 'cmb2' ),
		'id'   => $prefix . 'water_pdf',
		'type' => 'file',
	) );
	
	//For Utility Electricity
	$cmb_utility_electricity = new_cmb2_box( array(
		'id'            => $prefix . 'utility_electricity',
		'title'         => esc_html__( 'Utility Electricity', 'ub-utility-manager' ),
		'object_types'  => array( 'ub_property' ), // Post type
	) );
	
	$cmb_utility_electricity->add_field( array(
		'name' => esc_html__( 'Electricity', 'ub-utility-manager' ),
		'desc'       => esc_html__( 'Electricity', 'ub-utility-manager' ),
		'id'   => $prefix . 'electricity',
		'type' => 'checkbox',
	) );
	
	$cmb_utility_electricity->add_field( array(
		'name'       => esc_html__( 'Provider', 'ub-utility-manager' ),
		'id'         => $prefix . 'electricity_provider',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_electricity->add_field( array(
		'name'       => esc_html__( 'Name on Utilities', 'ub-utility-manager' ),
		'id'         => $prefix . 'electricity_utility_name',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_electricity->add_field( array(
		'name'       => esc_html__( 'Confirmation/Account Number', 'ub-utility-manager' ),
		'id'         => $prefix . 'electricity_account_number',
		'type'       => 'text',
		'show_on_cb' => 'yourprefix_hide_if_no_cats', 
	) );
	
	$cmb_utility_electricity->add_field( array(
		'name' => esc_html__( 'Upload PDF', 'cmb2' ),
		'id'   => $prefix . 'electricity_pdf',
		'type' => 'file',
	) );


}
