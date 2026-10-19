/**
 * Frontend entry point of Color or Image Variation Swatches for WooCommerce.
 *
 * Bundled into assets/js/alg-wc-civs.js by the build script.
 * Keeps the global `alg_wc_civs` object for backwards compatibility.
 *
 * @version   1.2.3
 * @since     1.2.3
 * @requires  jQuery.js
 */

import alg_wc_civs from './modules/main';

window.alg_wc_civs = alg_wc_civs;

jQuery(function ($) {
	alg_wc_civs.init();
	$('body').trigger({
		type: 'alg_wc_civs',
		obj: alg_wc_civs
	});
});
