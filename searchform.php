<?php
/**
 * Searchform template.
 *
 * @package bporg-breathe
 * @since 1.0.0
 */
?>
<form method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>" role="search">
	<label for="s" class="screen-reader-text"><?php _ex( 'Search', 'label', 'bporg-breathe' ); ?></label>
	<input type="search" class="field" name="s" value="<?php echo get_search_query(); ?>" id="s" placeholder="<?php _ex( 'Search &hellip;', 'placeholder', 'bporg-breathe' ); ?>">
	<input type="submit" class="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'bporg-breathe' ); ?>">
</form>
