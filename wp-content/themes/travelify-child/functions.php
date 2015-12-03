<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/custom.css' );
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/editor-style.css' );

}

/* Woocommerce price */
add_filter('woocommerce_variable_price_html', 'custom_variation_price', 10, 2);

function custom_variation_price( $price, $product ) {

	$price = '';

	if ( !$product->min_variation_price || $product->min_variation_price !== $product->max_variation_price ) {
		$price .= '<span class="from">' . _x('From', 'min_price', 'woocommerce') . ' </span>';
		$price .= woocommerce_price($product->get_price());
	}

	return $price;
}
