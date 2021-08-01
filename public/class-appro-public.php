<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://rafael.work
 * @since      1.0.0
 *
 * @package    Appro
 * @subpackage Appro/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appro
 * @subpackage Appro/public
 * @author     Rafael Business <devinvinson@gmail.com>
 */
class Appro_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->load_dependencies();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) .  'public/class-appro-shortcode.php';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( 'bulma-tooltip', plugin_dir_url( __FILE__ ) . 'css/bulma-tooltip.min.css', array( 'bulma' ) );
		wp_enqueue_style( 'fullcalendar', plugin_dir_url( __FILE__ ) . 'css/fullcalendar.min.css' );
		wp_enqueue_style( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'css/jquery-confirm.min.css' );
		//wp_enqueue_style( 'tableexport', plugin_dir_url( __FILE__ ) . 'css/tableexport.min.css' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/appro-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( 'fullcalendar', plugin_dir_url( __FILE__ ) . 'js/fullcalendar.min.js', array(), '5.7.0', false );
		wp_enqueue_script( 'jquery-confirm', plugin_dir_url( __FILE__ ) . 'js/jquery-confirm.min.js', array( 'jquery' ), '3.3.4', false );
		//wp_enqueue_script( 'xlsx-core', plugin_dir_url( __FILE__ ) . 'js/xlsx.core.min.js', array(), '0.17.0', false );
		//wp_enqueue_script( 'FileSaver', plugin_dir_url( __FILE__ ) . 'js/FileSaver.min.js', array(), '1.3.6', false );
		//wp_enqueue_script( 'tableexport', plugin_dir_url( __FILE__ ) . 'js/tableexport.min.js', array( 'FileSaver' ), '5.2.0', false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/appro-public.js', array( 'jquery' ), $this->version, false );

	}

}
