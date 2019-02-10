<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_new_account_shortcode_func( $atts ) {
  extract(shortcode_atts(array(
    'form_type' => '',
	'account_id' => 0
  ), $atts));
  
  ob_start();
	
	if(!is_user_logged_in()){
		$display_message = 'You need to login to view this page';
		echo ub_action_message($display_message, 'info');
		return;
	}
	
	if((ub_get_current_user_role() != 'property_manager') && !is_admin()){
		$display_message = 'Only property manager can access this page.';
		echo ub_action_message($display_message, 'info');
		return;
	}
	
	$property_manager_id = get_current_user_id();
	$accounttype = '';
	$company_name = '';
	if (isset($_POST['ub_new_account_nonce']) && (! isset( $_POST['ub_new_account_nonce'] ) || ! wp_verify_nonce( $_POST['ub_new_account_nonce'], 'ub_new_account_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['submit_account'])){
		$meta_data_update = true;
		$accounttype = 'employee';
		$password = sanitize_text_field($_POST['password']);
		$re_password = sanitize_text_field($_POST['re_password']);
		$fname = sanitize_text_field($_POST['fname']);
		$lname = sanitize_text_field($_POST['lname']);
		$phone_num = sanitize_text_field($_POST['phone_num']);
		$ub_email = sanitize_text_field($_POST['ub_email']);
		$company_name = 'Axis Software Dynamics';

		if ( !($password == $re_password) || empty($password) ){
				$display_message = 'Password does not match or empty. Please try again!';
				echo ub_action_message($display_message, 'danger');
				$meta_data_update = false;
		}elseif ( !is_email($ub_email) || email_exists($ub_email) ){
				$display_message = 'Email is not valid or already exist. Please try with different email!';
				echo ub_action_message($display_message, 'danger');
				$meta_data_update = false;
		}

		if($meta_data_update){
				$userdata = array(
					'user_pass' => $password,
					'user_login' => $ub_email,
					'user_email' => $ub_email,
					'first_name' => $fname,
					'last_name' => $lname,
					'role' => $accounttype,
				);
				$account_id = wp_insert_user( $userdata );
				
				update_user_meta($account_id, '_ub_phone', $phone_num);
				update_user_meta($account_id, '_ub_company_name', $company_name);
				update_user_meta($account_id, '_ub_property_manager_id', $property_manager_id);
				
				$employee_ids = array();
				$employee_ids = get_user_meta($property_manager_id, '_ub_employee_ids', true);
				if(is_array($employee_ids)){
					array_push($employee_ids, $account_id);
					update_user_meta( $property_manager_id, '_ub_employee_ids', $employee_ids );
				}else{
					$employee_id = array($account_id);
					update_user_meta( $property_manager_id, '_ub_employee_ids', $employee_id );
				}				
				//ub_debug($employee_ids);
			
				
				
				$display_message = 'Successfully added! Please login to edit your account.';
				echo ub_action_message($display_message, 'success');
				
		}

	}
	
?>

<div class="ub-form-wrap">
	<div class="ub-form-content ub-new-account">			
		<form id="account-data-form" class="form-horizontal" action="" method="post">
			<div class="row">
				<h4>New Account</h4>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group form-row">
						<label for="account_type" class="col-md-6">Account Type:</label>
						<div class="col-md-6">
							Employee
						</div>
					</div>
					<div class="form-group form-row">
						<label for="password" class="col-md-6">Password:</label>
						<div class="col-md-6">
							<input type="password" class="form-control required" id="password" name="password" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="re_password" class="col-md-6">Re. Enter Password:</label>
						<div class="col-md-6">
							<input type="password" class="form-control required" id="re_password" name="re_password" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="fname" class="col-md-6">First Name:</label>
						<div class="col-md-6">
							<input type="text" class="form-control" id="fname" name="fname" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="lname" class="col-md-6">Last Name:</label>
						<div class="col-md-6">
							<input type="text" class="form-control required" id="lname" name="lname" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="phone_num" class="col-md-6">Phone Number:</label>
						<div class="col-md-6">
							<input type="text" class="form-control required" id="phone_num" name="phone_num" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="ub_email" class="col-md-6">Email:</label>
						<div class="col-md-6">
							<input type="text" class="form-control required" id="ub_email" name="ub_email" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="company_name" class="col-md-6">Company Name:</label>
						<div class="col-md-6">
							Axis Software Dynamics
						</div>
					</div>
					
					<div class="form-group form-row">
						<?php wp_nonce_field( 'ub_new_account_action', 'ub_new_account_nonce' ); ?>
						<input type="hidden" name="" value="" />
						<input type="submit" class="btn btn-secondary" name="submit_account" value="Finish"/>
					</div>
						
				</div>				
			</div>		
		</form>
	</div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->
	
	<?php
	$form_content = ob_get_contents();
	ob_end_clean();
	return $form_content;
}
add_shortcode( 'ub_new_account_form', 'ub_new_account_shortcode_func');
