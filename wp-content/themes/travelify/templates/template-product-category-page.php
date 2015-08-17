<?php
/**
 * Template Name: Product
 *
 * Displays the Product Category Page
 *
 */
global $product_category_display;
$product_category_display = TRUE;
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
$product_cat_id = 0;
if (count($categories) > 0) {
    foreach ($categories as $key => $category) {
        $product_cat[] = $category->slug;
        $product_cat_id = $category->term_id;
    }
}

$args_category = array(
    'parent' => $product_cat_id,
    'taxonomy' => 'product_cat',
    'hide_empty' => 0,
);
$categories_parent = get_categories($args_category);

$args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    'product_cat' => implode(",", $product_cat),
);
$products = new WP_Query($args);
$info = array(
    "args" => $args,
    "products" => $products
);
?>
<?php
$header_text_product_category = get_field("header_text_product_category");
?>
<?php if (!empty($header_text_product_category)): ?>
    <div class="header_text_product_category">
        <?php echo $header_text_product_category; ?>
    </div>
<?php endif; ?>
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
        <div class="row margin-grid">
            <div class="col-md-3">
                <div class="sidebar-left">
                    <h3>Shop by Category</h3>
                    <ul>
                        <?php foreach ($categories_parent as $key => $category): ?>
                            <li>
                                <?php echo ucwords($category->name); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-9">
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
        </div>
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
