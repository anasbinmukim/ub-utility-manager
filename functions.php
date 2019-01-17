<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function ub_get_current_user_role() {
    if( is_user_logged_in() ) {
      $user = wp_get_current_user();
      $role = ( array ) $user->roles;
			if(isset($role[0])){
				return $role[0];
			}else{
				return false;
			}
    } else {
      return false;
    }
 }

 function ub_action_message($message, $type = 'secondary'){
	 	//alert-primary, alert-secondary, alert-success, alert-danger, alert-warning, alert-info, alert-light, alert-dark
	 	$message = '<div role="alert" class="ub-notification-message alert alert-'.esc_attr($type).'"><p>'.esc_html($message).'</p></div>';
		return $message;
 }
