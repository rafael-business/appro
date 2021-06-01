<?php

/**
 * The settings of the plugin.
 *
 * @link       https://rafael.work
 * @since      1.0.0
 *
 * @package    Appro
 * @subpackage Appro/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */
class Appro_Register_Post_Types {

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

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'Archive Pages Pró' menu.
	 */
	public function appro_register_archive_page_post_type() {

		$labels = array(
			'name'                  => _x( 'Archive Pages', 'Post Type General Name', 'appro' ),
			'singular_name'         => _x( 'Archive Page', 'Post Type Singular Name', 'appro' ),
			'menu_name'             => __( 'Archive Pages', 'appro' ),
			'name_admin_bar'        => __( 'Archive Page', 'appro' ),
			'archives'              => __( 'Archive Page Archives', 'appro' ),
			'attributes'            => __( 'Archive Page Attributes', 'appro' ),
			'parent_item_colon'     => __( 'Parent Archive Page:', 'appro' ),
			'all_items'             => __( 'All Archive Pages', 'appro' ),
			'add_new_item'          => __( 'Add New Archive Page', 'appro' ),
			'add_new'               => __( 'Add New', 'appro' ),
			'new_item'              => __( 'New Archive Page', 'appro' ),
			'edit_item'             => __( 'Edit Archive Page', 'appro' ),
			'update_item'           => __( 'Update Archive Page', 'appro' ),
			'view_item'             => __( 'View Archive Page', 'appro' ),
			'view_items'            => __( 'View Archive Pages', 'appro' ),
			'search_items'          => __( 'Search Archive Page', 'appro' ),
			'not_found'             => __( 'Not found', 'appro' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'appro' ),
			'featured_image'        => __( 'Featured Image', 'appro' ),
			'set_featured_image'    => __( 'Set featured image', 'appro' ),
			'remove_featured_image' => __( 'Remove featured image', 'appro' ),
			'use_featured_image'    => __( 'Use as featured image', 'appro' ),
			'insert_into_item'      => __( 'Insert into Archive Page', 'appro' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Archive Page', 'appro' ),
			'items_list'            => __( 'Archive Pages list', 'appro' ),
			'items_list_navigation' => __( 'Archive Pages list navigation', 'appro' ),
			'filter_items_list'     => __( 'Filter Archive Pages list', 'appro' ),
		);

		$args = array(
			'label'                 => __( 'Archive Page', 'appro' ),
			'description'           => __( 'Archive Pages Pró', 'appro' ),
			'labels'                => $labels,
			'supports'              => array( 'title' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 25,
			'menu_icon'             => 'dashicons-open-folder',
			'show_in_admin_bar'     => false,
			'show_in_nav_menus'     => false,
			'can_export'            => true,
			'has_archive'           => false,
			'exclude_from_search'   => true,
			'publicly_queryable'    => false,
			'rewrite'               => false,
			'capability_type'       => 'page',
			'show_in_rest'          => false,
		);

		register_post_type( 'appro_archive_page', $args );

	}

}