<?php
//View Properties
add_shortcode('ub_all_properties', 'ub_all_properties_shortcode');
function ub_all_properties_shortcode($atts){
	extract(shortcode_atts(array(
    'count' => '-1',
  ), $atts));
  ob_start();

	if(!current_user_can('manage_options')){
		$display_message = 'You are not allowed to see this page.';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
	}

	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
	
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
				<h2>My Properties</h2>
		</div>
<form action="" method="post">
	<div class="form-row">
		<div class="form-group col-md-4">
			<label for="client_name">Client Name</label>
			<input type="text" class="form-control" name="client_name" id="client_name">
		</div>
	</div>
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

	<table class="table table-bordered">
		<tr>
			<td>Client Name</td>
			<td>Street Address</td>
			<td>City</td>
			<td>Zipcode</td>
			<td>State</td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/gas-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/water-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/electric-icon.png" alt=""/></td>
		</tr>

	  <?php
	if(isset($_POST['search_property']) && ((isset($_POST['street_address']) && $_POST['street_address'] != '') || (isset($_POST['city']) && $_POST['city'] != '') || (isset($_POST['zipcode']) && $_POST['zipcode'] != '') || (isset($_POST['state']) && $_POST['state'] != ''))){
		$city = $_POST['city'];
		$street_address = $_POST['street_address'];
		$zipcode = $_POST['zipcode'];
		$state = $_POST['state'];
		$args = array(
			'post_type' => 'ub_property',
			'posts_per_page' => $count,
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
	}elseif(isset($_POST['search_property']) && (isset($_POST['client_name']) && $_POST['client_name'] != '')){
		$client_name = $_POST['client_name'];
		$args = array(
			'post_type' => 'ub_property',
			'posts_per_page' => $count,
			'name' => $client_name,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
	}else{
		$args = array(
			'post_type' => 'ub_property',
			'posts_per_page' => $count,
			'orderby' => 'menu_order',
			'order' => 'ASC'
		);
	}

	$property_posts = new WP_Query($args);
	if($property_posts->have_posts()){
	while($property_posts->have_posts()): $property_posts->the_post();
		$property_id = get_the_ID();
		$property_title = get_the_title();

		$gas_status = ub_property_status($property_id, 'gas');
		$electricity_status = ub_property_status($property_id, 'electricity');
		$water_status = ub_property_status($property_id, 'water');
		?>
		<tr>
			<td><?php echo $property_title; ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td>
				<?php
					if($gas_status == 'available_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					}
					if($gas_status == 'available_not_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-not-connected.png" alt=""/>';
					}
					if($gas_status == 'not_available'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					}
					if($gas_status == 'error'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					}
				?>
			</td>
			<td>
				<?php
					if($water_status == 'available_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					}
					if($water_status == 'available_not_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-not-connected.png" alt=""/>';
					}
					if($water_status == 'not_available'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					}
					if($water_status == 'error'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					}
				?>
			</td>
			<td>
				<?php
					if($electricity_status == 'available_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
					}
					if($electricity_status == 'available_not_connected'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-not-connected.png" alt=""/>';
					}
					if($electricity_status == 'not_available'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
					}
					if($electricity_status == 'error'){
							echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/error-notification.png" alt=""/>';
					}
				?>
			</td>
		</tr>
	<?php
	endwhile;
	echo '</table>';
	//echo get_ub_pagination($property_posts->max_num_pages, $range = 2);
  }else{
	  echo '</table>';
	  echo 'No property found';
  }
	wp_reset_query();

	?>
	</div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->
	<?php

	$output_result = ob_get_clean();
	return $output_result;
}
