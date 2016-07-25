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
//Ajax for updating / creating / deleting / managing wishlists
add_action('wp_ajax_chef_gift_registry_action', 'chef_gift_registry_action_callback', 1);
add_action('wp_ajax_chef_gift_registry_add_action', 'chef_gift_registry_add_action_callback', 1);
add_filter('login_redirect', 'redirect_add_registry', 10, 3);

function redirect_add_registry($redirect_to, $request, $user)
{
  //is there a user to check?
  if (isset($user->roles) && is_array($user->roles)) {
    //check for admins
    if (in_array('administrator', $user->roles)) {
      // redirect them to the default place
      return $redirect_to;
    } else {
      $redirect_url = get_query_var('redirect-url', '');
      // die(var_dump($redirect_ur));
      if ($redirect_url == "AddRegistry") {
        $redirect_to = home_url("/registry-page?redirect-action=AddRegistry");
        return $redirect_to;
      } else {
        return $redirect_to;
      }
    }
  } else {
    return $redirect_to;
  }
}

function chef_gift_registry_add_action_callback()
{
  if (!wp_verify_nonce($_POST['_wpnonce'], 'add_to_wishlist') || !absint($_POST['u'])) {
    _e('Adding the item to your wishlist failed.', 'ignitewoo-wishlists-pro');
    die;
  }

  $taxonomy_id = absint($_POST['wishlist_num']);
  if (!$taxonomy_id) {
    _e('There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro');
    die;
  }

  $wishlist_type = get_term($taxonomy_id, 'c_wishlists_cat', OBJECT);
  if (!$wishlist_type) {
    _e('There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro');
    die;
  }

  if ('' == trim(strip_tags($_POST['wishlist_title']))) {
    _e('You must specify a Wishlist title.', 'ignitewoo-wishlists-pro');
    die;
  }

  $user = absint($_POST['u']);

  // We have 3 predefined types: public, private, and shared.
  // Parse to find which string is in the slug so we can define the list.
  // Do this just in case an admin modifies the taxonomies.
  if (strpos($wishlist_type->slug, 'wishlist_public') !== false)
    $wishlist_type = 'public';

  else if (strpos($wishlist_type->slug, 'wishlist_private') !== false)
    $wishlist_type = 'private';

  else if (strpos($wishlist_type->slug, 'wishlist_shared') !== false)
    $wishlist_type = 'shared';

  else $wishlist_type = 'public';

  $wishlist_title = $_POST['wishlist_title'];

  $args = array(
    'post_type' => 'custom_wishlists',
    'post_title' => strip_tags($wishlist_title),
    'post_content' => '',
    'post_status' => 'publish', // save as draft just to be certain no public viewing can happening
    'post_author' => $user
  );

  $post_id = wp_insert_post($args);
  if ($post_id) {
    wp_set_post_terms($post_id, array($taxonomy_id), 'c_wishlists_cat');
    update_post_meta($post_id, 'wishlist_type', $wishlist_type);
    $event_type = isset($_POST['event-type']) ? $_POST['event-type'] : "";
    $event_date = isset($_POST['event-date']) ? $_POST['event-date'] : "";
    $co_registrant_name = isset($_POST['co-registrant-name']) ? $_POST['co-registrant-name'] : "";
    $co_registrant_email = isset($_POST['co-registrant-email']) ? $_POST['co-registrant-email'] : "";

    save_additional_wishlists_info($post_id, $user, $event_type, $event_date, $co_registrant_name, $co_registrant_email);

    include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/wishlist-add-action-success-result.tpl.php');
    die;
  } else {
    _e('There was an error adding the wishlist. Please try again shortly.', 'ignitewoo-wishlists-pro');
    die;
  }
}

function chef_gift_registry_action_callback()
{
  if (!isset($_POST['nonce']))
    die('no');
  if (!wp_verify_nonce($_POST['nonce'], 'wishlist_nonce'))
    die('fail');

  $wishlist_types = get_terms('c_wishlists_cat', '&hide_empty=0&order_by=id&order=asc');
  remove_all_filters('pre_get_posts');
  remove_all_filters('the_posts');
  remove_all_filters('wp');
  $args = array(
    'post_type' => 'custom_wishlists',
    'post_status' => 'publish',
    'order_by' => 'ID',
    'order' => 'ASC',
    'showposts' => 9999,
    'author' => absint($_POST['user']),
  );
  $user_wishlists = new WP_Query($args);
  if (is_user_logged_in()) {
    include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/wishlist-box-wrapper.tpl.php');
    die;
  }
}

function chef_gift_search_registry_shortcode($atts)
{
  global $wp;
  $redirect_action = get_query_var('redirect-action', '');

  wp_enqueue_script('chef-gift-registry-jquery-validate');
  wp_enqueue_style('chef-gift-registry');
  wp_enqueue_script('chef-gift-registry');
  // Load the datepicker script (pre-registered in WordPress).
  wp_enqueue_script('jquery-ui-datepicker');


  // You need styling for the datepicker . For simplicity I've linked to Google's hosted jQuery UI CSS.
  wp_register_style('jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css');
  wp_enqueue_style('jquery-ui');

  // prettyPhoto Assets
  wp_enqueue_script('prettyPhoto', WC()->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.js', array('jquery'), WC()->version, true);
  wp_enqueue_style('woocommerce_prettyPhoto_css', WC()->plugin_url() . '/assets/css/prettyPhoto.css');

  $is_user_logged_in = "FALSE";
  if (is_user_logged_in()) {
    $is_user_logged_in = "TRUE";
  }
  $user_id = get_current_user_id();

  $redirect_url = home_url("/my-account?redirect-url=AddRegistry");

  $data = array(
    'is_user_logged_in' => $is_user_logged_in,
    'wishlist_nonce' => wp_create_nonce('wishlist_nonce'),
    'admin_url' => admin_url('admin-ajax.php'),
    'user_id' => $user_id,
    'redirect_url' => $redirect_url,
    'redirect_action' => $redirect_action,
  );
  wp_localize_script('chef-gift-registry', 'chef_gift_registry', $data);

  $field_wishlist_type_key = "field_575726e432f3c";
  $field_wishlist_type = get_field_object($field_wishlist_type_key);

  $registrant_name = get_query_var('registrant-name', '');
  $co_registrant_name = get_query_var('co-registrant-name', '');
  $registrant_email = get_query_var('registrant-email', '');
  $co_registrant_email = get_query_var('co-registrant-email', '');
  $event_name = get_query_var('event-name', '');
  $event_type = get_query_var('event-type', '');
  $registry_no = get_query_var('registry-no', '');
  $event_date = get_query_var('event-date', '');

  $args = array();
  $args['post_type'] = 'custom_wishlists';
  $args["meta_query"] = array();

  if (!empty($event_name)) {
    $args["s"] = $event_name;
  }

  if (!empty($registry_no) && is_numeric($registry_no)) {
    $args["page_id"] = $registry_no;
  }

  if (!empty($registrant_name)) {
    $args["meta_query"][] = array(
      'key' => 'registrant_name',
      'value' => $registrant_name,
      'compare' => 'LIKE'
    );
  }

  if (!empty($co_registrant_name)) {
    $args["meta_query"][] = array(
      'key' => 'co-registrant_name',
      'value' => $co_registrant_name,
      'compare' => 'LIKE'
    );
  }

  if (!empty($registrant_email)) {
    $args["meta_query"][] = array(
      'key' => 'registrant_email',
      'value' => $registrant_email,
      'compare' => 'LIKE'
    );
  }

  if (!empty($co_registrant_email)) {
    $args["meta_query"][] = array(
      'key' => 'co-registrant_email',
      'value' => $co_registrant_email,
      'compare' => 'LIKE'
    );
  }

  if (!empty($event_type) && $event_type != "_none") {
    $args["meta_query"][] = array(
      'key' => 'wishlist_type',
      'value' => $event_type,
      'compare' => '='
    );
  }

  if (!empty($event_date)) {
    $args["meta_query"][] = array(
      'key' => 'event_date',
      'value' => $event_date,
      'compare' => '='
    );
  }

  // Return output
  include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/chef-gift-registry.tpl.php');

  if (count($args["meta_query"]) > 0 || isset($args["s"]) || isset($args["page_id"])) {
    $args["meta_query"]['relation'] = "AND";
    // query
    $wishlists = new WP_Query($args);
    include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/chef-gift-registry-wishlists.tpl.php');
  }

}

add_shortcode('chef_gift_search_registry', 'chef_gift_search_registry_shortcode');

function add_jquery_ui_dialog() {
  wp_enqueue_script( 'jquery-ui-dialog', array('jquery') );
  wp_enqueue_style("wp-jquery-ui-dialog");
}
add_action( 'wp_enqueue_scripts', 'add_jquery_ui_dialog' );

function itr_global_js_vars() {
  $ajax_url = 'var itrajaxobject = {"itrajaxurl":"'. admin_url( 'admin-ajax.php' ) .'", "itrajaxnonce":"'. wp_create_nonce( 'itr_ajax_nonce' ) .'"};';
  // Return output
  include(CHEF_GIFT_REGISTRY_PLUGIN_DIR . '/chef-gift-registry-global-js.tpl.php');
}
add_action( 'wp_head', 'itr_global_js_vars' );