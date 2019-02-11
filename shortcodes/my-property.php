<?php
//View Properties
add_shortcode('ub_my_property', 'ub_my_property_shortcode');
function ub_my_property_shortcode($atts){
	extract(shortcode_atts(array(
    'count' => '-1',
  ), $atts));
  ob_start();

?>
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
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

	<table class="table table-bordered">
		<tr>
			<td colspan="7">My Properties</td>
		</tr>
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
		//$property_title = get_the_title();
		?>
		<tr>
			<td><?php echo get_post_meta($property_id, '_ubp_street_address', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_city', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_zipcode', true); ?></td>
			<td><?php echo get_post_meta($property_id, '_ubp_state', true); ?></td>
			<td><?php if(get_post_meta($property_id, '_ubp_gas', true) == 'on'){
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
			}else{
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
			}
			?></td>
			<td><?php if(get_post_meta($property_id, '_ubp_water', true) == 'on'){
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
			}else{
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
			}
			?></td>
			<td><?php if(get_post_meta($property_id, '_ubp_electricity', true) == 'on'){
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/available-connected.png" alt=""/>';
			}else{
				echo '<img src="'.UBUMANAGER_FOLDER_URL.'/images/not-available.png" alt=""/>';
			}
			?></td>
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
