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

        </div>
    </div>
</div>
