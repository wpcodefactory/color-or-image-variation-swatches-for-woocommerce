<?php
/**
 * Color or Image Variation Swatches for WooCommerce - Custom Meta Box setting field
 *
 * @version 1.0.0
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CIVS_CMB' ) ) {

	class Alg_WC_CIVS_CMB {

		/**
		 * Creates meta box
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public static function add_meta_box( $value ) {
			// Doesn't show metabox if show_in_pro = false and it's loading from pro
			if ( ! $value['show_in_pro'] ) {
				if ( defined( 'ALG_WC_CIVS_PRO_DIR' ) ) {
					return;
				}
			}

			$option_description = $value['description'];
			$option_title = $value['title'];
			$option_id = esc_attr($value['id']);

			echo'
			<div id="poststuff">									
				<div id="'.$option_id.'" class="postbox">
					<h2 class="hndle"><span>'.$option_title.'</span></h2>
					<div class="inside">
						'.$option_description.'
					</div>
				</div>
			</div>
			';
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}
	}
}