<?php

//Add Property
add_shortcode('ub_register_property', 'ub_register_property_shortcode');
function ub_register_property_shortcode(){
	ob_start();

	$current_user_id = get_current_user_id();

	if(isset($_POST['add_property'])){
		$property_data = array(
			'post_type'      => 'ub_property',
			'post_title'     => sanitize_text_field( $_POST['owner_name']  ),
			'post_author'   => $current_user_id,
			'post_status'    => 'publish'
		);

 		$property_id = wp_insert_post( $property_data );


		update_post_meta( $property_id, '_ubp_owner_phone_number', sanitize_text_field( $_POST['phone']  ) );
		if(isset($_POST['property_manager'] )){
			update_post_meta( $property_id, '_ubp_property_manager', sanitize_text_field( $_POST['property_manager']  ) );
		}
		update_post_meta( $property_id, '_ubp_street_address', sanitize_text_field( $_POST['address'] ) );
		update_post_meta( $property_id, '_ubp_city', sanitize_text_field( $_POST['city'] ) );
		update_post_meta( $property_id, '_ubp_state', sanitize_text_field( $_POST['state'] ) );
		update_post_meta( $property_id, '_ubp_zipcode', sanitize_text_field( $_POST['zipcode'] ) );
		if(isset($_POST['utility_gas'] )){
			update_post_meta( $property_id, '_ubp_gas', sanitize_text_field( $_POST['utility_gas'] ) );
		}
		update_post_meta( $property_id, '_ubp_gas_provider', sanitize_text_field( $_POST['gas_provider'] ) );
		update_post_meta( $property_id, '_ubp_gas_utility_name', sanitize_text_field( $_POST['gas_utilities'] ) );
		update_post_meta( $property_id, '_ubp_gas_account_number', sanitize_text_field( $_POST['gas_confirmation'] ) );
		if(isset($_POST['utility_water'] )){
			update_post_meta( $property_id, '_ubp_water', sanitize_text_field( $_POST['utility_water'] ) );
		}
		update_post_meta( $property_id, '_ubp_water_provider', sanitize_text_field( $_POST['water_provider'] ) );
		update_post_meta( $property_id, '_ubp_water_utility_name', sanitize_text_field( $_POST['water_utilities'] ) );
		update_post_meta( $property_id, '_ubp_water_account_number', sanitize_text_field( $_POST['water_confirmation'] ) );
		if(isset($_POST['utility_water'] )){
			update_post_meta( $property_id, '_ubp_electricity', sanitize_text_field( $_POST['utility_electricity'] ) );
		}
		update_post_meta( $property_id, '_ubp_electricity_provider', sanitize_text_field( $_POST['electricity_provider'] ) );
		update_post_meta( $property_id, '_ubp_electricity_utility_name', sanitize_text_field( $_POST['electricity_utilities'] ) );
		update_post_meta( $property_id, '_ubp_electricity_account_number', sanitize_text_field( $_POST['electricity_confirmation'] ) );

	}
	?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
	<form action="" method="POST">
	<div class="row">
		<div class="col-md-3">
			<h4>New Property</h4>
			<div class="form-group">
				<label for="owner_name">Owner's Name</label>
				<input type="text" class="form-control" id="owner_name" name="owner_name" placeholder="">
			</div>
			<div class="form-group">
				<label for="phone">Owner's Phone Number</label>
				<input type="text" class="form-control" id="phone" name="phone" placeholder="">
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" name="property_manager" value="on"> Same as Property Manager
				</label>
			</div>
			<div class="form-group">
				<label for="address">Street Address</label>
				<input type="text" class="form-control" id="address" name="address" placeholder="">
			</div>
			<div class="form-group">
				<label for="city">City</label>
				<input type="text" class="form-control" id="city" name="city" placeholder="">
			</div>
			<div class="form-group form-row">
				<div class="col-md-6">
					<label for="state">State</label>
					<select class="form-control" name="state">
						<?php
							global $states_full_key;
							foreach($states_full_key as $key => $value){
								echo '<option value="'. $key .'">'. $value. '</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-6">
					<label for="zipcode">Zipcode</label>
					<input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="">
				</div>
			</div>
		</div>
		<div class="col-md-9">
			<div class="row">
				<h4>Utilities</h4>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="checkbox">
						<label>
						  <input type="checkbox" value="on" name="utility_gas"> Gas
						</label>
					</div>
					<div class="form-group">
						<label for="gprovider">Provider</label>
						<input type="text" class="form-control" id="gprovider" name="gas_provider" placeholder="">
					</div>
					<div class="form-group">
						<label for="gutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="gutilities" name="gas_utilities" placeholder="">
					</div>
					<div class="form-group">
						<label for="gconfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="gconfirmation" name="gas_confirmation" placeholder="">
					</div>
					<div class="form-group">
						<input type="file" id="">
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
						  <input type="checkbox" value="on" name="utility_water"> Water
						</label>
					</div>
					<div class="form-group">
						<label for="wprovider">Provider</label>
						<input type="text" class="form-control" id="wprovider" name="water_provider" placeholder="">
					</div>
					<div class="form-group">
						<label for="wgutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="wgutilities" name="water_utilities" placeholder="">
					</div>
					<div class="form-group">
						<label for="wconfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="wconfirmation" name="water_confirmation" placeholder="">
					</div>
					<div class="form-group">
						<input type="file" id="">
					</div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
						  <input type="checkbox" value="on" name="utility_electricity"> Electricity
						</label>
					</div>
					<div class="form-group">
						<label for="eprovider">Provider</label>
						<input type="text" class="form-control" id="eprovider" name="electricity_provider" placeholder="">
					</div>
					<div class="form-group">
						<label for="egutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="egutilities" name="electricity_utilities" placeholder="">
					</div>
					<div class="form-group">
						<label for="econfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="econfirmation" name="electricity_confirmation" placeholder="">
					</div>
					<div class="form-group">
						<input type="file" id="">
					</div>
				</div>
			</div>
			<div class="row">
				<input type="submit" name="add_property" class="btn btn-primary" value="Create Property" />
			</div>

		</div>
	</div>
	</form>
	<div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->

<?php
	$output_value = ob_get_clean();
	return $output_value;
}
