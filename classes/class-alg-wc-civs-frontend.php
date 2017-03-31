<?php
/**
 * Color or Image Variation Swatches for WooCommerce - Frontend
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

if ( ! class_exists( 'Alg_WC_CIVS_Frontend' ) ) {

	class Alg_WC_CIVS_Frontend {

		function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 11 );
			add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array(
				$this,
				'woocommerce_dropdown_variation_attribute_options_html',
			), 999, 2 );
		}


		function get_url_without_protocol( $post = 0, $leavename = false ) {
			$permalink = get_the_permalink( $post, $leavename );
			$find      = array( 'http://', 'https://' );
			$replace   = '';
			$permalink = str_replace( $find, $replace, $permalink );

			return $permalink;
		}

		function get_color_html( $args, $attr_taxonomy ) {
			$attribute = $args['attribute'];
			$product   = $args['product'];
			$options   = $args['options'];

			$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );
			$html  = '<div class="alg-wc-civs-attribute color ' . esc_attr( $attribute ) . '">';

			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $options ) ) {
					$value = get_term_meta( $term->term_id, 'alg_wc_civs_term_color_color', true );
					$html  .= '<span style="background-color:' . esc_html( $value ) . '" class="alg-wc-civs-term" data-value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . '</span>';
				}
			}

			$html .= '</div>';

			return $html;
		}

		function get_label_html( $args, $attr_taxonomy ) {
			$attribute = $args['attribute'];
			$product   = $args['product'];
			$options   = $args['options'];

			$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );
			$html  = '<div class="alg-wc-civs-attribute label ' . esc_attr( $attribute ) . '">';

			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $options ) ) {
					$value = get_term_meta( $term->term_id, 'alg_wc_civs_term_label_label', true );
					$html  .= '<span class="alg-wc-civs-term" data-value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' .esc_html( $value ). '</span>';
				}
			}

			$html .= '</div>';

			return $html;
		}

		function get_image_html( $args, $attr_taxonomy ) {
			$attribute = $args['attribute'];
			$product   = $args['product'];
			$options   = $args['options'];

			$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );
			$html  = '<div class="alg-wc-civs-attribute image ' . esc_attr( $attribute ) . '">';

			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $options ) ) {
					$value = get_term_meta( $term->term_id, 'alg_wc_civs_term_image_image_id', true );
					$image_src = wp_get_attachment_image_src( $value, array(36,36) );
					$html  .= '<span style="background-image: url('.esc_attr($image_src[0]).')" class="alg-wc-civs-term" data-value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . '</span>';
				}
			}

			$html .= '</div>';

			return $html;
		}

		public function woocommerce_dropdown_variation_attribute_options_html( $html, $args ) {
			$civs         = color_or_image_variation_swatches_for_wc();
			$admin        = $civs->get_admin();
			$types        = $admin->wc_attribute_types;
			$attribute    = $args['attribute'];
			$wc_functions = $civs->get_wc_functions();

			if ( empty( $attribute ) ) {
				return $html;
			}

			$attr_taxonomy = $wc_functions->get_attribute_taxonomy_by_attribute( $attribute );


			if ( ! $attr_taxonomy->valid ) {
				return $html;
			}

			$options          = $args['options'];
			$product          = $args['product'];
			$name             = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
			$id               = $args['id'] ? $args['id'] : sanitize_title( $attribute );
			$class            = $args['class'];
			$show_option_none = $args['show_option_none'] ? true : false;

			$custom_html = '';

			switch ( $attr_taxonomy->attribute_type ) {
				case 'alg_wc_civs_color':
					$custom_html = $this->get_color_html( $args, $attr_taxonomy );
				break;
				case 'alg_wc_civs_label':
					$custom_html = $this->get_label_html( $args, $attr_taxonomy );
				break;
				case 'alg_wc_civs_image':
					$custom_html = $this->get_image_html( $args, $attr_taxonomy );
				break;
			}


			//$custom_html =


			/*if ( ! empty( $options ) && $product && taxonomy_exists( $attribute ) ) {
				$terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options ) ) {
						$swatches .= '<span>a</span>';
					}
				}
			}

			if ( ! empty( $swatches ) ) {
				$class .= ' hidden';

				$swatches = '<div class="tawcvs-swatches" data-attribute_name="attribute_' . esc_attr( $attribute ) . '">' . $swatches . '</div>';
				$html     = '<div class="' . esc_attr( $class ) . '">' . $html . '</div>' . $swatches;
			}*/


			return '<div class="alg-wc-civs-original-select">' . $html . '</div>' . $custom_html;

			/*if ( ! $wc_functions->is_attribute_valid( $attribute ) ) {
				return $html;
			}*/

			/*if ( ! array_key_exists( $attribute, $types ) ) {
				return $html;
			}*/

			/*$html = $html . '<h1>Teste</h1>';

			return $html;*/
		}

		/**
		 * Load scripts and styles
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function enqueue_scripts() {
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			// Main js file
			$js_file = 'assets/dist/frontend/js/alg-wc-aps' . $suffix . '.js';
			$js_ver  = date( "ymd-Gis", filemtime( ALG_WC_CIVS_DIR . $js_file ) );
			wp_register_script( 'alg-wc-civs', ALG_WC_CIVS_URL . $js_file, array( 'jquery' ), $js_ver, true );
			wp_enqueue_script( 'alg-wc-civs' );

			// Main css file
			$css_file = 'assets/dist/frontend/css/alg-wc-aps' . $suffix . '.css';
			$css_ver  = date( "ymd-Gis", filemtime( ALG_WC_CIVS_DIR . $css_file ) );
			wp_register_style( 'alg-wc-civs', ALG_WC_CIVS_URL . $css_file, array(), $css_ver );
			wp_enqueue_style( 'alg-wc-civs' );
		}
	}
}