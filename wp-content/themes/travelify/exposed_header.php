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

    .exposed-header .clearfix {
        display: block;
    }

    .exposed-header .clearfix:before, {
        display: table;
        content: " ";
        clear: both;
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
        .exposed-header #main-nav{

        }

        .exposed-header #main-nav ul.root{
            border: 1px solid #231f20;
            cursor: pointer;
            background: #231f20;
            color: #fff;
            text-shadow: 0 1px 1px rgba(0,0,0,0.4);
            -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
            height: 23px;
            margin: 0px;
            padding: 0px;
            display: block;
            position: relative;
        }
        .exposed-header #main-nav li {
            float: left;
            position: relative;
            padding: 0;
        }
        .exposed-header #main-nav a {
            color: #fff;
            display: block;
            float: left;
            padding: 0px 10px 0 10px;
            height: auto;
            text-decoration: none;
            font: 14px Helvetica, sans-serif;
            line-height: 24px;
            word-wrap: break-word;
        }
        .exposed-header #main-nav a:hover,.exposed-header #main-nav ul li.current-menu-item a,.exposed-header #main-nav ul li.current_page_ancestor a,.exposed-header #main-nav ul li.current-menu-ancestor a,.exposed-header #main-nav ul li.current_page_item a,.exposed-header #main-nav ul li:hover > a {
            background: #231f20;
            -webkit-box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter: alpha(opacity=100);
            opacity: 1;
            color: #fff;
        }
        .exposed-header #main-nav ul ul {
            text-shadow: none;
        }
        .exposed-header #main-nav li:hover > a,.exposed-header #main-nav ul ul :hover > a,.exposed-header #main-nav a:focus {
            color: #231f20;
        }
        /* Dropdown */
        .exposed-header #main-nav ul li ul,
        .exposed-header #main-nav ul li:hover ul ul,
        .exposed-header #main-nav ul ul li:hover ul ul,
        .exposed-header #main-nav ul ul ul li:hover ul ul,
        .exposed-header #main-nav ul ul ul ul li:hover ul ul {
            display: none;
            z-index: 9999;
        }
        .exposed-header #main-nav ul li:hover ul,
        .exposed-header #main-nav ul ul li:hover ul,
        .exposed-header #main-nav ul ul ul li:hover ul,
        .exposed-header #main-nav ul ul ul ul li:hover ul,
        .exposed-header #main-nav ul ul ul ul ul li:hover ul  {
            display: block;
        }
        .exposed-header #main-nav ul li ul {
            position: absolute;
            background-color: #fff;
            border-bottom: 4px solid #231f20;
            top: 40px;
            left: 0px;
            width: 190px;
        }
        .exposed-header #main-nav ul li ul li {
            float: none;
            border-bottom: 1px solid #EAEAEA;
            border-left: 1px solid #EAEAEA;
            border-right: 1px solid #EAEAEA;
            padding: 0;
        }
        .exposed-header #main-nav ul li ul li a,
        .exposed-header #main-nav ul li.current-menu-item ul li a,
        .exposed-header #main-nav ul li ul li.current-menu-item a,
        .exposed-header #main-nav ul li.current_page_ancestor ul li a,
        .exposed-header #main-nav ul li.current-menu-ancestor ul li a,
        .exposed-header #main-nav ul li.current_page_item ul li a {
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
        .exposed-header #main-nav ul li.current_page_item ul li a {
            background: #fff;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .exposed-header #main-nav ul li.current_page_item a {
            border: none;
        }
        .exposed-header #main-nav ul li ul li a:hover,.exposed-header #main-nav ul li ul li:hover > a,.exposed-header #main-nav ul li.current-menu-item ul li a:hover {
            background-color: #F9F9F9;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #231f20;
        }
        .exposed-header #main-nav ul li ul li ul {
            left: 188px;
            top: 0px;
        }
        .exposed-header #main-nav select {
            display: none;
        }

        .default-menu {
            display: none;
        }

        .menu-header-bottom{
            float: right;
            margin-top: 22px;
        }


        .menu-header-bottom .menu li{
            float: left;
            padding: 0px 5px;
        }

        .menu-header-bottom .menu li a{
            color: #231f20;
            padding: 0px;
            background: transparent;
            text-transform: uppercase;
            font-size: 12px;
            line-height: 15px;
            display: block;
            position: relative;
            font-weight: normal;
            cursor: pointer;
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
    </nav><!-- .exposed-header #main-nav -->
    <div class="mobile-menu"></div>
</div>