<?php
/**
 * Appealing variation for WooCommerce - Core Class
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_APVA_Core' ) ) {

	class Alg_WC_APVA_Core {

		/**
		 * Plugin version.
		 *
		 * @var   string
		 * @since 1.0.0
		 */
		public $version = '1.0.0';

		/**
		 * @var   Alg_WC_APVA_Core The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		/**
		 * Main Alg_WC_APVA_Core Instance
		 *
		 * Ensures only one instance of Alg_WC_APVA_Core is loaded or can be loaded.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @static
		 * @return  Alg_WC_APVA_Core - Main instance
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

			if ( true === filter_var( get_option( Alg_WC_APVA_Settings_General::OPTION_ENABLE_PLUGIN, true ), FILTER_VALIDATE_BOOLEAN ) ) {
				if ( ! is_admin() ) {
					$this->init_frontend();
				}
			}
		}

		/**
		 * Load scripts and styles
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function enqueue_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		}

		/**
		 * Initialize frontend
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function init_frontend() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
		}

		/**
		 * Handle Localization
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function handle_localization() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'appealing-variation-for-woocommerce' );
			load_textdomain( 'appealing-variation-for-woocommerce', WP_LANG_DIR . dirname( ALG_WC_APVA_BASENAME ) . 'appealing-variation-for-woocommerce' . '-' . $locale . '.mo' );
			load_plugin_textdomain( 'appealing-variation-for-woocommerce', false, dirname( ALG_WC_APVA_BASENAME ) . '/languages/' );
		}

		/**
		 * Create custom settings fields
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		protected function create_custom_settings_fields() {
			$value = 'meta_box';
			add_action( 'woocommerce_admin_field_' . $value, array(
				Alg_WC_APVA_CMB::get_class_name(),
				'add_meta_box',
			), 10, 2 );
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
			add_filter( 'plugin_action_links_' . ALG_WC_APVA_BASENAME, array( $this, 'action_links' ) );

			// Create custom settings fields
			$this->create_custom_settings_fields();

			// Admin setting options inside WooCommerce
			new Alg_WC_APVA_Settings_General();

			// Update version
			if ( is_admin() && get_option( 'alg_wc_apva_version', '' ) !== $this->version ) {
				update_option( 'alg_wc_apva_version', $this->version );
			}

			// Handles the admin part of the new WooCommerce variation types
			new Alg_WC_APVA_Admin_Types();
		}

		/**
		 * Add settings tab to WooCommerce settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function add_woocommerce_settings_tab( $settings ) {
			$settings[] = new Alg_WC_APVA_Settings();

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
			$custom_links = array( '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_wc_apva' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>' );

			return array_merge( $custom_links, $links );
		}


	}
}