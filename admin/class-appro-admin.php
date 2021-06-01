<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rafael.work
 * @since      1.0.0
 *
 * @package    Appro
 * @subpackage Appro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appro
 * @subpackage Appro/admin
 * @author     Rafael Business <devinvinson@gmail.com>
 */
class Appro_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

	}

	/**
	 * Load the required dependencies for the Admin facing functionality.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Appro_Settings. Registers the admin settings and page.
	 *
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-appro-settings.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-appro-register-post-types.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'admin/class-appro-add-meta-boxes.php';

	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Appro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Appro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/appro-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Appro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Appro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/appro-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'jquery-ui-sortable', array( 'jquery' ), '1.11.4' );

	}



}
