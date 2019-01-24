<?php
/*
Plugin Name: Utillity Manager
Plugin URI: http://rmweblab.com/
Description: Utillity Manager with user accounts
Author: Anas
Version: 1.0.0
Author URI: http://rmweblab.com
Copyright: © 2018 RMWebLab.
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ub-utility-manager
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

if ( ! class_exists( 'ub_utility_manager' ) ) {
  /**
   * Main ub_utility_manager clas set up for us
   */
  class ub_utility_manager{

  	/**
  	 * Constructor
  	 */
  	public function __construct() {
  		define( 'UBUMANAGER_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
  		define( 'UBUMANAGER_MAIN_FILE', __FILE__ );
  		define( 'UBUMANAGER_BASE_FOLDER', dirname( __FILE__ ) );
      define( 'UBUMANAGER_FRONT_URL', home_url('/'));
      define( 'UBUMANAGER_FOLDER_URL', plugins_url('/', __FILE__));


  		// Actions
  		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
      add_action( 'plugins_loaded', array( $this, 'init' ), 0 );

  	}

		/**
		 * Init localisations and hook
		 */
		public function init() {

			require_once( UBUMANAGER_BASE_FOLDER . '/includes.php');

			// Localisation
			load_plugin_textdomain( 'ub-utility-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

      if(isset($_GET['addrole']) && ($_GET['addrole'] == 'do')){
        add_role( 'property_manager', 'Property Manager', array( 'read' => true, 'level_2' => true ) );
        add_role( 'homeowner', 'Homeowner', array( 'read' => true, 'level_2' => true ) );
        //remove_role( 'homewoner' );
      }

      add_action( 'wp_enqueue_scripts', array( $this, 'ub_utility_manager_script'));

		}

    function ub_utility_manager_script(){
      wp_register_style('ub-manager-front', UBUMANAGER_FOLDER_URL. 'css/utility-manager-style.css' );
			wp_enqueue_style('ub-manager-front');
			wp_register_style('ub-bootstrap', UBUMANAGER_FOLDER_URL. 'css/bootstrap.min.css' );
			wp_enqueue_style('ub-bootstrap');

			wp_register_script( 'jquery-validation', UBUMANAGER_FOLDER_URL. 'js/jquery.validate.min.js', array( 'jquery' ), '5.0.0', true );
			wp_enqueue_script( 'jquery-validation' );

			wp_localize_script( 'jquery-validation', 'ub_ajax_object', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

			// Load the datepicker script (pre-registered in WordPress).
			wp_enqueue_script( 'jquery-ui-datepicker' );
			// You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
			wp_register_style( 'jquery-ui', '//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'jquery-ui' );
    }

		/**
		 * Add relevant links to plugins page
		 * @param  array $links
		 * @return array
		 */
		public function plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=ub_utility_manager_settings' ) . '">' . esc_html__( 'Settings', 'ub-utility-manager' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}


	}


}

new ub_utility_manager();
