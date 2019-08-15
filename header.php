<?php
/**
 * The Header for our theme.
 *
 * @package bporg-breath
 * @since 1.0.0
 */
?>
<!DOCTYPE html><html>

<?php get_template_part( 'template-parts/header', 'head' ); ?>

<body id="buddypress-org" <?php body_class(); ?>>

<?php wp_body_open(); ?>

<?php get_template_part( 'template-parts/header', 'accessibility' ); ?>

<div id="header">
	<div id="header-inner">

		<?php get_template_part( 'template-parts/header', 'nav' ); ?>

		<div id="network-title">
			<a href="https://buddypress.org"><?php bloginfo( 'name' ); ?></a>
		</div>

	</div>
	<div style="clear:both"></div>
</div>

<header id="masthead" class="site-header" role="banner">
	<a href="#" id="secondary-toggle" onclick="return false;"><strong><?php esc_html_e( 'Menu', 'bporg-breathe' ); ?></strong></a>
	<div class="site-branding">
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>
	</div>

	<nav id="site-navigation" class="navigation-main clear" role="navigation">
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => false, 'depth' => 1 ) ); ?>
	</nav><!-- .navigation-main -->
</header><!-- .site-header -->

<?php do_action( 'bporg_breathe_after_header' ); ?>

<div id="page" class="hfeed site">
	<?php do_action( 'before' ); ?>

	<div id="main" class="site-main clear">
