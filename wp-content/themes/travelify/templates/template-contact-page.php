<?php
/**
 * Template Name: Contact Page Display
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

// CONTACT MAP
$location = get_field("map");

// EXPERIENCE CHEF'S
// Pull boxes
$post = 109;
$pull_boxes = get_field("pull_boxes", $post);
if ($pull_boxes == "pull_manually") {
    // First box
    $manually_first_background_image = get_field("manually_first_background_image", $post);
    $manually_first_title_black = get_field("manually_first_title_black", $post);
    $manually_first_title_red = get_field("manually_first_title_red", $post);
    $manually_first_link = get_field("manually_first_link", $post);
    // Second box
    $manually_second_background_image = get_field("manually_second_background_image", $post);
    $manually_second_title_black = get_field("manually_second_title_black", $post);
    $manually_second_title_red = get_field("manually_second_title_red", $post);
    $manually_second_link = get_field("manually_second_link", $post);
    // Third box
    $manually_third_background_image = get_field("manually_third_background_image", $post);
    $manually_third_title_black = get_field("manually_third_title_black", $post);
    $manually_third_title_red = get_field("manually_third_title_red", $post);
    $manually_third_link = get_field("manually_third_link", $post);
    // Fourth box
    $manually_fourth_background_image = get_field("manually_fourth_background_image", $post);
    $manually_fourth_title_black = get_field("manually_fourth_title_black", $post);
    $manually_fourth_title_red = get_field("manually_fourth_title_red", $post);
    $manually_fourth_link = get_field("manually_fourth_link", $post);
}

?>

    <div id="container">
        <div class="row margin-grid">

            <div class="col-md-3">
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
            <div class="col-md-9 gmapd">
                <?php if( !empty($location) ):  ?>
                    <div class="acf-map">
                        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"><small><span class='color-red'><a target='_blank' style="font-size: 12px;" href="https://www.google.com/maps/place/449+Boston+Post+Rd,+Orange,+CT+06477,+EE.+UU./@41.2576974,-73.0134718,17z/data=!3m1!4b1!4m2!3m1!1s0x89e875c119620cdf:0x5bae5e90ee506a94">449 Boston Post Road Orange, CT 06477</a></span></small></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
        <div class="row margin-grid-double">
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
    </div><!-- #container -->

<?php
/**
 * travelify_after_main_container hook
 */
do_action('travelify_after_main_container');
?>

<?php get_footer(); ?>