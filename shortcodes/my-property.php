<?php
//View Properties
add_shortcode('ub_my_property', 'ub_my_property_shortcode');
function ub_my_property_shortcode($atts){
	extract(shortcode_atts(array(
    'count' => '-1',
  ), $atts));
  ob_start();

	if(!is_user_logged_in()){
		$display_message = 'You need to login to view this page';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
	}

	$property_author_id = get_current_user_id();
	if(ub_get_current_user_role() == 'employee'){
		$property_author_id = get_user_meta($property_author_id, '_ub_property_manager_id', true);
	}

	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
	
	$current_page_url = get_permalink();
	
	$street_address = '';
	$city = '';
	$zipcode = '';
	$state = '';
	$args = array(
		'post_type' => 'ub_property',
		'author' => $property_author_id,
		'posts_per_page' => $count,
		'orderby' => 'menu_order',
		'order' => 'ASC'
	);
	
	$args['meta_query']['relation'] = 'AND';
	
	if(isset($_GET['search_property']) && (isset($_GET['street_address']) && $_GET['street_address'] != '')){
		$street_address = $_GET['street_address'];
		$args['meta_query'][] = 
			array(
				'key' => '_ubp_street_address',
				'value' => $street_address,
				'type' => 'CHAR',
				'compare' => '='
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['city']) && $_GET['city'] != '')){
		$city = $_GET['city'];
		$args['meta_query'][] = 
			array(
				'key' => '_ubp_city',
				'value' => $city,
				'type' => 'CHAR',
				'compare' => '='
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['zipcode']) && $_GET['zipcode'] != '')){
		$zipcode = $_GET['zipcode'];
		$args['meta_query'][] = 
			array(
				'key' => '_ubp_zipcode',
				'value' => $zipcode,
				'type' => 'CHAR',
				'compare' => '='
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['state']) && $_GET['state'] != '')){
		$state = $_GET['state'];
		$args['meta_query'][] = 
			array(
				'key' => '_ubp_state',
				'value' => $state,
				'type' => 'CHAR',
				'compare' => '='
			);
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
			<h2>My Properties</h2>
		</div>
<form action="<?php echo $current_page_url; ?>" method="get">
	<div class="form-row">
		<div class="form-group col-md-4">
			<label for="street_address">Street Address</label>
			<input type="text" class="form-control" name="street_address" id="street_address" value="<?php echo esc_attr($street_address); ?>">
		</div>
		<div class="form-group col-md-3">
			<label for="city">City</label>
			<input type="text" class="form-control" name="city" id="city" value="<?php echo esc_attr($city); ?>">
		</div>
		<div class="form-group col-md-2">
			<label for="zipcode">Zipcode</label>
			<input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo esc_attr($zipcode); ?>">
		</div>
		<div class="form-group col-md-2">
			<label for="city">State</label>
			<select class="form-control" name="state">
				<option value="">State</option>
				<?php
					global $states_full_key;
					foreach($states_full_key as $key => $value){
						echo '<option '.selected($state, $key).' value="'. $key .'">'. $value. '</option>';
					}
				?>
			</select>
		</div>
		<div class="form-group col-md-1">
			<label for="" class="search-label">Search</label>
			<button type="submit" class="btn btn-default" name="search_property"><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/search-icon.png" alt=""/></button>
		</div>
	</div>
</form>

	<table class="table table-bordered">
		<tr>
			<td>Street Address</td>
			<td>City</td>
			<td>Zipcode</td>
			<td>State</td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/gas-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/water-icon.png" alt=""/></td>
			<td><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/electric-icon.png" alt=""/></td>
		</tr>

	  <?php

	$property_posts = new WP_Query($args);
	if($property_posts->have_posts()){
	while($property_posts->have_posts()): $property_posts->the_post();
		$property_id = get_the_ID();
		//$property_title = get_the_title();

		$gas_status = ub_property_status($property_id, 'gas');
		$electricity_status = ub_property_status($property_id, 'electricity');
		$water_status = ub_property_status($property_id, 'water');
		?>
		<tr>
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