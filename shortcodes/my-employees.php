<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//My Employees
add_shortcode('ub_my_employees', 'ub_my_employees_shortcode');
function ub_my_employees_shortcode($atts){
	extract(shortcode_atts(array(
		'count' => '-1'
	), $atts));
	
	require_once(ABSPATH.'wp-admin/includes/user.php' );
  
  ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			return;
	}
	
	if((ub_get_current_user_role() != 'property_manager') && !is_admin()){
		$display_message = 'Only property manager can access this page.';
		echo ub_action_message($display_message, 'info');
		return;
	}
	
	$property_manager_id = get_current_user_id();
	
	if(isset($_GET['delete_employee_id'])){
		$delete_employee_id =$_GET['delete_employee_id'];
		
		$employee_ids = get_user_meta($property_manager_id, '_ub_employee_ids', true);
		$employee_ids = array_diff($employee_ids, array($delete_employee_id));
		update_user_meta( $property_manager_id, '_ub_employee_ids', $employee_ids );
		
		$employee_delete = wp_delete_user($delete_employee_id);
		if($employee_delete){
			$display_message = 'Employee deleted successfully.';
			echo ub_action_message($display_message, 'info');
		}
	}

?>
<div class="ub-form-wrap ub-new-connction-order">
	<div class="ub-form-content">
		<div class="only-heading">
				<h2><?php //echo $submitting_heading; ?></h2>
		</div>
		<form action="" method="post">
			<div class="form-row">
				<div class="form-group col-md-4">
					<label for="ub_name">Name</label>
					<input type="text" class="form-control" name="ub_name" id="ub_name">
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
					<label for="" class="search-label">Search</label>
					<button type="submit" class="btn btn-default" name="search_property"><img src="<?php echo UBUMANAGER_FOLDER_URL; ?>/images/search-icon.png" alt=""/></button>
				</div>
			</div>
		</form>
<div class="conn-dis-order">
	<table class="table table-bordered">
		<tr>
			<td colspan="3" align="center">My Employees</td>
		</tr>
		<tr>
			<td>Name</td>
			<td>Email</td>
			<td>Action</td>
		</tr>
	<?php
		$args = array(
			'role'         => 'employee',
			'order'        => 'ASC',
			'meta_query'   => array(
				array(
                'relation' => 'AND',
					array(
						'key' => '_ub_property_manager_id',
						'value' => $property_manager_id,
						'compare' => "=",
						'type' => 'numeric'
					)
				)
			)
		 ); 
		$all_employees = get_users( $args ); 
		//ub_debug($all_employees);
		foreach($all_employees as $employee){
	?>
		<tr>
			<td><?php echo $employee->display_name; ?></td>		
			<td><?php echo $employee->user_email; ?></td>			
			<td><a href="?delete_employee_id=<?php echo $employee->ID; ?>">Delete</a></td>
		</tr>
	<?php } ?>
	</table>
	</div>
	</div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->

	<?php

	$output_result = ob_get_clean();
	return $output_result;
}