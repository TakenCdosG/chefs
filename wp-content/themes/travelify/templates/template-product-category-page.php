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
$product_cat = array();
if (count($categories) > 0) {
    foreach ($categories as $key => $category) {
        $product_cat[] = $category["slug"];
    }
}

$args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    'product_cat' => implode(",", $product_cats),
);
$products = new WP_Query($args);
$info = array(
    "args" => $args,
    "products" => $products
);

dpm($categories);
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
            if ($products->have_posts()) {
                while ($products->have_posts()) : $products->the_post();
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
