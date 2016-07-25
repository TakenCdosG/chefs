<?php
/*
Plugin Name: Chef Gift Registry
Plugin URI: http://adrian-morelos.github.io
Description: A shortcodes plugin with support of Gift Registry for Chef
Author: Adrián Morelos
Author URI: http://adrian-morelos.github.io
Version: 1.0.0
License: GNU General Public License version 2.0
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!class_exists('ChefGiftRegistry')) {

  class ChefGiftRegistry
  {

    /**
     * Main Constructor
     *
     * @since  1.0.0
     * @access public
     */
    function __construct()
    {
      // Define path
      $this->dir_path = plugin_dir_path(__FILE__);

      // Register de-activation hook
      register_deactivation_hook(__FILE__, array($this, 'on_deactivation'));

      // Actions
      add_action('wp_enqueue_scripts', array($this, 'load_scripts'));
      add_action('plugins_loaded', array($this, 'load_text_domain'));
      add_action('plugins_loaded', array($this, 'constants'));
      add_action('plugins_loaded', array($this, 'init_query_variables'));

      add_action('wp_ajax_buy_wishlist_item_not_logged', array($this, 'buy_wishlist_item_not_logged_callback'));
      add_action('wp_ajax_nopriv_buy_wishlist_item_not_logged', array($this, 'buy_wishlist_item_not_logged_callback'));

      add_filter('query_vars', array($this, 'init_query_variables'), 10, 1);

      // Includes (useful functions and classes)
      require_once($this->dir_path . '/inc/commons.php');

      // The actual shortcodes
      require_once($this->dir_path . '/shortcodes/shortcodes.php');
    }


    /**
     * Init Query Variables
     *
     * @since  1.0.0
     * @access public
     */
    function init_query_variables($vars)
    {
      $vars[] = 'registrant-name'; // faq is the name of variable you want to add
      $vars[] = 'co-registrant-name';
      $vars[] = 'registrant-email';
      $vars[] = 'co-registrant-email';
      $vars[] = 'event-name';
      $vars[] = 'event-type';
      $vars[] = 'registry-no';
      $vars[] = 'event-date';
      $vars[] = 'redirect-url';
      $vars[] = 'redirect-action';
      return $vars;
    }

    /**
     * Load Text Domain for translations
     *
     * @since  1.0.0
     * @access public
     */
    function load_text_domain()
    {
      load_plugin_textdomain('chef_gift_registry', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * Define Constants
     *
     * @since   1.0.0
     * @access  public
     */
    public function constants()
    {
      //define( 'CONSTANT', 'VALUE' );
    }


    /**
     * Registers/Enqueues all scripts and styles
     *
     * @since  2.0.0
     * @access public
     */
    function load_scripts()
    {
      // Define js directory
      $js_dir = plugin_dir_url(__FILE__) . 'shortcodes/js/';

      // Define CSS directory
      $css_dir = plugin_dir_url(__FILE__) . 'shortcodes/css/';

      // JS
      wp_register_script('chef-gift-registry-jquery-validate', $js_dir . 'jquery.validate.min.js', array('jquery'), '1.0', true);
      wp_register_script('chef-gift-registry', $js_dir . 'chef-gift-registry.js', array('jquery'), '1.0', true);

      // CSS
      wp_enqueue_style('chef-gift-registry', $css_dir . 'chef-gift-registry.css');
    }

    /**
     * Run on plugin de-activation
     *
     * @since 1.0.0
     */
    public function on_deactivation()
    {


    }


    /**
     * Adds classes to the body tag
     *
     * @since 1.0.0
     */
    public function body_class($classes)
    {
      $classes[] = 'chef-gift-registry ';
      $responsive = apply_filters('chef_gift_registry', true);
      if ($responsive) {
        $classes[] = 'chef-gift-registry';
      }
      return $classes;
    }

    /**
     * Buy wishlist item not logged
     *
     * @since 1.0.0
     */
    function buy_wishlist_item_not_logged_callback()
    {
      global $woocommerce, $wpdb;

      $response = array();
      $response['success'] = FALSE;
      $response['msg'] = "";
      $response['url'] = "";
      $validacion = TRUE;

      if (!isset($_POST['nonce'])) {
        $response['success'] = FALSE;
        $response['msg'] = "Validation Error: Product could not be added to Cart!.";
        $validacion = FALSE;
      }
      if (!wp_verify_nonce($_POST['nonce'], 'wishlist_nonce')) {
        $response['success'] = FALSE;
        $response['msg'] = "Validation Error: Product could not be added to Cart!.";
        $validacion = FALSE;
      }

      $wlid = absint($_POST['wlid']);
      $user = absint($_POST['user']);
      $prod_id = absint($_POST['prod_id']);
      $var_id = absint($_POST['var_id']);
      if ($wlid <= 0 || $user <= 0 || $prod_id <= 0) {
        $response['success'] = FALSE;
        $response['msg'] = "Validation Error: Product could not be added to Cart!.";
        $validacion = FALSE;
      }

      if ($validacion) {

        $variation = array();
        if ($var_id > 0) {
          $sql = 'select * from ' . $wpdb->postmeta . ' where post_id = ' . $var_id . ' and meta_key like "attribute_%"';
          $vals = $wpdb->get_results($sql);
          if ($vals) {
            foreach ($vals as $v) {
              $type = ucfirst(str_replace('attribute_pa_', '', $v->meta_key));
              $variation[$type] = $v->meta_value;
            }
          }
        }

        if ($woocommerce->cart->add_to_cart($prod_id, 1, $var_id, $variation)) {
          $added = true;
          $response['success'] = TRUE;
          $response['msg'] = "The item was added to your cart. Be sure to check the cart quantity before checkout.";
        } else {
          $added = false;
          $response['success'] = FALSE;
          $response['msg'] = "Cart Error: " . $woocommerce->errors[0];
        }

        @session_start(); // In case it's not started yet
        $wishlist_session = $this->session_get('wishlist');
        $wishlist_session = maybe_unserialize($wishlist_session);

        // Avoid Adding Duplicates To The Session
        $in_there = false;
        if (is_array($wishlist_session)) {
          foreach ($wishlist_session as $key => $vals) {
            if ($var_id > 0) {
              if ($vals['pid'] == $prod_id && $vals['wlid'] == $wlid && $vals['vid'] == $var_id)
                $in_there = true;
            } else {
              if ($vals['pid'] == $prod_id && $vals['wlid'] == $wlid)
                $in_there = true;
            }
          }
        }

        if (!$in_there) {
          $wishlist_session[] = array('pid' => $prod_id, 'vid' => $var_id, 'wlid' => $wlid, 'user' => $user);
        }

        $this->session_set('wishlist', serialize($wishlist_session));
        $url = get_permalink(get_option('woocommerce_cart_page_id', false));
        if ($added) {
          $response['url'] = $url; // $out = array('url' => $url);
        }

      }

      echo json_encode($response);
      die;
    }

    /**
     * Safely retrieve data from the session. Compatible with WC 2.0 and
     * backwards compatible with previous versions.
     *
     * @param string $name the name
     * @return mixed the data, or null
     */
    public function session_get($name)
    {
      global $woocommerce;
      //var_dump( WC()->session->get( $name ) );
      if (version_compare(WOOCOMMERCE_VERSION, '2.3', '>='))
        return WC()->session->get($name);
      else if (isset($woocommerce->session)) {
        // WC 2.0
        if (isset($woocommerce->session->$name))
          return $woocommerce->session->$name;

      } else {
        // old style
        if (isset($_SESSION[$name]))
          return $_SESSION[$name];
      }
      return '';
    }

    /**
     * Safely store data into the session. Compatible with WC 2.0 and
     * backwards compatible with previous versions.
     *
     * @param string $name the name
     * @param mixed $value the value to set
     */
    private function session_set($name, $value)
    {
      global $woocommerce;
      if (version_compare(WOOCOMMERCE_VERSION, '2.3', '>='))
        WC()->session->set($name, $value);
      else if (isset($woocommerce->session)) {
        // WC 2.0
        $woocommerce->session->$name = $value;
      } else {
        // old style
        $_SESSION[$name] = $value;
      }
      //var_dump( $name, $value, WC()->session->get( $name ) );
    }

  }

  // Start things up
  $ChefGiftRegistry = new ChefGiftRegistry();
}

/**
 * Warn about comment not found
 *
 * @param int $comment_id Comment ID.
 */
function save_additional_wishlists_info($post_id = "", $user_id = "", $event_type = "", $event_date = "", $co_registrant_name = "", $co_registrant_email = "")
{
  if (!empty($user_id) && !empty($post_id)) {
    $user_info = get_userdata($user_id);
    $first_name = $user_info->first_name;
    $last_name = $user_info->last_name;
    $email = $user_info->user_email;
    // Set Registrant Name
    $registrant_name = implode(" ", array($first_name, $last_name));
    update_field('field_57573db70d652', $registrant_name, $post_id);
    // Set Registrant Email
    update_field('field_57573de00d654', $email, $post_id);
    if (!empty($event_type)) {
      // Set Event Type
      update_field('field_575726e432f3c', $event_type, $post_id);
    }
    if (!empty($event_date)) {
      // Set Event Date
      update_field('field_57573e110d656', $event_date, $post_id);
    }
    if (!empty($co_registrant_name)) {
      // Set  Co-Registrant Name
      update_field('field_57573dc70d653', $co_registrant_name, $post_id);
    }
    if (!empty($co_registrant_email)) {
      // Set  Co-Registrant Email
      update_field('field_57573df40d655', $co_registrant_email, $post_id);
    }
  }
}

add_action('set_additional_wishlists_info', 'save_additional_wishlists_info', 10, 2);


function already_in_cart($product_id = "")
{
  if (empty($product_id)) {
    return false;
  }
  foreach (WC()->cart->get_cart() as $cart_item_key => $values) {
    $_product = $values['data'];
    if ($product_id == $_product->id) {
      return true;
    }
  }
  return false;
}




