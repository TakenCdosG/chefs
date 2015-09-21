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


// CONTACT MAP
$location = get_field("map");

// EXPERIENCE CHEF'S
// Pull boxes

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

$pull_boxes = get_field("pull_boxes", $post);

?>

    <div id="container">

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
            <div class="col-md-9">
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
            <div class="col-md-3">
                <?php if( !empty($location) ):  ?>
                    <div class="acf-map">
                        <div class="marker" data-lat="<?php echo $location['lat']; ?>" data-lng="<?php echo $location['lng']; ?>"><small><span class='color-red'><a target='_blank' style="font-size: 12px;" href="https://www.google.com/maps/place/449+Boston+Post+Rd,+Orange,+CT+06477,+EE.+UU./@41.2576974,-73.0134718,17z/data=!3m1!4b1!4m2!3m1!1s0x89e875c119620cdf:0x5bae5e90ee506a94">449 Boston Post Road Orange, CT 06477</a></span></small></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Add clearfix -->
        <div class="clearfix-block"></div>
        <div class="row margin-grid">
            <div class="col-md-12">
                <div class="flexslider">
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