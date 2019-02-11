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


	//Process order confirmation
	if(isset($_POST['order_confirm_submit'])){

		$order_id = intval($_POST['order_id']);
		$property_id = intval($_POST['property_id']);
		$order_type = esc_html($_POST['order_type']);

		$cgas_deposit = floatval($_POST['cgas_deposit']);
		$celectric_deposit = floatval($_POST['celectric_deposit']);
		$water_deposit = floatval($_POST['water_deposit']);

		$total_deposit = $cgas_deposit + $celectric_deposit + $water_deposit;

		if($order_type == 'connect'){
				$connect_status = 'connected';
		}
		if($order_type == 'disconnect'){
				$connect_status = 'disconnected';
		}

		$customer_order_details = get_post_meta($order_id, '_ub_order_details', true);

		if(isset($customer_order_details['gas_charge']) && ($customer_order_details['gas_charge'] > 0)){
			update_post_meta( $property_id, '_ubp_gas_status', $connect_status);
		}

		if(isset($customer_order_details['electricity_charge']) && ($customer_order_details['electricity_charge'] > 0)){
			update_post_meta( $property_id, '_ubp_electricity_status', $connect_status);
		}

		if(isset($customer_order_details['water_charge']) && ($customer_order_details['water_charge'] > 0)){
			update_post_meta( $property_id, '_ubp_water_status', $connect_status);
		}

	  $order_post_arg = array(
	      'ID'           => $order_id,
	      'post_status'   => 'complete',
	  );
	  wp_update_post( $order_post_arg );



		$today = date("m/d/Y");

		$confirmation_details = array(
			'gas_deposit' =>  $cgas_deposit,
			'water_deposit' =>  $water_deposit,
			'electricity_deposit' =>  $celectric_deposit,
			'total_deposit' =>  $total_deposit,
			'confirmation_date' =>  $today,
		);

		update_post_meta( $order_id, '_ub_order_confirm_details', $confirmation_details);

		$display_message = 'Confirmation done';
		echo ub_action_message($display_message, 'success');
	}

?>
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
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
	wp_nonce_field( 'ub_order_confirm_action', 'ub_order_confirm_nonce' );
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
		$(document).on('click', '.close-corder-popup', function(e){
			e.preventDefault();
			$('.confirm-order-popup-overlay').delay(300).fadeOut('slow');
			$('.popup-overlay-bg').delay(300).fadeOut('slow');
		});

		$('.view-order-button').click(function() {
				//alert('Hello world');

				var order_type = $(this).data('order_type');
				var order_id = $(this).data('order_id');

				var dataPush = {
					order_type: order_type,
					order_id: order_id,
					action: 'order_confirm_popup_callback'
				}
				$.ajax({
					action: "order_confirm_popup_callback",
					type: "POST",
					dataType: "json",
					url: ub_ajax_object.ajaxurl,
					data: dataPush,
					beforeSend: function(){
						$('.confirm-order-popup-overlay').show();
						$('.popup-overlay-bg').show();
					},
					success: function(data){
						if(data.status == true){
								//alert(data.result);
								$('.confirm-order-popup-overlay').html(data.result);
						}else{
								//alert(data.result);
								$('.confirm-order-popup-overlay').delay(300).fadeOut('slow');
								$('.popup-overlay-bg').delay(300).fadeOut('slow');
						}
					}
				});
		});


		// order-deposit-field
	  // f-cgas-deposit
	  // f-celectric-deposit
	  // f-water-deposit
	  // total-deposit
		$('.order-deposit-field').live('change', function() {
				//alert("alive");
				var _thiswrap = $(this).closest('.corder-items');
				var gas_value = _thiswrap.find('#f-cgas-deposit').val();
				if(!gas_value){
					gas_value = 0;
				}

				var electric_value = _thiswrap.find('#f-celectric-deposit').val();
				if(!electric_value){
					electric_value = 0;
				}


				var water_value = _thiswrap.find('#f-water-deposit').val();
				if(!water_value){
					water_value = 0;
				}


				var total_value = parseFloat(gas_value) + parseFloat(electric_value) + parseFloat(water_value);
				total_value = '$' + total_value;
				_thiswrap.find('.total-deposit').html(total_value);
		});


	});
</script>

	<?php

	$output_result = ob_get_clean();
	return $output_result;
}

function order_confirmation_popup_modal() {
    ?>
		<div class="popup-overlay-bg">&nbsp;</div>
		<div class="confirm-order-popup-overlay">
				<div class="popup-loader"><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/loader.gif" alt=""/></div>
		</div>
		<?php
}
add_action( 'wp_footer', 'order_confirmation_popup_modal' );

function ub_order_confirmation_popup_content(){

}


add_action('wp_ajax_order_confirm_popup_callback', 'ub_order_confirm_popup_callback');
add_action('wp_ajax_nopriv_order_confirm_popup_callback', 'ub_order_confirm_popup_callback');
if (!function_exists('ub_order_confirm_popup_callback')) {
  function ub_order_confirm_popup_callback() {
		ob_start();

		$order_id = intval($_POST['order_id']);
    $order_type = esc_html($_POST['order_type']);

		$property_id = get_post_meta($order_id, '_ub_order_property_id', true);
		$order_details = get_post_meta($order_id, '_ub_order_details', true);
		$order_address = get_post_meta($order_id, '_ub_order_address', true);

		$apply_date = '';
		if(isset($order_details['apply_date'])){
			$apply_date = $order_details['apply_date'];
		}

		$gas_order = '';
		if(isset($order_details['gas_charge'])){
			$gas_order = $order_details['gas_charge'];
		}
		$electric_order = '';
		if(isset($order_details['electricity_charge'])){
			$electric_order = $order_details['electricity_charge'];
		}
		$water_order = '';
		if(isset($order_details['water_charge'])){
			$water_order = $order_details['water_charge'];
		}


		$order_type_display = '';
		$order_confirm_header = '';
		if($order_type == 'connect'){
			$order_type_display = 'Connect';
			$order_confirm_header = 'Review connection orders';
		}
		if($order_type == 'disconnect'){
			$order_type_display = 'Disconnect';
			$order_confirm_header = 'Review disconnection orders';
		}


		$client_name = get_the_title($property_id);

		$order_total_deposit = 0;
		?>
			<div class="confirm-order-popup-wrap">
				<div class="confirm-order-popup-inner">
					<div class="close-corder-popup"><span class="fa fa-remove"></span></div>
					<div class="corder-heading"><h2><?php echo $order_confirm_header; ?></h2></div>
					<div class="corder-content">
						<div class="section-client">
							<span>Client</span>
							<h3><?php echo $client_name; ?></h3>
						</div>
						<div class="corder-details">
								<div class="corder-address">
									<h4>Address</h4>
									<p><?php echo $order_address; ?></p>
								</div>
								<div class="corder-items">
									<h4><?php echo $order_type_display; ?> Date<br /> <?php echo $apply_date; ?></h4>

									<form action="" method="post" class="form-row utility-deposit">
										<table>
											<tr>
												<th width="150"><label for="f-cgas-deposit">Gas deposit</label></th>
												<td>
												<?php if(($gas_order > 0) && ($gas_order != 'n/a')){ ?>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<div class="input-group-text">$</div>
														</div>
														<input type="number" class="form-control order-deposit-field" id="f-cgas-deposit" name="cgas_deposit">
													</div>
												<?php
													}else{
														echo $gas_order;
														echo '<input type="hidden" class="form-control order-deposit-field" id="f-cgas-deposit" name="cgas_deposit" value="0">';
													}
												?>
												</td>
											</tr>
											<tr>
												<th width="150"><label for="f-celectric-deposit">Electric deposit</label></th>
												<td>
													<?php if(($electric_order > 0) && ($electric_order != 'n/a')){ ?>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<div class="input-group-text">$</div>
														</div>
														<input type="number" class="form-control order-deposit-field" id="f-celectric-deposit" name="celectric_deposit">
													</div>
													<?php
														}else{
															echo $electric_order;
															echo '<input type="hidden" class="form-control order-deposit-field" id="f-celectric-deposit" name="celectric_deposit" value="0">';
														}
													?>
												</td>
											</tr>
											<tr>
												<th width="150"><label for="f-water-deposit">Water deposit</label></th>
												<td>
													<?php if(($water_order > 0) && ($water_order != 'n/a')){ ?>
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<div class="input-group-text">$</div>
														</div>
														<input type="number" class="form-control order-deposit-field" id="f-water-deposit" name="water_deposit">
													</div>
													<?php
														}else{
															echo $water_order;
															echo '<input type="hidden" class="form-control order-deposit-field" id="f-water-deposit" name="water_deposit" value="0">';
														}
													?>
												</td>
											</tr>
											<tr>
												<th>Total</th>
												<td><div class="total-deposit"><?php echo $order_total_deposit; ?></div></td>
											</tr>
											<tr>
												<th></th>
												<td>
													<input type="hidden" name="order_id" value="<?php echo intval($order_id); ?>" />
													<input type="hidden" name="order_type" value="<?php echo esc_attr($order_type); ?>" />
													<input type="hidden" name="property_id" value="<?php echo intval($property_id); ?>" />
													<input type="submit" class="btn btn-primary" name="order_confirm_submit" value="Confirm" />
												</td>
											</tr>
										</table>
									</form>
								</div>
						</div>
					</div>
			</div><!-- confirm-order-popup-inner -->
		 </div><!-- confirm-order-popup-wrap -->
		<?php

		$output_result = ob_get_clean();


		$feedback = array("result" => $output_result, "status" => true);

		echo json_encode($feedback);
		die();
	}
}
