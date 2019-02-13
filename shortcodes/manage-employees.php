<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//My Employees
add_shortcode('ub_manage_employees', 'ub_manage_employees_shortcode');
function ub_manage_employees_shortcode($atts){
	extract(shortcode_atts(array(
		'count' => '-1'
	), $atts));

	require_once(ABSPATH.'wp-admin/includes/user.php' );

  ob_start();

	if(!is_user_logged_in()){
			$display_message = 'Please login to view this page.';
			echo ub_action_message($display_message, 'info');
			$output_result = ob_get_clean();
			return $output_result;
	}

	if((ub_get_current_user_role() == 'property_manager') || (current_user_can('manage_options'))){
		//should go well
	}else{
		$display_message = 'Only property manager can access this page.';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
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
<?php
	if(is_user_logged_in()){
		echo do_shortcode('[ub_inner_menus]');
	}
?>
<div class="ub-form-wrap">
	<div class="ub-form-content">
		<div class="ub-form-header">
				<h2>My Employees</h2>
		</div>
<div class="conn-dis-order">
	<table class="table table-bordered">
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
			<td><a class="btn btn-secondary" href="?delete_employee_id=<?php echo $employee->ID; ?>">Delete</a></td>
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
