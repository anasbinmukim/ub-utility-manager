<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'ub_manager_admin_property_settings_menu');

function ub_manager_admin_property_settings_menu() {
  add_submenu_page('edit.php?post_type=ub_property', esc_html__( 'Utility Manager Settings' ), esc_html__( 'Settings' ), 'administrator', 'utility-manager-properties', 'utility_manager_property_settings_page'
  );
}

function utility_manager_property_settings_page(){

	if ( isset($_POST['ubp_setting_nonce']) && (! isset( $_POST['ubp_setting_nonce'] ) || ! wp_verify_nonce( $_POST['ubp_setting_nonce'], 'ubp_setting_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['ubp_setting_submit'])){
		update_option( 'ubpid_register_property', intval($_POST['ubpid_register_property']));
		update_option( 'ubpid_view_property', intval($_POST['ubpid_view_property']));
		update_option( 'ubpid_create_account', intval($_POST['ubpid_create_account']));
		update_option( 'ubpid_add_employee', intval($_POST['ubpid_add_employee']));
		update_option( 'ubpid_manage_employee', intval($_POST['ubpid_manage_employee']));
		update_option( 'ubpid_all_properties', intval($_POST['ubpid_all_properties']));
    ?>

    <div class="updated"><p><?php echo esc_html__('Successfully Updated', 'ub-utility-manager'); ?></p></div>

    <?php }	?>

    <div class="wrap">
      <h2><?php echo esc_html__('Property Settings', 'ub-utility-manager'); ?></h2>
      <form name="utility_manager_settings" method="post">

      <table class="form-table">
		  <?php $pages = get_pages(); ?>

		  <tr valign="top">
				<th scope="row"><?php echo esc_html__('Register Property', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_register_property" id="ubpid_register_property">
						<?php
							$ubpid_register_property = intval(get_option('ubpid_register_property'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_register_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('View Property', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_view_property" id="ubpid_view_property">
						<?php
							$ubpid_view_property = intval(get_option('ubpid_view_property'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_view_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Create Account', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_create_account" id="ubpid_create_account">
						<?php
							$ubpid_create_account = intval(get_option('ubpid_create_account'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_create_account, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Add Employee', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_add_employee" id="ubpid_add_employee">
						<?php
							$ubpid_add_employee = intval(get_option('ubpid_add_employee'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_add_employee, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Manage Employee', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_manage_employee" id="ubpid_manage_employee">
						<?php
							$ubpid_manage_employee = intval(get_option('ubpid_manage_employee'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_manage_employee, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('All Properties', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubpid_all_properties" id="ubpid_all_properties">
						<?php
							$ubpid_all_properties = intval(get_option('ubpid_all_properties'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubpid_all_properties, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="blogname"></label></th>
				<td>
				<?php wp_nonce_field( 'ubp_setting_action', 'ubp_setting_nonce' ); ?>
				<input type="submit" class="button button-primary button-large" value="Save" id="ubp_setting_submit" name="ubp_setting_submit"></td>
			</tr>


		</table>
	 </form>
	</div><!--wrap-->
    <?php
}
