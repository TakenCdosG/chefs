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
$contact_map = get_field("map");
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
                <?php echo $contact_map; ?>
            </div>
        </div>
    </div><!-- #container -->

<?php
/**
 * travelify_after_main_container hook
 */
do_action('travelify_after_main_container');
?>

<?php get_footer(); ?>