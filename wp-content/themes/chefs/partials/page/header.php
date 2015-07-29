<?php
/**
 * Displays the page header
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

// Not needed on the front-page
if ( is_front_page() ) {
	return;
} ?>

<header class="page-header clr">
	<h1 class="page-header-title"><?php the_title(); ?></h1>
</header><!-- #page-header -->