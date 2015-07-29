<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package     Chef's WordPress theme
 * @subpackage  Templates
 * @author      Adrián A. Morelos H.
 * @link        http://thinkcreativegroup.com
 * @since       1.0.0
 */

if ( is_active_sidebar( 'sidebar' ) ) : ?>

	<aside id="secondary" class="sidebar-container" role="complementary">

		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'sidebar' ); ?>
			</div>
		</div>
		
	</aside><!-- #secondary -->

<?php endif; ?>