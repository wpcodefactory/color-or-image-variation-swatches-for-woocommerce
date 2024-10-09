<?php
/*
Plugin Name: Variation Swatches for WooCommerce – Color & Image Swatches
Plugin URI: https://wordpress.org/plugins/color-or-image-variation-swatches-for-woocommerce/
Description: Provides new WooCommerce type attributes (color,label,image) for creating beautiful variations.
Version: 1.1.9
WC requires at least: 3.0.0
WC tested up to: 9.3
Requires Plugins: woocommerce
Author: WPFactory
Author URI: https://wpfactory.com
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: color-or-image-variation-swatches-for-woocommerce
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Check if WooCommerce is active
$plugin = 'woocommerce/woocommerce.php';
if (
	! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ) ) &&
	! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) )
) {
	return;
}

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Autoloader without namespace
if ( ! function_exists( 'alg_wc_civs_autoloader' ) ) {

	/**
	 * Autoloads all classes
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @param   type $class
	 */
	function alg_wc_civs_autoloader( $class ) {
		if ( false !== strpos( $class, 'Alg_WC_CIVS' ) ) {
			$classes_dir     = array();
			$plugin_dir_path = realpath( plugin_dir_path( __FILE__ ) );
			$classes_dir[0]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;
			$classes_dir[1]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR;
			$classes_dir[2]  = $plugin_dir_path . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'frontend' . DIRECTORY_SEPARATOR;
			$class_file      = 'class-' . strtolower( str_replace( array( '_', "\0" ), array( '-', '' ), $class ) . '.php' );

			foreach ( $classes_dir as $key => $dir ) {
				$file = $dir . $class_file;
				if ( is_file( $file ) ) {
					require_once $file;
					break;
				}
			}
		}
	}

	spl_autoload_register( 'alg_wc_civs_autoloader' );
}

// Constants
if ( ! defined( 'ALG_WC_CIVS_DIR' ) ) {
	define( 'ALG_WC_CIVS_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR );
}

if ( ! defined( 'ALG_WC_CIVS_URL' ) ) {
	define( 'ALG_WC_CIVS_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'ALG_WC_CIVS_BASENAME' ) ) {
	define( 'ALG_WC_CIVS_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ALG_WC_CIVS_FOLDER_NAME' ) ) {
	define( 'ALG_WC_CIVS_FOLDER_NAME', untrailingslashit( plugin_dir_path( plugin_basename( __FILE__ ) ) ) );
}

if ( ! defined( 'ALG_WC_CIVS_FILE_PATH' ) ) {
	define( 'ALG_WC_CIVS_FILE_PATH', __FILE__ );
}

if ( ! function_exists( 'color_or_image_variation_swatches_for_wc' ) ) {
	/**
	 * Returns the main instance of Alg_WC_CIVS_Core to prevent the need to use globals.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 * @return  Alg_WC_CIVS_Core
	 */
	function color_or_image_variation_swatches_for_wc() {
		return Alg_WC_CIVS_Core::instance();
	}
}

// Start plugin
add_action( 'plugins_loaded', 'alg_wc_civs_start_plugin' );
	if ( ! function_exists( 'alg_wc_civs_start_plugin' ) ) {
	/**
	 * Starts the plugin
	 *
	 * @version 1.0.1
	 * @since   1.0.1 
	 */
	function alg_wc_civs_start_plugin(){
		color_or_image_variation_swatches_for_wc();	
	}
}
