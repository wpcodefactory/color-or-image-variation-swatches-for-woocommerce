/**
 * @summary Main JS of Color or Image Variation Swatches for WooCommerce
 *
 * @version   1.0.9
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
			$('.variations_form').bind('woocommerce_update_variation_values', function () {
				alg_wc_civs.remove_invalid_attributes();
			});
		},

		remove_invalid_attributes: function () {
			var select = $(this.original_select_selector);
			var all_terms = $(this.term_selector);
			all_terms.addClass('disabled');

			select.each(function () {
				var options = $(this).find('option:not([disabled])');
				options.each(function () {
					var value = $(this).attr('value');
					var term = $(this).closest('td').find(alg_wc_civs.term_selector + '[data-value="' + value + '"]');
					term.removeClass('disabled');
				});
			});
		},

		sync_terms_and_selects: function () {
			var term_str = this.term_selector;
			var select_str = this.original_select_selector;

			// Triggers the corresponding select and show an alert in case the combination does not exist
			jQuery('body').on('click', term_str, function () {
                var data_attribute = $(this).attr('data-attribute');
                var select = $("select#"+data_attribute);
				var value = $(this).attr('data-value');
				var opt = select.find('option[value="' + value + '"]');

				if (!$(this).hasClass('active')) {
					if (opt.length || !$(this).hasClass('disabled')) {
						opt.prop('selected', true);
						select.trigger('change');                        
					} else {
						window.alert(wc_add_to_cart_variation_params.i18n_no_matching_variations_text);
						select.val('').trigger('change');
					}
				} else {
					select.val('').trigger('change');
				}

				alg_wc_civs.remove_invalid_attributes();
			});

			// Highlights the corresponding term according to select
            jQuery('body').on('change', select_str, function () {
				var terms = $(this).closest('td').find(term_str);
				var value = $(this).find('option:selected').attr('value');
				terms.removeClass('active');
				var term = $(this).closest('td').find(term_str + '[data-value="' + value + '"]');
				var event = {
					type: 'alg_wc_civs_term_click',
					term: null,
					select: $(this),
					active: false
				};
				if (term.length) {
					term.addClass('active');
					event.active = true;
					event.term = term;
				}
				$('body').trigger(event);
				alg_wc_civs.remove_invalid_attributes();
			});

			$(select_str).trigger('change');
		}
	};

	alg_wc_civs.init();
	$('body').trigger({
		type: 'alg_wc_civs',
		obj: alg_wc_civs
	});
});