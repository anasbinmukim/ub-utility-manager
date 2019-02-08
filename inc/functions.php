<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_debug($data, $die = false){
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	if($die){
		die();
	}
}

function ub_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
			if(isset($role[0])){
				return $role[0];
			}else{
				return false;
			}
    } else {
      return false;
    }
 }

 function ub_action_message($message, $type = 'secondary'){
	 	//alert-primary, alert-secondary, alert-success, alert-danger, alert-warning, alert-info, alert-light, alert-dark
	 	$message = '<div role="alert" class="ub-notification-message alert alert-'.esc_attr($type).'"><p>'.esc_html($message).'</p></div>';
		return $message;
 }

 function ub_currency_display($number){
	 $output = '';
	 if($number > 0){
		 $formatted = number_format_i18n( $number, 2 );
		 $output = '<span class="ub-currency">$'.$formatted.'</span>';
	 }else{
		 $output = $number;
	 }

	 return $output;
 }

 function create_new_order($order_items, $order_type, $current_user_id){
	 $total_order_price = 0;
	 if(isset($order_items) && (count($order_items) > 0)){
	 	 foreach ($order_items as $property_id => $uti_value) {

	 		 $gas_value = 'n/a';
	 		 $water_value = 'n/a';
	 		 $electricity_value = 'n/a';
	 		 $apply_date = '';
	 		 $total_uti_value = 0;
	 		 $property_address = '';

	 		 $property_address .= get_post_meta( $property_id, '_ubp_street_address', true);
	 		 $property_address .= '<br />'.get_post_meta( $property_id, '_ubp_city', true);
	 		 $property_address .= ', '.get_post_meta( $property_id, '_ubp_state', true);
	 		 $property_address .= '<br />'.get_post_meta( $property_id, '_ubp_zipcode', true);

	 		 if(isset($uti_value['apply_date'])){
	 			 $apply_date = $uti_value['apply_date'];
	 		 }

	 		 if(isset($uti_value['gas']) && ($uti_value['gas'] == 'yes')){
	 				$gas_value = get_option('ub_gas_charge');
	 				$total_uti_value += $gas_value;
	 		 }
	 		 if(isset($uti_value['water']) && ($uti_value['water'] == 'yes')){
	 				$water_value = get_option('ub_water_charge');
	 				$total_uti_value += $water_value;
	 		 }
	 		 if(isset($uti_value['electricity']) && ($uti_value['electricity'] == 'yes')){
	 				$electricity_value = get_option('ubp_electricity_charge');
	 				$total_uti_value += $electricity_value;
	 		 }

	 		 $total_order_price += $total_uti_value;

	 		 $order_details = array(
	 			 'property_id' =>  $property_id,
	 			 'gas_charge' =>  $gas_value,
	 			 'water_charge' =>  $water_value,
	 			 'electricity_charge' =>  $electricity_value,
	 			 'order_total' =>  $total_uti_value,
	 			 'apply_date' =>  $apply_date,
	 		 );

	 		 $company_name = '';
	 		 $property_author_id = get_post_field( 'post_author', $property_id );
	 		 if(get_the_author_meta( '_ub_company_name', $property_author_id ) != ''){
	 				$company_name = get_the_author_meta( '_ub_company_name', $property_author_id );
	 		 }else{
	 				$company_name = get_the_title($property_id);
	 		 }

			 if($order_type == 'connect'){
				 $ubp_type_term_id = get_option('ubp_connect_term');
			 }

			 if($order_type == 'disconnect'){
				 $ubp_type_term_id = get_option('ubp_disconnect_term');
			 }



	 		 $order_data = array(
	 			'post_type'      => 'ub_order',
	 			'post_title'     => sanitize_text_field( $company_name  ),
	 			'post_author'   => $current_user_id,
	 			'post_status'    => 'new'
	 		);
	 		$order_id = wp_insert_post( $order_data );

	 		update_post_meta( $order_id, '_ub_order_property_id', $property_id);
	 		update_post_meta( $order_id, '_ub_order_address', $property_address);
	 		update_post_meta( $order_id, '_ub_order_details', $order_details);

	 		$order_type_value = array( $ubp_type_term_id );
	 		wp_set_post_terms( $order_id, $order_type_value, 'ub_order_type' );


	 	 }
		 return true;
	 }else{
		 return false;
	 }
 }

  function ub_order_status($order_id){
			if(get_post_status ( $order_id ) == 'new'){
					return 'New Order';
			}else{
				return '';
			}
	}
