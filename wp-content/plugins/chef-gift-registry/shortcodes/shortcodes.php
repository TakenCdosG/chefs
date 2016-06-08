<?php
/**
 * Register all shortcodes
 *
 * @package   Chef Gift Registry - Shortcodes
 * @author    Adrián Morelos
 * @copyright Copyright (c) 2016, adrian-morelos.github.io
 * @link      http://adrian-morelos.github.io
 * @since     1.0.0
 */

define('CHEF_GIFT_REGISTRY_PLUGIN_DIR', dirname(__FILE__));

function chef_gift_search_registry_shortcode($atts){

    wp_enqueue_style('chef-gift-registry');
    wp_enqueue_script('chef-gift-registry');
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to Google's hosted jQuery UI CSS.
    wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' ); 

    $field_wishlist_type_key = "field_575726e432f3c";
    $field_wishlist_type = get_field_object($field_wishlist_type_key);

    $registrant_name = get_query_var( 'registrant-name', '' ); 
    $co_registrant_name = get_query_var( 'co-registrant-name', '' ); 
    $registrant_email = get_query_var( 'registrant-email', '' ); 
    $co_registrant_email = get_query_var( 'co-registrant-email', '' );
    $event_name = get_query_var( 'event-name', '' );
    $event_type = get_query_var( 'event-type', '' );
    $registry_no = get_query_var( 'registry-no', '');
    $event_date = get_query_var( 'event-date', '');

    $args = array();
    $args['post_type'] = 'custom_wishlists';
    $args["meta_query"] = array();

    if(!empty($registrant_name)){
        $args["s"] => $registrant_name;
    }

    if(!empty($registry_no) && is_numeric($registry_no)){
        $args["page_id"] = $registry_no;
    }

    if(!empty($registrant_name)){
        $args["meta_query"][]= array(
            'key'       => 'registrant_name',
            'value'     => $registrant_name,
            'compare'   => 'LIKE'
        );
    }

    if(!empty($co_registrant_name)){
        $args["meta_query"][]= array(
            'key'       => 'co-registrant_name',
            'value'     => $co_registrant_name,
            'compare'   => 'LIKE'
        );
    }

    if(!empty($registrant_email)){
        $args["meta_query"][]= array(
            'key'       => 'registrant_email',
            'value'     => $registrant_email,
            'compare'   => 'LIKE'
        );
    }

    if(!empty($co_registrant_email)){
        $args["meta_query"][]= array(
            'key'       => 'co-registrant_email',
            'value'     => $co_registrant_email,
            'compare'   => 'LIKE'
        );
    }

    if(!empty($event_type) && $event_type != "_none"){
        $args["meta_query"][]= array(
            'key'       => 'wishlist_type',
            'value'     => $event_type,
            'compare'   => '='
        );
    }

    if(!empty($event_date)){
        $args["meta_query"][]= array(
            'key'       => 'event_date',
            'value'     => $event_date,
            'compare'   => '='
        );
    }
    
    // Return output
    include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/chef-gift-registry.tpl.php');

    if(count($args["meta_query"])>0 || isset($args["s"]) || isset($args["page_id"])){
        $args["meta_query"]['relation'] = "AND";
        // query
        $wishlists = new WP_Query( $args );
        include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/chef-gift-registry-wishlists.tpl.php');
    }
}

add_shortcode('chef_gift_search_registry', 'chef_gift_search_registry_shortcode');
