<?php
/**
 * Bootstrap the theme.
 *
 * @package Di Restaurant
 */

// Add class Di_Restaurant_Engine, responsible for setup, styles, scripts, sidebar registration.
require_once get_template_directory() . '/inc/core/class-di-restaurant-engine.php';

// Add class Di_Restaurant_Actions_Filter, responsible for mostly actions and filters.
require_once get_template_directory() . '/inc/core/class-di-restaurant-actions-filters.php';

// Add class Di_Restaurant_Topmain_Nav_Walker, nav walker for main top nav.
require_once get_template_directory() . '/inc/core/class-di-restaurant-topmain-nav-walker.php';

// Add class Di_Restaurant_Methods, for individual method.
require_once get_template_directory() . '/inc/core/class-di-restaurant-methods.php';

// Add class Di_Restaurant_Page_Metabox, for page metabox options.
require_once get_template_directory() . '/inc/core/class-di-restaurant-page-metabox.php';

// Add class Di_Restaurant_Post_Metabox, for page metabox options.
require_once get_template_directory() . '/inc/core/class-di-restaurant-post-metabox.php';

// Add Di Restaurant Theme Page..
require_once get_template_directory() . '/inc/core/class-di-restaurant-theme-page.php';

// Add class Di_Restaurant_Woo.
require_once get_template_directory() . '/inc/core/class-di-restaurant-woo.php';

// Add Rosa Lite style homepage and demo content.
require_once get_template_directory() . '/inc/core/class-di-restaurant-rosa.php';

// Rosa extended features: WooCommerce, visitor counter.
require_once get_template_directory() . '/inc/core/class-di-restaurant-rosa-features.php';

// Vietnamese content data.
require_once get_template_directory() . '/inc/core/rosa-vietnamese-data.php';

// Rosa AI chatbot (Gemini).
require_once get_template_directory() . '/inc/core/class-di-restaurant-rosa-chatbot.php';

// Add class TGM_Plugin_Activation.
require_once get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

// Add TGM_Plugin_Activation config.
require_once get_template_directory() . '/inc/tgm/tgm-options.php';

// Include kirki plugin files if it is not activated.
if ( ! class_exists( 'Kirki' ) ) {
	require get_template_directory() . '/inc/kirki/kirki/kirki.php';
}

// kirki-options.php file contain translation ready text so should be load at init or later otherwise throw PHP Notice.
add_action( 'init', function() {
	// Include the kirki options file.
	require get_template_directory() . '/inc/kirki/kirki-options.php';	
} );

