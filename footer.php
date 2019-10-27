<?php
/**
 * The template for displaying the footer.
 *
 * @package bporg-breathe
 * @since 1.0.0
 */
?>

		</div><!-- #main -->
	</div><!-- #page -->

	<hr class="hidden" />

<div id="footer">
	<div class="links">
		<p>
			<?php esc_html_e( 'See also:', 'bporg-breathe' ); ?>
			<a href="https://wordpress.org"><?php esc_html_e( 'WordPress.org', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://bbpress.org"><?php esc_html_e( 'bbPress.org', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://buddypress.org"><?php esc_html_e( 'BuddyPress.org', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://ma.tt"><?php esc_html_e( 'Matt', 'bporg-breathe' ); ?></a> &bull;
			<a href="<?php bloginfo( 'rss2_url' ); ?>"><?php esc_html_e( 'Blog RSS', 'bporg-breathe' ); ?></a>
		</p>
	</div>
	<div class="details">
		<p>
			<a href="https://twitter.com/buddypressdev" class="twitter"><?php esc_html_e( 'Follow BuddyPress on Twitter', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://buddypress.org/about/gpl/"><?php esc_html_e( 'GPL', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://buddypress.org/contact/"><?php esc_html_e( 'Contact Us', 'bporg-breathe' ); ?></a> &bull;
			<a href="https://wordpress.org/about/privacy/"><?php esc_html_e('Privacy', 'bporg-breathe'); ?></a> &bull;
			<a href="https://buddypress.org/terms/"><?php esc_html_e( 'Terms of Service', 'bporg-breathe' ); ?></a>
		</p>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
