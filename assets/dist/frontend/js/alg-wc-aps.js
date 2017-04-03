/**
 * @summary Main JS of Color or Image Variation Swatches for WooCommerce
 *
 * @version   1.0.0
 * @since     1.0.0
 * @requires  jQuery.js
 */

var alg_wc_civs = {};
jQuery(function ($) {
	alg_wc_civs = {

		/**
		 * Initiate
		 */
		init: function () {
			this.handle_terms('.alg-wc-civs-term', '.alg-wc-civs-original-select select');
		},

		handle_terms: function (term_str, select_str) {
			$(term_str).on('click', function () {
				var index = $(this).index();
				var select = $(this).parent().parent().find('select');
				var value = $(this).attr('data-value');
				var opt = select.find('option[value="' + value + '"]');

				if (!$(this).hasClass('active')) {
					if (opt.length) {
						opt.attr('selected', 'selected');
						select.trigger('change');
					} else {
						window.alert(wc_add_to_cart_variation_params.i18n_no_matching_variations_text);
						select.val('').trigger('change');
					}
				}else{
					select.val('').trigger('change');
				}
			});

			$('body').on('change', select_str, function () {
				var terms = $(this).parent().parent().find(term_str);
				var value = $(this).find('option:selected').attr('value');
				terms.removeClass('active');
				var term = $(this).parent().parent().find(term_str + '[data-value="' + value + '"]');
				term.addClass('active');
			});

			$(select_str).trigger('change');
		}
	}

	alg_wc_civs.init();
});