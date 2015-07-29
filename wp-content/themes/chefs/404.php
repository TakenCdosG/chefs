<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package     Chef's WordPress theme
 * @subpackage  Templates
 * @author      Adrián A. Morelos H.
 * @link        http://thinkcreativegroup.com
 * @since       1.0.0
 */

get_header(); ?>

	<div id="primary" class="content-area clr">

		<div id="content" class="site-content clr" role="main">

			<div id="error-page" class="clr">	
				<h1 id="error-page-title">404</h1>			
				<p id="error-page-text">
				<?php _e( 'Unfortunately, the page you tried accessing could not be retrieved.', 'wpex' ); ?>
				</p>
			</div><!-- #error-page -->
			
		</div><!-- #content -->

	</div><!-- #primary -->

<?php get_footer(); ?>