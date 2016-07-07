<?php

/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
get_header('shop');
?>
<?php

/**
 * woocommerce_before_main_content hook
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 */
do_action('woocommerce_before_main_content');

$top_banner = get_field("header_top_product");
$header_top_summary_product = get_field("header_top_summary_product");
$header_top_link_text_product = get_field("header_top_link_text_product");
$header_top_link_url_product = get_field("header_top_link_url_product");
?>
<div class="row">
    <div class="col-md-9">
        <?php if (!empty($top_banner)): ?>
            <div class="row margin-grid">
                <div class="col-md-12">
                    <div class="top_banner_product_category">
                        <img width="" height="" src="<?php echo $top_banner; ?>" class="img-responsive" alt="" title="">
                        <?php if (!empty($header_top_summary_product)): ?>
                            <div class="header_top_summary">
                                <h2><?php echo $header_top_summary_product; ?></h2>
                                <?php if (!empty($header_top_link_text_product) && !empty($header_top_link_url_product)): ?>
                                    <a href="<?php echo $header_top_link_url_product; ?>"><?php echo $header_top_link_text_product; ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Add clearfix -->
        <div class="clearfix-block"></div>

        <?php while (have_posts()) : the_post(); ?>
            <?php wc_get_template_part('content', 'single-product'); ?>
        <?php endwhile; // end of the loop.   ?>
        <?php

        /**
         * woocommerce_after_main_content hook
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action('woocommerce_after_main_content');
        ?>
        <div class="col-md-3">
            <?php
                $args = array(
                    'posts_per_page' => 5,
                );
            ?>
            <?php echo 	woocommerce_related_products($args); ?> 
        </div>
    </div>
</div>
<?php

/**
 * woocommerce_sidebar hook
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action('woocommerce_sidebar');
?>
<?php get_footer('shop'); ?>
