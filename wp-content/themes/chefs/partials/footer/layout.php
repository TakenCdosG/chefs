<?php
/**
 * Footer layout
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

<div id="footer-wrap" class="site-footer clr">

	<div id="footer" class="clr container">

		<?php get_template_part( 'partials/footer/widgets' ); ?>

	</div><!-- #footer -->

	<?php get_template_part( 'partials/footer/copyright' ); ?>

</div><!-- #footer-wrap -->