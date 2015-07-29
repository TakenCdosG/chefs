<?php
/**
 * Portfolio single layout
 *
 * @package     Chef's WordPress theme
 * @subpackage  Partials
 * @author      Adrián A. Morelos H.
 * @link        http://thinkcreativegroup.com
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<article class="clr">

	<?php get_template_part( 'partials/portfolio/single-header' ); ?>
	
	<?php get_template_part( 'partials/portfolio/single-media' ); ?>

	<?php get_template_part( 'partials/portfolio/single-content' ); ?>

	<?php get_template_part( 'partials/link-pages' ); ?>

	<?php get_template_part( 'partials/edit-post' ); ?>

	<?php if ( get_theme_mod( 'wpex_portfolio_comments') ) comments_template(); ?>

	<?php include( locate_template( 'partials/portfolio/single-related.php' ) ); ?>

</article><!-- .clr -->