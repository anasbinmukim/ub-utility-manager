<?php
//View All Orders
add_shortcode('ub_all_orders', 'ub_all_orders_shortcode');
function ub_all_orders_shortcode($atts){
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
	
	$current_page_url = get_permalink();	
	
	$client_name = '';
	$street_address = '';
	$city = '';
	$zipcode = '';
	$state = '';
	$con_dis_con = '';
	$date_from = '';
	$date_to = '';
	
	if(is_front_page()) {
		$paged = (get_query_var('page')) ? get_query_var('page') : 1;
	} else {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}	
	
	$args = array(
		'post_type' => 'ub_order',
		'paged' => $paged,
		'posts_per_page' => 10,
		'orderby' => 'date',
		'order' => 'DESC'
	);
	
	if(isset($_GET['search_property']) && (isset($_GET['client_name']) && $_GET['client_name'] != '')){
		$client_name = $_GET['client_name'];
		$args['s'] = $client_name;		
	}
	
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
	
	if(isset($_GET['search_property']) && (isset($_GET['date_from']) && $_GET['date_from'] != '') && (isset($_GET['date_to']) && $_GET['date_to'] != '')){
		$date_from = strtotime($_GET['date_from']);
		$date_from = date("Y-m-d", $date_from);
		$date_to = strtotime($_GET['date_to']);
		$date_to = date("Y-m-d", $date_to);
		$args['meta_query'][] = 
			array(
				'key' => '_ub_apply_date',
				'value' => array($date_from, $date_to),
				'compare' => 'BETWEEN'
			);
	}
	
	if(isset($_GET['search_property']) && (isset($_GET['con_dis_con']) && $_GET['con_dis_con'] != '')){
		$con_dis_con = $_GET['con_dis_con'];
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'ub_order_type',
				'field' => 'term_id',
				'terms' => $con_dis_con
			)
		);
	}

?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
			<h2>Order Look Up</h2>
		</div>
		<form action="<?php echo $current_page_url; ?>" method="get">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="client_name">Client Name</label>
					<input type="text" class="form-control" name="client_name" id="client_name" value="<?php echo esc_attr($client_name); ?>">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="street_address">Street Address</label>
					<input type="text" class="form-control" name="street_address" id="street_address" value="<?php echo esc_attr($street_address); ?>">
				</div>
				<div class="form-group col-md-3">
					<label for="city">City</label>
					<input type="text" class="form-control" name="city" id="city" value="<?php echo esc_attr($city); ?>">
				</div>
				<div class="form-group col-md-3">
					<label for="zipcode">Zipcode</label>
					<input type="text" class="form-control" name="zipcode" id="zipcode" value="<?php echo esc_attr($zipcode); ?>">
				</div>
				<div class="form-group col-md-2">
					<label for="state">State</label>
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
			</div>
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="con_dis_con">Connect/Disconnect</label>
						<?php $all_terms = get_terms(array(
								'taxonomy' => 'ub_order_type',
								'hide_empty' => false
							)); 					
						?>
					<select class="form-control" name="con_dis_con">
						<option value="">All</option>
							<?php foreach($all_terms as $terms){
								echo '<option '.selected($con_dis_con, $terms->term_id).' value="'. $terms->term_id .'">'. $terms->name. '</option>';
							} ?>										
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="">Date Range</label>
					<input type="text" class="ub-datepicker" name="date_from" value="<?php echo esc_attr($date_from); ?>" /><!--<i class="fa fa-angle-down" aria-hidden="true"></i>-->
				</div>
				<div class="form-group col-md-3">
					<label for="">To</label>
					<input type="text" class="ub-datepicker" name="date_to" value="<?php echo esc_attr($date_to); ?>" /><!--<i class="fa fa-angle-down" aria-hidden="true"></i>-->
				</div>
				<div class="form-group col-md-1">
					<label for="" class="search-label">Search</label>
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
			<td>Connect/Disconnect</td>
			<td>Date</td>
		</tr>

	  <?php
	//ub_debug($all_terms);
	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$order_title = get_the_title();
		$property_address = get_post_meta($order_id, '_ub_order_address', true);
		
		$ub_order_details = get_post_meta($order_id, '_ub_order_details', true);
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		?>
		<tr>
			<td><?php echo $order_title; ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td><?php $terms = get_the_terms( $order_id , 'ub_order_type' );
				if(isset($terms[0])){
					echo $terms[0]->name;
				}
			?></td>
			<td><?php echo get_post_meta($order_id, '_ub_apply_date', true); ?></td>
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
}