<?php
/**
 * Adds header structures.
 *
 */
/* * ************************************************************************************* */

add_action('wp_head', 'travelify_add_meta', 5);

/**
 * Add meta tags.
 */
function travelify_add_meta() {
    ?>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="p:domain_verify" content="1bd9e7871a3e3b91533bb6bfba5a6c24"/>
<?php
}

/* * ************************************************************************************* */

if (version_compare($GLOBALS['wp_version'], '4.1', '<')) :

    /**
     * Filters wp_title to print a neat <title> tag based on what is being viewed.
     *
     * @param string $title Default title text for current view.
     * @param string $sep Optional separator.
     * @return string The filtered title.
     */
    function travelify_wp_title($title, $sep) {
        if (is_feed()) {
            return $title;
        }
        global $page, $paged;
        // Add the blog name
        $title .= get_bloginfo('name', 'display');
        // Add the blog description for the home/front page.
        $site_description = get_bloginfo('description', 'display');
        if ($site_description && ( is_home() || is_front_page() )) {
            $title .= " $sep $site_description";
        }
        // Add a page number if necessary:
        if (( $paged >= 2 || $page >= 2 ) && !is_404()) {
            $title .= " $sep " . sprintf(__('Page %s', 'travelify'), max($paged, $page));
        }
        return $title;
    }

    add_filter('wp_title', 'travelify_wp_title', 10, 2);

    /**
     * Title shim for sites older than WordPress 4.1.
     *
     * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
     * @todo Remove this function when WordPress 4.3 is released.
     */
    function travelify_render_title() {
        ?>
        <title><?php wp_title('|', true, 'right'); ?></title>
    <?php
    }

    add_action('wp_head', 'travelify_render_title');
endif;

/* * ************************************************************************************* */

add_action('travelify_links', 'travelify_add_links', 10);

/**
 * Adding link to stylesheet file
 *
 * @uses get_stylesheet_uri()
 */
function travelify_add_links() {
    ?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<?php
}

/* * ************************************************************************************* */

// Load Favicon in Header Section
add_action('travelify_links', 'travelify_favicon', 15);
// Load Favicon in Admin Section
add_action('admin_head', 'travelify_favicon');

/**
 * Get the favicon Image from theme options
 * display favicon
 *
 * @uses set_transient and delete_transient
 */
function travelify_favicon() {

    $travelify_favicon = '';
    if ((!$travelify_favicon = get_transient('travelify_favicon'))) {
        global $travelify_theme_options_settings;
        $options = $travelify_theme_options_settings;

        if ("0" == $options['disable_favicon']) {
            if (!empty($options['favicon'])) {
                $travelify_favicon .= '<link rel="shortcut icon" href="' . esc_url($options['favicon']) . '" type="image/x-icon" />';
            }
        }

        set_transient('travelify_favicon', $travelify_favicon, 86940);
    }
    echo $travelify_favicon;
}

/* * ************************************************************************************* */

// Load webpageicon in Header Section
add_action('travelify_links', 'travelify_webpageicon', 20);

/**
 * Get the webpageicon Image from theme options
 * display webpageicon
 *
 * @uses set_transient and delete_transient
 */
function travelify_webpageicon() {

    $travelify_webpageicon = '';
    if ((!$travelify_webpageicon = get_transient('travelify_webpageicon'))) {
        global $travelify_theme_options_settings;
        $options = $travelify_theme_options_settings;

        if ("0" == $options['disable_webpageicon']) {
            if (!empty($options['webpageicon'])) {
                $travelify_webpageicon .= '<link rel="apple-touch-icon-precomposed" href="' . esc_url($options['webpageicon']) . '" />';
            }
        }

        set_transient('travelify_webpageicon', $travelify_webpageicon, 86940);
    }
    echo $travelify_webpageicon;
}

/* * ************************************************************************************* */

add_action('travelify_header', 'travelify_headerdetails', 10);

/**
 * Shows Header Part Content
 *
 * Shows the site logo, title, description, searchbar, social icons etc.
 */
function travelify_headerdetails() {
    ?>
    <?php
    global $travelify_theme_options_settings;
    $options = $travelify_theme_options_settings;

    $elements = array();
    $elements = array(
        $options['social_facebook'],
        $options['social_twitter'],
        $options['social_googleplus'],
        $options['social_linkedin'],
        $options['social_pinterest'],
        $options['social_youtube'],
        $options['social_vimeo'],
        $options['social_flickr'],
        $options['social_tumblr'],
        $options['social_instagram'],
        $options['social_rss'],
        $options['social_github']
    );

    $flag = 0;
    if (!empty($elements)) {
        foreach ($elements as $option) {
            if (!empty($option)) {
                $flag = 1;
            } else {
                $flag = 0;
            }
            if (1 == $flag) {
                break;
            }
        }
    }
    ?>

    <div class="container clearfix">
        <div class="hgroup-wrap clearfix">
            <hgroup id="site-logo" class="clearfix">
                <?php
                if ($options['header_show'] != 'disable-both' && $options['header_show'] == 'header-text') {
                    ?>
                    <h1 id="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                    <h2 id="site-description"><?php bloginfo('description'); ?></h2>
                <?php
                } elseif ($options['header_show'] != 'disable-both' && $options['header_show'] == 'header-logo') {
                    ?>
                    <h1 id="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                            <img src="<?php echo $options['header_logo']; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                        </a>
                    </h1>
                <?php
                }
                ?>
            </hgroup><!-- #site-logo -->
            <section class="hgroup-right-header_upper">
                <?php
                $defaults = array(
                    'menu'            => '77',
                    'container'       => 'div',
                    'container_class' => 'header_upper',
                    'container_id'    => '',
                    'menu_class'      => 'menu',
                    'menu_id'         => '',
                    'echo'            => true,
                );
                wp_nav_menu($defaults);
                ?>
                <?php $checkout_page_url = get_permalink(woocommerce_get_page_id('checkout')); ?>
                <!--
                <ul class="cart-contents">
                    <li>
                        <a class="color-red" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">
                            CART(<?php echo sprintf (_n( '%d <span class="cart1">item</span>', '%d <span class="cart1">items', WC()->cart->cart_contents_count ), WC()->cart->cart_contents_count); ?> <span class="cart1">-</span> <?php echo WC()->cart->get_cart_total()."</span>"; ?>)
                        </a>
                    </li>
                    <li>&nbsp;|&nbsp;</li>
                    <li>
                        <a href="<?php echo $checkout_page_url; ?>" title="<?php _e('Proceed to Checkout'); ?>">
                            CHECKOUT >
                        </a>
                    </li>
                </ul>
                -->
            </section>
            <section class="hgroup-right">
                <?php travelify_socialnetworks($flag); ?>
                <?php get_search_form(); ?>
            </section><!-- .hgroup-right -->

            <?php if ( is_active_sidebar('travelify_header_highlighted_widget')) : ?>
                <section class="hgroup-header-highlighted-widget">
                    <?php dynamic_sidebar('travelify_header_highlighted_widget'); ?>
                </section>
            <?php endif; ?>

            <section class="hgroup-right-header_bottom">
                <?php
                $defaults = array(
                    'menu'            => '78',
                    'container'       => 'div',
                    'container_class' => 'header_bottom',
                    'container_id'    => '',
                    'menu_class'      => 'menu',
                    'menu_id'         => '',
                    'echo'            => true,
                );
                wp_nav_menu($defaults);
                ?>
            </section>
        </div><!-- .hgroup-wrap -->
    </div><!-- .container -->
    <?php
    $header_image = get_header_image();
    if (!empty($header_image)) :
        ?>
        <img src="<?php echo esc_url($header_image); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
    <?php endif; ?>
    <?php
    if (has_nav_menu('primary')) {
        $args = array(
            'theme_location' => 'primary',
            'container' => '',
            'items_wrap' => '<ul class="root">%3$s</ul>'
        );
        echo '<nav id="main-nav" class="clearfix">
					<div class="container clearfix">';
        wp_nav_menu($args);
        echo '</div><!-- .container -->
					</nav><!-- #main-nav -->
					<div class="mobile-menu"></div>
					';
    } else {
        echo '<nav id="main-nav" class="clearfix">
					<div class="container clearfix">';
        wp_page_menu(array('menu_class' => 'root'));
        echo '</div><!-- .container -->
					</nav><!-- #main-nav -->
					<div class="mobile-menu"></div>
					';
    }
    ?>
    <?php
    if (is_home() || is_front_page()) {
        if ("0" == $options['disable_slider']) {
            if (function_exists('travelify_pass_cycle_parameters'))
                travelify_pass_cycle_parameters();
            if (function_exists('travelify_featured_post_slider'))
                travelify_featured_post_slider();
        }
    }
    else {
        if (( '' != travelify_header_title() ) || function_exists('bcn_display_list')) {

            $postid = get_the_ID();
            if($postid == '8'){
                if(is_user_logged_in()){
                    $show_title = "TRUE";
                }else{
                    $show_title = "FALSE";
                }
            }else{
                $show_title = get_post_meta($postid, $key='show_title', $single = TRUE);
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

/* * ************************************************************************************* */

if (!function_exists('travelify_socialnetworks')) :

    /**
     * This function for social links display on header
     *
     * Get links through Theme Options
     */
    function travelify_socialnetworks($flag) {

        global $travelify_theme_options_settings;
        $options = $travelify_theme_options_settings;

        $travelify_socialnetworks = '';
        if ((!$travelify_socialnetworks = get_transient('travelify_socialnetworks') ) && ( 1 == $flag )) {


            $travelify_socialnetworks .='
			<div class="social-icons clearfix">
				<ul>';

            $social_links = array();
            $social_links = array(
                'Facebook' => 'social_facebook',
                'Twitter' => 'social_twitter',
                'Google-Plus' => 'social_googleplus',
                'Pinterest' => 'social_pinterest',
                'YouTube' => 'social_youtube',
                'Vimeo' => 'social_vimeo',
                'LinkedIn' => 'social_linkedin',
                'Flickr' => 'social_flickr',
                'Tumblr' => 'social_tumblr',
                'Instagram' => 'social_instagram',
                'RSS' => 'social_rss',
                'GitHub' => 'social_github'
            );

            foreach ($social_links as $key => $value) {
                if (!empty($options[$value])) {
                    $travelify_socialnetworks .=
                        '<li class="' . strtolower($key) . '"><a href="' . esc_url($options[$value]) . '" title="' . sprintf(esc_attr__('%1$s on %2$s', 'travelify'), get_bloginfo('name'), $key) . '" target="_blank"></a></li>';
                }
            }

            $travelify_socialnetworks .='
			</ul>
			</div><!-- .social-icons -->';

            set_transient('travelify_socialnetworks', $travelify_socialnetworks, 86940);
        }
        echo $travelify_socialnetworks;
    }

endif;


/* * ************************************************************************************* */

if (!function_exists('travelify_featured_post_slider')) :

    /**
     * display featured post slider
     *
     */
    function travelify_featured_post_slider() {
        global $post;

        global $travelify_theme_options_settings;
        $options = $travelify_theme_options_settings;

        $travelify_featured_post_slider = '';
        if (!empty($options['featured_post_slider'])) {
            $travelify_featured_post_slider .= '
		<section class="featured-slider"><div class="left-featured-slider"><div class="slider-cycle">';
            $get_featured_posts = new WP_Query(array(
                'posts_per_page' => $options['slider_quantity'],
                'post_type' => array('post', 'page', 'slider'),
                'post__in' => $options['featured_post_slider'],
                'orderby' => 'post__in',
                'suppress_filters' => false,
                'ignore_sticky_posts' => 1       // ignore sticky posts
            ));
            $i = 0;
            while ($get_featured_posts->have_posts()) : $get_featured_posts->the_post();
                $i++;
                $title_attribute = apply_filters('the_title', get_the_title($post->ID));
                $excerpt = get_the_excerpt();
                if (1 == $i) {
                    $classes = "slides displayblock";
                } else {
                    $classes = "slides displaynone";
                }
                $travelify_featured_post_slider .= '
				<div class="' . $classes . '">';
                //-> print images
                $featured_image_left = get_field("featured_image_left", $post->ID);
                $featured_image_left_title_red = get_field("featured_image_left_title_red", $post->ID);
                $featured_image_left_title_black = get_field("featured_image_left_title_black", $post->ID);
                $featured_image_left_link_text = get_field("featured_image_left_link_text", $post->ID);
                $featured_image_left_link_url = get_field("featured_image_left_link_url", $post->ID);

                $travelify_featured_post_slider .= '<a href="' . $featured_image_left_link_url . '" title="' . $featured_image_left_link_text . '">
                                                        <figure class="featured_image_left" style="background: url('.$featured_image_left.') no-repeat center center;">
                                                            <img style="display:none;" width="" height="" src="'.$featured_image_left.'" class="img-responsive pngfix" alt="'.$featured_image_left_link_text.'" title="'.$featured_image_left_link_text.'">
                                                            <article class="featured-text">
                                                                <div class="featured-title"><a href="' . $featured_image_left_link_url . '" title="' . $featured_image_left_link_text . '">' . $featured_image_left_title_red . '</a></div><!-- .featured-title -->
                                                                <div class="featured-content">' . $featured_image_left_title_black . '</div><div class="clear"></div><!-- .featured-content -->
                                                                <a class="box-link-red" href="'.$featured_image_left_link_url.'">'.$featured_image_left_link_text.'</a>
                                                            </article><!-- .featured-text -->
                                                        </figure>
                                                    </a>';

                $travelify_featured_post_slider .= '</div><!-- .slides -->';

            endwhile;
            wp_reset_query();

            $home_post_id = 109;
            $featured_image_right = get_field("slider_featured_image_right_image", $home_post_id);
            $featured_image_right_title_red = get_field("slider_featured_image_right_title_red", $home_post_id);
            $featured_image_right_title_black = get_field("slider_featured_image_right_title_black", $home_post_id);
            $featured_image_right_link_text = get_field("slider_featured_image_right_link_text", $home_post_id);
            $featured_image_right_link_url = get_field("slider_featured_image_right_link_url", $home_post_id);

            $travelify_righ_featured_post_slider = '<figure class="featured_image_right slides">
                                                        <div class="img-resp-ver-right-slider" style="background-image: url('.$featured_image_right.');
                                                                                         min-height: 300px;
                                                                                         background-size: cover;"></div>
                                                        <a href="' . $featured_image_right_link_url . '" title="' . $featured_image_right_link_text . '">
                                                              <img width="" height="" src="'.$featured_image_right.'" class="img-responsive pngfix" alt="'.$featured_image_right_link_text.'" title="'.$featured_image_right_link_text.'">
                                                        </a>
                                                        <article class="featured-text">
                                                            <div class="featured-content">' . $featured_image_right_title_red . '</div><!-- .featured-content -->
                                                            <div class="featured-title"><a href="' . $featured_image_right_link_url . '" title="' . $featured_image_right_link_text . '">' . $featured_image_right_title_black . '</a></div><!-- .featured-title -->
                                                            <a class="box-link-red" href="'.$featured_image_right_link_url.'">'.$featured_image_right_link_text.'</a>
                                                        </article><!-- .featured-text -->
                                                    </figure>';

            $travelify_featured_post_slider .= '</div></div><div class="right-featured-slider">'.$travelify_righ_featured_post_slider.'</div>

		<nav id="controllers" class="clearfix">
		</nav><!-- #controllers --></section><!-- .featured-slider -->';
        }
        echo $travelify_featured_post_slider;
    }

endif;

/* * ************************************************************************************* */

if (!function_exists('travelify_breadcrumb')) :

    /**
     * Display breadcrumb on header.
     *
     * If the page is home or front page, slider is displayed.
     * In other pages, breadcrumb will display if breadcrumb NavXT plugin exists.
     */
    function travelify_breadcrumb() {
        if (function_exists('bcn_display_list')) {
            echo '<div class="breadcrumb">
		<ul>';
            bcn_display_list();
            echo '</ul>
		</div> <!-- .breadcrumb -->';
        }
    }

endif;

/* * ************************************************************************************* */

if (!function_exists('travelify_header_title')) :

    /**
     * Show the title in header
     */
    function travelify_header_title() {
        if (is_archive()) {
            $travelify_header_title = single_cat_title('', FALSE);
        } elseif (is_search()) {
            $travelify_header_title = __('Search Results', 'travelify');
        } elseif (is_page_template()) {
            $travelify_header_title = get_the_title();
        } else {
            $travelify_header_title = '';
        }

        return $travelify_header_title;
    }

endif;
?>