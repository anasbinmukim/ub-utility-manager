<?php
//Dropdown menus shortcode
add_shortcode('ub_dropdown_menus', 'ub_dropdown_menus_shortcode');
function ub_dropdown_menus_shortcode($atts){
	extract(shortcode_atts(array(
		//'redirect' => '',
	), $atts));
	ob_start();
	?>
	
	<div class="inner-menu-wrap">
		<ul>
			<li class="main-menu"><a href="<?php echo esc_url(get_permalink(get_option('ub_create_account'))); ?>">Account</a><i class="fa fa-angle-down"></i>
				<ul class="sub-menu">
					<li><a href="#">Edit Account Information</a></li>
				</ul>
			</li>
			<li class="main-menu"><a href="#">Properties</a><i class="fa fa-angle-down"></i>
				<ul class="sub-menu">
					<li><a href="#">Add/Remove Properties</a></li>
					<li><a href="<?php echo esc_url(get_permalink(get_option('ub_view_property')));  ?>">View My Properties</a></li>
				</ul>
			</li>
			<li class="main-menu"><a href="#">Orders</a><i class="fa fa-angle-down"></i>
				<ul class="sub-menu">
					<li><a href="#">Submit New Order</a></li>
					<li><a href="#">View Orders</a></li>
				</ul>
			</li>
		</ul>
	</div>

	
	<?php
	$output_result = ob_get_clean();
	return $output_result;
}
