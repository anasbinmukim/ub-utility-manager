<div class="inner-menu-wrap">
	<ul>
		<li class="main-menu"><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_account'))); ?>">Account</a><i class="fa fa-angle-down"></i>
			<ul class="sub-menu">
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_account'))); ?>?account-edit=doedit">Edit Account Information</a></li>
			</ul>
		</li>
		<li class="main-menu"><a href="<?php echo esc_url(get_permalink(get_option('ubpid_view_property')));  ?>">Properties</a><i class="fa fa-angle-down"></i>
			<ul class="sub-menu">
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_register_property')));  ?>">Add/Remove Properties</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_view_property')));  ?>">View My Properties</a></li>
			</ul>
		</li>
		<li class="main-menu"><a href="<?php echo esc_url(get_permalink(get_option('ubpid_my_order')));  ?>">Orders</a><i class="fa fa-angle-down"></i>
			<ul class="sub-menu">
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_order')));  ?>?submit_type=connect">Submit New Connect Order</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_order')));  ?>?submit_type=disconnect">Submit New Disconnect Order</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_my_order')));  ?>">View Orders</a></li>
			</ul>
		</li>
	</ul>
</div>
