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
    $manually_first_link = get_field("manually_first_link");
    // Second box
    $manually_second_background_image = get_field("manually_second_background_image");
    $manually_second_title_black = get_field("manually_second_title_black");
    $manually_second_title_red = get_field("manually_second_title_red");
    $manually_second_link = get_field("manually_second_link");
    // Third box
    $manually_third_background_image = get_field("manually_third_background_image");
    $manually_third_title_black = get_field("manually_third_title_black");
    $manually_third_title_red = get_field("manually_third_title_red");
    $manually_third_link = get_field("manually_third_link");
    // Fourth box
    $manually_fourth_background_image = get_field("manually_fourth_background_image");
    $manually_fourth_title_black = get_field("manually_fourth_title_black");
    $manually_fourth_title_red = get_field("manually_fourth_title_red");
    $manually_fourth_link = get_field("manually_fourth_link");
}



/*
$info = array(
    "first_post_id" => $first_post_id[0],
    "second_post" => $second_post[0],
    "third_post" => $third_post[0]
);

$first_blog_post = get_post($first_post_id);
$second_blog_post = get_post($second_post_id);
$third_blog_post = get_post($third_post_id);
*/
// Front Page - Logos
$num_logos = 10;
$logos_image = array();
for ($i = 1; $i <= $num_logos; $i++) {
    $image_key = "front_logo_" . $i;
    $link_key = "front_link_logo_" . $i;
    $image = get_field($image_key);
    $link = get_field($link_key);
    if (!empty($image)) {
        $new_image = array("image" => $image, "link" => $link);
        $logos_image[] = $new_image;
    }
}

//echo "<pre>";
//var_dump($category);
//echo "</pre>";
?>

    <div id="container">
        <?php if (!empty($text_top)): ?>
            <div class="row margin-grid margin-top-25 min-height-72">
                <div class="col-md-12">
                    <div class="text-top">
                        <?php echo $text_top; ?>
                    </div>
                </div>
            </div>
            <!-- Add clearfix -->
            <div class="clearfix-block"></div>
        <?php endif; ?>
        <?php if (!empty($title_top)): ?>
            <div class="row margin-grid min-height-30">
                <div class="col-md-12">
                    <div class="title_top">
                        <h1><?php echo $title_top; ?></h1>
                    </div>
                </div>
            </div>
            <!-- Add clearfix -->
            <div class="clearfix-block"></div>
        <?php endif; ?>
        <?php if (!empty($boxes_left_background_image) && !empty($boxes_right_background_image)): ?>
            <div class="row margin-grid boxes-dos-columns min-height-282">
                <div class="col-md-6">
                    <div class="box rl-boxes">
                        <?php if (!empty($boxes_left_background_image)): ?>
                            <img width="" height="" src="<?php echo $boxes_left_background_image; ?>" class="img-responsive" alt="<?php echo $boxes_left_title_red . " " . $boxes_left_title_black; ?>" title="<?php echo $boxes_left_title_red . " " . $boxes_left_title_black; ?>">
                            <div class="img-boxbg" style="background-image: url(<?php echo $boxes_left_background_image ?>);
                                                                                         min-height: 250px;
                                                                                         background-size: cover;"></div>
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
                    <div class="box rl-boxes">
                        <?php if (!empty($boxes_right_background_image)): ?>
                            <img width="" height="" src="<?php echo $boxes_right_background_image; ?>" class="img-responsive" alt="<?php echo $boxes_right_title_red . " " . $boxes_right_title_black; ?>" title="<?php echo $boxes_right_title_red . " " . $boxes_right_title_black; ?>">
                            <div class="img-boxbg" style="background-image: url(<?php echo $boxes_right_background_image ?>);
                                min-height: 250px;
                                background-size: cover;"></div>
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
        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
    <?php endif; ?>
        <div class="row margin-grid">
            <div class="col-md-12">
                <h2 class="line"><span class='color-black'>EXPERIENCE</span><span class='color-red'> CHEF'S</span></h2>
            </div>
            <div class="boxes-dos-columns ceps">
                <div class="col-md-3">
                    <div class="box rl-boxes">
                        <?php if(!empty($manually_first_link)): ?>
                        <a href="<?php echo $manually_first_link; ?>">
                            <?php endif; ?>
                            <?php if (!empty($manually_first_background_image)): ?>
                                <img width="" height="" src="<?php echo $manually_first_background_image; ?>" class="img-responsive" alt="<?php echo $manually_first_title_black . " " . $manually_first_title_red; ?>" title="<?php echo $manually_first_title_black . " " . $manually_first_title_red; ?>">
                                <div class="img-boxbg" style="background-image: url(<?php echo $manually_first_background_image ?>);
                                    min-height: 250px;
                                    background-size: cover;"></div>
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
                            <?php if(!empty($manually_first_link)): ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box rl-boxes">
                        <?php if(!empty($manually_second_link)): ?>
                        <a href="<?php echo $manually_second_link; ?>">
                            <?php endif; ?>
                            <?php if (!empty($manually_second_background_image)): ?>
                                <img width="" height="" src="<?php echo $manually_second_background_image; ?>" class="img-responsive" alt="<?php echo $manually_second_title_black . " " . $manually_second_title_red; ?>" title="<?php echo $manually_second_title_black . " " . $manually_second_title_red; ?>">
                                <div class="img-boxbg" style="background-image: url(<?php echo $manually_second_background_image ?>);
                                    min-height: 250px;
                                    background-size: cover;"></div>
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
                            <?php if(!empty($manually_second_link)): ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box rl-boxes">
                        <?php if(!empty($manually_third_link)): ?>
                        <a href="<?php echo $manually_third_link; ?>">
                            <?php endif; ?>
                            <?php if (!empty($manually_third_background_image)): ?>
                                <img width="" height="" src="<?php echo $manually_third_background_image; ?>" class="img-responsive" alt="<?php echo $manually_third_title_black . " " . $manually_third_title_red; ?>" title="<?php echo $manually_third_title_black . " " . $manually_third_title_red; ?>">
                                <div class="img-boxbg" style="background-image: url(<?php echo $manually_third_background_image ?>);
                                    min-height: 250px;
                                    background-size: cover;"></div>
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
                            <?php if(!empty($manually_third_link)): ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box rl-boxes">
                        <?php if(!empty($manually_fourth_link)): ?>
                        <a href="<?php echo $manually_fourth_link; ?>">
                            <?php endif; ?>
                            <?php if (!empty($manually_fourth_background_image)): ?>
                                <img width="" height="" src="<?php echo $manually_fourth_background_image; ?>" class="img-responsive" alt="<?php echo $manually_fourth_title_black . " " . $manually_fourth_title_red; ?>" title="<?php echo $manually_fourth_title_black . " " . $manually_fourth_title_red; ?>">
                                <div class="img-boxbg" style="background-image: url(<?php echo $manually_fourth_background_image ?>);
                                    min-height: 250px;
                                    background-size: cover;"></div>
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
                            <?php if(!empty($manually_fourth_link)): ?>
                        </a>
                    <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="resp-yes flexslider">
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
        <?php  get_template_part($slug = "from_the_blog", $name);?>
    <!-- Add clearfix -->
    <div class="clearfix-block"></div>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="resp-no flexslider">
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