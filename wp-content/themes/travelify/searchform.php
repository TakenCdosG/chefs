<?php
/**
 * Displays the searchform of the theme.
 */
?>
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" class="searchform" method="get">
		<label class="assistive-text" for="s"><?php _e( 'Search', 'travelify' ); ?></label>
		<input type="text" placeholder="<?php esc_attr_e( 'Search', 'travelify' ); ?>" class="s field" name="s">
        <input type="submit" class="field-submit .btn-search" value="">
	</form>