<div class="inner-menu-wrap">
	<ul>
		<li class="main-menu"><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_account'))); ?>">Account</a><i class="fa fa-angle-down"></i>
			<ul class="sub-menu">
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_account'))); ?>?account-edit=doedit">Edit Account Information</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_manage_employee'))); ?>">Manage Employee</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_add_employee'))); ?>">Add Employee</a></li>
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
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_create_order')));  ?>">Submit New Order</a></li>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_my_order')));  ?>">View Orders</a></li>
				<?php if(current_user_can('manage_options')){ ?>
				<li><a href="<?php echo esc_url(get_permalink(get_option('ubpid_confirm_order')));  ?>">Manage Orders</a></li>
				<?php } ?>
			</ul>
		</li>
	</ul>
</div>
