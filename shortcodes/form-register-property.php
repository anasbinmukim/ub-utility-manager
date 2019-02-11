<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Add Property
add_shortcode('ub_register_property', 'ub_register_property_shortcode');
function ub_register_property_shortcode(){
	ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to access this page.';
			echo ub_action_message($display_message, 'info');
			return;
	}

	$property_author_id = get_current_user_id();
	$current_user_info = get_userdata($property_author_id);

	if(ub_get_current_user_role() == 'employee'){
		$property_author_id = get_user_meta($property_author_id, '_ub_property_manager_id', true);
		$current_user_info = get_userdata($property_author_id);
	}

	$current_page_url = get_permalink();
	$form_submitted = true;

	$property_submission_type = 'new';
	$update_property_id = 0;
	$owner_name = '';
	$phone = '';
	$same_as_property_manager = '';
	$address = '';
	$city = '';
	$state = '';
	$zipcode = '';
	$utility_gas = '';
	$gas_provider = '';
	$gas_utilities = '';
	$gas_confirmation = '';
	$utility_water = '';
	$water_provider = '';
	$water_utilities = '';
	$water_confirmation = '';
	$utility_electricity = '';
	$electricity_provider = '';
	$electricity_utilities = '';
	$electricity_confirmation = '';

	$gas_pdf_file = '';
	$water_pdf_file = '';
	$electricity_pdf_file = '';

	if (isset($_POST['ub_property_nonce']) && (! isset( $_POST['ub_property_nonce'] ) || ! wp_verify_nonce( $_POST['ub_property_nonce'], 'ub_property_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['submit_add_property'])){

		if(!isset($_POST['utility_gas'])){
			$form_submitted = true;
		}elseif(isset($_POST['utility_gas']) && (($_POST['gas_provider'] != '') && ($_POST['gas_utilities'] != '') && ($_POST['gas_confirmation'] != ''))){
			$form_submitted = true;
		}else{
			$display_message = 'All gas fields required!';
			echo ub_action_message($display_message, 'danger');
			$form_submitted = false;
		}

		if(!isset($_POST['utility_water'])){
			$form_submitted = true;
		}elseif(isset($_POST['utility_water']) && (($_POST['water_provider'] != '') && ($_POST['water_utilities'] != '') && ($_POST['water_confirmation'] != ''))){
			$form_submitted = true;
		}else{
			$display_message = 'All water fields required!';
			echo ub_action_message($display_message, 'danger');
			$form_submitted = false;
		}

		if(!isset($_POST['utility_electricity'])){
			$form_submitted = true;
		}elseif(isset($_POST['utility_electricity']) && (($_POST['electricity_provider'] != '') && ($_POST['electricity_utilities'] != '') && ($_POST['electricity_confirmation'] != ''))){
			$form_submitted = true;
		}else{
			$display_message = 'All electricity fields required!';
			echo ub_action_message($display_message, 'danger');
			$form_submitted = false;
		}

		if(isset($_POST['owner_name']) && ($_POST['owner_name'] == '')){
			$display_message = 'Please fill the required fields';
			echo ub_action_message($display_message, 'danger');
			$form_submitted = false;
		}

		$owner_name = sanitize_text_field( $_POST['owner_name']  );
		$phone = sanitize_text_field( $_POST['phone']  );
		$same_as_property_manager = '';
		if(isset($_POST['same_as_property_manager'] )){
			$same_as_property_manager = sanitize_text_field( $_POST['same_as_property_manager']  );
		}
		$address = sanitize_text_field( $_POST['address'] );
		$city = sanitize_text_field( $_POST['city'] );
		$state = sanitize_text_field( $_POST['state'] );
		$zipcode = sanitize_text_field( $_POST['zipcode'] );
		$utility_gas = '';
		if(isset($_POST['utility_gas'] )){
			$utility_gas = sanitize_text_field( $_POST['utility_gas'] );
		}
		$gas_provider = sanitize_text_field( $_POST['gas_provider'] );
		$gas_utilities = sanitize_text_field( $_POST['gas_utilities'] );
		$gas_confirmation = sanitize_text_field( $_POST['gas_confirmation'] );
		if(isset($_POST['gas_pdf_file_url'] )){
			$gas_pdf_file = esc_url($_POST['gas_pdf_file_url']);
		}

		$utility_water = '';
		if(isset($_POST['utility_water'] )){
			$utility_water = sanitize_text_field( $_POST['utility_water'] );
		}
		$water_provider = sanitize_text_field( $_POST['water_provider'] );
		$water_utilities = sanitize_text_field( $_POST['water_utilities'] );
		$water_confirmation = sanitize_text_field( $_POST['water_confirmation'] );
		if(isset($_POST['water_pdf_file_url'] )){
			$water_pdf_file = esc_url($_POST['water_pdf_file_url']);
		}


		$utility_electricity = '';
		if(isset($_POST['utility_electricity'] )){
			$utility_electricity = sanitize_text_field( $_POST['utility_electricity'] );
		}
		$electricity_provider = sanitize_text_field( $_POST['electricity_provider'] );
		$electricity_utilities = sanitize_text_field( $_POST['electricity_utilities'] );
		$electricity_confirmation = sanitize_text_field( $_POST['electricity_confirmation'] );
		if(isset($_POST['electricity_pdf_file_url'] )){
			$electricity_pdf_file = esc_url($_POST['electricity_pdf_file_url']);
		}

		$property_submission_type = sanitize_text_field( $_POST['property_submission_type'] );
		$update_property_id = intval( $_POST['update_property_id'] );



		if($form_submitted){
			if(($property_submission_type == 'new')){
				$property_data = array(
					'post_type'      => 'ub_property',
					'post_title'     => sanitize_text_field( $owner_name  ),
					'post_author'   => $property_author_id,
					'post_status'    => 'publish'
				);
		 		$property_id = wp_insert_post( $property_data );
			}

			if(($property_submission_type == 'update')){
				$property_id = $update_property_id;
				$property_update_data = array(
						'ID'           => $property_id,
						'post_title'   => sanitize_text_field( $owner_name  )
				);
				$property_id = wp_update_post( $property_update_data );
			}

			update_post_meta( $property_id, '_ubp_owner_phone_number', $phone);
			update_post_meta( $property_id, '_ubp_same_as_property_manager', $same_as_property_manager );
			update_post_meta( $property_id, '_ubp_street_address', $address);
			update_post_meta( $property_id, '_ubp_city', $city);
			update_post_meta( $property_id, '_ubp_state', $state);
			update_post_meta( $property_id, '_ubp_zipcode', $zipcode);
			update_post_meta( $property_id, '_ubp_gas', $utility_gas);
			update_post_meta( $property_id, '_ubp_gas_provider', $gas_provider);
			update_post_meta( $property_id, '_ubp_gas_utility_name', $gas_utilities);
			update_post_meta( $property_id, '_ubp_gas_account_number', $gas_confirmation);
			update_post_meta( $property_id, '_ubp_gas_pdf', $gas_pdf_file);
			update_post_meta( $property_id, '_ubp_water', $utility_water);
			update_post_meta( $property_id, '_ubp_water_provider', $water_provider);
			update_post_meta( $property_id, '_ubp_water_utility_name', $water_utilities);
			update_post_meta( $property_id, '_ubp_water_account_number', $water_confirmation);
			update_post_meta( $property_id, '_ubp_water_pdf', $water_pdf_file);
			update_post_meta( $property_id, '_ubp_electricity', $utility_electricity);
			update_post_meta( $property_id, '_ubp_electricity_provider', $electricity_provider);
			update_post_meta( $property_id, '_ubp_electricity_utility_name', $electricity_utilities);
			update_post_meta( $property_id, '_ubp_electricity_account_number', $electricity_confirmation);
			update_post_meta( $property_id, '_ubp_electricity_pdf', $electricity_pdf_file);

			if(($property_submission_type == 'new')){
					$display_message = 'Added successful';
					echo ub_action_message($display_message, 'success');
					$params_url = array('added-success' => 'yes');
					$my_property_page_url = get_permalink(get_option('ubpid_view_property'));
					$redirect_page_url = add_query_arg( $params_url, $my_property_page_url);
					echo '<script type="text/javascript">window.location = "'.$redirect_page_url.'"</script>';
			}

			if(($property_submission_type == 'update')){
					$display_message = 'Update successful';
					echo ub_action_message($display_message, 'success');
					$params_url = array('edit_property' => $property_id, 'update-success' => 'yes');
					$redirect_page_url = add_query_arg( $params_url, $current_page_url);
					echo '<script type="text/javascript">window.location = "'.$redirect_page_url.'"</script>';
			}

		}
	}elseif(isset($_GET['edit_property'])){
		$property_submission_type = 'update';
		$property_id = intval($_GET['edit_property']);
		$owner_name = get_the_title($property_id);
		$phone = get_post_meta( $property_id, '_ubp_owner_phone_number', true);
		$same_as_property_manager = get_post_meta( $property_id, '_ubp_same_as_property_manager', true );
		$address = get_post_meta( $property_id, '_ubp_street_address', true);
		$city = get_post_meta( $property_id, '_ubp_city', true);
		$state = get_post_meta( $property_id, '_ubp_state', true);
		$zipcode = get_post_meta( $property_id, '_ubp_zipcode', true);
		$utility_gas = get_post_meta( $property_id, '_ubp_gas', true);
		$gas_provider = get_post_meta( $property_id, '_ubp_gas_provider', true);
		$gas_utilities = get_post_meta( $property_id, '_ubp_gas_utility_name', true);
		$gas_confirmation = get_post_meta( $property_id, '_ubp_gas_account_number', true);
		$gas_pdf_file = get_post_meta( $property_id, '_ubp_gas_pdf', true);
		$utility_water = get_post_meta( $property_id, '_ubp_water', true);
		$water_provider = get_post_meta( $property_id, '_ubp_water_provider', true);
		$water_utilities = get_post_meta( $property_id, '_ubp_water_utility_name', true);
		$water_confirmation = get_post_meta( $property_id, '_ubp_water_account_number', true);
		$water_pdf_file = get_post_meta( $property_id, '_ubp_water_pdf', true);
		$utility_electricity = 	get_post_meta( $property_id, '_ubp_electricity', true);
		$electricity_provider = get_post_meta( $property_id, '_ubp_electricity_provider', true);
		$electricity_utilities = get_post_meta( $property_id, '_ubp_electricity_utility_name', true);
		$electricity_confirmation = get_post_meta( $property_id, '_ubp_electricity_account_number', true);
		$electricity_pdf_file = get_post_meta( $property_id, '_ubp_electricity_pdf', true);

	}
	?>
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
	<form action="<?php echo esc_url($current_page_url); ?>" method="POST" id="registration-form">
	<div class="row">
		<div class="col-md-3">
			<h4>New Property</h4>
			<div class="form-group">
				<label for="owner_name">Owner's Name</label>
				<input type="text" class="form-control required" id="owner_name" name="owner_name" placeholder="" value="<?php echo esc_html($owner_name); ?>">
			</div>
			<div class="form-group">
				<label for="phone">Owner's Phone Number</label>
				<input type="text" class="form-control required" id="phone" name="phone" placeholder="" value="<?php echo esc_html($phone); ?>">
			</div>
			<div class="checkbox">
				<label>
					<input type="checkbox" <?php checked( 'on', $same_as_property_manager ); ?> name="same_as_property_manager" id="same_as_property_manager" value="on"> Same as Property Manager
				</label>
			</div>
			<div class="form-group">
				<label for="address">Street Address</label>
				<input type="text" class="form-control" id="address" name="address" placeholder="" value="<?php echo esc_html($address); ?>">
			</div>
			<div class="form-group">
				<label for="city">City</label>
				<input type="text" class="form-control" id="city" name="city" placeholder="" value="<?php echo esc_html($city); ?>">
			</div>
			<div class="form-group form-row">
				<div class="col-md-6">
					<label for="state">State</label>
					<select class="form-control" name="state">
						<?php
							global $states_full_key;
							foreach($states_full_key as $key => $value){
								echo '<option '.selected( $key, $state, false).' value="'. $key .'">'. $value. '</option>';
							}
						?>
					</select>
				</div>
				<div class="col-md-6">
					<label for="zipcode">Zipcode</label>
					<input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="" value="<?php echo esc_html($zipcode); ?>">
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
						  <input type="checkbox" <?php checked( 'on', $utility_gas ); ?> value="on" name="utility_gas" id="utility_gas"> Gas
						</label>
					</div>
					<div class="form-group">
						<label for="gprovider">Provider</label>
						<input type="text" class="form-control" id="gprovider" name="gas_provider" placeholder="" value="<?php echo esc_html($gas_provider); ?>">
					</div>
					<div class="form-group">
						<label for="gutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="gutilities" name="gas_utilities" placeholder="" value="<?php echo esc_html($gas_utilities); ?>">
					</div>
					<div class="form-group">
						<label for="gconfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="gconfirmation" name="gas_confirmation" placeholder="" value="<?php echo esc_html($gas_confirmation); ?>">
					</div>
					<div class="form-group utility-file-wrap form-row">
		            <div class="existing_file">
									<?php if($gas_pdf_file != ''){ ?>
										<a href="<?php echo esc_url($gas_pdf_file); ?>" target="_blank"><img class="pdf_icon" src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/pdf-icon.png" alt="" /></a>
									<?php } ?>
		             </div>
		             <div class="input-group- upload-file-btn-wrap">
	                  <span class="input-group-btn">
	                      <span class="btn-file">
														<button class="btn btn-default btn-upload-file-label">
					                      Upload PDF
					                  </button>
	                          <input type="file" name="file_upload" class="file_upload"  accept=".pdf">
	                      </span>
	                  </span>
	                  <span class="btn-upload-file"></span>
	              </div>
	              <span class="status"></span>
		            <input type="hidden" class="utility-file-url" name="gas_pdf_file_url" value="<?php echo esc_url($gas_pdf_file); ?>">
		      </div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
						  <input type="checkbox" <?php checked( 'on', $utility_water ); ?> value="on" name="utility_water"> Water
						</label>
					</div>
					<div class="form-group">
						<label for="wprovider">Provider</label>
						<input type="text" class="form-control" id="wprovider" name="water_provider" placeholder="" value="<?php echo esc_html($water_provider); ?>">
					</div>
					<div class="form-group">
						<label for="wgutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="wgutilities" name="water_utilities" placeholder="" value="<?php echo esc_html($water_utilities); ?>">
					</div>
					<div class="form-group">
						<label for="wconfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="wconfirmation" name="water_confirmation" placeholder="" value="<?php echo esc_html($water_confirmation); ?>">
					</div>
					<div class="form-group utility-file-wrap form-row">
		            <div class="existing_file">
									<?php if($water_pdf_file != ''){ ?>
										<a href="<?php echo esc_url($water_pdf_file); ?>" target="_blank"><img class="pdf_icon" src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/pdf-icon.png" alt="" /></a>
									<?php } ?>
		             </div>
		             <div class="input-group- upload-file-btn-wrap">
	                  <span class="input-group-btn">
	                      <span class="btn-file">
														<button class="btn btn-default btn-upload-file-label">
					                      Upload PDF
					                  </button>
	                          <input type="file" name="file_upload" class="file_upload"  accept=".pdf">
	                      </span>
	                  </span>
	                  <span class="btn-upload-file"></span>
	              </div>
	              <span class="status"></span>
		            <input type="hidden" class="utility-file-url" name="water_pdf_file_url" value="<?php echo esc_url($water_pdf_file); ?>">
		      </div>
				</div>
				<div class="col-md-4">
					<div class="checkbox">
						<label>
						  <input type="checkbox" <?php checked( 'on', $utility_electricity ); ?> value="on" name="utility_electricity"> Electricity
						</label>
					</div>
					<div class="form-group">
						<label for="eprovider">Provider</label>
						<input type="text" class="form-control" id="eprovider" name="electricity_provider" placeholder="" value="<?php echo esc_html($electricity_provider); ?>">
					</div>
					<div class="form-group">
						<label for="egutilities">Name on Utilities</label>
						<input type="text" class="form-control" id="egutilities" name="electricity_utilities" placeholder="" value="<?php echo esc_html($electricity_utilities); ?>">
					</div>
					<div class="form-group">
						<label for="econfirmation">Confirmation/Account Number</label>
						<input type="text" class="form-control" id="econfirmation" name="electricity_confirmation" placeholder="" value="<?php echo esc_html($electricity_confirmation); ?>">
					</div>
					<div class="form-group utility-file-wrap form-row">
		            <div class="existing_file">
									<?php if($electricity_pdf_file != ''){ ?>
										<a href="<?php echo esc_url($electricity_pdf_file); ?>" target="_blank"><img class="pdf_icon" src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/pdf-icon.png" alt="" /></a>
									<?php } ?>
		             </div>
		             <div class="input-group- upload-file-btn-wrap">
	                  <span class="input-group-btn">
	                      <span class="btn-file">
														<button class="btn btn-default btn-upload-file-label">
					                      Upload PDF
					                  </button>
	                          <input type="file" name="file_upload" class="file_upload"  accept=".pdf">
	                      </span>
	                  </span>
	                  <span class="btn-upload-file"></span>
	              </div>
	              <span class="status"></span>
		            <input type="hidden" class="utility-file-url" name="electricity_pdf_file_url" value="<?php echo esc_url($electricity_pdf_file); ?>">
		      </div>
				</div>
			</div>
			<div class="row">
				<?php
				$update_property_id = 0;
				$submit_button_label = 'Create Property';
				if(isset($_GET['edit_property'])){
					$update_property_id = intval($_GET['edit_property']);
					$submit_button_label = 'Update Property';
				}else{
					$submit_button_label = 'Create Property';
				}

				$current_owner_name = $current_user_info->last_name .  " " . $current_user_info->first_name;

				$current_owner_phone = get_the_author_meta( '_ub_phone', $property_author_id );
				?>
				<input type="hidden" data-owner_name="<?php echo esc_attr($current_owner_name); ?>" data-phone="<?php echo esc_attr($current_owner_phone); ?>" name="update_property_id" id="update_property_id" value="<?php echo intval($update_property_id); ?>" />
				<input type="hidden" name="property_submission_type" value="<?php echo esc_attr($property_submission_type); ?>" />
				<?php wp_nonce_field('file_upload', 'file_upload_nonce'); ?>
				<?php wp_nonce_field( 'ub_property_action', 'ub_property_nonce' ); ?>
				<input type="submit" name="submit_add_property" class="btn btn-primary" value="<?php echo esc_attr($submit_button_label); ?>" />
			</div>
		</div>
	</div>
	</form>
	<div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->

<script>
	jQuery(document).ready(function() {
		jQuery("#registration-form").validate();
		jQuery('#same_as_property_manager').click(function () {
				var owner_name = jQuery('#update_property_id').data('owner_name');
				var phone = jQuery('#update_property_id').data('phone');
				if (jQuery(this).prop('checked')) {
					var check_value = jQuery(this).val();
					if(check_value == 'on'){
							jQuery('#owner_name').val(owner_name);
							jQuery('#phone').val(phone);
					}else{
							jQuery('#owner_name').val('');
							jQuery('#phone').val('');
					}
				}
		});
		//update_property_id
	});
</script>
<script>
( function( $ ) {
      $(document).on('change', '.file_upload', function() {
          var $this = $(this).closest("div.utility-file-wrap");
          $this.find('.btn-upload-file').addClass( "btn-success" );
          $this.find('.btn-upload-file').trigger('click');
      });
      $(document).on('click', '.btn-upload-file', function(e){
			e.preventDefault();
      var val_nonce = $('#file_upload_nonce').val();
			//var $this = $(this),
        var $this = $(this).closest("div.utility-file-wrap"),
				nonce = val_nonce,
				images_wrap = $this.find('.existing_file'),
        set_url_value = $this.find('.utility-file-url'),
				status = $this.find('.status'),
				formdata = false;

			if ( $this.find('.file_upload').val() == '' ) {
				alert('Please select a pdf file to upload');
				return;
			}

			status.fadeIn().text('Loading...')

			if (window.FormData) {
				formdata = new FormData();
			}
			var files_data = $this.find('.file_upload');

			$.each($(files_data), function(i, obj) {
				$.each(obj.files, function(j, file) {
					formdata.append('files[' + j + ']', file);
				})
			});
			// our AJAX identifier
			formdata.append('action', 'ubfl_file_upload');

			formdata.append('nonce', nonce);

      var pdf_icon = '<?php echo UBUMANAGER_FOLDER_URL; ?>/images/pdf-icon.png';

			$.ajax({
				url: ub_ajax_object.ajaxurl,
				type: 'POST',
				data: formdata,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(data) {
					if (data.status) {
            images_wrap.append('<a href="'+data.file_url+'" target="_blank"><img class="pdf_icon" src="'+pdf_icon+'" alt="" /></a>');
            set_url_value.val(data.file_url);
						status.fadeIn().text('File uploaded').fadeOut(2000);
					} else {
						status.fadeIn().text(data.message);
					}
				}
			});

		});
} )( jQuery );
</script>
<?php
	$output_value = ob_get_clean();
	return $output_value;
}
