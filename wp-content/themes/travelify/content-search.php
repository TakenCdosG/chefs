<?php
/**
 * This file displays page with no sidebar.
 *
 */
?>


<?php
   /**
    * travelify_before_loop_content
	 *
	 * HOOKED_FUNCTION_NAME PRIORITY
	 *
	 * travelify_loop_before 10
    */
   do_action( 'travelify_before_loop_content' );

   /**
    * travelify_loop_content
	 *
	 * HOOKED_FUNCTION_NAME PRIORITY
	 *
	 * travelify_theloop 10
    */
    global $post;
    $hide_out_of_stock_items = (get_option("woocommerce_hide_out_of_stock_items")== "no")?FALSE:TRUE;

        if (have_posts()) {
        	?>
        	<div class="page-template-template-product-category-page">
	        	<div class="woocommerce">
	                 <div class="row margin-grid">
	                 	<div class="col-md-12">
					        	<?php
					        	$iterator = 0;
					            while (have_posts()) {
					                the_post();
					                $skip = FALSE;
				                	if($hide_out_of_stock_items){
				                		$postid = get_the_ID();
				                		$product = get_product( $postid );
				                		if(!$product->is_in_stock( )){
				                			$skip = TRUE;
				                		}
				                	}	
				                	if(!$skip){
				                		if($iterator == 0){
	                               		 echo "<div class='row'><ul class='products no_left_sidebar'>";
		                            	}
		                           		wc_get_template_part('content', 'product');
			                            $iterator = $iterator + 1;
			                            if($iterator >= 4){
		                                  echo "</ul></div>";
		                                  $iterator = 0;
			                            }
				                	}
					            }
					            ?>
					        </div>
	            		</div>
	            	</div>
	            </div>
            </div>
            <?php
        } else {
            ?>
            <h1 class="entry-title"><?php _e('No Posts Found.', 'travelify'); ?></h1>
            <?php
        }

   /**
    * travelify_after_loop_content
	 *
	 * HOOKED_FUNCTION_NAME PRIORITY
	 *
	 * travelify_next_previous 5
	 * travelify_loop_after 10
    */
   do_action( 'travelify_after_loop_content' );
?>