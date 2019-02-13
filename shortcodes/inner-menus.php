<?php
//Dropdown menus shortcode
add_shortcode('ub_inner_menus', 'ub_inner_menus_shortcode');
function ub_inner_menus_shortcode($atts){
	extract(shortcode_atts(array(
		//'redirect' => '',
	), $atts));
	ob_start();
	?>

	<?php
	if((ub_get_current_user_role() == 'property_manager') || (current_user_can('manage_options'))){
		require_once( UBUMANAGER_BASE_FOLDER . '/shortcodes/inner-menus-property-manager.php');
	}elseif(ub_get_current_user_role() == 'homeowner'){
		require_once( UBUMANAGER_BASE_FOLDER . '/shortcodes/inner-menus-homeowner.php');
	}elseif(ub_get_current_user_role() == 'employee'){
		require_once( UBUMANAGER_BASE_FOLDER . '/shortcodes/inner-menus-employee.php');
	}
	?>


	<?php
	$output_result = ob_get_clean();
	return $output_result;
}
