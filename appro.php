<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rafael.work
 * @since             1.0.0
 * @package           Appro
 *
 * @wordpress-plugin
 * Plugin Name:       Archive Pages Pró
 * Plugin URI:        https://rafael.work/archive-pages-pro
 * Description:       Crie Páginas de Arquivo personalizadas para o seu site WordPress.
 * Version:           1.0.0
 * Author:            Rafael Business
 * Author URI:        https://rafael.business
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       appro
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-appro-activator.php
 */
function activate_Appro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-appro-activator.php';
	Appro_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-appro-deactivator.php
 */
function deactivate_Appro() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-appro-deactivator.php';
	Appro_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_Appro' );
register_deactivation_hook( __FILE__, 'deactivate_Appro' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-appro.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Appro() {

	$plugin = new Appro();
	$plugin->run();

}
run_Appro();


if ( ! is_admin() )
{

    add_action( 'wp_loaded', function()
    {
        wp_register_style(
            'bulma',
            plugin_dir_url( __FILE__ ) . 'public/css/bulma.min.css'
        );
    });

	function appro_unshift_style() {

	    add_filter( 'print_styles_array', function( $styles ) {

	        array_unshift( $styles, 'bulma' );
	        return $styles;
	    });
	}

	add_action( 'wp_enqueue_scripts', 'appro_unshift_style', 99 );
}

add_image_size( '400by300', 400, 300, true );
