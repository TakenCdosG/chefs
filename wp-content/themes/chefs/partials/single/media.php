<?php
/**
 * Post single media
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

// Return if password protected
if ( post_password_required() ) {
	return;
}

// Get post format
$format = get_post_format();

// Video
if ( 'video' == $format ) {

	get_template_part( 'partials/single/video' );

} elseif ( has_post_thumbnail() ) {

	get_template_part( 'partials/single/thumbnail' );

}