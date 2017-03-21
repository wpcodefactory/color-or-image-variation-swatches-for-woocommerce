<?php
/**
 * Appealing Variation for WooCommerce - Admin Types
 *
 * Handles the admin part of the new WooCommerce variation types
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_APVA_Admin_Types' ) ) {

	class Alg_WC_APVA_Admin_Types {

		function __construct() {

			// Adds new type attributes for WooCommerce variations
			add_filter( 'product_attributes_type_selector', array( $this, 'add_wc_variation_types' ) );
		}

		/**
		 * Adds new type attributes for WooCommerce variations
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_wc_variation_types( $types ) {
			$new_types = array(
				'alg_wc_apva_color' => esc_html__( 'Color (apva)', 'appealing-variation-for-woocommerce' ),
				'alg_wc_apva_image' => esc_html__( 'Image (apva)', 'appealing-variation-for-woocommerce' ),
				'alg_wc_apva_text'  => esc_html__( 'Text (apva)', 'appealing-variation-for-woocommerce' ),
			);

			return array_merge( $types, $new_types );
		}
	}
}