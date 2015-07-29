<?php
/**
 * Page links
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

// Display page links
wp_link_pages( array(
	'before'		=> '<div class="page-links clr">',
	'after'			=> '</div>',
	'link_before'	=> '<span>',
	'link_after'	=> '</span>',
) ); ?>