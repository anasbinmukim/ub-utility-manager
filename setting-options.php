<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'ub_manager_admin_settings_menu');

function ub_manager_admin_settings_menu() {
  add_submenu_page('edit.php?post_type=ub_property', esc_html__( 'Utility Manager Settings' ), esc_html__( 'Settings' ), 'administrator', 'utility-manager-properties', 'utility_manager_settings_page'
  );
}

function utility_manager_settings_page(){

	if ( isset($_POST['ub_setting_nonce']) && (! isset( $_POST['ub_setting_nonce'] ) || ! wp_verify_nonce( $_POST['ub_setting_nonce'], 'ub_setting_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['ub_setting_submit'])){

		update_option( 'ubp_register_property', intval($_POST['ubp_register_property']));
		update_option( 'ubp_view_property', intval($_POST['ubp_view_property']));
		update_option( 'ubp_create_account', intval($_POST['ubp_create_account']));
		update_option( 'ubp_create_order', intval($_POST['ubp_create_order']));
		update_option( 'ubp_review_order', intval($_POST['ubp_review_order']));
    ?>

    <div class="updated"><p><?php echo esc_html__('Successfully Updated', 'ub-utility-manager'); ?></p></div>

    <?php }	?>

    <div class="wrap">
      <h2><?php echo esc_html__('Utility Manager Settings', 'ub-utility-manager'); ?></h2>
      <form name="utility_manager_settings" method="post">

      <table class="form-table">
		  <?php $pages = get_pages(); ?>

		  <tr valign="top">
				<th scope="row"><?php echo esc_html__('Register Property', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubp_register_property" id="ubp_register_property">
						<?php
							$ubp_register_property = intval(get_option('ubp_register_property'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubp_register_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('View Property', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubp_view_property" id="ubp_view_property">
						<?php
							$ubp_view_property = intval(get_option('ubp_view_property'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubp_view_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Create Account', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubp_create_account" id="ubp_create_account">
						<?php
							$ubp_create_account = intval(get_option('ubp_create_account'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubp_create_account, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Create Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubp_create_order" id="ubp_create_order">
						<?php
							$ubp_create_order = intval(get_option('ubp_create_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubp_create_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><?php echo esc_html__('Review Order', 'ub-utility-manager'); ?></th>
				<td>
					<select name="ubp_review_order" id="ubp_review_order">
						<?php
							$ubp_review_order = intval(get_option('ubp_review_order'));
						?>
						<option value="">
						<?php echo esc_attr( esc_html__( 'Select page', 'ub-utility-manager' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ubp_review_order, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>

			<tr valign="top">
				<th scope="row"><label for="blogname"></label></th>
				<td>
				<?php wp_nonce_field( 'ub_setting_action', 'ub_setting_nonce' ); ?><input type="submit" class="button button-primary button-large" value="Save" id="ub_setting_submit" name="ub_setting_submit"></td>
			</tr>


		</table>
	 </form>
	</div><!--wrap-->
    <?php
}
