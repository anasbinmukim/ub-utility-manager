<?php
//View Properties
add_shortcode('ub_confirm_orders', 'ub_confirm_orders_shortcode');
function ub_confirm_orders_shortcode($atts){
	extract(shortcode_atts(array(
    'count' => '-1',
  ), $atts));
  ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			return;
	}

	$order_author_id = get_current_user_id();
	$current_page_url = get_permalink();

?>
<div class="confirm-order-popup-wrap">
	
</div><!-- confirm-order-popup-wrap -->

<div class="ub-form-wrap">
	<div class="ub-form-content">
	<table class="table table-bordered">
		<tr>
			<td colspan="5">Connection Orders</td>
		</tr>
		<tr>
			<td></td>
			<td>Connection Date</td>
			<td>Client Name</td>
			<td>Status</td>
		</tr>

	  <?php

	$args = array(
		'post_type' => 'ub_order',
		'tax_query' => array(
			array(
				'taxonomy' => 'ub_order_type',
				'field' => 'term_id',
				'terms' => get_option('ubp_connect_term')
			)
		),
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		$order_details = get_post_meta($order_id, '_ub_order_details', true);
		if(isset($order_details['apply_date'])){
			$apply_date = $order_details['apply_date'];
		}
		$params_url = array('review_confirmation' => 'connect', 'order_id' => $order_id);
		$action_url = esc_url( add_query_arg( $params_url, $current_page_url) );
		?>
		<tr>
			<td><a data-order_type="connect" data-order_id="<?php echo $order_id; ?>" href="javascript:void(0)" class="view-order-button btn btn-secondary btn-sm">View</a></td>
			<td><?php echo $apply_date; ?></td>
			<td><?php echo get_the_title($property_id); ?></td>
			<td><?php echo ub_order_status($order_id); ?></td>
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

<div class="ub-form-wrap">
	<div class="ub-form-content">
	<table class="table table-bordered">
		<tr>
			<td colspan="5">Disconnection Orders</td>
		</tr>
		<tr>
			<td>View</td>
			<td>Connection Date</td>
			<td>Client Name</td>
			<td>Status</td>
		</tr>

	  <?php

	$args = array(
		'post_type' => 'ub_order',
		'tax_query' => array(
			array(
				'taxonomy' => 'ub_order_type',
				'field' => 'term_id',
				'terms' => get_option('ubp_disconnect_term')
			)
		),
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'DESC'
	);

	$order_posts = new WP_Query($args);
	if($order_posts->have_posts()){
	while($order_posts->have_posts()): $order_posts->the_post();
		$order_id = get_the_ID();
		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		$order_details = get_post_meta($order_id, '_ub_order_details', true);
		if(isset($order_details['apply_date'])){
			$apply_date = $order_details['apply_date'];
		}
		$params_url = array('review_confirmation' => 'disconnect', 'order_id' => $order_id);
		$action_url = esc_url( add_query_arg( $params_url, $current_page_url) );
		?>
		<tr>
			<td><a data-order_type="disconnect" data-order_id="<?php echo $order_id; ?>" href="javascript:void(0)" class="view-order-button btn btn-secondary btn-sm">View</a></td>
			<td><?php echo $apply_date; ?></td>
			<td><?php echo get_the_title($property_id); ?></td>
			<td><?php echo ub_order_status($order_id); ?></td>
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
