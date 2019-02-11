<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_add_new_employee_shortcode_func( $atts ) {
  extract(shortcode_atts(array(
    'form_type' => 'new',
	'account_id' => 0
  ), $atts));

	ob_start();
?>
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content ub-new-employee">
		<form id="account-data-form" class="form-horizontal" action="" method="post">
			<div class="row">
				<div class="col-md-12">
					<h4>Add New Employee</h4>
					<div class="para">Please enter the email of the employee you'd <br>like to invite to create account. </div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-5">
					Account Type
					<div class="form-group label-space" name="first_account_type">
						<select class="form-control">
						  <option value=""></option>
						  <option value="">Property Manager</option>
						  <option value="">Home Owner</option>
						  <option value="">Employee</option>
						</select>
					</div>
					<div class="form-group" name="second_account_type">
						<select class="form-control">
						  <option value=""></option>
						  <option value="">Property Manager</option>
						  <option value="">Home Owner</option>
						  <option value="">Employee</option>
						</select>
					</div>
				</div>
				<div class="col-md-7">
					Email Address
					<div class="form-group label-space">
						<input type="text" class="form-control required" id="first_email" name="first_email" placeholder="" value="">
					</div>
					<div class="form-group">
						<input type="text" class="form-control required" id="second_email" name="second_email" placeholder="" value="">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="para"><a href="#">+ Add Another Employee</a></div>
					<?php wp_nonce_field( 'ub_new_employee_action', 'ub_new_employee_nonce' ); ?>
					<input type="hidden" name="" value="" />
					<input type="submit" class="btn btn-secondary" name="submit_invite" value="Send Invite"/>
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
add_shortcode( 'ub_new_employee_form', 'ub_add_new_employee_shortcode_func');
