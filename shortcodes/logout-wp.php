<?php
//Logout shortcode
add_shortcode('ub_logout', 'ub_logout_shortcode');
function ub_logout_shortcode($atts){
	extract(shortcode_atts(array(
		'redirect' => get_permalink(),
	), $atts));

	if(!is_user_logged_in()){
		return;
	}

	$output_result = wp_logout_url( $redirect );
	$logout_url = str_replace("https://", "", $output_result);

	return $logout_url;
}
