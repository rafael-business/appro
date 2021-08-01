<?php

/**
 * The shortcode-facing functionality of the plugin.
 *
 * @link       https://rafael.work
 * @since      1.0.0
 *
 * @package    Appro
 * @subpackage Appro/shortcode
 */

/**
 * The shortcode-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Appro
 * @subpackage Appro/shortcode
 * @author     Rafael Business <devinvinson@gmail.com>
 */
class Appro_Shortcode {

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

	}

	public function appro_the_posts( $posts ) {

	    return $posts;
	}

	public function appro_has_archive_page( $result = false ) {

		if ( is_post_type_archive() ) : 
	        
	        $aps = get_posts( array( 
				'suppress_filters' 	=> false, 
				'post_type'			=> 'appro_archive_page', 
				'post_status'		=> 'publish', 
				'meta_query'		=> array( 
					array( 
			            'key'   => '_appro_options_post_type', 
			            'value' => get_post_type() 
			        )
				)
			));

			$result = $aps ? true : false;
	    endif;

	    return $result;
	}

	public function appro_template_include( $template ) {

	    
	    if ( $this->appro_has_archive_page() ) : 
	        
	        $template = WP_PLUGIN_DIR .'/'. plugin_basename( dirname(__FILE__) ) .'/templates/archive.php';
	    endif;
	    
	    return $template;
	}

	public function appro_posts_where( $where = '' ) {

		global $wpdb;
 
	    if ( is_post_type_archive() ) : 
	    	
	    	//$where .= $wpdb->prepare( " AND post_date > %s", date( 'Y-m-d', strtotime('-30 days') ) );
		endif;

	    return $where;
	}

}