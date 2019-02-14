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

?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
				<h2>All Orders</h2>
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
				<div class="form-group col-md-3">
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
			</div>
			<div class="form-row">
				<div class="form-group col-md-3">
					<label for="con_dis_con">Connect/Disconnect</label>
					<select class="form-control" name="con_dis_con">
						<option value="">All</option>				
						<option value="connect">Connect</option>				
						<option value="disconnect">Disconnect</option>				
					</select>
				</div>
				<div class="form-group col-md-3">
					<label for="">Date Range</label>
					<input type="text" class="ub-datepicker" name="apply_date[]" value="" /><!--<i class="fa fa-angle-down" aria-hidden="true"></i>-->
				</div>
				<div class="form-group col-md-3">
					<label for="">To</label>
					<input type="text" class="ub-datepicker" name="apply_date[]" value="" /><!--<i class="fa fa-angle-down" aria-hidden="true"></i>-->
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
			<td>Connect/Disconnect</td>
			<td>Date</td>
		</tr>

	  <?php
	$paged = 1;
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
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$order_title = get_the_title();
		$ub_order_details = get_post_meta($order_id, '_ub_order_details', true);
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		?>
		<tr>
			<td><?php echo $order_title; ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td><?php  ?></td>
			<td><?php if(isset($ub_order_details['apply_date'])){ echo $ub_order_details['apply_date']; } ?>  			
			</td>
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
