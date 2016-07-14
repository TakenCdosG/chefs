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
 * @see      https://docs.woothemes.com/document/template-structure/
 * @author    WooThemes
 * @package  WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if (!defined('ABSPATH')) {
  exit;
}

global $product;
if (isset($product->id)) {
  $defaults = array('fields' => 'ids');
  $args = wp_parse_args(array(), $defaults);
  $categories = wp_get_object_terms(array($product->id), 'product_cat');
  //$category = reset($categories);
  //dpm($result);
  if (!empty($breadcrumb)) {
    echo $wrap_before;
    echo $before;
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html("Home") . '</a>';
    echo $after;
    echo $delimiter;
    echo format_breadcrumb_category($categories, $before, $after, $delimiter, $product);
    echo $wrap_after;
  }
} else {
  if (!empty($breadcrumb)) {

    echo $wrap_before;

    foreach ($breadcrumb as $key => $crumb) {

      echo $before;

      if (!empty($crumb[1]) && sizeof($breadcrumb) !== $key + 1) {
        echo '<a href="' . esc_url($crumb[1]) . '">' . esc_html($crumb[0]) . '</a>';
      } else {
        echo esc_html($crumb[0]);
      }

      echo $after;

      if (sizeof($breadcrumb) !== $key + 1) {
        echo $delimiter;
      }

    }

    echo $wrap_after;

  }
}