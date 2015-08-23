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

$args_category_material = array(
    'taxonomy' => 'pa_material',
    'hide_empty' => 0,
);

$categories_parent_material = get_categories($args_category_material);

$args_category_brand = array(
    'taxonomy' => 'pa_brand',
    'hide_empty' => 0,
);

$categories_parent_brand = get_categories($args_category_brand);

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

/*
* Filtros.
*/
$filtros = array();
$filtros["category"] = get_query_var('category');
$filtros["material"] = get_query_var('material');
$filtros["brand"] = get_query_var('brand');

/*
$args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    'paged' => $paged,
    'product_cat' => implode(",", $product_cat),
    'meta_query' => array(
        array(
            'key' => '_stock_status',
            'value' => array('instock', 'outofstock'),
            'compare' => 'IN',
        ),
    )
);
*/

$taxonomy_product_cat = (!empty($filtros["category"]))?$filtros["category"]:$product_cat;

$args = array(
    'post_type' => 'product',
    'posts_per_page' => 9,
    'paged' => $paged,
    'meta_query' => array(
        array(
            'key' => '_stock_status',
            'value' => array('instock', 'outofstock'),
            'compare' => 'IN',
        ),
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $taxonomy_product_cat,
        ),
    ),
);

if(!empty($filtros["material"]) || !empty($filtros["brand"])){
    $args['tax_query']['relation'] = 'AND';
    if(!empty($filtros["material"])){
          $args['tax_query'][] = array(
            'taxonomy' => 'pa_material',
            'field'    => 'slug',
            'terms'    => $filtros["material"],
          );
    }
    if(!empty($filtros["brand"])){
          $args['tax_query'][] = array(
            'taxonomy' => 'pa_brand',
            'field'    => 'slug',
            'terms'    => $filtros["brand"],
          );
    }
}

$products = new WP_Query($args);

$info = array(
    "args" => $args,
    "products" => $products
);

// Front Page - Logos
$post_id_home = 109;
$num_logos = 10;
$logos_image = array();
for ($i = 1; $i <= $num_logos; $i++) {
    $image_key = "front_logo_" . $i;
    $link_key = "front_link_logo_" . $i;
    $image = get_field($image_key, $post_id_home);
    $link = get_field($link_key, $post_id_home);
    if (!empty($image)) {
        $new_image = array("image" => $image, "link" => $link);
        $logos_image[] = $new_image;
    }
}
?>
<?php
$header_text_product_category = get_field("header_text_product_category");
$top_banner = get_field('header_top_product_category');
$header_top_summary = get_field('header_top_summary');
$header_top_link_text = get_field('header_top_link_text');
$header_top_link_url = get_field('header_top_link_url');

global $wp;
$query_vars = $wp->query_vars;
unset($query_vars["page"]);
unset($query_vars["pagename"]);
$current_url = home_url($wp->request);
$info = array(
    "current_url" => $current_url,
    "query_string" => $wp->query_string,
    "request" => $wp->request,
    "query_vars" => $wp->query_vars,
    "category" => get_query_var("category"),
    "brand" => get_query_var("brand"),
    "material" => get_query_var("material"),
    "wp" => $wp
);
//dpm($info);
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
    $filter_category_items = "";
    $filter_material_items  = "";
    $filter_brand_items = "";

    ?>
    <div class="woocommerce">
        <div class="row margin-grid">
            <div class="col-md-3">
                <div class="sidebar-left">
                    <div class="box-sidebar-left">
                        <h3>Shop by Category</h3>
                        <ul>
                            <?php $empty_filtros_category = empty($filtros["category"]); ?>
                            <?php foreach ($categories_parent as $key => $category): ?>
                                <li>
                                    <?php
                                    $query_vars_category = array_merge($query_vars, array('category' => $category->slug));
                                    $current_url_category = add_query_arg($query_vars_category, $current_url);
                                    $category_name = ucwords($category->name);
                                    $class = "";
                                    if(!$empty_filtros_category){
                                       if($filtros["category"] == $category->slug){
                                           $class = "active";
                                       }
                                    }
                                    ?>
                                    <a href="<?php echo $current_url_category; ?>" class="<?php echo $class;?>"> <?php echo $category_name; ?></a>
                                </li>
                                <?php $filter_category_items .= '<li><a href="'.$current_url_category.'" class="'.$class.'">'.$category_name.'</a></li>';?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="box-sidebar-left">
                        <h3>Shop by Material</h3>
                        <ul>
                            <?php $empty_filtros_material = empty($filtros["material"]); ?>
                            <?php foreach ($categories_parent_material as $key => $category): ?>
                                <li>
                                    <?php
                                    $query_vars_material = array_merge($query_vars, array('material' => $category->slug));
                                    $current_url_material = add_query_arg($query_vars_material, $current_url);
                                    $category_name = ucwords($category->name);
                                    $class = "";
                                    if(!$empty_filtros_material){
                                       if($filtros["material"] == $category->slug){
                                           $class = "active";
                                       }
                                    }
                                    ?>
                                    <a href="<?php echo $current_url_material; ?>" class="<?php echo $class;?>"> <?php echo ucwords($category->name); ?></a>
                                </li>
                                <?php $filter_material_items .= '<li><a href="'.$current_url_material.'" class="'.$class.'">'.$category_name.'</a></li>';?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="box-sidebar-left">
                        <h3>Shop by Brand</h3>
                        <ul>
                            <?php $empty_filtros_brand = empty($filtros["brand"]); ?>
                            <?php foreach ($categories_parent_brand as $key => $category): ?>
                                <li>
                                    <?php
                                    $query_vars_brand = array_merge($query_vars, array('brand' => $category->slug));
                                    $current_url_brand = add_query_arg($query_vars_brand, $current_url);
                                    $category_name = ucwords($category->name);
                                    $class = "";
                                    if(!$empty_filtros_brand){
                                       if($filtros["brand"] == $category->slug){
                                           $class = "active";
                                       }
                                    }
                                    ?>
                                    <a href="<?php echo $current_url_brand; ?>" class="<?php echo $class; ?>"> <?php echo ucwords($category->name); ?></a>
                                </li>
                                <?php $filter_brand_items .= '<li><a href="'.$current_url_brand.'" class="'.$class.'">'.$category_name.'</a></li>';?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <?php if (!empty($top_banner)): ?>
                    <div class="row margin-grid">
                        <div class="col-md-12">
                            <div class="top_banner_product_category">
                                <img width="" height="" src="<?php echo $top_banner; ?>" class="img-responsive" alt="" title="">
                                <?php if (!empty($header_top_summary)): ?>
                                    <div class="header_top_summary">
                                        <h2><?php echo $header_top_summary; ?></h2>
                                        <?php if (!empty($header_top_link_text) && !empty($header_top_link_url)): ?>
                                            <a href="<?php echo $header_top_link_url; ?>"><?php echo $header_top_link_text; ?></a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endif; ?>
                <!-- Add Filtros -->
                <div class="row margin-grid">
                     <div class="col-md-3">
                         <div class="category-filter">
                            <a href="#" data-jq-dropdown="#jq-dropdown-1" class="jq-dropdown-link">
                                Category
                                <div class="jq-dropdown-before"></div>
                            </a>
                            <div id="jq-dropdown-1" class="jq-dropdown jq-dropdown-tip jq-dropdown-scroll">
                                <ul class="jq-dropdown-menu">
                                    <?php echo $filter_category_items; ?>
                                </ul>
                            </div>
                         </div>
                     </div>
                     <div class="col-md-3">
                         <div class="category-filter">
                            <a href="#" data-jq-dropdown="#jq-dropdown-2" class="jq-dropdown-link">
                                Material
                                <div class="jq-dropdown-before"></div>
                            </a>
                            <div id="jq-dropdown-2" class="jq-dropdown jq-dropdown-tip jq-dropdown-scroll">
                                <ul class="jq-dropdown-menu">
                                    <?php echo $filter_material_items; ?>
                                </ul>
                            </div>
                         </div>
                     </div>
                     <div class="col-md-3">
                         <div class="category-filter">
                            <a href="#" data-jq-dropdown="#jq-dropdown-3" class="jq-dropdown-link">
                                Brand
                                <div class="jq-dropdown-before"></div>
                            </a>
                            <div id="jq-dropdown-3" class="jq-dropdown jq-dropdown-tip jq-dropdown-scroll">
                                <ul class="jq-dropdown-menu">
                                    <?php echo $filter_brand_items; ?>
                                </ul>
                            </div>
                         </div>
                     </div>
                </div>   
                <!-- End Filtros -->  
                <!-- Add clearfix -->
                <div class="clearfix-block"></div>
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
                <!-- Add pagination -->
                <div class="pagination-category">
                <?php
                $big = 999999999; // need an unlikely integer
                echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $products->max_num_pages,
                ) );
                ?>
                </div>
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
