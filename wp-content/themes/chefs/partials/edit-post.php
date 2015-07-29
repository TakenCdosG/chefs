<?php
/**
 * Post edit link
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

<footer class="entry-footer">
	<?php edit_post_link( __( 'Edit Post', 'wpex' ), '<span class="edit-link clr">', '</span>' ); ?>
</footer><!-- .entry-footer -->