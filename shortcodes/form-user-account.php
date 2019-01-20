<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_utility_manager_account_form_shortcode_func( $atts ) {
  extract(shortcode_atts(array(
    'form_type' => 'new',
		'account_id' => 0
  ), $atts));

	$current_page_url = get_permalink();

	if(is_user_logged_in()){
			$account_id = get_current_user_id();
			$account_info = get_userdata($account_id);
			$submission_type = 'view';
	}elseif(isset($_GET['account-signup']) && (!is_user_logged_in())){
		$submission_type = 'new';
		$params_url = array('account-signup' => 'dosignup');
		$current_page_url = esc_url( add_query_arg( $params_url, $current_page_url) );
	}else{
			$submission_type = 'view';
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			//return;
	}

	if(isset($_GET['account-edit'])){
			$submission_type = 'update';
			$params_url = array('account-edit' => 'doedit');
			$current_page_url = esc_url( add_query_arg( $params_url, $current_page_url) );
	}







	ob_start();
	$accounttype = 'property_manager';
	if (isset($_POST['ub_account_nonce']) && (! isset( $_POST['ub_account_nonce'] ) || ! wp_verify_nonce( $_POST['ub_account_nonce'], 'ub_account_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['submit_user_account'])){
		$meta_data_update = true;
		$submission_type = sanitize_text_field($_POST['submission_type']);
		$accounttype = sanitize_text_field($_POST['accounttype']);
		$fname = sanitize_text_field($_POST['fname']);
		$lname = sanitize_text_field($_POST['lname']);
		$phone = sanitize_text_field($_POST['phone']);
		$email = sanitize_text_field($_POST['email']);
		$company_name = sanitize_text_field($_POST['company_name']);
		$tax_id = sanitize_text_field($_POST['tax_id']);
		$brithdate = sanitize_text_field($_POST['brithdate']);
		$drlicense = sanitize_text_field($_POST['drlicense']);
		$comments = sanitize_textarea_field($_POST['comments']);
		$street = sanitize_text_field($_POST['street']);
		$city = sanitize_text_field($_POST['city']);
		$state = sanitize_text_field($_POST['state']);
		$zip = sanitize_text_field($_POST['zip']);

		$cardname_arr = $_POST['cardname'];
		$cardnum_arr = $_POST['cardnum'];
		$expdate_arr = $_POST['expdate'];
		$ccv_arr = $_POST['ccv'];

		$card_info_arr = array();
		if(count($cardname_arr) > 0){
			foreach ($cardname_arr as $key_card => $cardname) {
					if((isset($cardname_arr[$key_card]) && ($cardname_arr[$key_card] != '')) && (isset($cardnum_arr[$key_card]) && ($cardnum_arr[$key_card] != '')) && (isset($expdate_arr[$key_card]) && ($expdate_arr[$key_card] != '')) && (isset($ccv_arr[$key_card]) && ($ccv_arr[$key_card] != '')) ){
						$card_info_arr[] = array(
							'cardname' => (isset($cardname_arr[$key_card]) ? esc_html($cardname_arr[$key_card]) : ''),
							'cardnum' => (isset($cardnum_arr[$key_card]) ? esc_html($cardnum_arr[$key_card]) : ''),
							'expdate' => (isset($expdate_arr[$key_card]) ? esc_html($expdate_arr[$key_card]) : ''),
							'ccv' => (isset($ccv_arr[$key_card]) ? esc_html($ccv_arr[$key_card]) : ''),
						);
					}
			}
		}

		$ub_cardinfo = $card_info_arr;


		if (($submission_type == 'new') && ( !is_email($email) || email_exists($email) ) ){
				$display_message = 'Email is not valid or already exist. Please try with different email!';
				echo ub_action_message($display_message, 'danger');
				$meta_data_update = false;
		}


		//Check if update existing email address
		if (($submission_type == 'update') && ($email != $account_info->user_email) && ( !is_email($email) || email_exists($email) ) ){
				$display_message = 'Email is not valid or already exist. Please try with different email!';
				echo ub_action_message($display_message, 'danger');
				$meta_data_update = false;
		}



		if(($submission_type == 'new') && ($meta_data_update)){
				$user_pass = wp_generate_password( 10, true, true );
				$userdata = array(
					'user_pass' => $user_pass,
					'user_login' => $email,
					'user_email' => $email,
					'first_name' => $fname,
					'last_name' => $lname,
					'description' => $comments,
					'role' => $accounttype,
				);
				$account_id = wp_insert_user( $userdata );
				$display_message = 'Successfully added! Please login to edit your account.';
				echo ub_action_message($display_message, 'success');
		}elseif(($submission_type == 'update')  && ($meta_data_update)){
			$args_update = array(
				'ID'         => $account_id,
				'user_email' => $email,
				'first_name' => $fname,
				'last_name' => $lname,
				'description' => $comments,
				'role' => $accounttype
			);
			wp_update_user( $args_update );
			$meta_data_update = TRUE;
			$display_message = 'Successfully updated!';
			echo ub_action_message($display_message, 'success');
		}

		if($meta_data_update){
				update_user_meta($account_id, '_ub_phone', $phone);
				update_user_meta($account_id, '_ub_company_name', $company_name);
				update_user_meta($account_id, '_ub_tax_id', $tax_id);
				update_user_meta($account_id, '_ub_dateob', $brithdate);
				update_user_meta($account_id, '_ub_driver_license', $drlicense);
				update_user_meta($account_id, '_ub_street', $street);
				update_user_meta($account_id, '_ub_city', $city);
				update_user_meta($account_id, '_ub_state', $state);
				update_user_meta($account_id, '_ub_zipcode', $zip);

				if(count($card_info_arr) > 0){
					update_user_meta( $account_id, '_ub_cardinfo', $card_info_arr );
				}
		}

		if(($submission_type == 'new') || ($submission_type == 'update')){
				echo '<script type="text/javascript">window.location = "'.$current_page_url.'"</script>';
		}

	}elseif(is_user_logged_in()){
		$accounttype = ub_get_current_user_role();
		$fname = $account_info->first_name;
		$lname = $account_info->last_name;
		$phone = get_the_author_meta( '_ub_phone', $account_id );
		$email = $account_info->user_email;
		$company_name = get_the_author_meta( '_ub_company_name', $account_id );
		$tax_id = get_the_author_meta( '_ub_tax_id', $account_id );
		$brithdate = get_the_author_meta( '_ub_dateob', $account_id );
		$drlicense = get_the_author_meta( '_ub_driver_license', $account_id );
		$comments = $account_info->description;
		$street = get_the_author_meta( '_ub_street', $account_id );
		$city = get_the_author_meta( '_ub_city', $account_id );
		$state = get_the_author_meta( '_ub_state', $account_id );
		$zip = get_the_author_meta( '_ub_zipcode', $account_id );
		$ub_cardinfo = get_the_author_meta( '_ub_cardinfo', $account_id );
	}else{
		$accounttype = 'property_manager';
		$fname = '';
		$lname = '';
		$phone = '';
		$email = '';
		$company_name = '';
		$tax_id = '';
		$brithdate = '';
		$drlicense = '';
		$comments = '';
		$street = '';
		$city = '';
		$state = '';
		$zip = '';
		$ub_cardinfo = '';
	}
	?>

	<div class="ub-form-wrap">
		<div class="ub-form-content">
				<div class="ub-form-header">
					<?php if($submission_type == 'new'){ ?>
						<h2>New Account</h2>
					<?php } ?>
					<?php if($submission_type == 'update'){ ?>
						<h2>Edit Account</h2>
					<?php } ?>
					<?php if($submission_type == 'view'){ ?>
						<h2>Account Information</h2>
					<?php } ?>
				</div><!-- ub-form-header -->
				<form id="account-data-form" class="form-horizontal" action="<?php echo esc_url($current_page_url); ?>" method="post">
					<div class="row">
						<div class="col-6 col-md-6">
							<h4>Personal Information:</h4>
								<div class="form-group  form-row">
								<label for="accounttype" class="col-md-12">Account Type</label>
								<div class="col-md-12">
									<label class="radio-inline">
										<input class="property-type-field required" type="radio" <?php checked( 'property_manager', $accounttype ); ?> name="accounttype" id="propertymanager" value="property_manager"> Property Manager
									</label>
									<label class="radio-inline">
										<input class="property-type-field required" type="radio" <?php checked( 'homeowner', $accounttype ); ?> name="accounttype" id="homeowner" value="homeowner"> Homeowner
									</label>
								</div>
								</div>
								<div class="form-group form-row">
								<label for="fname" class="col-md-6">First Name:</label>
								<div class="col-md-6">
									<input type="text" class="form-control required" id="fname" name="fname" placeholder="" value="<?php echo esc_attr($fname); ?>">
								</div>
								</div>
								<div class="form-group form-row">
								<label for="lname" class="col-md-6">Last Name:</label>
								<div class="col-md-6">
									<input type="text" class="form-control required" id="lname" name="lname" placeholder="" value="<?php echo esc_attr($lname); ?>">
								</div>
								</div>
								<div class="form-group form-row">
								<label for="phone" class="col-md-6">Phone Number:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="phone" name="phone" placeholder="" value="<?php echo esc_attr($phone); ?>">
								</div>
								</div>
								<div class="form-group form-row">
								<label for="email" class="col-md-6">Email:</label>
								<div class="col-md-6">
									<input type="text" class="form-control required" id="email" name="email" placeholder="" value="<?php echo esc_attr($email); ?>">
								</div>
								</div>
								<div class="field-for-pro-manager">
								<div class="form-group form-row" id="field-company-name">
								<label for="company_name" class="col-md-6">Company Name:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="company_name" name="company_name" placeholder="" value="<?php echo esc_attr($company_name); ?>">
								</div>
								</div>
								</div>
								<div class="field-for-pro-manager">
								<div class="form-group form-row" id="field-tax-id">
								<label for="tax_id" class="col-md-6">Tax ID:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="tax_id" name="tax_id" placeholder="" value="<?php echo esc_attr($tax_id); ?>">
								</div>
								</div>
								</div>
								<div class="field-for-home-owner">
								<div class="form-group form-row" id="field-date-of-birth">
								<label for="brithdate" class="col-md-6">Date of Birth:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="brithdate" name="brithdate" placeholder="" value="<?php echo esc_attr($brithdate); ?>">
								</div>
								</div>
								</div>
								<div class="field-for-home-owner">
								<div class="form-group form-row" id="field-driving-license">
								<label for="drlicense" class="col-md-6">Driver's License:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="drlicense" name="drlicense" placeholder="" value="<?php echo esc_attr($drlicense); ?>">
								</div>
								</div>
								</div>
								<div class="form-group form-row">
								<label for="comments" class="col-md-12">Comments:</label>
								<div class="col-md-12">
									<textarea class="form-control" rows="5" id="comments" name="comments"><?php echo esc_textarea($comments); ?></textarea>
								</div>
								</div>
						</div>
						<div class="col-6 col-md-6">
							<h4>Billing Address:</h4>
							<div class="form-group form-row">
								<label for="street" class="col-md-6">Street:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="street" name="street" placeholder="" value="<?php echo esc_attr($street); ?>">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="city" class="col-md-6">City:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="city" name="city" placeholder="" value="<?php echo esc_attr($city); ?>">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="state" class="col-md-6">State:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="state" name="state" placeholder="" value="<?php echo esc_attr($state); ?>">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="zip" class="col-md-6">Zip:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" id="zip" name="zip" placeholder="" value="<?php echo esc_attr($zip); ?>">
								</div>
							</div>
							<h4>Payment Information:</h4>
							<?php if(isset($ub_cardinfo) && is_array($ub_cardinfo) && (count($ub_cardinfo) > 0)){
								$add_new_card_features = 'ub-add-new-more-card';
								?>
								<?php foreach ($ub_cardinfo as $key => $ub_cards) { ?>
										<?php
											$cardname = esc_html( $ub_cards['cardname'] );
											$cardnum = esc_html( $ub_cards['cardnum'] );
											$expdate = esc_html( $ub_cards['expdate'] );
											$ccv = esc_html( $ub_cards['ccv'] );
										?>
										<div class="ub-card-info-wrap">
										<div class="form-group form-row">
											<label for="cardname" class="col-md-6">Name on Card:</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="cardname[]" placeholder="" value="<?php echo esc_attr($cardname); ?>">
											</div>
										</div>
										<div class="form-group form-row">
											<label for="cardnum" class="col-md-6">Card Number:</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="cardnum[]" placeholder="" value="<?php echo esc_attr($cardnum); ?>">
											</div>
										</div>
										<div class="form-group form-row">
											<label for="expdate" class="col-md-6">Exp. Date:</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="expdate[]" placeholder="" value="<?php echo esc_attr($expdate); ?>">
											</div>
										</div>
										<div class="form-group form-row">
											<label for="ccv" class="col-md-6">CCV:</label>
											<div class="col-md-6">
												<input type="text" class="form-control" name="ccv[]" placeholder="" value="<?php echo esc_attr($ccv); ?>">
											</div>
										</div>
										</div>
								<?php } ?>
							<?php }else{ ?>
									<?php $add_new_card_features = ''; ?>
							<?php } ?>
							<div class="ub-card-info-wrap add-new-card-wrap <?php echo esc_attr($add_new_card_features); ?>">
							<div class="form-group form-row">
								<label for="cardname" class="col-md-6">Name on Card:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="cardname[]" placeholder="">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="cardnum" class="col-md-6">Card Number:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="cardnum[]" placeholder="">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="expdate" class="col-md-6">Exp. Date:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="expdate[]" placeholder="">
								</div>
							</div>
							<div class="form-group form-row">
								<label for="ccv" class="col-md-6">CCV:</label>
								<div class="col-md-6">
									<input type="text" class="form-control" name="ccv[]" placeholder="">
								</div>
							</div>
							</div>
							<?php if($submission_type != 'view'){ ?>
							<div class="form-group form-row">
								<div class="col-12 col-md-12 align-right">
									<button type="button" class="btn btn-default add-more-card">Add Another Card</button>
								</div>
							</div>
							<div class="form-group form-row">
								<div class="col-md-offset-3 col-md-9">
									<input type="hidden" name="submission_type" value="<?php echo esc_attr($submission_type); ?>" />
									<?php wp_nonce_field( 'ub_account_action', 'ub_account_nonce' ); ?>
									<input type="submit" name="submit_user_account" class="btn btn-primary" value="Finish">
								</div>
							</div>
						<?php }else{ ?>
							<div class="form-group form-row">
								<div class="col-12 col-md-12 align-right">
									<a href="?account-edit=doedit" class="btn btn-secondary">Edit</a>
								</div>
							</div>
						<?php }?>
						</div>
					</div>
				</form>
		<div><!-- ub-form-content -->
	</div><!-- ub-form-wrap -->
	<style>
	<?php if($accounttype == 'homeowner'){ ?>
	.field-for-pro-manager{display: none;}
	<?php } ?>
	<?php if($accounttype == 'property_manager'){ ?>
	.field-for-home-owner{display: none;}
	<?php } ?>
	.ub-add-new-more-card{ display: none; }

	<?php if($submission_type == 'view'){ ?>
	.form-control {
    border: none !important;
    background: transparent !important;
	}
	<?php } ?>
	</style>
	<script>
	jQuery(document).ready(function() {
		jQuery("#account-data-form").validate();
		jQuery('.property-type-field').click(function () {
				if (jQuery(this).prop('checked')) {
					var check_value = jQuery(this).val();
					if(check_value == 'homeowner'){
							jQuery('.field-for-pro-manager').hide();
							jQuery('.field-for-home-owner').show();
					}else{
							jQuery('.field-for-pro-manager').show();
							jQuery('.field-for-home-owner').hide();
					}
				}
		});
		jQuery('.add-more-card').click(function () {
				jQuery('.ub-add-new-more-card').show();
		});

	});

	</script>
	<?php
	$form_content = ob_get_contents();
	ob_end_clean();
	return $form_content;
}
add_shortcode( 'ub_utility_manager_account_form', 'ub_utility_manager_account_form_shortcode_func');