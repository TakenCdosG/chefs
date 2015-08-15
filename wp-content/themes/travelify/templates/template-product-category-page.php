<?php
/**
 * Template Name: Product Category Display
 *
 * Displays the Product Category Page
 *
 */
?>

<?php get_header('products'); ?>
<?php
/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');
?>
<?php
/**
 * travelify_before_main_container hook
 */
do_action('travelify_before_main_container');
$categories = get_field("shop_by_category");
?>


<?php
$tax_query = "";
if (count($categories) > 0) {
    $tax_query['tax_query'] = array(
        'taxonomy' => 'product_cat',
        'field' => 'id',
        'terms' => $categories
    );
}
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    $tax_query
);
$loop = new WP_Query($args);
dpm($args);
?>
<div id="container">
    <?php
    /**
     * travelify_main_container hook
     *
     * HOOKED_FUNCTION_NAME PRIORITY
     *
     * travelify_content 10
     */
    // do_action('travelify_main_container');
    ?>
    <div class="woocommerce">
        <ul class="products">
            <?php
            if ($loop->have_posts()) {
                while ($loop->have_posts()) : $loop->the_post();
                    wc_get_template_part('content', 'product');
                endwhile;
            } else {
                echo __('No products found');
            }
            wp_reset_postdata();
            ?>
        </ul>
    </div>
</div><!-- #container -->

<?php
/**
 * travelify_after_main_container hook
 */
do_action('travelify_after_main_container');
?>

<?php
/**
 * woocommerce_after_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');
?>

<?php get_footer(''); ?>
