<?php
//Login form shortcode
add_shortcode('ub_review_order', 'ub_review_order_shortcode');
function ub_review_order_shortcode($atts){
	extract(shortcode_atts(array(
    'order_type' => 'connect',
  ), $atts));
  ob_start();
	if(!is_user_logged_in()){
		$display_message = 'You need to login to view this page';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
	}

	if(isset($_GET['order_type'])){
		$order_type = esc_html($_GET['order_type']);
	}



	$current_user_id = get_current_user_id();
	$current_page_url = get_permalink();
	$edit_order_url = get_permalink(get_option('ubp_create_order'));
	$cart_items = array();

	$connect_cart_items = get_the_author_meta( '_ub_connect_cart_items', $current_user_id );
	$disconnect_cart_items = get_the_author_meta( '_ub_disconnect_cart_items', $current_user_id );

	//ub_debug($connect_cart_items);

	if((isset($connect_cart_items) && (count($connect_cart_items) > 0)) || (isset($disconnect_cart_items) && (count($disconnect_cart_items) > 0))){
		//good to process..
	}else{
		$display_message = 'No items found';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
	}

	if($order_type == 'connect'){
			$cart_items = $connect_cart_items;
			$review_heading = 'Review Connection Order';
			$submit_label = 'Submit Connect';

			$params_url = array('order_type' => 'connect');
			$action_url = esc_url( add_query_arg( $params_url, $current_page_url) );


			$edit_order_params = array('submit_type' => 'connect');
			$edit_order_url = esc_url( add_query_arg( $edit_order_params, $edit_order_url) );
	}

	if($order_type == 'disconnect'){
			$cart_items = $disconnect_cart_items;
			$review_heading = 'Review Disconnection Order';
			$submit_label = 'Submit Disconnect';

			$params_url = array('order_type' => 'disconnect');
			$action_url = esc_url( add_query_arg( $params_url, $current_page_url) );

			$edit_order_params = array('submit_type' => 'disconnect');
			$edit_order_url = esc_url( add_query_arg( $edit_order_params, $edit_order_url) );

	}

	//remove item from cart
	if(isset($_GET['ubaction']) && ($_GET['ubaction'] == 'doremove') && isset($_GET['property_id'])){
			$property_id = $_GET['property_id'];
			if(isset($cart_items[$property_id])){
				unset($cart_items[$property_id]);
			}
			if($order_type == 'connect'){
				update_user_meta($current_user_id, '_ub_connect_cart_items', $cart_items);

			}
			if($order_type == 'disconnect'){
				update_user_meta($current_user_id, '_ub_disconnect_cart_items', $cart_items);
			}

			$display_message = 'Successfully remove from cart.';
			echo ub_action_message($display_message, 'danger');

			echo '<script type="text/javascript">window.location = "'.$action_url.'"</script>';

	}

	$total_order_price = 0;

	$output_cart_items = '';
	if(isset($cart_items) && (count($cart_items) > 0)){
		 foreach ($cart_items as $property_id => $uti_value) {
					$gas_value = 'n/a';
					$water_value = 'n/a';
					$electricity_value = 'n/a';
					$total_uti_value = 0;
					$property_address = '';

					$property_address .= get_post_meta( $property_id, '_ubp_street_address', true);
					$property_address .= '<br />'.get_post_meta( $property_id, '_ubp_city', true);
					$property_address .= ', '.get_post_meta( $property_id, '_ubp_state', true);
					$property_address .= '<br />'.get_post_meta( $property_id, '_ubp_zipcode', true);


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

					$params_url = array('ubaction' => 'doremove', 'property_id' => $property_id, '_wpnonce' => wp_create_nonce( 'action' ));
					$remove_cart_item_url = esc_url( add_query_arg( $params_url, $action_url) );


				$output_cart_items .= '<div class="cart-order-item row">
						<div class="col-6 col-md-6">
							<div class="property-address">'.$property_address.'</div>
							<a href="'.$remove_cart_item_url.'" class="btn btn-secondary btn-sm remove-from-cart">Remove</a>
						</div>
						<div class="col-6 col-md-6 table-responsive">
							<table class="table table-striped">
								<tr><th>Gas:</th><td>'.ub_currency_display($gas_value).'</td></tr>
								<tr><th>Water: </th><td>'.ub_currency_display($water_value).'</td></tr>
								<tr><th>Electricity: </th><td>'.ub_currency_display($electricity_value).'</td></tr>
								<tr class="total-deposit"><th class="table-success">Total: </th><td class="table-success">'.ub_currency_display($total_uti_value).'</td></tr>
							</table>
						</div>
				</div><!-- cart-order-item -->';
				}
		 }else{
			 $display_message = 'No property found in cart!';
			 echo ub_action_message($display_message, 'info');
			 return;
		 }

		 //Process order for submitting connect and disconnect
		 if(isset($_POST['order_submit'])){
			 if(isset($_POST['order_submit_type']) && ($_POST['order_submit_type'] == 'connect')){
				 	//ub_debug($cart_items);
					if(create_new_order($cart_items, 'connect', $current_user_id)){
						update_user_meta($current_user_id, '_ub_connect_cart_items', '');
					}
			 }

			 if(isset($_POST['order_submit_type']) && ($_POST['order_submit_type'] == 'disconnect')){
					//ub_debug($cart_items);
					if(create_new_order($cart_items, 'disconnect', $current_user_id)){
						update_user_meta($current_user_id, '_ub_disconnect_cart_items', '');
					}
			 }

			 $redirect_page_url = get_permalink(intval(get_option('ubpid_my_order')));
			 echo '<script type="text/javascript">window.location = "'.$redirect_page_url.'"</script>';

		 }

?>
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
					<h2><?php echo $review_heading;  ?></h2>
		</div><!-- ub-form-header -->

		<div class="ub-review-order">
			<div class="order-heading row">
				<div class="total-order-price col-6 col-md-6">
					<strong>Total Order: </strong> <?php echo ub_currency_display($total_order_price); ?>
				</div>
				<div class="edit-order col-6 col-md-6">
						<a href="<?php echo $edit_order_url; ?>" class="btn btn-secondary btn-sm link-edit-order">Edit Order</a>
				</div>
			</div><!-- order-heading -->

			<div class="review-order-items">
				<?php echo $output_cart_items; ?>
			</div><!-- review-order-items -->
			<div class="order-submission">
					<form action="<?php echo $action_url; ?>" method="post">
						<input type="hidden" name="order_submit_type" value="<?php echo esc_attr($order_type); ?>" />
						<input type="submit" class="btn btn-primary" name="order_submit" value="<?php echo esc_attr($submit_label); ?>" />
					</form>
			</div><!-- order-submission -->
		</div>

	<div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->
<?php

	$output_result = ob_get_clean();
	return $output_result;
}
