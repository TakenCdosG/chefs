<?php

/**
 * Travelify defining constants, adding files and WordPress core functionality.
 *
 */
 
 /* Too late to implement a child theme */
/* Adding this here againts proper programming practices */

/* Woocommerce is caching the prices of the products. This script disables the cache */

/**
 * Disables the product price cache. This function looks for the cache key
 * that is about to be retrieved by WooCommerce, and deletes the cached data
 * associated to it.
 *
 * IMPORTANT
 * This function can have an impact on WooCommerce's performance, and it
 * should not be used unless necessary.
 * 
 * @param array cache_key_args The arguments that form the cache key.
 * @param WC_Product product The product for which the key is being generated.
 * @param bool display Indicates if the prices are being retrieved for display
 * purposes.
 * @return array
 * @since WooCommerce 2.4
 * @author Aelia <support@aelia.co>
 * @link http://aelia.co/2015/08/11/wc-2-4-workaround-for-price-cache
 */
/*function disable_variation_prices_cache($cache_key_args, $product, $display) {
  // Delete the cached data, if it exists
  $cache_key = 'wc_var_prices' . md5(json_encode($cache_key_args));
  delete_transient($cache_key);
  
  return $cache_key_args;
}
add_filter('woocommerce_get_variation_prices_hash', 'disable_variation_prices_cache', 10, 3);*/

function custom_price( $price, $product ) {
	
	$new_price = '';
	if ($product->regular_price == $product->price) {
		$new_price = '<span class="custom-price">$' . $product->regular_price . '</span>';
	}
	else {
		$new_price = '<del><span class="amount">Regular price: ' . $product->regular_price . '</span></del><br /><ins><span class="amount">Sale price: ' . $product->sale_price . '</span></ins>';
	}
    //dpm($new_price);
    //dpm($product);
	return $new_price;

}

function add_woocommerce_cart_nav_item($items, $args) {
	global $woocommerce;
	
	if ($args->menu == 77) {
        $items .= '<li class="menu-item-cart-item">'
        			. '<a class="cart-items" href="'.$woocommerce->cart->get_cart_url().'" title="View your shopping cart">CART('. $woocommerce->cart->cart_contents_count . ') </a> | '
        			. '<a class="checkout-cart" href="' . $woocommerce->cart->get_checkout_url() . '" title="' . __( 'Checkout' ) . '">' . __( 'Checkout' ) . '</a>'
        		. '</li>';
    }
    return $items;
}

add_filter('woocommerce_get_price_html', 'custom_price', 10, 2);
add_filter('wp_nav_menu_items','add_woocommerce_cart_nav_item', 10, 2);

/**
 * Disables the product price cache. This function looks for the cache key
 * that is about to be retrieved by WooCommerce, and deletes the cached data
 * associated to it.
 *
 * IMPORTANT
 * This function can have an impact on WooCommerce's performance, and it
 * should not be used unless necessary.
 *
 * @param array cache_key_args The arguments that form the cache key.
 * @param WC_Product product The product for which the key is being generated.
 * @param bool display Indicates if the prices are being retrieved for display
 * purposes.
 * @return array
 * @since WooCommerce 2.4
 * @author Aelia <support@aelia.co>
 * @link http://aelia.co/2015/08/11/wc-2-4-workaround-for-price-cache
 */
function disable_variation_prices_cache($cache_key_args, $product, $display) {
    // Delete the cached data, if it exists
    $cache_key = 'wc_var_prices' . md5(json_encode($cache_key_args));
    delete_transient($cache_key);

    return $cache_key_args;
}
add_filter('woocommerce_get_variation_prices_hash', 'disable_variation_prices_cache', 10, 3);

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width))
    $content_width = 700;

if (!function_exists('dpm')) {

    function dpm($var) {
        echo "<pre>";
        var_dump($var);
        echo "</pre>";
    }

}

if (!function_exists('travelify_setup')):

    add_filter('widget_text', 'do_shortcode');

    /**
     * Sets up theme defaults and registers support for various WordPress features.
     */
    add_action('after_setup_theme', 'travelify_setup');

    /**
     * Note that this function is hooked into the after_setup_theme hook, which runs
     * before the init hook. The init hook is too late for some features, such as indicating
     * support post thumbnails.
     *
     */
    function travelify_setup() {
        /**
         * travelify_add_files hook
         *
         * Adding other addtional files if needed.
         */
        do_action('travelify_add_files');

        /* Travelify is now available for translation. */
        require( get_template_directory() . '/library/functions/i18n.php' );

        /** Load functions */
        require( get_template_directory() . '/library/functions/functions.php' );

        /** Load WP backend related functions */
        require( get_template_directory() . '/library/panel/themeoptions-defaults.php' );
        require( get_template_directory() . '/library/panel/theme-options.php' );
        require( get_template_directory() . '/library/panel/metaboxes.php' );
        require( get_template_directory() . '/library/panel/show-post-id.php' );

        /** Load Shortcodes */
        require( get_template_directory() . '/library/functions/shortcodes.php' );

        /** Load WP Customizer */
        require( get_template_directory() . '/library/functions/customizer.php' );

        /** Load Structure */
        require( get_template_directory() . '/library/structure/header-extensions.php' );
        require( get_template_directory() . '/library/structure/sidebar-extensions.php' );
        require( get_template_directory() . '/library/structure/footer-extensions.php' );
        require( get_template_directory() . '/library/structure/content-extensions.php' );

        /**
         * travelify_add_functionality hook
         *
         * Adding other addtional functionality if needed.
         */
        do_action('travelify_add_functionality');

        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // This theme uses Featured Images (also known as post thumbnails) for per-post/per-page.
        add_theme_support('post-thumbnails');

        // This theme uses wp_nav_menu() in header menu location.
        register_nav_menu('primary', __('Primary Menu', 'travelify'));

        // Add Travelify custom image sizes
        add_image_size('featured', 670, 300, true);
        add_image_size('featured-medium', 230, 230, true);
        add_image_size('slider', 1018, 460, true);   // used on Featured Slider on Homepage Header
        add_image_size('gallery', 474, 342, true);     // used to show gallery all images
        // This feature enables WooCommerce support for a theme.
        add_theme_support('woocommerce');

        /**
         * This theme supports custom background color and image
         */
        $args = array(
            'default-color' => '#d3d3d3',
            'default-image' => get_template_directory_uri() . '/images/background.png',
        );
        add_theme_support('custom-background', $args);

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');

        /**
         * This theme supports add_editor_style
         */
        add_editor_style();
    }

endif; // travelify_setup

?>