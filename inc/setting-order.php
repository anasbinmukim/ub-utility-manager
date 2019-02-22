<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'ub_manager_admin_order_settings_menu');

function ub_manager_admin_order_settings_menu() {
  add_submenu_page('edit.php?post_type=ub_order', esc_html__( 'Order Settings' ), esc_html__( 'Settings' ), 'administrator', 'utility-manager-order-settings', 'utility_manager_order_settings_page'
  );
}

function utility_manager_order_settings_page(){

	if ( isset($_POST['ubo_setting_nonce']) && (! isset( $_POST['ubo_setting_nonce'] ) || ! wp_verify_nonce( $_POST['ubo_setting_nonce'], 'ubo_setting_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['ubo_setting_submit'])){

		update_option( 'ubpid_create_order', intval($_POST['ubpid_create_order']));
		update_option( 'ubpid_review_order', intval($_POST['ubpid_review_order']));
		update_option( 'ubpid_my_order', intval($_POST['ubpid_my_order']));
		update_option( 'ubpid_confirm_order', intval($_POST['ubpid_confirm_order']));
		update_option( 'ubpid_all_orders', intval($_POST['ubpid_all_orders']));
		update_option( 'ub_gas_charge', sanitize_text_field($_POST['ub_gas_charge']));
		update_option( 'ub_water_charge', sanitize_text_field($_POST['ub_water_charge']));
		update_option( 'ubp_electricity_charge', sanitize_text_field($_POST['ubp_electricity_charge']));
		update_option( 'ubp_connect_term', intval($_POST['ubp_connect_term']));
		update_option( 'ubp_disconnect_term', intval($_POST['ubp_disconnect_term']));
		update_option( 'ub_order_admin_emails', sanitize_text_field($_POST['ub_order_admin_emails']));

    ?>

    <div class="updated"><p><?php echo esc_html__('Successfully Updated', 'ub-utility-manager'); ?></p></div>

    <?php }	?>

    <div class="wrap">
      <h2><?php echo esc_html__('Order Settings', 'ub-utility-manager'); ?></h2>
      <form name="utility_manager_order_settings" method="post">

      <table class="form-table">
		  <?php $pages = get_pages(); ?>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Create Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_create_order" id="ubpid_create_order">
						<?php
							$ubpid_create_order = intval(get_option('ubpid_create_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_create_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Review Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_review_order" id="ubpid_review_order">
						<?php
							$ubpid_review_order = intval(get_option('ubpid_review_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_review_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('My Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_my_order" id="ubpid_my_order">
						<?php
							$ubpid_my_order = intval(get_option('ubpid_my_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_my_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Confirm Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_confirm_order" id="ubpid_confirm_order">
						<?php
							$ubpid_confirm_order = intval(get_option('ubpid_confirm_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_confirm_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('All Orders', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_all_orders" id="ubpid_all_orders">
						<?php
							$ubpid_all_orders = intval(get_option('ubpid_all_orders'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_all_orders, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Gas Charge', 'ub-utility-manager'); ?></th>
				<td>
					<?php $ub_gas_charge = get_option('ub_gas_charge'); ?>
					$<input class="text-small" type="text" name="ub_gas_charge" value="<?php echo esc_attr($ub_gas_charge); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Water Charge', 'ub-utility-manager'); ?></th>
				<td>
					<?php $ub_water_charge = get_option('ub_water_charge'); ?>
					$<input class="text-small" type="text" name="ub_water_charge" value="<?php echo esc_attr($ub_water_charge); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Electricity Charge', 'ub-utility-manager'); ?></th>
				<td>
					<?php $ubp_electricity_charge = get_option('ubp_electricity_charge'); ?>
					$<input class="text-small" type="text" name="ubp_electricity_charge" value="<?php echo esc_attr($ubp_electricity_charge); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Connect Type ID', 'ub-utility-manager'); ?></th>
				<td>
					<?php
					$ubp_connect_term_id = get_option('ubp_connect_term');
					$term_type_connect_args = array(
						'hide_empty'         => 0,
						'echo'               => 1,
						'selected'           => $ubp_connect_term_id,
						'name'               => 'ubp_connect_term',
						'id'                 => 'ubp_connect_term',
						'taxonomy'           => 'ub_order_type',
						'hide_if_empty'      => false,
						'value_field'	     => 'term_id',
						);
						wp_dropdown_categories( $term_type_connect_args );
					?>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Disconnect Type ID', 'ub-utility-manager'); ?></th>
				<td>
					<?php
					$ubp_disconnect_term_id = get_option('ubp_disconnect_term');
					$term_type_disconnect_args = array(
						'hide_empty'         => 0,
						'echo'               => 1,
						'selected'           => $ubp_disconnect_term_id,
						'name'               => 'ubp_disconnect_term',
						'id'                 => 'ubp_disconnect_term',
						'taxonomy'           => 'ub_order_type',
						'hide_if_empty'      => false,
						'value_field'	     => 'term_id',
						);
						wp_dropdown_categories( $term_type_disconnect_args );
					?>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Admin Notification Email', 'ub-utility-manager'); ?></th>
				<td>
					<?php $ub_order_admin_emails = get_option('ub_order_admin_emails'); ?>
					<input class="text-large" type="text" name="ub_order_admin_emails" value="<?php echo esc_attr($ub_order_admin_emails); ?>" />
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="blogname"></label></th>
				<td>
				<?php wp_nonce_field( 'ubo_setting_action', 'ubo_setting_nonce' ); ?>
				<input type="submit" class="button button-primary button-large" value="Save" id="ubo_setting_submit" name="ubo_setting_submit"></td>
			</tr>


		</table>
	 </form>
	</div><!--wrap-->
    <?php
}
