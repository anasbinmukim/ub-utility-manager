<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'admin_settings_menu');

function admin_settings_menu() {
  add_submenu_page('options-general.php', 'Utility Manager', 'Utility Manager', 'administrator', 'utility-manager-properties', 'utility_manager_settings_page'
  );
}

function utility_manager_settings_page(){
  
	if ( isset($_POST['ub_setting_nonce']) && (! isset( $_POST['ub_setting_nonce'] ) || ! wp_verify_nonce( $_POST['ub_setting_nonce'], 'ub_setting_action' ) ) ) {
		//This is nonce error
	}elseif(isset($_POST['ub_setting_submit'])){			
		
		update_option( 'ub_register_property', sanitize_text_field($_POST['ub_register_property']));		
		update_option( 'ub_view_property', sanitize_text_field($_POST['ub_view_property']));		
		update_option( 'ub_create_account', sanitize_text_field($_POST['ub_create_account']));		
    
    ?>
      
    <div class="updated"><p><?php echo __('Successfully Updated', 'ub-utility-manager'); ?></p></div>
            
    <?php }	?>
  
    <div class="wrap">
      <h2><?php echo __('Utility Manager Settings', 'ub-utility-manager'); ?></h2>
      <form name="utility_manager_settings" method="post">
            
      <table class="form-table"> 
		  <?php $pages = get_pages(); ?>		
		  
		  <tr valign="top">
				<th scope="row"><?php echo __('Register Property', 'twentythirteen'); ?></th>
				<td>
					<select name="ub_register_property" id="ub_register_property">
						<?php 
							$ub_register_property = sanitize_text_field(get_option('ub_register_property'));
						?>
						<option value="">
						<?php echo esc_attr( __( 'Select page' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ub_register_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><?php echo __('View Property', 'twentythirteen'); ?></th>
				<td>
					<select name="ub_view_property" id="ub_view_property">
						<?php 
							$ub_view_property = sanitize_text_field(get_option('ub_view_property'));
						?>
						<option value="">
						<?php echo esc_attr( __( 'Select page' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ub_view_property, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row"><?php echo __('Create Account', 'twentythirteen'); ?></th>
				<td>
					<select name="ub_create_account" id="ub_create_account">
						<?php 
							$ub_create_account = sanitize_text_field(get_option('ub_create_account'));
						?>
						<option value="">
						<?php echo esc_attr( __( 'Select page' ) ); ?></option>
						<?php foreach ( $pages as $page ) {	?>
							<option <?php selected($ub_create_account, $page->ID); ?> value="<?php echo $page->ID; ?>"><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			
			<tr valign="top">	
				<th scope="row"><label for="blogname"></label></th>
				<td><input type="hidden" name="submission_type" value="" />
				<?php wp_nonce_field( 'ub_setting_action', 'ub_setting_nonce' ); ?><input type="submit" class="button button-primary button-large" value="Save" id="ub_setting_submit" name="ub_setting_submit"></td>
			</tr>


		</table>
	 </form>		
	</div><!--wrap-->
    <?php  
}