/**
 * @summary Main JS of Color or Image Variation Swatches for WooCommerce
 *
 * @version   1.0.1
 * @since     1.0.0
 * @requires  jQuery.js
 */

var alg_wc_civs = {};
jQuery(function ($) {
	alg_wc_civs = {

		term_selector: null,
		original_select_selector: null,

		/**
		 * Initiate
		 */
		init: function () {
			this.term_selector = '.alg-wc-civs-term';
			this.original_select_selector = '.alg-wc-civs-original-select select';
			this.sync_terms_and_selects();
		},

		sync_terms_and_selects: function () {
			term_str = this.term_selector;
			select_str = this.original_select_selector;

			// Triggers the corresponding select and show an alert in case the combination does not exist
			$(term_str).on('click', function () {
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
				} else {
					select.val('').trigger('change');
				}
			});

			// Highlights the corresponding term according to select
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
	$("body").trigger({
		type: "alg_wc_civs",
		obj: alg_wc_civs
	});
});