<?php
/**
 * Color or Image Variation Swatches for WooCommerce - General Section Settings
 *
 * @version 1.0.1
 * @since   1.0.0
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CIVS_Settings_General' ) ) {

	class Alg_WC_CIVS_Settings_General extends Alg_WC_CIVS_Settings_Section {

		const OPTION_ENABLE_PLUGIN = 'alg_wc_civs_enable';
		const OPTION_METABOX_PRO   = 'alg_wc_civs_cmb_pro';

		protected $pro_version_url = 'https://coder.fm/item/color-or-image-variation-swatches-for-woocommerce/';

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function __construct( $handle_autoload = true ) {
			$this->id   = '';
			$this->desc = __( 'General', 'color-or-image-variation-swatches-for-woocommerce' );
			parent::__construct( $handle_autoload );
		}

		/**
		 * get_settings.
		 *
		 * @version 1.0.1
		 * @since   1.0.0
		 */
		function get_settings( $settings = null ) {

			$new_settings = array(
				array(
					'title' => __( 'General options', 'color-or-image-variation-swatches-for-woocommerce' ),
					'type'  => 'title',
					'id'    => 'alg_wc_civs_opt',
				),
				array(
					'title'          => 'Pro version',
					'enable'         => !class_exists('Alg_WC_CIVS_Pro_Core'),
					'type'           => 'wccso_metabox',
					'show_in_pro'    => false,
					'accordion'      => array(
						'title' => __( 'Take a look on some of its features:', 'color-or-image-variation-swatches-for-woocommerce' ),
						'items' => array(
							array(
								'trigger' => __( 'Display only the possible term combinations, so your user does not have to guess the right ones', 'color-or-image-variation-swatches-for-woocommerce' ),
								'img_src' => plugin_dir_url( __FILE__ ) . '../../assets/images/combination.gif',
							),
							array(
								'trigger'     => __( 'Display your attributes on frontend using Select2, an enhanced version of the select element.', 'color-or-image-variation-swatches-for-woocommerce' ),
								'description' => __( 'It works great when you have a big amount of variations', 'color-or-image-variation-swatches-for-woocommerce' ),
								'img_src'     => plugin_dir_url( __FILE__ ) . '../../assets/images/select2.gif',
							),
							array(
								'trigger' => __( 'Add attribute images of a variable product on its own gallery', 'color-or-image-variation-swatches-for-woocommerce', 'color-or-image-variation-swatches-for-woocommerce' ),
								'img_src' => plugin_dir_url( __FILE__ ) . '../../assets/images/images-on-gallery.gif',
							),

						),
					),
					'call_to_action' => array(
						'href'  => $this->pro_version_url,
						'label' => 'Upgrade to Pro version now',
					),
					'description'    => __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'color-or-image-variation-swatches-for-woocommerce' ) . '<br />' . sprintf( __( 'Check it out <a target="_blank" href="%1$s">here</a> or on this link: <a target="_blank" href="%1$s">%1$s</a>', 'color-or-image-variation-swatches-for-woocommerce' ), esc_url( $this->pro_version_url ) ),
					'id'             => self::OPTION_METABOX_PRO,
				),
				array(
					'title'   => __( 'Enable Plugin', 'color-or-image-variation-swatches-for-woocommerce' ),
					'desc'    => __( 'Enable "Color or Image Variation Swatches for WooCommerce" plugin', 'color-or-image-variation-swatches-for-woocommerce' ),
					'id'      => self::OPTION_ENABLE_PLUGIN,
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_civs_opt',
				),
			);

			return parent::get_settings( array_merge( $settings, $new_settings ) );
		}
	}
}