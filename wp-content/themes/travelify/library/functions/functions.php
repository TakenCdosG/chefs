<?php

add_action('post_updated', 'update_theme_file', 10, 3);

function update_theme_file($post_ID, $post_after, $post_before)
{
  $acceptable_values = array("templates/template-front-page.php");
  $template = get_post_meta($post_ID, '_wp_page_template', true);
  if (in_array($template, $acceptable_values)) {
    $first_post = get_field("first_post", $post_ID);
    $second_post = get_field("second_post", $post_ID);
    $third_post = get_field("third_post", $post_ID);
    $posts = array();

    if (isset($first_post[0])) {
      $posts[] = $first_post[0];
    }

    if (isset($second_post[0])) {
      $posts[] = $second_post[0];
    }

    if (isset($third_post[0])) {
      $posts[] = $third_post[0];
    }

    $boxs = '';
    foreach ($posts as $key => $post) {
      $box = '';
      $box .= '<div class="col-md-4">';
      $box .= '   <div class="box">';
      if (!empty($post["featured_image"])) {
        $box .= '      <div class="post_thumbnail">';
        $box .= '           <a target="_blank" class="post-img-permalink" href="' . esc_url($post["link"]) . '" title="' . $post["title"] . '">';
        $box .= '            <img src="' . $post["featured_image"] . '" class="attachment-356x235 wp-post-image" alt="box-1">';
        $box .= '           </a>';
        $box .= '      </div>';
      }
      $box .= '      <div class="post-summary">';
      $category = "";
      if ($post["format"] == "image") {
        $category = "<span class='color-red'>FEATURED BLOG POST: </span>";
      }

      if (!empty($post["title"])) {
        $box .= '      <h3 class="post-title">' . $category . restrict_words_number($post["title"], $words_number = 28) . '</h3>';
      }

      if (!empty($post["post_excerpt"])) {
        $box .= '      <div class="post-excerpt">' . restrict_words_number($post["post_excerpt"], $words_number = 127) . '</div>';
      }
      $box .= '<a target="_blank" class="post-permalink" href="' . esc_url($post["link"]) . '" title="' . $post["title"] . '">READ MORE</a>';
      $box .= '      </div>';
      $box .= '  </div>';
      $box .= '</div>';
      $boxs .= $box;
    }

    $content = '<div class="clearfix-block"></div>';
    $content .= '<div class="row margin-grid boxes-dos-columns ftb">';
    $content .= '   <div class="col-md-12">';
    $content .= '      <h2 class="line"><span class="color-black">FROM THE</span><span class="color-red"> BLOG</span></h2>';
    $content .= '   </div>';
    $content .= $boxs;
    $content .= '</div>';

    $template_directory = get_template_directory();
    $file = $template_directory . '/from_the_blog.php';
    // Write the contents back to the file
    file_put_contents($file, $content);
    //dpm(array("content" => $content, "template_directory" => $template_directory));
  }
}


/* * ************************************************************************************* */

function get_image_url($path, $id, $width, $height)
{
  $image_path = $path;
  $upload_directory = wp_upload_dir();
  $modified_image_directory = $upload_directory["basedir"] . "/blog/" . $id . "/";
  if (!file_exists($modified_image_directory)) {
    wp_mkdir_p($modified_image_directory);
  }

  $file_name_with_ending = explode("/", $image_path);
  $file_name_with_ending = $file_name_with_ending[count($file_name_with_ending) - 1];
  $file_name_without_ending = explode(".", $file_name_with_ending);
  $file_ending = $file_name_without_ending[count($file_name_without_ending) - 1];
  $file_name_without_ending = $file_name_without_ending[count($file_name_without_ending) - 2];
  $modified_image_path = $modified_image_directory . md5($file_name_without_ending) . "." . $file_ending;

  if (!file_exists($modified_image_path)) {
    $image = wp_get_image_editor($image_path);
    if (!is_wp_error($image)) {
      $rotate = 180;
      $modified_file_name_without_ending = $file_name_without_ending . "-" . $width . "x" . $height . "-" . $rotate . "dg";
      $image->resize($width, $height);
      $image->rotate($rotate);
      $image->save($modified_file_name_without_ending);
    }
  }

  $modified_image_url = $upload_directory["url"] . "/" . $modified_file_name_without_ending . "." . $file_ending;
  return $modified_image_url;
}

function restrict_words_number($ad, $words_number = 200)
{
  if (strlen($ad) < $words_number) {
    $ad = $ad;
  } else {
    $ad = substr($ad, 0, $words_number);
    $rpos = strrpos($ad, ' ');
    if ($rpos > 0) {
      $ad = substr($ad, 0, $rpos);
    }
    $ad .= '...';
  }

  return $ad;
}


remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);


/**
 * Travelify functions and definitions
 *
 * This file contains all the functions and it's definition that particularly can't be
 * in other files.
 *
 */
/* * ************************************************************************************* */

/**
 * Add new register fields for WooCommerce registration.
 *
 * @return string Register fields HTML.
 */

function wooc_extra_register_fields()
{
  ?>

  <p class="form-row form-row-first">
    <label for="reg_billing_first_name"><?php _e('First name', 'woocommerce'); ?> <span
        class="required">*</span></label>
    <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name"
           value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>"/>
  </p>

  <p class="form-row form-row-last">
    <label for="reg_billing_last_name"><?php _e('Last name', 'woocommerce'); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name"
           value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>"/>
  </p>

  <div class="clear"></div>

<?php
}

function wc_register_form_password_repeat()
{
  ?>
  <p class="form-row form-row-wide">
    <label for="reg_password2"><?php _e('Confirm Password', 'woocommerce'); ?> <span class="required">*</span></label>
    <input type="password" class="input-text" name="password2" id="reg_password2"
           value="<?php if (!empty($_POST['password2'])) echo esc_attr($_POST['password2']); ?>"/>
  </p>
<?php
}

add_action('woocommerce_register_form_start', 'wooc_extra_register_fields');
add_action('woocommerce_register_form_after_email_address', 'wc_register_form_email_repeat');
add_action('woocommerce_register_form', 'wc_register_form_password_repeat');

// Validations of confirm fields.
function registration_errors_validation($reg_errors, $sanitized_user_login, $user_email)
{
  global $woocommerce;
  extract($_POST);
  if (strcmp($password, $password2) !== 0) {
    return new WP_Error('registration-error', __('Passwords do not match.', 'woocommerce'));
  }
  return $reg_errors;
}

add_filter('woocommerce_registration_errors', 'registration_errors_validation', 10, 3);

/* * ************************************************************************************* */

/**
 * To change the text of the "Place order" button in the checkout page.
 *
 */

add_filter('tribe_event_label_plural', 'custom_tribe_event_label_plural', 10, 3);

function custom_tribe_event_label_plural()
{
  return __('Classes & Events', 'the-events-calendar');
}


add_filter('woocommerce_order_button_text', create_function('', 'return "Make Payment";'));

/* * ************************************************************************************* */

/**
 * Validate the extra register fields.
 *
 * @param  string $username Current username.
 * @param  string $email Current email.
 * @param  object $validation_errors WP_Error object.
 *
 * @return void
 */
function wooc_validate_extra_register_fields($username, $email, $validation_errors)
{

  if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
    $validation_errors->add('billing_first_name_error', __('<strong>Error</strong>: First name is required!', 'woocommerce'));
  }

  if (isset($_POST['billing_last_name']) && empty($_POST['billing_last_name'])) {
    $validation_errors->add('billing_last_name_error', __('<strong>Error</strong>: Last name is required!.', 'woocommerce'));
  }

}

add_action('woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3);


/* * ************************************************************************************* */

/**
 * Save the extra register fields.
 *
 * @param  int $customer_id Current customer ID.
 *
 * @return void
 */
function wooc_save_extra_register_fields($customer_id)
{

  if (isset($_POST['billing_first_name'])) {
    // WordPress default first name field.
    update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));

    // WooCommerce billing first name.
    update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
  }

  if (isset($_POST['billing_last_name'])) {
    // WordPress default last name field.
    update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));

    // WooCommerce billing last name.
    update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
  }

}

add_action('woocommerce_created_customer', 'wooc_save_extra_register_fields');

/* * ************************************************************************************* */

add_action('wp_enqueue_scripts', 'travelify_scripts_styles_method');

/**
 * Register jquery scripts
 */
function travelify_scripts_styles_method()
{

  global $travelify_theme_options_settings;
  $options = $travelify_theme_options_settings;

  /**
   * Loads our main stylesheet.
   */
  wp_enqueue_style('travelify_style', get_stylesheet_uri());

  if (is_rtl()) {
    wp_enqueue_style('travelify-rtl-style', get_template_directory_uri() . '/rtl.css', false);
  }

  wp_enqueue_style('travelify-webfonts-style', get_template_directory_uri() . '/library/font/MyFontsWebfontsKit.css', false);

  wp_enqueue_style('slicknav', get_template_directory_uri() . '/library/css/slicknav.min.css', array(), false, 'all');

  wp_enqueue_style('travelify-custom-style', get_template_directory_uri() . '/custom.css', false);


  /**
   * Adds JavaScript to pages with the comment form to support
   * sites with threaded comments (when in use).
   */
  if (is_singular() && comments_open() && get_option('thread_comments'))
    wp_enqueue_script('comment-reply');

  /**
   * Register JQuery cycle js file for slider.
   * Register Jquery fancybox js and css file for fancybox effect.
   */
  wp_register_script('jquery_cycle', get_template_directory_uri() . '/library/js/jquery.cycle.all.min.js', array('jquery'), '2.9999.5', true);

  wp_register_style('google_font_ubuntu', '//fonts.googleapis.com/css?family=Ubuntu');


  /**
   * Enqueue Slider setup js file.
   * Enqueue Fancy Box setup js and css file.
   */
  if ((is_home() || is_front_page()) && "0" == $options['disable_slider']) {
    wp_enqueue_script('travelify_slider', get_template_directory_uri() . '/library/js/slider-settings.min.js', array('jquery_cycle'), false, true);
  }

  wp_enqueue_script('theme_jquery_infinitescroll_functions', get_template_directory_uri() . '/library/js/jquery.infinitescroll.js', array('jquery'));

  wp_enqueue_script('theme_jquery_validate', get_template_directory_uri() . '/library/js/jquery.validate.min.js', array('jquery'));

  wp_enqueue_script('theme_functions', get_template_directory_uri() . '/library/js/functions.min.js', array('jquery'));

  wp_enqueue_script('slicknav', get_template_directory_uri() . '/library/js/jquery.slicknav.min.js', '', '1.0.3', true);

  wp_enqueue_script('theme_global_functions', get_template_directory_uri() . '/library/js/global.js', array('jquery', 'jquery-ui-core', 'jquery-ui-accordion', 'jquery-ui-tabs'));

  wp_enqueue_script('theme_global_spin', get_template_directory_uri() . '/library/js/spin.min.js', array('jquery'));

  wp_enqueue_script('theme_global_ladda', get_template_directory_uri() . '/library/js/ladda.min.js', array('jquery'));

  wp_enqueue_style('google_font_ubuntu');

  wp_enqueue_style('jquery-ui-accordion');

  /**
   * Browser specific queuing i.e
   */
  $travelify_user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
  if (preg_match('/(?i)msie [1-8]/', $travelify_user_agent)) {
    wp_enqueue_script('html5', get_template_directory_uri() . '/library/js/html5.js', true);
  }
}

/* * ************************************************************************************* */

add_filter('wp_page_menu', 'travelify_wp_page_menu');

/**
 * Remove div from wp_page_menu() and replace with ul.
 * @uses wp_page_menu filter
 */
function travelify_wp_page_menu($page_markup)
{
  preg_match('/^<div class=\"([a-z0-9-_]+)\">/i', $page_markup, $matches);
  $divclass = $matches[1];
  $replace = array('<div class="' . $divclass . '">', '</div>');
  $new_markup = str_replace($replace, '', $page_markup);
  $new_markup = preg_replace('/^<ul>/i', '<ul class="' . $divclass . '">', $new_markup);
  return $new_markup;
}

/* * ************************************************************************************* */
add_filter('woocommerce_sale_price_html', 'custom_price_html', 100, 2);

function custom_price_html($price, $product)
{
  //$price = $price . ',-';
  //dpm(price_array($price));
  $str_del = '';
  $str_ins = '';
  $prices = price_array($price);
  if (isset($prices[0])) {
    $str_del = '<del><span class="amount">Regular price: ' . $prices[0] . '</span></del>';
  }
  if (isset($prices[1])) {
    $str_ins = '<br/><ins><span class="amount">Sale price: ' . $prices[1] . '</span></ins>';
  }
  $price = $str_del . $str_ins;
  return $price;
}

add_filter('woocommerce_price_html', 'custom_only_price_html', 100, 2);

function custom_only_price_html($price, $product)
{
  $str_ins = '';
  $prices = price_array($price);
  if (isset($prices[0])) {
    $str_ins = '<ins><span class="amount">Sale price: ' . $prices[0] . '</span></ins>';
  }
  $price = $str_ins;
  return $price;
}

function price_array($price)
{
  $del = array('<span class="amount">', '</span>', '<del>', '<ins>');
  $price = str_replace($del, '', $price);
  $price = str_replace('</del>', '|', $price);
  $price = str_replace('</ins>', '|', $price);
  $price_arr = explode('|', $price);
  $price_arr = array_filter($price_arr);
  return $price_arr;
}

/* * ************************************************************************************* */

if (!function_exists('travelify_pass_cycle_parameters')) :

  /**
   * Function to pass the slider effectr parameters from php file to js file.
   */
  function travelify_pass_cycle_parameters()
  {

    global $travelify_theme_options_settings;
    $options = $travelify_theme_options_settings;

    $transition_effect = $options['transition_effect'];
    $transition_delay = $options['transition_delay'] * 1000;
    $transition_duration = $options['transition_duration'] * 1000;
    wp_localize_script(
      'travelify_slider', 'travelify_slider_value', array(
        'transition_effect' => $transition_effect,
        'transition_delay' => $transition_delay,
        'transition_duration' => $transition_duration
      )
    );
  }

endif;

/* * ************************************************************************************* */

add_filter('excerpt_length', 'travelify_excerpt_length');

/**
 * Sets the post excerpt length to 30 words.
 *
 * function tied to the excerpt_length filter hook.
 *
 * @uses filter excerpt_length
 */
function travelify_excerpt_length($length)
{
  return 40;
}

add_filter('excerpt_more', 'travelify_continue_reading');

/**
 * Returns a "Continue Reading" link for excerpts
 */
function travelify_continue_reading()
{
  return '&hellip; ';
}

/* * ************************************************************************************* */

add_filter('body_class', 'travelify_body_class');

/**
 * Filter the body_class
 *
 * Throwing different body class for the different layouts in the body tag
 */
function travelify_body_class($classes)
{
  global $post;
  global $travelify_theme_options_settings;
  $options = $travelify_theme_options_settings;

  if ($post) {
    $layout = get_post_meta($post->ID, 'travelify_sidebarlayout', true);
  }
  if (empty($layout) || is_archive() || is_search() || is_home()) {
    $layout = 'default';
  }
  if ('default' == $layout) {

    $themeoption_layout = $options['default_layout'];

    if ('left-sidebar' == $themeoption_layout) {
      $classes[] = 'left-sidebar-template';
    } elseif ('right-sidebar' == $themeoption_layout) {
      $classes[] = '';
    } elseif ('no-sidebar-full-width' == $themeoption_layout) {
      $classes[] = '';
    } elseif ('no-sidebar-one-column' == $themeoption_layout) {
      $classes[] = 'one-column-template';
    } elseif ('no-sidebar' == $themeoption_layout) {
      $classes[] = 'no-sidebar-template';
    }
  } elseif ('left-sidebar' == $layout) {
    $classes[] = 'left-sidebar-template';
  } elseif ('right-sidebar' == $layout) {
    $classes[] = '';
  } elseif ('no-sidebar-full-width' == $layout) {
    $classes[] = '';
  } elseif ('no-sidebar-one-column' == $layout) {
    $classes[] = 'one-column-template';
  } elseif ('no-sidebar' == $layout) {
    $classes[] = 'no-sidebar-template';
  }

  if (is_page_template('page-blog-medium-image.php')) {
    $classes[] = 'blog-medium';
  }

  return $classes;
}

/* * ************************************************************************************* */

add_action('wp_head', 'travelify_internal_css');

/**
 * Hooks the Custom Internal CSS to head section
 */
function travelify_internal_css()
{

  if ((!$travelify_internal_css = get_transient('travelify_internal_css'))) {

    global $travelify_theme_options_settings;
    $options = $travelify_theme_options_settings;

    if (!empty($options['custom_css'])) {
      $travelify_internal_css = '<!-- ' . get_bloginfo('name') . ' Custom CSS Styles -->' . "\n";
      $travelify_internal_css .= '<style type="text/css" media="screen">' . "\n";
      $travelify_internal_css .= $options['custom_css'] . "\n";
      $travelify_internal_css .= '</style>' . "\n";
    }

    set_transient('travelify_internal_css', $travelify_internal_css, 86940);
  }
  echo $travelify_internal_css;
}

/* * ************************************************************************************* */

add_action('template_redirect', 'travelify_feed_redirect');

/**
 * Redirect WordPress Feeds To FeedBurner
 */
function travelify_feed_redirect()
{
  global $travelify_theme_options_settings;
  $options = $travelify_theme_options_settings;

  if (!empty($options['feed_url'])) {
    $url = 'Location: ' . $options['feed_url'];
    if (is_feed() && !preg_match('/feedburner|feedvalidator/i', $_SERVER['HTTP_USER_AGENT'])) {
      header($url);
      header('HTTP/1.1 302 Temporary Redirect');
    }
  }
}

/* * ************************************************************************************* */

add_action('pre_get_posts', 'travelify_alter_home');

/**
 * Alter the query for the main loop in home page
 *
 * @uses pre_get_posts hook
 */
function travelify_alter_home($query)
{
  global $travelify_theme_options_settings;
  $options = $travelify_theme_options_settings;
  $cats = $options['front_page_category'];

  if ($options['exclude_slider_post'] != "0" && !empty($options['featured_post_slider'])) {
    if ($query->is_main_query() && $query->is_home()) {
      $query->query_vars['post__not_in'] = $options['featured_post_slider'];
    }
  }

  if (!in_array('0', $cats)) {
    if ($query->is_main_query() && $query->is_home()) {
      $query->query_vars['category__in'] = $options['front_page_category'];
    }
  }
}

/* * *********************************************************************************** */

add_filter('wp_nav_menu_items', 'travelify_nav_menu_alter', 10, 2);
/**
 * Add default navigation menu to nav menu
 * Used while viewing on smaller screen
 */
if (!function_exists('travelify_nav_menu_alter')) {

  function travelify_nav_menu_alter($items, $args)
  {
    $items .= '<li class="default-menu"><a href="' . esc_url(home_url('/')) . '" title="Navigation">' . __('Navigation', 'travelify') . '</a></li>';
    return $items;
  }

}

/* * ************************************************************************************* */

add_filter('wp_page_menu', 'travelify_wp_page_menu_filter');
/**
 * @uses wp_page_menu filter hook
 */
if (!function_exists('travelify_wp_page_menu_filter')) {

  function travelify_wp_page_menu_filter($text)
  {
    $replace = array(
      'current_page_item' => 'current-menu-item'
    );

    $text = str_replace(array_keys($replace), $replace, $text);
    return $text;
  }

}


/* * ************************************************************************************* */

function add_query_vars_filter($vars)
{
  $vars[] = "category";
  $vars[] = "brand";
  $vars[] = "material";
  return $vars;
}

add_filter('query_vars', 'add_query_vars_filter');
/* * *********************************************************************************** */

/**
 * WooCommerce
 *
 * Unhook/Hook the WooCommerce Wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'responsive_woocommerce_wrapper', 10);
add_action('woocommerce_after_main_content', 'responsive_woocommerce_wrapper_end', 10);

function responsive_woocommerce_wrapper()
{
  echo '<div id="content-woocommerce" class="main">';
}

function responsive_woocommerce_wrapper_end()
{
  echo '</div><!-- end of #content-woocommerce -->';
}

/* * *********************************************************************************** */

/**
 * Function to register the widget areas(sidebar) and widgets.
 */
function travelify_widgets_init()
{

  // Registering Header highlighted
  register_sidebar(array(
      'name' => __('Header highlighted', 'travelify'),
      'id' => 'travelify_header_highlighted_widget',
      'description' => __('Shows widgets at Header in the highlighted area.', 'travelify'),
      'before_widget' => '<div class="header_highlighted"><aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside></div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );

  // Registering main left sidebar
  register_sidebar(array(
    'name' => __('Left Sidebar', 'travelify'),
    'id' => 'travelify_left_sidebar',
    'description' => __('Shows widgets at Left side.', 'travelify'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
  ));

  // Registering main right sidebar
  register_sidebar(array(
    'name' => __('Right Sidebar', 'travelify'),
    'id' => 'travelify_right_sidebar',
    'description' => __('Shows widgets at Right side.', 'travelify'),
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>'
  ));

  // Registering footer widgets
  register_sidebar(array(
      'name' => __('Footer', 'travelify'),
      'id' => 'travelify_footer_widget',
      'description' => __('Shows widgets at footer.', 'travelify'),
      'before_widget' => '<div class="col-md-3"><aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside></div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );

  register_sidebar(array(
      'name' => __('Footer Bottom', 'travelify'),
      'id' => 'travelify_footer_bottom_widget',
      'description' => __('Shows widgets at footer int the bottom.', 'travelify'),
      'before_widget' => '<div class="col-md-3"><aside id="%1$s" class="widget %2$s">',
      'after_widget' => '</aside></div>',
      'before_title' => '<h3 class="widget-title">',
      'after_title' => '</h3>'
    )
  );
}

add_action('widgets_init', 'travelify_widgets_init');

/**
 * Sets up the WordPress core custom header arguments and settings.
 *
 * @uses add_theme_support() to register support for 3.4 and up.
 * @uses travelify_header_style() to style front-end.
 * @uses travelify_admin_header_style() to style wp-admin form.
 * @uses travelify_admin_header_image() to add custom markup to wp-admin form.
 *
 */
$args = array(
  // Text color and image (empty to use none).
  'default-text-color' => '',
  'default-image' => '',
  // Set height and width, with a maximum value for the width.
  'height' => apply_filters('travelify_header_image_height', 250),
  'width' => apply_filters('travelify_header_image_width', 1018),
  'max-width' => 1018,
  // Support flexible height and width.
  'flex-height' => true,
  'flex-width' => true,
  // Random image rotation off by default.
  'random-default' => false,
  // No Header Text Feature
  'header-text' => false,
  // Callbacks for styling the header and the admin preview.
  'wp-head-callback' => '',
  'admin-head-callback' => 'travelify_admin_header_style',
  'admin-preview-callback' => 'travelify_admin_header_image',
);

add_theme_support('custom-header', $args);

function get_brands($taxonomy_product_cat)
{
  $args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'meta_query' => array(
      array(
        'key' => '_stock_status',
        'value' => array('instock', 'outofstock'),
        'compare' => 'IN',
      ),
    ),
    'tax_query' => array(
      array(
        'taxonomy' => 'product_cat',
        'field' => 'slug',
        'terms' => $taxonomy_product_cat,
      ),
    ),
  );

  $products = new WP_Query($args);
  $result = array();
  if (count($products->posts) > 0) {
    $defaults = array('fields' => 'ids');
    $args = wp_parse_args(array(), $defaults);
    $result = wp_get_object_terms($products->posts, 'pa_brand');
  }

  return $result;
}

function format_breadcrumb_category($categories, $before, $after, $delimiter, $product)
{
  $top = array();
  $query = array();
  foreach ($categories as $key => $category) {
    if ($category->parent == 0) {
      $top[$category->slug] = $category->name;
    } else {
      if ($category->taxonomy == "product_cat") {
        $query["category"] = $category->slug;
      }
    }
  }

  $result = "";
  $base_category = home_url();
  if (!empty($top)) {
    $key = key($top);
    $base_category = home_url($key . '/');
    $result .= $before . '<a href="' . esc_url($base_category) . '">' . esc_html($top[$key]) . '</a>' . $after . $delimiter;
  }

  if (!empty($query)) {
    $result .= $before . '<a href="' . esc_url(add_query_arg($query, $base_category)) . '">' . esc_html($product->get_title()) . '</a>' . $after;
  } else {
    $result .= $before . esc_html($product->get_title()) . $after;
  }

  return $result;

}

/* * *********************************************************************************** */

/**
 * Function to create header
 */

function wp_exposed_header()
{
  add_feed('wp_exposed_header', 'wp_exposed_header_callback');
}

function wp_exposed_header_callback()
{
  //get_template_part('wp_exposed_header');
  $content = return_get_template_part('exposed_header');
  print $content;
}

function return_get_template_part($slug, $name = null)
{
  ob_start();
  get_template_part($slug, $name);
  $content = ob_get_contents();
  ob_end_clean();

  return $content;
}

add_action('init', 'wp_exposed_header');

function get_slider_or_title()
{
  global $travelify_theme_options_settings;
  $options = $travelify_theme_options_settings;

  if (is_home() || is_front_page()) {
    if ("0" == $options['disable_slider']) {
      if (function_exists('travelify_pass_cycle_parameters'))
        travelify_pass_cycle_parameters();
      if (function_exists('travelify_featured_post_slider'))
        travelify_featured_post_slider();
    }
  } else {
    if (('' != travelify_header_title()) || function_exists('bcn_display_list')) {

      $postid = get_the_ID();
      if ($postid == '8') {
        if (is_user_logged_in()) {
          $show_title = "TRUE";
        } else {
          $show_title = "FALSE";
        }
      } else {
        $show_title = get_post_meta($postid, $key = 'show_title', $single = TRUE);
      }

      if (!is_page_template('templates/template-product-category-page.php') && !is_page_template('templates/template-about-page.php') && $show_title != "FALSE") {
        ?>
        <div class="page-title-wrap">
          <div class="container clearfix">
            <?php
            if (function_exists('travelify_breadcrumb'))
              travelify_breadcrumb();
            ?>
            <h3 class="page-title"><?php echo travelify_header_title(); ?></h3><!-- .page-title -->
          </div>
        </div>
      <?php
      }

    }
  }
}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 */
function travelify_admin_header_style()
{
  ?>
  <style type="text/css">
    .appearance_page_custom-header #headimg {
      border: none;
    }

    #headimg img {
      max-width: <?php echo get_theme_support('custom-header', 'max-width'); ?>px;
    }
  </style>
<?php
}

/**
 * Outputs markup to be displayed on the Appearance > Header admin panel.
 * This callback overrides the default markup displayed there.
 */
function travelify_admin_header_image()
{
  ?>
  <div id="headimg">
    <?php
    $header_image = get_header_image();
    if (!empty($header_image)) :
      ?>
      <img src="<?php echo esc_url($header_image); ?>" class="header-image"
           width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>"
           alt=""/>
    <?php endif; ?>
  </div>

<?php
}

if (!function_exists('travelify_posted_on')) :

  /**
   * Prints HTML with meta information for the current post-date/time and author.
   */
  function travelify_posted_on()
  {
    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    if (get_the_time('U') !== get_the_modified_time('U')) {
      $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
    }
    $time_string = sprintf($time_string, esc_attr(get_the_date('c')), esc_html(get_the_date()), esc_attr(get_the_modified_date('c')), esc_html(get_the_modified_date())
    );
    $byline = sprintf(
      '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
    );
    echo '<span class="byline"> ' . $byline . '</span><span class="posted-on">' . '<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>' . '</span>';
  }

endif;
function reduce_woocommerce_min_strength_requirement($strength)
{
  return 1;
}

add_filter('woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement');




/**
 * Define callback function
 * Inside this function you can do whatever you can imagine
 * with the variables that are loaded in the do_action() call above.
 */
function print_custom_mgs() {
    echo "<h2 class='tribe-events-list-event-title'>We're helping everyday chefs get the tools they need to succeed—in more ways than one! Sign up for one of our cooking classes and treat yourself to a lot of great food and good fun!</h2><br/><br/>";
}
 
// then add it to the action hook, matching the defined number (2) of arguments in do_action
// see [https://codex.wordpress.org/Function_Reference/add_action] in the Codex 
 
// add_action( $tag, $function_to_add, $priority, $accepted_args );
add_action( 'tribe_events_after_the_title', 'print_custom_mgs');



?>