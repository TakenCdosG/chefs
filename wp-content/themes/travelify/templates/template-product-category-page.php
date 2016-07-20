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
$product_cat_id = array();
$categories_parent = array();
if (count($categories) > 0) {
    foreach ($categories as $key => $category) {
        $product_cat[] = $category->slug;
        $product_cat_id[] = $category->term_id;
    }
}
foreach ($product_cat_id as $pcikey) {
    $args_category = array(
        'parent' => $pcikey,
        'taxonomy' => 'product_cat',
        'hide_empty' => 1,
    );
    $tmp_categories = get_categories($args_category);
    if(count($tmp_categories)>0){
      $categories_parent[] = $tmp_categories;
    }
}

$args_category_material = array(
    'taxonomy' => 'pa_material',
    'hide_empty' => 1,
);

$categories_parent_material = get_categories($args_category_material);

$args_category_brand = array(
    'taxonomy' => 'pa_brand',
    'hide_empty' => 1,
);

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

/*
* Filtros.
*/
$filtros = array();
$filtros["category"] = get_query_var('category');
$filtros["material"] = get_query_var('material');
$filtros["brand"] = get_query_var('brand');

$taxonomy_product_cat = (!empty($filtros["category"]))?$filtros["category"]:$product_cat;

// get_categories($args_category_brand)
$categories_parent_brand = get_brands($taxonomy_product_cat);

$hide_out_of_stock_item = (get_option("woocommerce_hide_out_of_stock_items")== "no")?FALSE:TRUE;
if($hide_out_of_stock_item){
    $availability = array('instock');
}else{
    $availability = array('outofstock');
}

$order_by = get_field("order_by");
$order_by_parameters = array();
if($order_by){
  if($order_by != "default"){
    if($order_by == "_price"){
      $order_by_parameters["orderby"] = "meta_value_num";
      $order_by_parameters["meta_key"] = "_price";
      $order = get_field("order");
      if($order){
        $order_by_parameters['order'] = $order;
      }
    }  
  }  
}

$args = array(
    'post_type' => 'product',
    'posts_per_page' => 18,
    'paged' => $paged,
    'meta_query' => array(
      array(
        'key' => '_stock_status',
        'value' => $availability,
        'compare' => 'IN',
      )
    ),
    'tax_query' => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $taxonomy_product_cat,
        ),
    ),
);

if(!empty($order_by_parameters)){
  $args = array_merge($args, $order_by_parameters);
}

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
$current_url = home_url($wp->request)."/";
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
    <?php
     $count_categories_parent = count($categories_parent);
     $count_categories_parent_material = count($categories_parent_material);
     $count_categories_parent_brand = count($categories_parent_brand);
     $left_sidebar = ($count_categories_parent > 0) || ($count_categories_parent_material > 0) || ($count_categories_parent_brand > 0);
    ?>
    <div class="woocommerce">
        <div class="row margin-grid">
            <?php if ($left_sidebar): ?>
            <div class="col-md-3">
                <div class="sidebar-left">

                    <?php if(count($count_categories_parent) > 0): ?>
                      <div class="box-sidebar-left">
                          <h3>Shop by Category</h3>
                          <ul>
                              <?php $empty_filtros_category = empty($filtros["category"]); ?>
                              <?php foreach ($categories_parent as $catpar): ?>
                                  <?php foreach ($catpar as $key => $category): ?>
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
                              <?php endforeach; ?>
                          </ul>
                      </div>
                    <?php endif; ?>

                    <?php if(count($categories_parent_material) > 0): ?>
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
                    <?php endif; ?>

                    <?php if(count($count_categories_parent_brand) > 0): ?>
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
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            <div class="<?php if ($left_sidebar){ ?> col-md-9 <?php }else{ ?> col-md-12<?php } ?>">
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
                     <?php if($count_categories_parent > 0): ?>
                     <div class="col-md-3 catfilter">
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
                     <?php endif; ?>

                     <?php if(count($categories_parent_material)>0): ?>
                       <div class="col-md-3 matfilter">
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
                     <?php endif; ?>

                     <?php if($count_categories_parent_brand > 0): ?>
                       <div class="col-md-3 brfilter">
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
                     <?php endif; ?>
                </div>
                <!-- End Filtros -->
                <!-- Add clearfix -->
                <div class="clearfix-block"></div>
                <ul class="products <?php if ($left_sidebar){ ?> left_sidebar <?php }else{ ?> no_left_sidebar<?php } ?>">
                    <?php
                    if ($products->have_posts()) {
                        $iterator = 0;
                        while ($products->have_posts()) : $products->the_post();
                            $skip = FALSE;
                            if($hide_out_of_stock_item){
                                $postid = get_the_ID();
                                $product = get_product( $postid );
                                if(!$product->is_in_stock()){
                                    $skip = TRUE;
                                }
                            }
                            if(!$skip){
                                if($iterator == 0){
                                    //echo "<div class='row'>";
                                }
                                wc_get_template_part('content', 'product');
                                $iterator = $iterator + 1;
                                if($left_sidebar){
                                    if($iterator == 3){
                                        //echo "</div>";
                                        $iterator = 0;
                                    }
                                }
                            }
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
