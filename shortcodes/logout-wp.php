<?php
//View Properties
add_shortcode('ub_logout', 'ub_logout_shortcode');
function ub_logout_shortcode($atts){
	extract(shortcode_atts(array(
		'redirect' => get_permalink(),
	), $atts));

	$output_result = wp_logout_url( $redirect );
	$live_url = str_replace("http://", "", $output_result);
	
	return $live_url;
}
