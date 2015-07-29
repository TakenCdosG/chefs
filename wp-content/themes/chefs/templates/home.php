<?php
/**
 * Template Name: Homepage
 *
 * @package     Chef's WordPress theme
 * @subpackage  Templates
 * @author      Adrián A. Morelos H.
 * @link        http://thinkcreativegroup.com
 * @since       1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area clr">

		<main id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article class="homepage-wrap clr">

					<?php include( locate_template( 'partials/home/layout.php' ) ); ?>
					
				</article><!-- #post -->

			<?php endwhile; ?>

		</main><!-- #content -->

	</div><!-- #primary -->
	
<?php get_footer(); ?>