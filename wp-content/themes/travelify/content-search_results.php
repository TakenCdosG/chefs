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
            while (have_posts()) {
                the_post();

                do_action('travelify_before_post');
                $postid = get_the_ID();
                $product = get_product( $postid );
                dpm($product);
                dpm($postid);
                ?>
                <section id="adrian post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <article>

                        <?php do_action('travelify_before_post_header'); ?>

                        <?php if($show_title != "FALSE"): ?>
                            <header class="entry-header">
                                <h2 class="entry-title">
                                    <?php the_title(); ?>
                                </h2><!-- .entry-title -->
                            </header>
                        <?php endif; ?>

                        <?php do_action('travelify_after_post_header'); ?>

                        <?php do_action('travelify_before_post_content'); ?>

                        <div class="entry-content clearfix">
                            <?php the_content(); ?>
                            <?php
                            wp_link_pages(array(
                                'before' => '<div style="clear: both;"></div><div class="pagination clearfix">' . __('Pages:', 'travelify'),
                                'after' => '</div>',
                                'link_before' => '<span>',
                                'link_after' => '</span>',
                                'pagelink' => '%',
                                'echo' => 1
                            ));
                            ?>
                        </div>

                        <?php
                        do_action('travelify_after_post_content');

                        do_action('travelify_before_comments_template');

                        comments_template();

                        do_action('travelify_after_comments_template');
                        ?>

                    </article>
                </section>
                <?php
                do_action('travelify_after_post');
            }
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