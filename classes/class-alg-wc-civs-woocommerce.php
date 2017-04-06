<?php
/**
 * Color or Image Variation Swatches for WooCommerce - WooCommerce
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

if ( ! class_exists( 'Alg_WC_CIVS_WooCommerce' ) ) {

	class Alg_WC_CIVS_WooCommerce {

		protected $attribute_taxonomies;

		function __construct() {

		}

		/**
		 * Get attribute taxonomies
		 *
		 * @return array
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function get_attribute_taxonomies() {
			if ( empty( $this->attribute_taxonomies ) ) {
				$this->attribute_taxonomies = wc_get_attribute_taxonomies();
			}

			return $this->attribute_taxonomies;
		}

		/**
		 * Get attribute taxonomy by attribute
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $attribute
		 *
		 * @return bool|mixed
		 */
		public function get_attribute_taxonomy_by_attribute( $attribute ) {
			$attribute       = substr( $attribute, 3 );
			$attr_taxonomies = $this->get_attribute_taxonomies();
			foreach ( $attr_taxonomies as $attr_tax ) {
				if ( $attr_tax->attribute_name == $attribute ) {
					if ( strpos( $attr_tax->attribute_type, 'alg_wc_civs_' ) !== false ) {
						$attr_tax->valid = true;

						return $attr_tax;
					} else {
						$attr_tax->valid = false;

						return $attr_tax;
					}
				}
			}

			return false;
		}
	}
}