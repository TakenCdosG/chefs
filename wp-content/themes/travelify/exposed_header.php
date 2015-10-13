<?php
/**
 * Displays the header section of the theme.
 *
 */
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
<style type="text/css">
    .exposed-header{
        position: relative;
        display: block;
    }

    @media only screen and (min-width: 320px) and (max-width: 500px){

    }

    @media only screen and (min-width: 501px) and (max-width: 767px){

    }

    @media only screen and (min-width: 768px) and (max-width: 991px){

    }

    /*
    * RESPONSIVE > 992PX
    */
    @media only screen and (min-width: 992px){
        /* =Menu
        --------------------------------------------------------------*/
        #main-nav {
            border: 1px solid #439f55;
            cursor: pointer;
            background: #57ad68;
            color: #fff;
            position: relative;
            text-shadow: 0 1px 1px rgba(0,0,0,0.4);
            -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
        }
        #main-nav li {
            float: left;
            position: relative;
            padding: 0;
        }
        #main-nav a {
            color: #fff;
            display: block;
            float: left;
            font-size: 14px;
            padding: 8px 12px 0 10px;
            height: 32px;
        }
        #main-nav a:hover,#main-nav ul li.current-menu-item a,#main-nav ul li.current_page_ancestor a,#main-nav ul li.current-menu-ancestor a,#main-nav ul li.current_page_item a,#main-nav ul li:hover > a {
            background: #439f55;
            -webkit-box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter: alpha(opacity=100);
            opacity: 1;
            color: #fff;
        }
        #main-nav ul ul {
            text-shadow: none;
        }
        #main-nav li:hover > a,#main-nav ul ul :hover > a,#main-nav a:focus {
            color: #439f55;
        }
        /* Dropdown */
        #main-nav ul li ul,
        #main-nav ul li:hover ul ul,
        #main-nav ul ul li:hover ul ul,
        #main-nav ul ul ul li:hover ul ul,
        #main-nav ul ul ul ul li:hover ul ul {
            display: none;
            z-index: 9999;
        }
        #main-nav ul li:hover ul,
        #main-nav ul ul li:hover ul,
        #main-nav ul ul ul li:hover ul,
        #main-nav ul ul ul ul li:hover ul,
        #main-nav ul ul ul ul ul li:hover ul  {
            display: block;
        }
        #main-nav ul li ul {
            position: absolute;
            background-color: #fff;
            border-bottom: 4px solid #439f55;
            top: 40px;
            left: 0px;
            width: 190px;
        }
        #main-nav ul li ul li {
            float: none;
            border-bottom: 1px solid #EAEAEA;
            border-left: 1px solid #EAEAEA;
            border-right: 1px solid #EAEAEA;
            padding: 0;
        }
        #main-nav ul li ul li a,
        #main-nav ul li.current-menu-item ul li a,
        #main-nav ul li ul li.current-menu-item a,
        #main-nav ul li.current_page_ancestor ul li a,
        #main-nav ul li.current-menu-ancestor ul li a,
        #main-nav ul li.current_page_item ul li a {
            float: none;
            line-height: 21px;
            font-size: 13px;
            font-weight: normal;
            height: 100%;
            padding: 6px 10px;
            color: #777;
            text-transform: capitalize;
            background: #fff;
            border: none;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        #main-nav ul li.current_page_item ul li a {
            background: #fff;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        #main-nav ul li.current_page_item a {
            border: none;
        }
        #main-nav ul li ul li a:hover,#main-nav ul li ul li:hover > a,#main-nav ul li.current-menu-item ul li a:hover {
            background-color: #F9F9F9;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #439f55;
        }
        #main-nav ul li ul li ul {
            left: 188px;
            top: 0px;
        }
        #main-nav select {
            display: none;
        }
        .default-menu {
            display: none;
        }
    }

</style>

<div class="exposed-header">
    <div class="container clearfix">
        <div class="hgroup-wrap clearfix">
            <div class="hgroup-wrap-left">
                <!-- #site-logo -->
                <hgroup id="site-logo" class="clearfix">
                    <h1 id="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home">
                            <img src="<?php echo $options['header_logo']; ?>" alt="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>">
                        </a>
                    </h1>
                </hgroup>
                <!-- #site-logo -->
            </div>
            <div class="hgroup-wrap-right">
                <div class="menu-header-top">
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
                </div>
                <div class="middle-header-content">
                    <?php travelify_socialnetworks($flag); ?>
                    <?php get_search_form(); ?>
                </div>
                <div class="menu-header-bottom">
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
                </div>
            </div>
        </div>
    </div>

    <?php
    $args = array(
        'theme_location' => 'primary',
        'container' => '',
        'items_wrap' => '<ul class="root">%3$s</ul>'
    );
    ?>

    <nav id="main-nav" class="clearfix">
        <div class="container clearfix">
            <?php  wp_nav_menu($args); ?>
        </div><!-- .container -->
    </nav><!-- #main-nav -->
    <div class="mobile-menu"></div>
</div>