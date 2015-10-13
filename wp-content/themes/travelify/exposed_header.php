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
        clear: both;
        width: 100%;
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
        .exposed-header .hgroup-wrap-left{
            float: left;
            position: relative;
            display: block;
        }

        .exposed-header .hgroup-wrap-right{
            float: right;
            position: relative;
            display: block;
         }

        .exposed-header .hgroup-wrap-right .menu-header-top{
            display: block;
            clear: both;
            position: relative;
        }


        .exposed-header .hgroup-wrap-right .middle-header-content{
            display: block;
            clear: both;
            position: relative;
            float: right;
            height: 31px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .exposed-header .assistive-text {
            position: absolute !important;
            clip: rect(1px 1px 1px 1px);
            clip: rect(1px, 1px, 1px, 1px);
        }

        .exposed-header .hgroup-wrap-right .menu-header-bottom{
            display: block;
            clear: both;
            position: relative;
        }

        .exposed-header #main-nav{
            position: relative;
            display: block;
            clear: both;
            background: #231f20;
            height: 27px;
        }

        .exposed-header #main-nav ul.root{
            border: 1px solid #231f20;
            cursor: pointer;
            background: #231f20;
            color: #fff;
            text-shadow: 0 1px 1px rgba(0,0,0,0.4);
            -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.2);
            height: 26px;
            margin: 0px;
            padding: 0px;
            display: block;
            position: relative;
            list-style: none;
        }
        .exposed-header #main-nav li {
            float: left;
            position: relative;
            padding: 0;
            list-style: none;
            margin: 0;
        }
        .exposed-header #main-nav a {
            color: #fff;
            display: block;
            float: left;
            padding: 0px 10px 0 10px;
            height: auto;
            text-decoration: none;
            font: 12px Helvetica, sans-serif;
            line-height: 24px;
            word-wrap: break-word;
            transition: none;
        }
        .exposed-header #main-nav a:hover,.exposed-header #main-nav ul li.current-menu-item a,.exposed-header #main-nav ul li.current_page_ancestor a,.exposed-header #main-nav ul li.current-menu-ancestor a,.exposed-header #main-nav ul li.current_page_item a,.exposed-header #main-nav ul li:hover > a {
            background: #231f20;
            -webkit-box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            box-shadow: inset 0 0 4px rgba(0, 0, 0, 0.2);
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
            filter: alpha(opacity=100);
            opacity: 1;
            color: #fff;
            background: #ef3d42;
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

        .exposed-header .default-menu {
            display: none;
        }

        .exposed-header .menu-header-bottom{
            float: right;
            margin-top: 22px;
        }


        .exposed-header .menu-header-bottom .menu li{
            float: left;
            padding: 0px 5px;
        }

        .exposed-header .menu-header-bottom .menu li a{
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


        /* Dropdown */
        .exposed-header ul#menu-header-bottom{
            position: relative;
            display: block;
        }

        .exposed-header ul#menu-header-bottom li{
            position: relative;
            display: block;
        }

        .exposed-header ul#menu-header-bottom li a{
           text-decoration: none;
        }

        .exposed-header ul#menu-header-bottom li.current-menu-item a,
        .exposed-header ul#menu-header-bottom li.current-menu-ancestor a{
            color: #ef3d42;
            font-weight: 600;
        }

        .exposed-header ul#menu-header-bottom li ul li.current-menu-item a{
            color: #ef3d42!important;
        }

        .exposed-header ul#menu-header-bottom li ul,
        .exposed-header ul#menu-header-bottom li:hover ul ul,
        .exposed-header ul#menu-header-bottom ul li:hover ul ul,
        .exposed-header ul#menu-header-bottom ul ul li:hover ul ul,
        .exposed-header ul#menu-header-bottom ul ul ul li:hover ul ul {
            display: none;
            z-index: 9999;
        }

        .exposed-header ul#menu-header-bottom li:hover ul,
        .exposed-header ul#menu-header-bottom ul li:hover ul,
        .exposed-header ul#menu-header-bottom ul ul li:hover ul,
        .exposed-header ul#menu-header-bottom ul ul ul li:hover ul,
        .exposed-header ul#menu-header-bottom ul ul ul ul li:hover ul  {
            display: block;
        }
        .exposed-header ul#menu-header-bottom li ul {
            position: absolute;
            background-color: #fff;
            border-bottom: 4px solid #231f20;
            top: 15px;
            left: 0px;
            width: 190px;
            padding: 0px;
        }
        .exposed-header ul#menu-header-bottom li ul li {
            float: none;
            border-bottom: 1px solid #EAEAEA;
            border-left: 1px solid #EAEAEA;
            border-right: 1px solid #EAEAEA;
            padding: 0;
        }
        .exposed-header ul#menu-header-bottom li ul li a,
        .exposed-header ul#menu-header-bottom li.current-menu-item ul li a,
        .exposed-header ul#menu-header-bottom li ul li.current-menu-item a,
        .exposed-header ul#menu-header-bottom li.current_page_ancestor ul li a,
        .exposed-header  ul#menu-header-bottom li.current-menu-ancestor ul li a,
        .exposed-header ul#menu-header-bottom li.current_page_item ul li a {
            float: none;
            line-height: 15px;
            font-size: 12px;
            font-weight: normal;
            height: 100%;
            padding: 6px 10px;
            color: #231f20;
            text-transform: capitalize;
            background: #fff;
            border: none;
            -webkit-box-shadow: none;
            box-shadow: none;
            text-align: left;
        }
        .exposed-header ul#menu-header-bottom li.current_page_item ul li a {
            background: #fff;
            -webkit-box-shadow: none;
            box-shadow: none;
        }
        .exposed-header ul#menu-header-bottom li.current_page_item a {
            border: none;
        }
        .exposed-header ul#menu-header-bottom li ul li a:hover,ul#menu-header-bottom li ul li:hover > a,ul#menu-header-bottom li.current-menu-item ul li a:hover {
            background-color: #F9F9F9;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #ef3d42;
        }
        .exposed-header ul#menu-header-bottom li ul li ul {
            left: 188px;
            top: 0px;
        }

        .exposed-header ul#menu-header-bottom .default-menu {
            display: none;
        }

        .exposed-header #main-nav li:hover > a,
        .exposed-header #main-nav ul ul :hover > a,
        .exposed-header #main-nav a:focus {
            color: #fff;
        }


        /* Social Profiles */
        .exposed-header .social-icons {
            float: left;
        }

        .exposed-header .social-icons ul {
            margin: -10px 0 0;
            float: right;
        }

        .exposed-header .social-icons ul li {
            margin-top: 10px;
            float: left;
            padding-right: 1px;
        }
        .exposed-header .social-icons ul li a {
            display: inline-block;
            font-family: 'Genericons';
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            padding: 2px 3px;
            border-radius: 2px;
            font-size: 21px;
            color: #d0d0d0;
            -webkit-transition: all 0.3s ease-out;
            -moz-transition: all 0.3s ease-out;
            -ms-transition: all 0.3s ease-out;
            -o-transition: all 0.3s ease-out;
            transition: all 0.3s ease-out;
            vertical-align: top;
            text-align: center;
            font-style: normal;
            font-weight: normal;
            font-variant: normal;
            line-height: 1;
            text-decoration: inherit;
            text-transform: none;
            speak: none;
        }

        .exposed-header .social-icons ul li a:hover {
            color: #fff !important;
        }
        .social-icons ul li.facebook a:before {
            content: '\f204';
        }
        .social-icons ul li.facebook a:hover {
            background: #3B5998;
        }
        .social-icons ul li.twitter a:before {
            content: '\f202';
        }
        .social-icons ul li.twitter a:hover {
            background: #00aced;
        }
        .social-icons ul li.google-plus a:before {
            content: '\f218';
        }
        .social-icons ul li.google-plus a:hover {
            background: #cd4132;
        }
        .social-icons ul li.pinterest a:before {
            content: '\f209';
        }
        .social-icons ul li.pinterest a:hover {
            background: #cb2027;
        }
        .social-icons ul li.linkedin a:before {
            content: '\f207';
        }
        .social-icons ul li.linkedin a:hover {
            background: #005a87;
        }
        .social-icons ul li.tumblr a:before {
            content: '\f214';
        }
        .social-icons ul li.tumblr a:hover {
            background: #2b4761;
        }
        .social-icons ul li.vimeo a:before {
            content: '\f212';
        }
        .social-icons ul li.vimeo a:hover {
            background: #1bb7ea;
        }
        .social-icons ul li.instagram a:before {
            content: '\f215';
        }
        .social-icons ul li.instagram a:hover {
            background: #517fa4;
        }
        .social-icons ul li.flickr a:before {
            content: '\f211';
        }
        .social-icons ul li.flickr a:hover {
            background: #0063db;
        }
        .social-icons ul li.youtube a:before {
            content: '\f213';
        }
        .social-icons ul li.youtube a:hover {
            background: #cd4132;
        }
        .social-icons ul li.rss a:before {
            content: '\f413';
        }
        .social-icons ul li.rss a:hover {
            background: #fc7216;
        }
        .social-icons ul li.github a:before {
            content: '\f200';
        }
        .social-icons ul li.github a:hover {
            background: #151013;
        }

        .social-icons{
            float: left;
        }

        .middle-header-content .searchform{
            float: left;
            position: relative;
            min-width: 100px;
        }

        .middle-header-content .searchform input.field,
        .mobile-menu .searchform input.field{
            display: block;
            width: 100%;
            height: 31px;
            padding: 6px 12px;
            font-size: 14px;
            line-height: 1.42857143;
            color: #555;
            background-color: #fff;
            background-image: none;
            border: 1px solid #ccc;
            margin-left: 10px;
            text-indent: 0px;
            border-radius: 0px;
            cursor: text;
        }

        .middle-header-content .searchform input.field-submit{
            background-color: transparent!important;
            background: transparent!important;
            background-image: url('<?php echo get_template_directory_uri();?>/images/btn-search.png')!important;
            background-repeat: no-repeat!important;
            -webkit-appearance: none!important;
            border: transparent!important;
            color: #fff!important;
            cursor: pointer!important;
            font-size: 1em!important;
            font-weight: 400!important;
            outline: none!important;
            padding: 0px 10px!important;
            height: 22px!important;
            width: 20px!important;
            line-height: 30px!important;
            display: block!important;
            position: absolute!important;
            margin-top: 0px;
            text-indent: -999px!important;
            right: -4px;
            top: 6px;
        }
        

        .menu-header-top {
            float: right;
            display: block;
            position: relative;
        }

        .menu-header-top .cart-contents li{
            float: left;
            position: relative;
            display: block;
        }

        .menu-header-top .header_upper{
            display: block;
            position: relative;
            float: left;
            margin-top: 15px;
            margin-right: 5px;
        }

        .menu-header-top .header_upper .menu li a img{
            display: inline-block;
            vertical-align: top;
        }

        .menu-header-top .cart-contents{
            background-color: #e9e9e9;
            padding: 5px 5px;
            display: block;
            position: relative;
            float: left;
            line-height: 14px;
            margin-top: 10px;
            font-size: 11px;
        }

        .menu-header-top .menu{
            margin: 0px;
            padding: 0px;
        }

        .menu-header-top .menu li{
            float: left;
            padding: 0px 5px;
        }

        .menu-header-top .menu li a {
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