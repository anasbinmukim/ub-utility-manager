<?php
//View Properties
add_shortcode('ub_my_orders', 'ub_my_orders_shortcode');
function ub_my_orders_shortcode($atts){
	extract(shortcode_atts(array(
    'count' => '-1',
  ), $atts));
  ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			$output_result = ob_get_clean();
			return $output_result;
	}

	$order_author_id = get_current_user_id();
	if(ub_get_current_user_role() == 'employee'){
		$order_author_id = get_user_meta($order_author_id, '_ub_property_manager_id', true);
	}
	
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
	
	$current_page_url = get_permalink();
	
	$street_address = '';
	$city = '';
	$zipcode = '';
	$state = '';
	
	if(is_front_page()) {
		$paged = (get_query_var('page')) ? get_query_var('page') : 1;
	} else {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}
	$args = array(
		'post_type' => 'ub_order',
		'paged' => $paged,
		'tax_query' => array(
			array(
				'taxonomy' => 'ub_order_type',
				'field' => 'term_id',
				'terms' => get_option('ubp_connect_term')
			)
		),
		'posts_per_page' => 10,
		'author' => $order_author_id,
		'orderby' => 'date',
		'order' => 'DESC'
	);
	
	$args['meta_query']['relation'] = 'AND';
	
	if(isset($_GET['search_property']) && (isset($_GET['street_address']) && $_GET['street_address'] != '')){
		$street_address = $_GET['street_address'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $street_address,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['city']) && $_GET['city'] != '')){
		$city = $_GET['city'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $city,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['zipcode']) && $_GET['zipcode'] != '')){
		$zipcode = $_GET['zipcode'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $zipcode,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['state']) && $_GET['state'] != '')){
		$state = $_GET['state'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $state,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
			<h2>Connection Orders</h2>
		</div>
		<?php //ub_debug($args); ?>
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
			<td>Status</td>
		</tr>

	  <?php

	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		?>
		<tr>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td><?php echo ub_order_status($order_id); ?></td>
		</tr>
	<?php
	endwhile;
	echo '</table>';
	echo get_ub_pagination($order_posts->max_num_pages, $range = 2);
  }else{
	  echo '</table>';
	  echo 'No property found';
  }
	wp_reset_query();

	?>
	</div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->

<?php
	$dis_street_address = '';
	$dis_city = '';
	$dis_zipcode = '';
	$dis_state = '';
	
	$args = array(
		'post_type' => 'ub_order',
		'paged' => $paged,
		'tax_query' => array(
			array(
				'taxonomy' => 'ub_order_type',
				'field' => 'term_id',
				'terms' => get_option('ubp_disconnect_term')
			)
		),
		'posts_per_page' => 10,
		'author' => $order_author_id,
		'orderby' => 'date',
		'order' => 'DESC'
	);
	
	$args['meta_query']['relation'] = 'AND';
	
	if(isset($_GET['dis_search_property']) && (isset($_GET['dis_street_address']) && $_GET['dis_street_address'] != '')){
		$dis_street_address = $_GET['dis_street_address'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $dis_street_address,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['dis_search_property']) && (isset($_GET['dis_city']) && $_GET['dis_city'] != '')){
		$dis_city = $_GET['dis_city'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $dis_city,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['dis_search_property']) && (isset($_GET['dis_zipcode']) && $_GET['dis_zipcode'] != '')){
		$dis_zipcode = $_GET['dis_zipcode'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $dis_zipcode,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}
	
	if(isset($_GET['dis_search_property']) && (isset($_GET['dis_state']) && $_GET['dis_state'] != '')){
		$dis_state = $_GET['dis_state'];
		$args['meta_query'][] = 
			array(
				'key' => '_ub_order_address',
				'value' => $dis_state,
				'type' => 'CHAR',
				'compare' => 'LIKE'
			);
	}

?>

<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
			<h2>Disconnection Orders</h2>
		</div>
		<form action="<?php echo $current_page_url; ?>" method="get">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="dis_street_address">Street Address</label>
					<input type="text" class="form-control" name="dis_street_address" id="dis_street_address" value="<?php echo esc_attr($dis_street_address); ?>">
				</div>
				<div class="form-group col-md-3">
					<label for="dis_city">City</label>
					<input type="text" class="form-control" name="dis_city" id="dis_city" value="<?php echo esc_attr($dis_city); ?>">
				</div>
				<div class="form-group col-md-2">
					<label for="dis_zipcode">Zipcode</label>
					<input type="text" class="form-control" name="dis_zipcode" id="dis_zipcode" value="<?php echo esc_attr($dis_zipcode); ?>">
				</div>
				<div class="form-group col-md-2">
					<label for="dis_state">State</label>
					<select class="form-control" name="dis_state">
						<option value="">State</option>
						<?php
							global $states_full_key;
							foreach($states_full_key as $key => $value){
								echo '<option '.selected($dis_state, $key).' value="'. $key .'">'. $value. '</option>';
							}
						?>
					</select>
				</div>
				<div class="form-group col-md-1">
					<label for="" class="search-label">Search</label>
					<button type="submit" class="btn btn-default" name="dis_search_property"><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/search-icon.png" alt=""/></button>
				</div>
			</div>
		</form>

	<table class="table table-bordered">
		<tr>
			<td>Street Address</td>
			<td>City</td>
			<td>Zipcode</td>
			<td>State</td>
			<td>Status</td>
		</tr>

	  <?php

	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		?>
		<tr>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td><?php echo ub_order_status($order_id); ?></td>
		</tr>
	<?php
	endwhile;
	echo '</table>';
	echo get_ub_pagination($order_posts->max_num_pages, $range = 2);
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
