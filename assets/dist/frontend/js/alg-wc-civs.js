/**
 * @summary Main JS of Color or Image Variation Swatches for WooCommerce
 *
 * @version   1.0.2
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
			jQuery(document).bind('woocommerce_update_variation_values', function () {
				alg_wc_civs.remove_invalid_attributes();
			});
		},

		remove_invalid_attributes:function(){
			select = $(this.original_select_selector);
			all_terms = $(this.term_selector);
			all_terms.addClass('disabled');

			select.each(function (index) {
				var options = $(this).find('option:not([disabled])');
				options.each(function (index) {
					var value = $(this).attr('value');
					var term = $(this).parent().parent().parent().find(alg_wc_civs.term_selector + '[data-value="' + value + '"]');
					term.removeClass('disabled');
				});
			});
		},

		sync_terms_and_selects: function () {
			term_str = this.term_selector;
			select_str = this.original_select_selector;

			// Triggers the corresponding select and show an alert in case the combination does not exist
			$(term_str).on('click', function () {
				var select = $(this).parent().parent().find('select');
				var value = $(this).attr('data-value');
				var opt = select.find('option[value="' + value + '"]');

				//if(!$(this).hasClass('disabled')){
					if (!$(this).hasClass('active')) {
						if (opt.length || !$(this).hasClass('disabled')) {
							opt.attr('selected', 'selected');
							select.trigger('change');
						} else {
							window.alert(wc_add_to_cart_variation_params.i18n_no_matching_variations_text);
							select.val('').trigger('change');
						}
					} else {
						select.val('').trigger('change');
					}
				//}
			});

			// Highlights the corresponding term according to select
			$('body').on('change', select_str, function () {
				var terms = $(this).parent().parent().find(term_str);
				var value = $(this).find('option:selected').attr('value');
				terms.removeClass('active');
				var term = $(this).parent().parent().find(term_str + '[data-value="' + value + '"]');
				var event = {
					type: "alg_wc_civs_term_click",					
					term: null,
					select: $(this),
					active: false
				};
				if(term.length){
					term.addClass('active');
					event.active = true;
					event.term = term;	
				}
				$("body").trigger(event);
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