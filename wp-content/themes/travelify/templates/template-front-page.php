<?php
/**
 * Template Name: Front Page Display
 *
 * Displays the Homepage - Front Page.
 *
 */
?>

<?php get_header(); ?>

<?php
/**
 * travelify_before_main_container hook
 */
do_action('travelify_before_main_container');
$text_top = get_field("text_top");
$title_top = get_field("title_top");

// Boxes.
// Left.
$boxes_left_background_image = get_field("boxes_left_background_image");
$boxes_left_title_red = get_field("boxes_left_title_red");
$boxes_left_title_black = get_field("boxes_left_title_black");
$boxes_left_link_text = get_field("boxes_left_link_text");
$boxes_left_link_url = get_field("boxes_left_link_url");

// Right.
$boxes_right_background_image = get_field("boxes_right_background_image");
$boxes_right_title_red = get_field("boxes_right_title_red");
$boxes_right_title_black = get_field("boxes_right_title_black");
$boxes_right_link_text = get_field("boxes_right_link_text");
$boxes_right_link_url = get_field("boxes_right_link_url");

// EXPERIENCE CHEF'S
// Pull boxes
$pull_boxes = get_field("pull_boxes");
if ($pull_boxes == "pull_manually") {
    // First box
    $manually_first_background_image = get_field("manually_first_background_image");
    $manually_first_title_black = get_field("manually_first_title_black");
    $manually_first_title_red = get_field("manually_first_title_red");
    // Second box
    $manually_second_background_image = get_field("manually_second_background_image");
    $manually_second_title_black = get_field("manually_second_title_black");
    $manually_second_title_red = get_field("manually_second_title_red");
    // Third box
    $manually_third_background_image = get_field("manually_third_background_image");
    $manually_third_title_black = get_field("manually_third_title_black");
    $manually_third_title_red = get_field("manually_third_title_red");
    // Fourth box
    $manually_fourth_background_image = get_field("manually_fourth_background_image");
    $manually_fourth_title_black = get_field("manually_fourth_title_black");
    $manually_fourth_title_red = get_field("manually_fourth_title_red");
}

// From The Blog.
$first_post = get_field("first_post");
$first_post_id = $first_post[0];
$second_post = get_field("second_post");
$second_post_id = $second_post[0];
$third_post = get_field("third_post");
$third_post_id = $third_post[0];

$info = array(
    "first_post_id" => $first_post_id[0],
    "second_post" => $second_post[0],
    "third_post" => $third_post[0]
);
$first_blog_post = get_post($first_post_id);
$second_blog_post = get_post($second_post_id);
$third_blog_post = get_post($third_post_id);
$category = get_the_category($first_blog_post->ID);
echo "<pre>";
var_dump($category);
echo "</pre>";
?>

<div id="container">
    <?php if (!empty($text_top)): ?>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="text-top">
                    <?php echo $text_top; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($title_top)): ?>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="title_top">
                    <h1><?php echo $title_top; ?></h1>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($boxes_left_background_image) && !empty($boxes_right_background_image)): ?>
        <div class="row margin-grid">
            <div class="col-md-6">
                <div class="box">
                    <?php if (!empty($boxes_left_background_image)): ?>
                        <img width="" height="" src="<?php echo $boxes_left_background_image; ?>" class="img-responsive" alt="<?php echo $boxes_left_title_red . " " . $boxes_left_title_black; ?>" title="<?php echo $boxes_left_title_red . " " . $boxes_left_title_black; ?>">
                    <?php endif; ?>
                    <div class="box-summary-right">
                        <?php if (!empty($boxes_left_title_red)): ?>
                            <h1 class="red-title">
                                <?php echo $boxes_left_title_red; ?>
                            </h1>
                        <?php endif; ?>
                        <?php if (!empty($boxes_left_title_black)): ?>
                            <h3 class="black-title">
                                <?php echo $boxes_left_title_black; ?>
                            </h3>
                        <?php endif; ?>
                        <?php if (!empty($boxes_left_link_url) && !empty($boxes_left_link_text)): ?>
                            <a class="box-link-red" href="<?php echo $boxes_left_link_url; ?>"><?php echo $boxes_left_link_text; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <?php if (!empty($boxes_right_background_image)): ?>
                        <img width="" height="" src="<?php echo $boxes_right_background_image; ?>" class="img-responsive" alt="<?php echo $boxes_right_title_red . " " . $boxes_right_title_black; ?>" title="<?php echo $boxes_right_title_red . " " . $boxes_right_title_black; ?>">
                    <?php endif; ?>
                    <div class="box-summary-right">
                        <?php if (!empty($boxes_right_title_red)): ?>
                            <h1 class="red-title">
                                <?php echo $boxes_right_title_red; ?>
                            </h1>
                        <?php endif; ?>
                        <?php if (!empty($boxes_right_title_black)): ?>
                            <h3 class="black-title">
                                <?php echo $boxes_right_title_black; ?>
                            </h3>
                        <?php endif; ?>
                        <?php if (!empty($boxes_right_link_url) && !empty($boxes_right_link_text)): ?>
                            <a class="box-link-red" href="<?php echo $boxes_right_link_url; ?>"><?php echo $boxes_right_link_text; ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row margin-grid">
        <div class="col-md-12">
            <h2 class="line"><span class='color-black'>EXPERIENCE</span><span class='color-red'> CHEF'S</span></h2>
        </div>
        <div class="col-md-3">
            <div class="box">
                <?php if (!empty($manually_first_background_image)): ?>
                    <img width="" height="" src="<?php echo $manually_first_background_image; ?>" class="img-responsive" alt="<?php echo $manually_first_title_black . " " . $manually_first_title_red; ?>" title="<?php echo $manually_first_title_black . " " . $manually_first_title_red; ?>">
                <?php endif; ?>
                <div class="box-summary-middle">
                    <?php if (!empty($manually_first_title_black)): ?>
                        <h3 class="title-color-black">
                            <?php echo $manually_first_title_black; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($manually_first_title_red)): ?>
                        <h2 class="title-color-red">
                            <?php echo $manually_first_title_red; ?>
                        </h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box">
                <?php if (!empty($manually_second_background_image)): ?>
                    <img width="" height="" src="<?php echo $manually_second_background_image; ?>" class="img-responsive" alt="<?php echo $manually_second_title_black . " " . $manually_second_title_red; ?>" title="<?php echo $manually_second_title_black . " " . $manually_second_title_red; ?>">
                <?php endif; ?>
                <div class="box-summary-middle">
                    <?php if (!empty($manually_second_title_black)): ?>
                        <h3 class="title-color-black">
                            <?php echo $manually_second_title_black; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($manually_second_title_red)): ?>
                        <h2 class="title-color-red">
                            <?php echo $manually_second_title_red; ?>
                        </h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box">
                <?php if (!empty($manually_third_background_image)): ?>
                    <img width="" height="" src="<?php echo $manually_third_background_image; ?>" class="img-responsive" alt="<?php echo $manually_third_title_black . " " . $manually_third_title_red; ?>" title="<?php echo $manually_third_title_black . " " . $manually_third_title_red; ?>">
                <?php endif; ?>
                <div class="box-summary-middle">
                    <?php if (!empty($manually_third_title_black)): ?>
                        <h3 class="title-color-black">
                            <?php echo $manually_third_title_black; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($manually_third_title_red)): ?>
                        <h2 class="title-color-red">
                            <?php echo $manually_third_title_red; ?>
                        </h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box">
                <?php if (!empty($manually_fourth_background_image)): ?>
                    <img width="" height="" src="<?php echo $manually_fourth_background_image; ?>" class="img-responsive" alt="<?php echo $manually_fourth_title_black . " " . $manually_fourth_title_red; ?>" title="<?php echo $manually_fourth_title_black . " " . $manually_fourth_title_red; ?>">
                <?php endif; ?>
                <div class="box-summary-middle">
                    <?php if (!empty($manually_fourth_title_black)): ?>
                        <h3 class="title-color-black">
                            <?php echo $manually_fourth_title_black; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($manually_fourth_title_red)): ?>
                        <h2 class="title-color-red">
                            <?php echo $manually_fourth_title_red; ?>
                        </h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row margin-grid">
        <div class="col-md-12">
            <h2 class="line"><span class='color-black'>FROM THE</span><span class='color-red'> BLOG</span></h2>
        </div>
        <div class="col-md-4">
            <div class="box">
                <?php echo get_the_post_thumbnail($first_blog_post->ID, 'full'); ?>
                <div class="post-summary">
                    <?php if (!empty($first_blog_post->post_title)): ?>
                        <h3 class="post-title">
                            <?php echo $first_blog_post->post_title; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($first_blog_post->post_excerpt)): ?>
                        <div class="post-excerpt">
                            <?php echo $first_blog_post->post_excerpt; ?>
                        </div>
                    <?php endif; ?>
                    <a class="post-permalink" href="<?php echo esc_url(get_permalink($first_blog_post->ID)); ?>" title="<?php echo $first_blog_post->post_title; ?>">READ MORE</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <?php echo get_the_post_thumbnail($second_blog_post->ID, 'full'); ?>
                <div class="post-summary">
                    <?php if (!empty($second_blog_post->post_title)): ?>
                        <h3 class="post-title">
                            <?php echo $second_blog_post->post_title; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($second_blog_post->post_excerpt)): ?>
                        <div class="post-excerpt">
                            <?php echo $second_blog_post->post_excerpt; ?>
                        </div>
                    <?php endif; ?>
                    <a class="post-permalink" href="<?php echo esc_url(get_permalink($second_blog_post->ID)); ?>" title="<?php echo $second_blog_post->post_title; ?>">READ MORE</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">
                <?php echo get_the_post_thumbnail($third_blog_post->ID, 'full'); ?>
                <div class="post-summary">
                    <?php if (!empty($third_blog_post->post_title)): ?>
                        <h3 class="post-title">
                            <?php echo $third_blog_post->post_title; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if (!empty($third_blog_post->post_excerpt)): ?>
                        <div class="post-excerpt">
                            <?php echo $third_blog_post->post_excerpt; ?>
                        </div>
                    <?php endif; ?>
                    <a class="post-permalink" href="<?php echo esc_url(get_permalink($third_blog_post->ID)); ?>" title="<?php echo $third_blog_post->post_title; ?>">READ MORE</a>
                </div>
            </div>
        </div>
    </div>
    <?php
    /**
     * travelify_main_container hook
     *
     * HOOKED_FUNCTION_NAME PRIORITY
     *
     * travelify_content 10
     */
    //do_action('travelify_main_container');
    ?>
</div><!-- #container -->

<?php
/**
 * travelify_after_main_container hook
 */
do_action('travelify_after_main_container');
?>

<?php get_footer(); ?>