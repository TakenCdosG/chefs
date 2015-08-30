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
            <div class="col-md-9">
                <?php if( !empty($location) ):  ?>
                    <div class="acf-map">
                        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
        <div class="row margin-grid">
            <div class="col-md-12">
                <h2 class="line"><span class='color-black'>EXPERIENCE</span><span class='color-red'> CHEF'S</span></h2>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <?php if(!empty($manually_first_link)): ?>
                    <a href="<?php echo $manually_first_link; ?>">
                        <?php endif; ?>
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
                        <?php if(!empty($manually_first_link)): ?>
                    </a>
                <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <?php if(!empty($manually_second_link)): ?>
                    <a href="<?php echo $manually_second_link; ?>">
                        <?php endif; ?>
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
                        <?php if(!empty($manually_second_link)): ?>
                    </a>
                <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <?php if(!empty($manually_third_link)): ?>
                    <a href="<?php echo $manually_third_link; ?>">
                        <?php endif; ?>
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
                        <?php if(!empty($manually_third_link)): ?>
                    </a>
                <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box">
                    <?php if(!empty($manually_fourth_link)): ?>
                    <a href="<?php echo $manually_fourth_link; ?>">
                        <?php endif; ?>
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
                        <?php if(!empty($manually_fourth_link)): ?>
                    </a>
                <?php endif; ?>
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