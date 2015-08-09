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
?>

<div id="container">
    <?php if (!empty($text_top)): ?>
        <div class="text-top">
            <?php echo $text_top; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($title_top)): ?>
        <div class="title_top">
            <h1><?php echo $title_top; ?></h1>
        </div>
    <?php endif; ?>
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