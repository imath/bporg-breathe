<?php
/**
 * The sidebar template used in a handbook.
 *
 * @package bporg-breathe
 * @since 1.0.0
 */

if ( ! is_active_sidebar( wporg_get_current_handbook() ) )
	return;
?>
	<div id="secondary" class="widget-area" role="complementary">
		<a href="#" id="secondary-toggle"></a>
		<div id="secondary-content">
			<?php do_action( 'before_sidebar' ); ?>
			<?php dynamic_sidebar( wporg_get_current_handbook() ); ?>
		</div>
	</div><!-- #secondary -->

