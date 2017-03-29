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

		public $wc_attribute_types = array();

		function __construct() {
			add_action( 'admin_init', array( $this, 'initialize_types_variable' ) );
			//add_action( 'admin_init', array( $this, 'add_types_fields_and_columns' ) );

			// Adds new type attributes for WooCommerce variations
			add_filter( 'product_attributes_type_selector', array( $this, 'add_wc_attribute_types' ) );

			// CMB 2
			add_action( 'cmb2_admin_init', array( $this, 'cmb2_admin_init' ) );
			$object = 'term';
			$cmb_id = 'alg_wc_apva_term_label';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );
			$cmb_id = 'alg_wc_apva_term_image';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );
			$cmb_id = 'alg_wc_apva_term_color';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );

			// Custom scripts
			add_action( 'admin_footer', array( $this, 'add_scripts' ),999 );
		}

		/**
		 * Add scripts on admin.
         *
         * For now this function is required to translate type to label on product_page_product_attributes.
         * After woocommerce 3.0, this function can be removed as it will be fixed by them
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_scripts() {
		    $screen = get_current_screen();
			if ( $screen->id != 'product_page_product_attributes' ) {
				return;
			}
			?>
            <script>
                jQuery(document).ready(function($){
	                jQuery.expr[":"].contains = jQuery.expr.createPseudo(function(arg) {
		                return function( elem ) {
			                return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		                };
	                })
                	function alg_wc_apva_translate_type_to_label(){
		                var types = <?php echo wp_json_encode($this->wc_attribute_types); ?>;
		                $.each( types, function( key, value ) {
		                	console.log(key);
		                	$("td:contains('"+key+"')").html(value);
		                });
                    }
	                alg_wc_apva_translate_type_to_label();
                })
            </script>
			<?php
		}

		/**
		 * Searches on a multidimensional array by key and value
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		function array_search( $array, $key, $value ) {
			$results = array();

			if ( is_array( $array ) ) {
				if ( isset( $array[ $key ] ) && $array[ $key ] == $value ) {
					$results[] = $array;
				}

				foreach ( $array as $subarray ) {
					$results = array_merge( $results, $this->array_search( $subarray, $key, $value ) );
				}
			} else if ( gettype( $array ) == 'object' ) {
				if ( property_exists( $array, $key ) && $array->{$key} == $value ) {
					$results[] = $array;
				}
			}

			return $results;
		}

		/**
		 * Handles Cmb2 custom style
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function cmb2_custom_style( $post_id, $cmb ) {
			?>
            <style type="text/css" media="screen">
                [id*='alg_wc_apva_term'] .cmb-td {
                    margin-bottom: 20px;
                }

                [id*='alg_wc_apva_term'] .cmb-file-field-image {
                    margin-top: 15px;
                }

                #cmb2-metabox-alg_wc_apva_term_color .wp-color-result {
                    vertical-align: top;
                }

                .wp-list-table td[class*='alg_wc_apva_term'] {
                    vertical-align: middle;
                }

                .alg-wc-apva-display {
                    -webkit-box-shadow: 3px 3px 9px 0px rgba(0, 0, 0, 0.28);
                    -moz-box-shadow: 3px 3px 9px 0px rgba(0, 0, 0, 0.28);
                    box-shadow: 3px 3px 9px 0px rgba(0, 0, 0, 0.28);
                    border: 2px solid #ccc;
                    width: 32px;
                    height: 32px;
                    text-align: center;
                    text-align: center;
                    line-height: 30px;
                }

                .alg-wc-apva-color-display {

                }

                .alg-wc-apva-label-display {
                    padding: 0px 0 0 0;
                    color: #888;
                    font-weight: bold;
                }

                .alg-wc-apva-image-display {
                    width: 38px;
                    height: 38px;
                    padding: 0px 0 0 0;
                    background-size: cover;
                }
            </style>
			<?php

		}

		/**
		 * Initializes types variable
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function initialize_types_variable() {
			$new_types                = array(
				'alg_wc_apva_color' => __( 'Color (apva)', 'appealing-variation-for-woocommerce' ),
				'alg_wc_apva_image' => __( 'Image (apva)', 'appealing-variation-for-woocommerce' ),
				'alg_wc_apva_label' => __( 'Label (apva)', 'appealing-variation-for-woocommerce' ),
			);
			$this->wc_attribute_types = $new_types;
		}

		/**
		 * Manually renders a image field column display.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param  array      $field_args Array of field arguments.
		 * @param  CMB2_Field $field      The field object
		 */
		public function cmb2_image_display( $field_args, CMB2_Field $field ) {
			//debug($field);
			//$id = CMB2_Utils::image_id_from_url( esc_url_raw( $field->escaped_value()) );
			$id = $field->get_field_clone( array(
				'id' => $field->_id() . '_id',
			) )->escaped_value( 'absint' );

			$img_size  = $field->args( 'preview_size' );
			$image_src = wp_get_attachment_image_src( $id, $img_size );

			?>
            <div class="custom-column-display <?php echo $field->row_classes(); ?>">
                <div class="alg-wc-apva-display alg-wc-apva-image-display"
                     style="background-image: url(<?php echo $image_src[0]; ?>)"></div>
            </div>
			<?php
		}

		/**
		 * Manually renders a color field column display.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param  array      $field_args Array of field arguments.
		 * @param  CMB2_Field $field      The field object
		 */
		public function cmb2_color_display( $field_args, CMB2_Field $field ) {
			?>
            <div class="custom-column-display <?php echo $field->row_classes(); ?>">
                <div class="alg-wc-apva-display alg-wc-apva-color-display"
                     style="background:<?php echo $field->escaped_value(); ?>"></div>
            </div>
			<?php
		}

		/**
		 * Manually renders a color field column display.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param  array      $field_args Array of field arguments.
		 * @param  CMB2_Field $field      The field object
		 */
		public function cmb2_label_display( $field_args, CMB2_Field $field ) {
			?>
            <div class="custom-column-display <?php echo $field->row_classes(); ?>">
                <div class="alg-wc-apva-display alg-wc-apva-label-display"><?php echo $field->escaped_value(); ?></div>
            </div>
			<?php
		}

		/**
		 * Handles Image admin field for woocommerce variation types
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tax
		 */
		public function alg_wc_apva_image_fields( $wc_taxes ) {
			$prefix = 'alg_wc_apva_term_image_';
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_apva_term_image',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'         => esc_html__( 'Image', 'cmb2' ),
				'id'           => $prefix . 'image',
				'display_cb'   => array( $this, 'cmb2_image_display' ),
				'type'         => 'file',
				'options'      => array(
					'url' => false, // Hide the text input for the url
				),
				'preview_size' => array( 35, 35 ), // Default: array( 50, 50 )
				'attributes'   => array( 'style' => 'width:95%' ),
				'column'       => true, // Display field value in the admin post-listing columns
				'on_front'     => false,
			) );
		}

		/**
		 * Handles Color admin field for woocommerce variation types
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tax
		 */
		public function alg_wc_apva_color_fields( $wc_taxes ) {
			$prefix = 'alg_wc_apva_term_color_';

			/**
			 * Metabox to add fields to categories and tags
			 */
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_apva_term_color',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'         => esc_html__( 'Color', 'cmb2' ),
				'id'           => $prefix . 'color',
				'type'         => 'colorpicker',
				'display_cb'   => array( $this, 'cmb2_color_display' ),
				'options'      => array(
					'url' => false, // Hide the text input for the url
				),
				'preview_size' => array( 50, 50 ), // Default: array( 50, 50 )
				'column'       => true, // Display field value in the admin post-listing columns
				'on_front'     => false,
			) );
		}

		/**
		 * Handles Label admin field for woocommerce variation types
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $tax
		 */
		public function alg_wc_apva_label_fields( $wc_taxes ) {
			$prefix = 'alg_wc_apva_term_label_';

			/**
			 * Metabox to add fields to categories and tags
			 */
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_apva_term_label',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'       => esc_html__( 'Label', 'cmb2' ),
				'display_cb' => array( $this, 'cmb2_label_display' ),
				'id'         => $prefix . 'label',
				'type'       => 'text',
				'attributes' => array( 'style' => 'width:95%' ),
				'column'     => true, // Display field value in the admin post-listing columns
				'on_front'   => false,
			) );
		}

		/**
		 * Initialize Cmb2 that will create admin fields for woocommerce variation types
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function cmb2_admin_init() {
			$this->initialize_types_variable();
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( empty( $attribute_taxonomies ) ) {
				return;
			}

			foreach ( $this->wc_attribute_types as $key => $type ) {
				$found_types = $this->array_search( $attribute_taxonomies, 'attribute_type', $key );
				$taxonomies  = array();
				$found       = false;
				foreach ( $found_types as $found_type ) {
					if ( $found_type->attribute_type == $key ) {
						$found = true;
					}
					$taxonomies[] = wc_attribute_taxonomy_name( $found_type->attribute_name );
				}
				if ( $found ) {
					call_user_func( array( $this, $key . '_fields' ), $taxonomies );
				}
			}
		}

		/**
		 * Adds new type attributes for WooCommerce
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_wc_attribute_types( $types ) {
			$new_types = $this->wc_attribute_types;

			return array_merge( $types, $new_types );
		}
	}
}