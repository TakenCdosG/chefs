<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
$defaults = array('fields' => 'ids');
$args = wp_parse_args(  array(), $defaults );
$result = wp_get_object_terms(array($product->id), 'product_cat');
$category = reset($result);
// dpm($category);

if ( ! empty( $breadcrumb ) ) {

	echo $wrap_before;
  echo $before;
  echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( "Home" ) . '</a>';
  echo $after;
  echo $delimiter;
  echo $before;
  echo '<a href="' . esc_url( home_url( $category->slug.'/') ) . '">' . esc_html( $category->name ) . '</a>';
  echo $after;
  echo $delimiter;
  echo $before;
  echo esc_html( $product->get_formatted_name());
  echo $after;
	echo $wrap_after;

}
