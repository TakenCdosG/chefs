<?php
/**
 * Portfolio single gallery
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

// Get gallery image ids
$attachments = wpex_get_gallery_ids();

// Return if there aren't any images
if ( ! $attachments ) {
	return;
} ?>

<div class="post-slider-wrap clr flexslider-container">

	<div class="post-slider flexslider">

		<ul class="slides clr wpex-lightbox-gallery">

			<?php
			// Loop through each attachment ID
			foreach ( $attachments as $attachment ) :
				$img_url	= wp_get_attachment_url( $attachment );
				$img_alt	= get_post_meta( $attachment, '_wp_attachment_image_alt', true );
				$img_html	= wp_get_attachment_image( $attachment, 'wpex-portfolio-post' ); ?>
				<li>
					<?php
					// Display image with lightbox
					if (  'on' == wpex_gallery_is_lightbox_enabled() ) : ?>
						<a href="<?php echo $img_url; ?>" title="<?php echo $img_alt; ?>" class="wpex-lightbox-item">
							<?php echo $img_html; ?>
						</a>
					<?php
					// Lightbox is disabled, only show image
					else : ?>
						<?php echo $img_html; ?>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>

		</ul><!-- .slides -->

	</div><!-- .post-slider .flexslider -->

</div><!-- .post-slider-wrap -->