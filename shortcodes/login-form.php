<?php
//Login form shortcode
add_shortcode('ub_login_form', 'ub_login_form_shortcode');
function ub_login_form_shortcode($atts){
	extract(shortcode_atts(array(
    'redirect' => '',
  ), $atts));
  ob_start();
	if(is_user_logged_in()){
		$display_message = 'You are already logged in.';
		echo ub_action_message($display_message, 'info');
		$output_result = ob_get_clean();
		return $output_result;
	}

	if($redirect != ''){
		$redirect_url = $redirect;
	}elseif(isset($_SERVER['HTTP_HOST'])){
		$redirect_url = $_SERVER['HTTP_HOST'];
	}

	if(isset($_GET['redirect_to'])){
		$redirect_url = esc_url($_GET['redirect_to']);
	}

	if(isset($_GET['redirect'])){
		$redirect_url = esc_url($_GET['redirect']);
	}

	$args = array(
		'echo'           => true,
		'remember'       => true,
		'redirect'       => $redirect_url,
		'form_id'        => 'loginform',
		'id_username'    => 'user_login',
		'id_password'    => 'user_pass',
		'id_remember'    => 'rememberme',
		'id_submit'      => 'wp-submit',
		'label_username' => __( 'Username or Email' ),
		'label_password' => __( 'Password' ),
		'label_remember' => __( 'Remember Me' ),
		'label_log_in'   => __( 'Submit' ),
		'value_username' => '',
		'value_remember' => false
	);

?>
<div class="ub-form-wrap">
	<div class="ub-form-content ub-login-form-wrap">
		<div class="ub-form-header">
				<h2>Login</h2>
		</div><!-- ub-form-header -->
		<a class="button new-user-signup" href="<?php echo get_permalink(intval(get_option('ubpid_create_account'))); ?>?account-signup=dosignup">New User? Sign Up ></a>
		<?php wp_login_form( $args ); ?>

	<div><!-- ub-form-content -->
</div><!-- ub-form-wrap -->
<?php

	$output_result = ob_get_clean();
	return $output_result;
}
