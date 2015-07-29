<?php
/**
 * Outputs correct page layout
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
// Get page thumbnail
get_template_part( 'partials/page/thumbnail' );

// Get page header
get_template_part( 'partials/page/header' );

// Get page entry
get_template_part( 'partials/page/article' );

// Edit post link
get_template_part( 'partials/edit-post' );

// Display comments
comments_template();