<?php
/**
 * Color or Image Variation Swatches for WooCommerce - Admin Types
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

if ( ! class_exists( 'Alg_WC_CIVS_Admin_Types' ) ) {

	class Alg_WC_CIVS_Admin_Types {

		public $wc_attribute_types = array();

		function __construct() {

			// Initializes $wc_attribute_types variable with types
			$this->initialize_types_variable();
			add_action( 'admin_init', array( $this, 'initialize_types_variable' ) );

			// Adds new type attributes for WooCommerce variations
			add_filter( 'product_attributes_type_selector', array( $this, 'add_wc_attribute_types' ) );
		}

		function init() {
			// Adds attribute values on product attribute tab
			add_action( 'woocommerce_product_option_terms', array( $this, 'add_attribute_values_on_tabs' ), 10, 2 );

			// CMB 2
			add_action( 'cmb2_admin_init', array( $this, 'cmb2_admin_init' ) );
			$object = 'term';
			$cmb_id = 'alg_wc_civs_term_label';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );
			$cmb_id = 'alg_wc_civs_term_image';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );
			$cmb_id = 'alg_wc_civs_term_color';
			add_action( "cmb2_after_{$object}_form_{$cmb_id}", array( $this, 'cmb2_custom_style' ), 10, 2 );
		}

		/**
		 * Adds attribute values on product attribute tab.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 *
		 * @param $attribute_taxonomy
		 * @param $i
		 */
		public function add_attribute_values_on_tabs( $attribute_taxonomy, $i ) {
			if ( ! array_key_exists( $attribute_taxonomy->attribute_type, $this->wc_attribute_types ) ) {
				return;
			}

			$tax_name = wc_attribute_taxonomy_name( $attribute_taxonomy->attribute_name );
			global $thepostid;

			?>

            <select multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select terms', 'woocommerce' ); ?>" class="multiselect attribute_values wc-enhanced-select" name="attribute_values[<?php echo $i; ?>][]">
				<?php
				$args = array(
					'orderby'    => 'name',
					'hide_empty' => 0,
				);
				$all_terms = get_terms( $tax_name, apply_filters( 'woocommerce_product_attribute_terms', $args ) );
				if ( $all_terms ) {
					foreach ( $all_terms as $term ) {
						echo '<option value="' . esc_attr( $term->term_id ) . '" ' . selected( has_term( absint( $term->term_id ), $tax_name, $thepostid ), true, false ) . '>' . esc_attr( apply_filters( 'woocommerce_product_attribute_term_name', $term->name, $term ) ) . '</option>';
					}
				}
				?>
            </select>
            <button class="button plus select_all_attributes"><?php _e( 'Select all', 'woocommerce' ); ?></button>
            <button class="button minus select_no_attributes"><?php _e( 'Select none', 'woocommerce' ); ?></button>
            <button class="button fr plus add_new_attribute"><?php _e( 'Add new', 'woocommerce' ); ?></button>

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
                [id*='alg_wc_civs_term'] .cmb-td {
                    margin-bottom: 20px;
                }

                [id*='alg_wc_civs_term'] .cmb-file-field-image {
                    margin-top: 15px;
                }

                #cmb2-metabox-alg_wc_civs_term_color .wp-color-result {
                    vertical-align: top;
                }

                .wp-list-table td[class*='alg_wc_civs_term'] {
                    vertical-align: middle;
                }

                .alg-wc-civs-display {
                    border: 2px solid #ccc;
                    width: 36px;
                    height: 36px;
                    text-align: center;
                    text-align: center;
                    line-height: 34px;
                }

                .alg-wc-civs-color-display {

                }

                .alg-wc-civs-label-display {
                    padding: 0px 0 0 0;
                    color: #888;
                    font-weight: bold;
                }

                .alg-wc-civs-image-display {
                    /*width: 38px;
                    height: 38px;*/
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
				'alg_wc_civs_color' => __( 'Color', 'color-or-image-variation-swatches-for-woocommerce' ),
				'alg_wc_civs_image' => __( 'Image', 'color-or-image-variation-swatches-for-woocommerce' ),
				'alg_wc_civs_label' => __( 'Label', 'color-or-image-variation-swatches-for-woocommerce' ),
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
			$id = $field->get_field_clone( array(
				'id' => $field->_id() . '_id',
			) )->escaped_value( 'absint' );


			$img_size  = $field->args( 'preview_size' );
			$image_src = wp_get_attachment_image_src( $id, $img_size );

			?>
            <div class="custom-column-display <?php echo $field->row_classes(); ?>">
                <div class="alg-wc-civs-display alg-wc-civs-image-display"
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
                <div class="alg-wc-civs-display alg-wc-civs-color-display"
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
                <div class="alg-wc-civs-display alg-wc-civs-label-display"><?php echo $field->escaped_value(); ?></div>
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
		public function alg_wc_civs_image_fields( $wc_taxes ) {
			$prefix   = 'alg_wc_civs_term_image_';
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_civs_term_image',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'         => esc_html__( 'Image', 'color-or-image-variation-swatches-for-woocommerce' ),
				'id'           => $prefix . 'image',
				'display_cb'   => array( $this, 'cmb2_image_display' ),
				'type'         => 'file',
				'options'      => array(
					'url' => false, // Hide the text input for the url
				),
				'preview_size' => array( 35, 35 ), // Default: array( 50, 50 )
				'attributes'   => array( 'style' => 'width:95%' ),
				'column'       => array(
					'position' => 1,
				),
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
		public function alg_wc_civs_color_fields( $wc_taxes ) {
			$prefix = 'alg_wc_civs_term_color_';

			/**
			 * Metabox to add fields to categories and tags
			 */
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_civs_term_color',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'         => esc_html__( 'Color', 'color-or-image-variation-swatches-for-woocommerce' ),
				'id'           => $prefix . 'color',
				'type'         => 'colorpicker',
				'display_cb'   => array( $this, 'cmb2_color_display' ),
				'options'      => array(
					'url' => false, // Hide the text input for the url
				),
				'preview_size' => array( 50, 50 ), // Default: array( 50, 50 )
				'column'       => array(
					'position' => 1,
				),
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
		public function alg_wc_civs_label_fields( $wc_taxes ) {
			$prefix = 'alg_wc_civs_term_label_';

			/**
			 * Metabox to add fields to categories and tags
			 */
			$cmb_term = new_cmb2_box( array(
				'id'           => 'alg_wc_civs_term_label',
				'cmb_styles'   => isset( $_GET['wp_http_referer'] ) ? true : false,
				'object_types' => array( 'term' ),
				'taxonomies'   => $wc_taxes,
			) );
			$cmb_term->add_field( array(
				'name'       => esc_html__( 'Label', 'color-or-image-variation-swatches-for-woocommerce' ),
				'display_cb' => array( $this, 'cmb2_label_display' ),
				'id'         => $prefix . 'label',
				'type'       => 'text',
				'attributes' => array( 'style' => 'width:95%' ),
				'column'     => array(
					'position' => 1,
				),
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