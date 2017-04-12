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
					'enabled'        => !class_exists('Alg_WC_CIVS_Settings_General'),
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

		/**
		 * Gets meta box description.
		 *
		 * The description is about the pro version of the plugin
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 */
		function get_meta_box_pro_description() {
			$presentation   = __( 'Do you like the free version of this plugin? Imagine what the Pro version can do for you!', 'color-or-image-variation-swatches-for-woocommerce' );
			$url            = 'https://coder.fm/item/color-or-image-variation-swatches-for-woocommerce-pro/';
			$links          = sprintf( wp_kses( __( 'Check it out <a target="_blank" href="%s">here</a> or on this link: <a target="_blank" href="%s">%s</a>', 'color-or-image-variation-swatches-for-woocommerce' ), array( 'a' => array( 'href' => array() ) ) ), esc_url( $url ), esc_url( $url ), esc_url( $url ) );
			$features_title = __( 'Take a look on some of its features:', 'color-or-image-variation-swatches-for-woocommerce' );
			$features       = array(
				array(
					'desc'  => __( 'Display only the possible term combinations, so your user does not have to guess the right ones', 'color-or-image-variation-swatches-for-woocommerce' ),
					'image' => plugin_dir_url( __FILE__ ) . '../../assets/images/combination.gif',
				),
				array(
					'desc'  => __( 'Display your attributes on frontend using Select2, an enhanced version of the select element. It works great when you have a big amount of variations', 'color-or-image-variation-swatches-for-woocommerce' ),
					'image' => plugin_dir_url( __FILE__ ) . '../../assets/images/select2.gif',
				),
				array(
					'desc'  => __( 'Add attribute images of a variable product on its own gallery', 'color-or-image-variation-swatches-for-woocommerce' ),
					'image' => plugin_dir_url( __FILE__ ) . '../../assets/images/images-on-gallery.gif',
				),
			);

			$features_str = "<ul class='alg-admin-accordion'>";
			foreach ( $features as $feature ) {
				$img          = ! empty( $feature['image'] ) ? '<br /><div class="details-container"><img src="' . esc_attr( $feature['image'] ) . '"></div>' : '';
				$li_class     = ! empty( $feature['image'] ) ? 'accordion-item item' : 'item';
				$features_str .= "<li class='{$li_class}'>{$feature['desc']}{$img}</li>";
			}
			$features_str   .= "</ul>";
			$call_to_action = sprintf( __( '<a target="_blank" style="margin:9px 0 15px 0;" class="button-primary" href="%s">Upgrade to Pro version now</a> ', 'color-or-image-variation-swatches-for-woocommerce' ), esc_url( $url ) );

			return "
				<p>{$presentation}<br/>
				{$links}</p>
				<strong>{$features_title}</strong>
				{$features_str}
				{$call_to_action}
			";
		}

	}
}