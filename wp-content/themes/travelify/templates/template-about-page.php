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

$title = ucfirst(strtolower(get_the_title(get_the_ID())));
// Header Top
$header_top_image_about = get_field("header_top_image_about");

// From The blog

$from_the_blog_about_post = get_field("from_the_blog_about");
$box_blog_post = $from_the_blog_about_post[0];
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

if($box_blog_post){
    $left_col_class = "col-md-8";
    $right_col_class = "col-md-4";
}else{
    $left_col_class = "col-md-12";
    $right_col_class = "hide hidden";
}

?>

    <div id="container">
        <nav class="general-breadcrumb"><a href="<?php echo get_home_url(); ?>">Home</a>&nbsp;/&nbsp;<?php echo $title; ?></nav>
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
            <div class="<?php echo $left_col_class;?>">
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

        </div>

        <div class="row margin-grid ftb">
            <div class="<?php echo $right_col_class;?>">
                <?php if($box_blog_post):?>
                    <div class="box margin-top-50">

                        <div class="post_thumbnail">
                            <?php if(!empty($box_blog_post["featured_image"])):?>
                                <img src="<?php echo $box_blog_post["featured_image"]; ?>" class="attachment-356x235 wp-post-image" alt="box-1">
                            <?php endif; ?>
                        </div>
                        <div class="post-summary">
                            <?php
                            $category = "";
                            if($box_blog_post["format"] == "image"){
                                $category = "<span class='color-red'>FEATURED BLOG POST: </span>";
                            }
                            ?>
                            <?php if (!empty($box_blog_post["title"])): ?>
                                <h3 class="post-title">
                                    <?php echo $category . restrict_words_number($box_blog_post["title"], $words_number = 28); ?>
                                </h3>
                            <?php endif; ?>
                            <?php if (!empty($box_blog_post["post_excerpt"])): ?>
                                <div class="post-excerpt">
                                    <?php echo restrict_words_number($box_blog_post["post_excerpt"], $words_number = 127); ?>
                                </div>
                            <?php endif; ?>
                            <a target="_blank" class="post-permalink" href="<?php echo esc_url($box_blog_post["link"]); ?>" title="<?php echo $box_blog_post["title"]; ?>">READ MORE</a>
                        </div>
                    </div>
                <?php endif;?>
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