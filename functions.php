<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', 'enqueue_load_font_awesome' );
function enqueue_load_font_awesome() {
	wp_enqueue_style( 'load-fa', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
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
