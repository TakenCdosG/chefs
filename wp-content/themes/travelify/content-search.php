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

        if (have_posts()) {
        	?>
        	<div class="woocommerce">
                 <div class="row margin-grid">
                 	<div class="col-md-12">
                 	    <ul class="products no_left_sidebar">
				        	<?php
				        	$iterator = 1;
				            while (have_posts()) {
				                the_post();
				                if($iterator == 1){
                               		 echo "<div class='row'>";
                            	}
                           		 wc_get_template_part('content', 'product');
	                            $iterator = $iterator + 1;
	                            if($left_sidebar){
	                              if($iterator == 4){
	                                  echo "</div>";
	                                  $iterator = 1;
	                              }
	                            }else{
	                              if($iterator == 5){
	                                echo "</div>";
	                                $iterator = 1;
	                              }
	                            }
				            }
				            ?>
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