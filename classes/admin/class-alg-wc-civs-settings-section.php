<?php
/**
 * Color or Image Variation Swatches for WooCommerce - Section Settings
 *
 * @version 1.1.9
 * @since   1.0.0
 * @author  WPFactory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CIVS_Settings_Section' ) ) :

	class Alg_WC_CIVS_Settings_Section {

		/**
		 * ID.
		 *
		 * @since 1.1.9
		 */
		public $id;

		/**
		 * Description.
		 *
		 * @since 1.1.9
		 */
		public $desc;

		protected $settings;
		protected $handle_autoload = true;

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->handle_autoload = $handle_autoload;
			if ( $this->handle_autoload ) {
				$this->get_settings( array() );
				$this->handle_autoload();
			}
			add_filter( 'woocommerce_get_sections_alg_wc_civs', array( $this, 'settings_section' ) );
			add_filter( 'woocommerce_get_settings_alg_wc_civs_' . $this->id, array(
				$this,
				'get_settings',
			), PHP_INT_MAX );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function get_settings( $settings = array() ) {
			$this->settings = $settings;

			return $this->settings;
		}

		/**
		 * handle_autoload.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function handle_autoload() {
			foreach ( $this->settings as $value ) {
				if ( isset( $value['default'] ) && isset( $value['id'] ) ) {
					$autoload = isset( $value['autoload'] ) ? ( bool ) $value['autoload'] : true;
					add_option( $value['id'], $value['default'], '', ( $autoload ? 'yes' : 'no' ) );
				}
			}
		}

		/**
		 * settings_section.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;

			return $sections;
		}

	}

endif;
