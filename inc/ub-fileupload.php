<?php
add_action( 'wp_ajax_ubfl_file_upload', 'ubfl_file_upload_callback' );
add_action( 'wp_ajax_nopriv_ubfl_file_upload', 'ubfl_file_upload_callback' );

if ( ! function_exists( 'ubfl_file_upload_callback' ) ) :
	function ubfl_file_upload_callback() {
		$data = array();
		$attachment_ids = array();

		if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'file_upload' ) ){
			$files = ubReArrayFiles($_FILES['files']);
			if ( empty($_FILES['files']) ) {
				$data['status'] = false;
				$data['message'] = __('Please select a pdf to upload!', 'ub-utility-manager');
			} elseif ( $files[0]['size'] > 5242880 ) { // Maximum image size is 5M
				$data['size'] = $files[0]['size'];
				$data['status'] = false;
				$data['message'] = __('File is too large. It must be less than 2M!', 'ub-utility-manager');
			} else {
				$i = 0;
				$data['message'] = '';
				foreach( $files as $file ){
					if( is_array($file) ){
						$attachment_id = ub_upload_user_file( $file, false );
						if ( is_numeric($attachment_id) ) {
							$file_url = wp_get_attachment_url( $attachment_id );
							$data['status'] = true;
							$data['file_url'] = $file_url;
							$attachment_ids[] = $attachment_id;
						}
					}
					$i++;
				}

				if( ! $attachment_ids ){
					$data['status'] = false;
					$data['message'] = __('An error has occured. Your file was not added.', 'ub-utility-manager');
				}
			}
		} else {
			$data['status'] = false;
			$data['message'] = __('Nonce verify failed', 'ub-utility-manager');
		}

		echo json_encode($data);
		die();
	}
endif;

if ( ! function_exists( 'ub_upload_user_file' ) ) :
	function ub_upload_user_file( $file = array(), $title = false ) {
		require_once ABSPATH.'wp-admin/includes/admin.php';
		$file_return = wp_handle_upload($file, array('test_form' => false));
		if(isset($file_return['error']) || isset($file_return['upload_error_handler'])){
			return false;
		}else{
			$filename = $file_return['file'];
			$attachment = array(
				'post_mime_type' => $file_return['type'],
				'post_content' => '',
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'guid' => $file_return['url']
			);
			if($title){
				$attachment['post_title'] = $title;
			}
			$attachment_id = wp_insert_attachment( $attachment, $filename );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			if( 0 < intval( $attachment_id ) ) {
				return $attachment_id;
			}
		}
		return false;
	}
endif;

/**
 * Rearray $_FILES array for easy use
 *
 */

if ( ! function_exists( 'ubReArrayFiles' ) ) :
	function ubReArrayFiles(&$file_post) {

	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}
endif;

add_action( 'wp_ajax_ubfl_upload_images', 'ub_fl_upload_images_callback' );
add_action( 'wp_ajax_nopriv_ubfl_upload_images', 'ub_fl_upload_images_callback' );

if ( ! function_exists( 'ub_fl_upload_images_callback' ) ) :
	function ub_fl_upload_images_callback() {
		$data = array();
		$attachment_ids = array();

		if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'image_upload' ) ){
			$files = ubReArrayFiles($_FILES['files']);
			if ( empty($_FILES['files']) ) {
				$data['status'] = false;
				$data['message'] = __('Please select an image to upload!', 'ub-utility-manager');
			} elseif ( $files[0]['size'] > 5242880 ) { // Maximum image size is 5M
				$data['size'] = $files[0]['size'];
				$data['status'] = false;
				$data['message'] = __('Image is too large. It must be less than 2M!', 'ub-utility-manager');
			} else {
				$i = 0;
				$data['message'] = '';
				foreach( $files as $file ){
					if( is_array($file) ){
						$attachment_id = ub_upload_user_file( $file, false );

						if ( is_numeric($attachment_id) ) {
							$img_thumb = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
							$data['status'] = true;
							$data['photo_url'] = $img_thumb[0];
							$data['attachment_id'] = $attachment_id;
							$data['message'] .='<img src="'.$img_thumb[0].'" alt="" />';
							$attachment_ids[] = $attachment_id;
						}
					}
					$i++;
				}

				if( ! $attachment_ids ){
					$data['status'] = false;
					$data['message'] = __('An error has occured. Your image was not added.', 'ub-utility-manager');
				}
			}
		} else {
			$data['status'] = false;
			$data['message'] = __('Nonce verify failed', 'ub-utility-manager');
		}

		echo json_encode($data);
		die();
	}
endif;
