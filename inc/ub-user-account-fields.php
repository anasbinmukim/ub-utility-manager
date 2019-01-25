<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'show_user_profile', 'ub_utility_manager_account_extra_profile_fields' );
add_action( 'edit_user_profile', 'ub_utility_manager_account_extra_profile_fields' );

function ub_utility_manager_account_extra_profile_fields( $user ) {
	?>
	<h3><?php esc_html_e( 'Account Information', 'ub-utility-manager' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="ub-phone"><?php esc_html_e( 'Phone Number', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-phone" id="ub-phone" value="<?php echo esc_attr( get_the_author_meta( '_ub_phone', $user->ID ) ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="ub-company-name"><?php esc_html_e( 'Company Name', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-company-name" id="ub-company-name" value="<?php echo esc_attr( get_the_author_meta( '_ub_company_name', $user->ID ) ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="ub-tax-id"><?php esc_html_e( 'Tax ID', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-tax-id" id="ub-tax-id" value="<?php echo esc_attr( get_the_author_meta( '_ub_tax_id', $user->ID ) ); ?>" /></td>
		</tr>

		<tr>
			<th><label for="ub-dateob"><?php esc_html_e( 'Date of birth', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-dateob" id="ub-dateob" value="<?php echo esc_attr( get_the_author_meta( '_ub_dateob', $user->ID ) ); ?>" /></td>
		</tr>

		<tr>
			<th><label for="ub-driver-license"><?php esc_html_e( 'Driver License #', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-driver-license" id="ub-driver-license" value="<?php echo esc_attr( get_the_author_meta( '_ub_driver_license', $user->ID ) ); ?>" /></td>
		</tr>

	</table>

	<h3><?php esc_html_e( 'Billing Address', 'ub-utility-manager' ); ?></h3>

	<table class="form-table">

		<tr>
			<th><label for="ub-street"><?php esc_html_e( 'Street', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-street" id="ub-street" value="<?php echo esc_attr( get_the_author_meta( '_ub_street', $user->ID ) ); ?>" /></td>
		</tr>

		<tr>
			<th><label for="ub-city"><?php esc_html_e( 'City', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-city" id="ub-city" value="<?php echo esc_attr( get_the_author_meta( '_ub_city', $user->ID ) ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="ub-state"><?php esc_html_e( 'State', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-state" id="ub-state" value="<?php echo esc_attr( get_the_author_meta( '_ub_state', $user->ID ) ); ?>" /></td>
		</tr>
		<tr>
			<th><label for="ub-zipcode"><?php esc_html_e( 'Zipcode', 'ub-utility-manager' ); ?></label></th>
			<td><input type="text" name="ub-zipcode" id="ub-zipcode" value="<?php echo esc_attr( get_the_author_meta( '_ub_zipcode', $user->ID ) ); ?>" /></td>
		</tr>
	</table>

	<h3><?php esc_html_e( 'Payment Information', 'ub-utility-manager' ); ?></h3>

	<?php
		$ub_cardinfo = get_the_author_meta( '_ub_cardinfo', $user->ID );
	?>
	<?php if(isset($ub_cardinfo) && is_array($ub_cardinfo) && (count($ub_cardinfo) > 0)){
		?>
		<?php foreach ($ub_cardinfo as $key => $ub_cards) { ?>
				<?php
					$cardname = esc_html( $ub_cards['cardname'] );
					$cardnum = esc_html( $ub_cards['cardnum'] );
					$expdate = esc_html( $ub_cards['expdate'] );
					$ccv = esc_html( $ub_cards['ccv'] );
				?>
				<h4>Card info</h4>
				<table class="form-table">
					<tr>
						<th>Name on Card:</th>
						<td><?php echo $cardname; ?></td>
					</tr>
					<tr>
						<th>Card Number:</th>
						<td><?php echo $cardnum; ?></td>
					</tr>
					<tr>
						<th>Exp. Date:</th>
						<td><?php echo $expdate; ?></td>
					</tr>
					<tr>
						<th>CCV:</th>
						<td><?php echo $ccv; ?></td>
					</tr>
				</table>

		<?php } ?>
	<?php } ?>


	<?php
}


add_action( 'personal_options_update', 'ub_utility_manager_account_update_profile_fields' );
add_action( 'edit_user_profile_update', 'ub_utility_manager_account_update_profile_fields' );

function ub_utility_manager_account_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}
	if ( ! empty( $_POST['ub-phone'] ) && ($_POST['ub-phone'] != '') ) {
		update_user_meta( $user_id, '_ub_phone', sanitize_text_field( $_POST['ub-phone'] ) );
	}else{
		update_user_meta( $user_id, '_ub_phone', '');
	}
	if ( ! empty( $_POST['ub-company-name'] ) && ($_POST['ub-company-name'] != '') ) {
		update_user_meta( $user_id, '_ub_company_name', sanitize_text_field( $_POST['ub-company-name'] ) );
	}else{
		update_user_meta( $user_id, '_ub_company_name', '');
	}

	if ( ! empty( $_POST['ub-tax-id'] ) && ($_POST['ub-tax-id'] != '') ) {
		update_user_meta( $user_id, '_ub_tax_id', sanitize_text_field( $_POST['ub-tax-id'] ) );
	}else{
		update_user_meta( $user_id, '_ub_tax_id', '');
	}

	if ( ! empty( $_POST['ub-dateob'] ) && ($_POST['ub-dateob'] != '') ) {
		update_user_meta( $user_id, '_ub_dateob', sanitize_text_field( $_POST['ub-dateob'] ) );
	}else{
		update_user_meta( $user_id, '_ub_dateob', '');
	}
	if ( ! empty( $_POST['ub-driver-license'] ) && ($_POST['ub-driver-license'] != '') ) {
		update_user_meta( $user_id, '_ub_driver_license', sanitize_text_field( $_POST['ub-driver-license'] ) );
	}else{
		update_user_meta( $user_id, '_ub_driver_license', '');
	}

	if ( ! empty( $_POST['ub-street'] ) && ($_POST['ub-street'] != '') ) {
		update_user_meta( $user_id, '_ub_street', sanitize_text_field( $_POST['ub-street'] ) );
	}else{
		update_user_meta( $user_id, '_ub_street', '');
	}
	if ( ! empty( $_POST['ub-city'] ) && ($_POST['ub-city'] != '') ) {
		update_user_meta( $user_id, '_ub_city', sanitize_text_field( $_POST['ub-city'] ) );
	}else{
		update_user_meta( $user_id, '_ub_city', '');
	}
	if ( ! empty( $_POST['ub-state'] ) && ($_POST['ub-state'] != '') ) {
		update_user_meta( $user_id, '_ub_state', sanitize_text_field( $_POST['ub-state'] ) );
	}else{
		update_user_meta( $user_id, '_ub_state', '');
	}
	if ( ! empty( $_POST['ub-zipcode'] ) && ($_POST['ub-zipcode'] != '') ) {
		update_user_meta( $user_id, '_ub_zipcode', sanitize_text_field( $_POST['ub-zipcode'] ) );
	}else{
		update_user_meta( $user_id, '_ub_zipcode', '');
	}


}
