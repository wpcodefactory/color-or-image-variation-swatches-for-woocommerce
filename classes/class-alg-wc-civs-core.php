<?php
/**
 * Color or Image Variation Swatches for WooCommerce - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CIVS_Core' ) ) {

	class Alg_WC_CIVS_Core {

		public $admin;

		public $wc_functions;

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.0.0';

		/**
		 * @var   Alg_WC_CIVS_Core The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main Alg_WC_CIVS_Core Instance
		 *
		 * Ensures only one instance of Alg_WC_CIVS_Core is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_CIVS_Core - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct() {
			// Set up localization
			$this->handle_localization();

			// Init admin part
			if ( is_admin() ) {
				$this->init_admin();
			}

			if ( true === filter_var( get_option( Alg_WC_CIVS_Settings_General::OPTION_ENABLE_PLUGIN, true ), FILTER_VALIDATE_BOOLEAN ) ) {
				if ( ! is_admin() ) {
					$this->init_frontend();
				}
			}
		}

		/**
		 * Initialize frontend
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function init_frontend() {
			new Alg_WC_CIVS_Frontend();
		}

		/**
		 * Handle Localization
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function handle_localization() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'color-or-image-variation-swatches-for-woocommerce' );
			load_textdomain( 'color-or-image-variation-swatches-for-woocommerce', WP_LANG_DIR . dirname( ALG_WC_CIVS_BASENAME ) . 'color-or-image-variation-swatches-for-woocommerce' . '-' . $locale . '.mo' );
			load_plugin_textdomain( 'color-or-image-variation-swatches-for-woocommerce', false, dirname( ALG_WC_CIVS_BASENAME ) . '/languages/' );
		}

		/**
		 * Create custom settings fields
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function create_custom_settings_fields() {
			WCCSO_Metabox::get_instance();
		}

		/**
		 * Init admin fields
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function init_admin() {
			// Creates settings tabs
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

			// Creates action links on plugins page
			add_filter( 'plugin_action_links_' . ALG_WC_CIVS_BASENAME, array( $this, 'action_links' ) );

			// Create custom settings fields
			$this->create_custom_settings_fields();

			// Admin setting options inside WooCommerce
			new Alg_WC_CIVS_Settings_General();

			// Update version
			if ( is_admin() && get_option( 'alg_wc_civs_version', '' ) !== $this->version ) {
				update_option( 'alg_wc_civs_version', $this->version );
			}

			// Handles the admin part of the new WooCommerce variation types
			$this->admin = $this->get_admin();
			$this->admin->init();
		}

		/**
		 * Get admin part
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $smart
		 *
		 * @return Alg_WC_CIVS_Admin_Types
		 */
		function get_admin( $smart = true ) {
			if ( $smart ) {
				if ( empty( $this->admin ) ) {
					$this->admin = new Alg_WC_CIVS_Admin_Types();
				}
			}

			return $this->admin;
		}

		/**
		 * Get WooCommerce functions
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $smart
		 *
		 * @return Alg_WC_CIVS_WooCommerce
		 */
		function get_wc_functions( $smart = true ) {
			if ( $smart ) {
				if ( empty( $this->wc_functions ) ) {
					$this->wc_functions = new Alg_WC_CIVS_WooCommerce();
				}
			}

			return $this->wc_functions;
		}

		/**
		 * Add settings tab to WooCommerce settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function add_woocommerce_settings_tab( $settings ) {
			$settings[] = new Alg_WC_CIVS_Settings();

			return $settings;
		}

		/**
		 * Show action links on the plugin screen
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param   mixed $links
		 *
		 * @return  array
		 */
		function action_links( $links ) {
			$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_civs' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>' );

			return array_merge( $custom_links, $links );
		}


	}
}