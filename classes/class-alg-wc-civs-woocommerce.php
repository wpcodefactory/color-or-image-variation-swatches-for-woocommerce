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

		public function get_attribute_taxonomies() {
			if ( empty( $this->attribute_taxonomies ) ) {
				$this->attribute_taxonomies = wc_get_attribute_taxonomies();
			}

			return $this->attribute_taxonomies;
		}

		public function get_attribute_taxonomy_by_attribute( $attribute ) {
			$attribute       = substr( $attribute, 3 );
			$attr_taxonomies = $this->get_attribute_taxonomies();
			foreach ( $attr_taxonomies as $attr_tax ) {
				if($attr_tax->attribute_name==$attribute){
					if ( strpos( $attr_tax->attribute_type, 'alg_wc_civs_' ) !== false ) {
						$attr_tax->valid = true;
						return $attr_tax;
					}else{
						$attr_tax->valid = false;
						return $attr_tax;
					}
				}
			}
			return false;
		}

		/*public function is_attribute_valid( $attribute ) {

			$attribute = substr( $attribute, 3 );

			$attr_taxonomies = $this->get_attribute_taxonomies();
			$civs            = color_or_image_variation_swatches_for_wc();
			$admin           = $civs->get_admin();
			$types           = $admin->wc_attribute_types;

			foreach ( $attr_taxonomies as $attr_tax ) {
				if ( in_array( $attribute, (array) $attr_tax ) ) {
					if ( array_key_exists( $attr_tax->attribute_type, $types ) ) {
						return true;
					}
				}
			}

			return false;

		}*/

		/*public function get_attribute_taxonomy_by_wp_attribute($attribute){
			//if ( ! in_array( wc_clean( $type ), array_keys( wc_get_attribute_types() ) ) ) {
			//wc_get_attribute_types
			return wc_get_attribute_types();
			//return wc_get_attribute_taxonomies();
		}*/
	}
}