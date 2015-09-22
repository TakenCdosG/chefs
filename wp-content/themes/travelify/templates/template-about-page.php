<?php
/**
 * Template Name: About Page Display
 *
 * Displays the Blog with Full Content Display.
 *
 */
?>

<?php get_header(); ?>

<?php
/**
 * travelify_before_main_container hook
 */
do_action('travelify_before_main_container');

// Header Top
$header_top_image_about = get_field("header_top_image_about");

// From The blog

$from_the_blog_about_post = get_field("from_the_blog_about");
$from_the_blog_about_post_id = $from_the_blog_about_post[0];
/*
$info = array(
    "from_the_blog_about_post" => $from_the_blog_about_post,
    'from_the_blog_about_post_id' => $from_the_blog_about_post_id
);

dpm(get_fields());
*/
// Front Page - Logos
$num_logos = 10;
$post = 109;

$logos_image = array();
for ($i = 1; $i <= $num_logos; $i++) {
    $image_key = "front_logo_" . $i;
    $link_key = "front_link_logo_" . $i;
    $image = get_field($image_key, $post);
    $link = get_field($link_key, $post);
    if (!empty($image)) {
        $new_image = array("image" => $image, "link" => $link);
        $logos_image[] = $new_image;
    }
}


$box_blog_post = get_post($from_the_blog_about_post_id);

?>

    <div id="container">
        <nav class="general-breadcrumb"><a href="<?php echo get_home_url(); ?>">Home</a>&nbsp;/&nbsp;<?php echo ucfirst(get_the_title(get_the_ID())); ?></nav>
        <?php if (!empty($header_top_image_about)): ?>
            <div class="row margin-grid">
                <div class="col-md-12">
                    <div class="top_banner_about_page">
                        <img width="" height="" src="<?php echo $header_top_image_about; ?>" class="img-responsive" alt="" title="">
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row margin-grid">
            <div class="col-md-8">
                <?php
                /**
                 * travelify_main_container hook
                 *
                 * HOOKED_FUNCTION_NAME PRIORITY
                 *
                 * travelify_content 10
                 */
                do_action('travelify_main_container');
                ?>
            </div>
            <div class="col-md-4">
                <div class="box margin-top-50">
                    <div class="post_thumbnail">
                        <?php echo get_the_post_thumbnail($box_blog_post->ID, array("356", "235")); ?>
                    </div>
                    <div class="post-summary">
                        <?php
                        $categories = get_the_category($box_blog_post->ID);
                        $category = "";
                        if (isset($categories[0]->name)) {
                            $category = "<span class='color-red'>" . $categories[0]->name . ": </span>";
                        }
                        ?>
                        <?php if (!empty($box_blog_post->post_title)): ?>
                            <h3 class="post-title">
                                <?php echo $category . $box_blog_post->post_title; ?>
                            </h3>
                        <?php endif; ?>
                        <?php if (!empty($box_blog_post->post_excerpt)): ?>
                            <div class="post-excerpt">
                                <?php echo $box_blog_post->post_excerpt; ?>
                            </div>
                        <?php endif; ?>
                        <a class="post-permalink" href="<?php echo esc_url(get_permalink($box_blog_post->ID)); ?>" title="<?php echo $box_blog_post->post_title; ?>">READ MORE</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="flexslider margin-bottom-60">
                    <ul class="slides">
                        <?php foreach ($logos_image as $key => $item): ?>
                            <li>
                                <img src="<?php echo $item["image"]; ?>" />
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Add clearfix -->
        <div class="clearfix-block"></div>

    </div><!-- #container -->

<?php
/**
 * travelify_after_main_container hook
 */
do_action('travelify_after_main_container');
?>

<?php get_footer(); ?>