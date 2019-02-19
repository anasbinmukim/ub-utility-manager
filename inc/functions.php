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
			$apply_date_db = date('Y-m-d', strtotime($apply_date));
			update_post_meta( $order_id, '_ub_apply_date', $apply_date_db);

			//Order created by current user:
			$order_creator_id = get_current_user_id();
			update_post_meta( $order_id, '_ub_order_creator_id', $order_creator_id);

	 		$order_type_value = array( $ubp_type_term_id );
	 		wp_set_post_terms( $order_id, $order_type_value, 'ub_order_type' );

			//Send notification to admin
			$subject = 'New '.$order_type.' order received';
			$message = 'A new order submitted to Utility Bothers.';
			$message = '<br />Order ID: '.$order_id;

			$ub_order_admin_emails = get_option('ub_order_admin_emails');
			ub_send_email($ub_order_admin_emails, $subject, $message);


	 	 }
		 return true;
	 }else{
		 return false;
	 }
 }

  function ub_order_status($order_id){
			if(get_post_status ( $order_id ) == 'new'){
					return 'New';
			}elseif(get_post_status ( $order_id ) == 'complete'){
					return 'Complete';
			}else{
				return '';
			}
	}

	//Retrun available_connected, available_not_connected, not_available, error
	function ub_property_status($property_id, $utility = 'gas'){

		if((get_post_meta( $property_id, '_ubp_gas_status', true) == 'connected') && ($utility == 'gas')){
				return 'available_connected';
		}
		if((get_post_meta( $property_id, '_ubp_electricity_status', true) == 'connected') && ($utility == 'electricity')){
				return 'available_connected';
		}
		if((get_post_meta( $property_id, '_ubp_water_status', true) == 'connected') && ($utility == 'water')){
				return 'available_connected';
		}



		if((get_post_meta( $property_id, '_ubp_gas_status', true) == 'disconnected') && ($utility == 'gas')){
				return 'available_not_connected';
		}
		if((get_post_meta( $property_id, '_ubp_electricity_status', true) == 'disconnected') && ($utility == 'electricity')){
				return 'available_not_connected';
		}
		if((get_post_meta( $property_id, '_ubp_water_status', true) == 'disconnected') && ($utility == 'water')){
				return 'available_not_connected';
		}


		if(ub_property_error_status($property_id, $utility)){
			if($utility == 'gas'){
				$upb_meta_key = '_ubp_gas';
			}
			if($utility == 'water'){
				$upb_meta_key = '_ubp_water';
			}
			if($utility == 'electricity'){
				$upb_meta_key = '_ubp_electricity';
			}
			if(get_post_meta($property_id, $upb_meta_key, true) == 'on'){
				return 'available_not_connected';
			}else{
				return 'not_available';
			}
		}else{
			return 'error';
		}



	}

	function ub_property_error_status($property_id, $utility = 'gas'){

			if(get_post_meta($property_id, '_ubp_street_address', true) == ''){
				return false;
			}
			if(get_post_meta($property_id, '_ubp_city', true) == ''){
				return false;
			}
			if(get_post_meta($property_id, '_ubp_zipcode', true) == ''){
				return false;
			}
			if(get_post_meta($property_id, '_ubp_state', true) == ''){
				return false;
			}
			if(get_the_title($property_id) == ''){
				return false;
			}
			if(get_post_meta($property_id, '_ubp_owner_phone_number', true) == ''){
				return false;
			}

			if($utility == 'gas'){
					if((get_post_meta( $property_id, '_ubp_gas_provider', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_gas_utility_name', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_gas_account_number', true) == '')){
							return false;
					}
				}
				if($utility == 'water'){
					if((get_post_meta( $property_id, '_ubp_water_provider', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_water_utility_name', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_water_account_number', true) == '')){
							return false;
					}
				}
				if($utility == 'electricity'){
					if((get_post_meta( $property_id, '_ubp_electricity_provider', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_electricity_utility_name', true) == '')){
							return false;
					}
					if((get_post_meta( $property_id, '_ubp_electricity_account_number', true) == '')){
							return false;
					}
				}

				return true;

	}

	if(!function_exists('get_ub_pagination')){
		function get_ub_pagination($pages = '', $range = 2)
		{
			$output = '';
			 $showitems = ($range * 2)+1;
			 global $paged;
			 if(empty($paged)) $paged = 1;
			 if($pages == '')
			 {
				 global $wp_query;
				 $pages = $wp_query->max_num_pages;
				 if(!$pages)
				 {
					 $pages = 1;
				 }
			 }
			 if(1 != $pages)
			 {
				 $output .= "<div class='ub-pagination ub-loop-pagination clearfix'>";
				 if($paged > 1) $output .= "<a class='prev page-numbers' href='".get_pagenum_link($paged - 1)."'><span class='page-prev'></span>".__('Previous', 'ub-utility-manager')."</a>";

				 for ($i=1; $i <= $pages; $i++)
				 {
					 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
					 {
						 $output .= ($paged == $i)? "<span class='page-numbers current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
					 }
				 }
				 if ($paged < $pages) $output .= "<a class='next page-numbers' href='".get_pagenum_link($paged + 1)."'>".__('Next', 'ub-utility-manager')."<span class='page-next'></span></a>";
				 $output .= "</div>\n";
			 }

			 return $output;
		}
	}



	function ub_send_email($send_to, $subject, $message){
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
		//add_filter( 'wp_mail_from_name', function( $name ) { return get_option('quote_email_from_name'); });
		//add_filter( 'wp_mail_from', function( $email ) { return get_option('quote_email_from_email'); });
		$message .= '<br/><br/>Regards<br/>~Utility Brothers Team';
		$message .= '<br />';
		$message .= esc_url( home_url( '/' ) );
		@wp_mail( $send_to, $subject, $message );
	}
