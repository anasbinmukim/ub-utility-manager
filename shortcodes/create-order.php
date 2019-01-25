<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//Connection disconnection
add_shortcode('connect_disconnect_order', 'ub_connect_disconnect_order_shortcode');
function ub_connect_disconnect_order_shortcode($atts){
	extract(shortcode_atts(array(
    'submit_type' => 'connect',
		'count' => '-1'
  ), $atts));
  ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			return;
	}

	$property_author_id = get_current_user_id();
	$current_user_id = get_current_user_id();
	$current_page_url = get_permalink();

	if (isset($_POST['ub_connection_nonce']) && (! isset( $_POST['ub_connection_nonce'] ) || ! wp_verify_nonce( $_POST['ub_connection_nonce'], 'ub_connection_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['review_order']) && (isset($_POST['add_to_cart_products']))){


		$connection_type = $_POST['connection_type'];

		$add_to_cart_products = $_POST['add_to_cart_products'];
		$check_utility_gas = $_POST['check_utility_gas'];
		$check_utility_water = $_POST['check_utility_water'];
		$check_utility_electricity = $_POST['check_utility_electricity'];
		$apply_date = $_POST['apply_date'];

		$total_cart_items = array();
		foreach ($add_to_cart_products as $pkey => $product_id) {
			if((isset($check_utility_gas[$pkey]) && ($check_utility_gas[$pkey] == 'yes')) || (isset($check_utility_water[$pkey]) && ($check_utility_water[$pkey] == 'yes')) || (isset($check_utility_electricity[$pkey]) && ($check_utility_electricity[$pkey] == 'yes'))){
				$total_cart_items[$product_id] = array(
					'gas' => esc_html($check_utility_gas[$pkey]),
					'water' => esc_html($check_utility_water[$pkey]),
					'electricity' => esc_html($check_utility_electricity[$pkey]),
					'apply_date' => esc_html($apply_date[$pkey])
				);
			}
		}

		//ub_debug($total_cart_items);
		$review_order_url = get_permalink(get_option('ubp_review_order'));

		if($connection_type == 'connect'){
				update_user_meta($current_user_id, '_ub_connect_cart_items', $total_cart_items);
				$params_url = array('order_type' => 'connect');
				$redirect_page_url = esc_url( add_query_arg( $params_url, $review_order_url) );
				echo '<script type="text/javascript">window.location = "'.$redirect_page_url.'"</script>';

		}

		if($connection_type == 'disconnect'){
				update_user_meta($current_user_id, '_ub_disconnect_cart_items', $total_cart_items);
				$params_url = array('order_type' => 'disconnect');
				$redirect_page_url = esc_url( add_query_arg( $params_url, $review_order_url) );
				echo '<script type="text/javascript">window.location = "'.$redirect_page_url.'"</script>';
		}

	}

	if($submit_type == 'connect'){
			$cart_items = get_the_author_meta( '_ub_connect_cart_items', $current_user_id );
			$submitting_heading = 'New Connection Order';
			$params_url = array('submit_type' => 'connect');
			$action_url = esc_url( add_query_arg( $params_url, $current_page_url) );
	}
	if($submit_type == 'disconnect'){
		 $cart_items = get_the_author_meta( '_ub_disconnect_cart_items', $current_user_id );
		 $submitting_heading = 'New Disconnection Order';
		 $params_url = array('submit_type' => 'disconnect');
		 $action_url = esc_url( add_query_arg( $params_url, $current_page_url) );
	}

?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="only-heading">
				<h2><?php echo $submitting_heading; ?></h2>
		</div>
		<form action="" method="post">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="street_address">Street Address</label>
					<input type="text" class="form-control" name="street_address" id="street_address">
				</div>
				<div class="form-group col-md-3">
					<label for="city">City</label>
					<input type="text" class="form-control" name="city" id="city">
				</div>
				<div class="form-group col-md-2">
					<label for="zipcode">Zipcode</label>
					<input type="text" class="form-control" name="zipcode" id="zipcode">
				</div>
				<div class="form-group col-md-2">
					<label for="city">State</label>
					<select class="form-control" name="state">
						<option value="">State</option>
						<?php
							global $states_full_key;
							foreach($states_full_key as $key => $value){
								echo '<option value="'. $key .'">'. $value. '</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group col-md-1">
					<label for="">Search</label>
					<button type="submit" class="btn btn-default" name="search_property"><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/search-icon.png" alt=""/></button>
				</div>
			</div>
		</form>
<div class="conn-dis-order">
<form action="<?php echo $action_url; ?>" method="post">
	<table class="table table-bordered">
		<tr>
			<td colspan="9" align="center">My Properties</td>
		</tr>
		<tr>
			<td><input type="checkbox" name="" class="select-all"/></td>
			<td>Street Address</td>
			<td>City</td>
			<td>Zipcode</td>
			<td>State</td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/gas-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/water-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/electric-icon.png" alt=""/></td>
			<td>Connect Date</td>
		</tr>

	  <?php

	if(isset($_POST['search_property']) && ((isset($_POST['street_address']) && !empty($_POST['street_address'])) || (isset($_POST['city']) && !empty($_POST['city'])) || (isset($_POST['zipcode']) && !empty($_POST['zipcode'])) || (isset($_POST['state']) && !empty($_POST['state'])))){
		$city = $_POST['city'];
		$street_address = $_POST['street_address'];
		$zipcode = $_POST['zipcode'];
		$state = $_POST['state'];
		$args = array(
			'post_type' => 'ub_property',
			'posts_per_page' => $count,
			'author' => $property_author_id,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'meta_query' => array(
			   'relation' => 'OR',
			   array(
				 'key' => '_ubp_street_address',
				 'value' => $street_address,
				 'type' => 'CHAR',
				 'compare' => '='
			   ),
			   array(
				 'key' => '_ubp_city',
				 'value' => $city,
				 'type' => 'CHAR',
				 'compare' => '='
			   ),
			   array(
				 'key' => '_ubp_zipcode',
				 'value' => $zipcode,
				 'type' => 'CHAR',
				 'compare' => '='
			   ),
			   array(
				 'key' => '_ubp_state',
				 'value' => $state,
				 'type' => 'CHAR',
				 'compare' => '='
			   )
			)
		);
	}else{
		$args = array(
			'post_type' => 'ub_property',
			'posts_per_page' => $count,
			'author' => $property_author_id,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
	}

	$property_posts = new WP_Query($args);
	if($property_posts->have_posts()){
	$countproperty = 1;
	while($property_posts->have_posts()): $property_posts->the_post();
		$property_id = get_the_ID();
		//$property_title = get_the_title();
		$ubp_street_address = get_post_meta($property_id, '_ubp_street_address', true);
		$ubp_city = get_post_meta($property_id, '_ubp_city', true);
		$ubp_zipcode = get_post_meta($property_id, '_ubp_zipcode', true);
		$ubp_state = get_post_meta($property_id, '_ubp_state', true);

		$ubp_gas = get_post_meta($property_id, '_ubp_gas', true);
		$ubp_water = get_post_meta($property_id, '_ubp_water', true);
		$ubp_electricity = get_post_meta($property_id, '_ubp_electricity', true);

		//connected, disconnected
		$ubp_gas_status = get_post_meta($property_id, '_ubp_gas_status', true);
		$ubp_water_status = get_post_meta($property_id, '_ubp_water_status', true);
		$ubp_electricity_status = get_post_meta($property_id, '_ubp_electricity_status', true);
		?>
		<tr>
			<td>
				<input type="checkbox" name="select_all_utility[]" class="select-available select-available-<? echo esc_attr($countproperty); ?>"/>
				<?php if ( current_user_can('manage_options') ){ ?>
					<a target="_blank" class="edit-property" href="<?php echo get_permalink(get_option('ubp_register_property')); ?>?edit_property=<?php echo $property_id; ?>">Edit</a>
				<?php } ?>
			</td>
			<td><?php echo esc_html($ubp_street_address); ?></td>
			<td><?php echo esc_html($ubp_city); ?></td>
			<td><?php echo esc_html($ubp_zipcode); ?></td>
			<td><?php echo esc_html($ubp_state); ?></td>

			<?php if($submit_type == 'connect'){ ?>
				<td><?php if(($ubp_gas == 'on') && ($ubp_gas_status == 'connected')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_gas[]" value="no">';
				}elseif(($ubp_gas == 'on') && ($ubp_gas_status == 'error')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_gas[]" value="no">';
				}elseif(($ubp_gas == 'on') && ($ubp_gas_status != 'connected')){
					echo '<input class="check_flag" type="checkbox" /><input type="hidden" class="check_value" name="check_utility_gas[]" value="no">';
				}else{
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_gas[]" value="no">';
				}
				?></td>
				<td><?php if(($ubp_water == 'on') && ($ubp_water_status == 'connected')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_water[]" value="no">';
				}elseif(($ubp_water == 'on') && ($ubp_water_status == 'error')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_water[]" value="no">';
				}elseif(($ubp_water == 'on') && ($ubp_water_status != 'connected')){
					echo '<input class="check_flag" type="checkbox" /><input type="hidden" class="check_value" name="check_utility_water[]" value="no">';
				}else{
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_water[]" value="no">';
				}
				?></td>
				<td><?php if(($ubp_electricity == 'on') && ($ubp_electricity_status == 'connected')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_electricity[]" value="no">';
				}elseif(($ubp_electricity == 'on') && ($ubp_electricity_status == 'error')){
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_electricity[]" value="no">';
				}elseif(($ubp_electricity == 'on') && ($ubp_electricity_status != 'connected')){
					echo '<input class="check_flag" type="checkbox" /><input type="hidden" class="check_value" name="check_utility_electricity[]" value="no">';
				}else{
					echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					echo '<input type="hidden" class="check_value" name="check_utility_electricity[]" value="no">';
				}
				?></td>
			<?php } ?>

			<td>
				<input type="text" class="ub-datepicker" name="apply_date[]" value="" />
				<input type="hidden" name="add_to_cart_products[]" value="<?php echo esc_attr($property_id); ?>" />
			</td>
		</tr>
	<?php
	$countproperty++;
	endwhile;
	?>
	<tr>
	<td colspan="9" align="right">
		<?php wp_nonce_field( 'ub_connection_action', 'ub_connection_nonce' ); ?>
		<input type="hidden" name="connection_type" value="<?php echo esc_attr($submit_type); ?>" />
		<input type="submit" class="btn btn-primary" name="review_order" value="Review Order"/>
	</td>
	</tr>
	</table>
	<?php }else{ ?>
	 </table>
	<?php
	  echo 'No property found';
  }
  echo '</form>';
  echo '</div>';
  wp_reset_query();

	?>
	</div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->
<script>
jQuery(document).ready(function($) {
		$( ".ub-datepicker" ).datepicker({
        dateFormat : "mm/dd/yy"
    });
    $('.select-all').click(function() {
			if ($(this).is(':checked')) {
				$('.conn-dis-order input').attr('checked', true);
			} else {
				$('.conn-dis-order input').attr('checked', false);
			}
		});

		$('.check_flag').click(function() {
			if ($(this).is(':checked')) {
				$(this).closest('td').children('.check_value').val('yes');
			} else {
				$(this).closest('td').children('.check_value').val('no');
			}
		});

});
</script>
	<?php

	$output_result = ob_get_clean();
	return $output_result;
}//
