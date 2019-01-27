<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_new_account_shortcode_func( $atts ) {
  extract(shortcode_atts(array(
    'form_type' => 'new',
	'account_id' => 0
  ), $atts));
  
	ob_start();
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
						<label for="phpne_num" class="col-md-6">Phone Number:</label>
						<div class="col-md-6">
							<input type="text" class="form-control required" id="phpne_num" name="phpne_num" placeholder="" value="">
						</div>
					</div>
					<div class="form-group form-row">
						<label for="email" class="col-md-6">Email:</label>
						<div class="col-md-6">
							scott@axissoftwaredynamics.com
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
