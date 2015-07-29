<?php
/**
 * Homepage Content
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
}

// Return if there isn't any content or it's disabled
if ( ! get_the_content() || ! get_theme_mod( 'wpex_homepage_content', true ) ) {
	return;
} ?>

<div id="homepage-content" class="entry clr">
	<?php the_content(); ?>
</div><!-- .entry-content -->