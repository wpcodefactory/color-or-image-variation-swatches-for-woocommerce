<?php
/**
 * Wish List for WooCommerce - Meta box about the Pro version
 *
 * @version 1.0.1
 * @since   1.0.1
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_CIVS_Custom_Settings_CMB' ) ) {

	class Alg_WC_CIVS_Custom_Settings_CMB {

		/**
		 * Constructor.
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 */
		function __construct() {
			$value = 'meta_box';
			add_action( 'woocommerce_admin_field_' . $value, array( $this, 'add_meta_box' ), 10, 2 );
		}

		/**
		 * Creates meta box
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 */
		public static function add_meta_box( $value ) {
			// Doesn't show metabox if show_in_pro = false and it's loading from pro
			if ( ! $value['show_in_pro'] ) {
				if ( defined('ALG_WC_CIVS_PRO_DIR') ) {
					return;
				}
			}

			$option_description = $value['description'];
			$option_title       = $value['title'];
			$option_id          = esc_attr( $value['id'] );

			echo '
			<div id="poststuff">									
				<div id="' . $option_id . '" class="postbox">
					<h2 class="hndle"><span>' . $option_title . '</span></h2>
					<div class="inside">
						' . $option_description . '
					</div>
				</div>
			</div>';
			echo "
			<style>
				.alg-admin-accordion .details-container{display:none;margin-top:10px;}
				.alg-admin-accordion .accordion-item{
					cursor:pointer;					
				}
				.alg-admin-accordion .accordion-item:hover{
					text-decoration: underline;
				}
				.alg-admin-accordion .accordion-item:before{
					  content:' ';
					  width: 0; 
					  height: 0; 
					  border-left: 5px solid transparent;
					  border-right: 5px solid transparent;					  
					  border-top: 9px solid #000;
					  display:inline-block;
					  margin-right:7px;	
					  transition:all 0.3s ease-in-out;
					  transform: rotate(-90deg);
				}
				.alg-admin-accordion .accordion-item.active:before{
					transform: rotate(0deg);
    				transform-origin: 50% 50%;			  
				}
			</style>
            <script>
            	jQuery(document).ready(function($){
            		$('.alg-admin-accordion .accordion-item').on('click',function(){
            			if($(this).hasClass('active')){
            				$(this).removeClass('active');
            				$(this).find('.details-container').slideUp();
            			}else{
            				$('.alg-admin-accordion .accordion-item .details-container').slideUp();
            				$('.alg-admin-accordion .accordion-item').removeClass('active');
            				$(this).addClass('active');   
            				$(this).find('.details-container').slideDown();
            			}
					})
            	})
			</script>
			";
		}

		/**
		 * Returns class name
		 *
		 * @version 1.0.1
		 * @since   1.0.1
		 * @return type
		 */
		public static function get_class_name() {
			return get_called_class();
		}
	}
}