<?php namespace BuddyPressdotorg\Contribute\Breathe;
/**
 * Theme Functions
 *
 * @package bporg-breathe
 * @since 1.0.0
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Sets up theme defaults.
 */
function after_setup_theme() {
	register_nav_menu( 'header-nav-menu', 'Main nav bar' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'title-tag' );

	remove_theme_support( 'custom-header' );
	remove_theme_support( 'custom-background' );

	remove_action( 'customize_register', 'breathe_customize_register' );
	remove_action( 'customize_preview_init', 'breathe_customize_preview_js' );
	remove_filter( 'wp_head', 'breathe_color_styles' );

	add_action( 'customize_register', __NAMESPACE__ . '\customize_register' );

	add_theme_support( 'starter-content', array(
        // Set up nav menus
        'nav_menus'   => array(
            // Assign a menu to the "header-nav-menu" location.
            'header-nav-menu' => array(
                'name'  => __( 'BuddyPress.org nav', 'bporg-developer' ),
                'items' => array(
                    'about' => array(
                        'type'  => 'custom',
                        'title' => __( 'About', 'bporg-developer' ),
                        'url'   => 'https://buddypress.org/about/',
                    ),
                    'plugins' => array(
                        'type'  => 'custom',
                        'title' => __( 'Plugins', 'bporg-developer' ),
                        'url'   => 'https://buddypress.org/plugins/',
                    ),
                    'themes' => array(
                        'type'  => 'custom',
                        'title' => __( 'Themes', 'bporg-developer' ),
                        'url'   => 'https://buddypress.org/themes/',
                    ),
                    'documentation' => array(
                        'type'  => 'custom',
                        'title' => __( 'Documentation', 'bporg-developer' ),
                        'url'   => 'https://codex.buddypress.org/',
                    ),
                    'blog' => array(
                        'type'  => 'custom',
                        'title' => __( 'Blog', 'bporg-developer' ),
                        'url'   => 'https://buddypress.org/blog/',
                    ),
                    'download' => array(
                        'type'  => 'custom',
                        'title' => __( 'Download', 'bporg-developer' ),
                        'url'   => 'https://buddypress.org/download/',
                    ),
                ),
            ),
        ),
    ) );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\after_setup_theme', 11 );

/**
 * Add postMessage support for site title and description in the customizer.
 *
 * @param WP_Customize_Manager $wp_customize The customizer object.
 */
function customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', [
			'selector'            => '.site-title a',
			'container_inclusive' => false,
			'render_callback'     => __NAMESPACE__ . '\customize_partial_blogname',
		] );
	}
}

/**
 * Outputs a 'noindex,follow' meta tag for search results.
 */
function no_robots_search_results() {
	if ( is_search() ) {
		wp_no_robots();
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\no_robots_search_results', 9 );

/**
 * Renders the site title for the selective refresh partial.
 */
function customize_partial_blogname() {
	bloginfo( 'name' );
}

function styles() {
	wp_dequeue_style( 'breathe-style' );
	wp_enqueue_style( 'p2-breathe', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bporg-breathe', get_stylesheet_uri(), array( 'p2-breathe' ), '20190811' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\styles', 11 );

function inline_scripts() {
	$current_site = get_site();
	?>
	<script type="text/javascript">
		var el = document.getElementById( 'contribute-welcome-toggle' );
		if ( el ) {
			el.addEventListener( 'click', function( e ) {
				if ( jQuery( '.contribute-welcome .entry-content' ).is( ':hidden' ) ) {
					document.cookie = el.dataset.cookie + '=' +
						'; expires=Thu, 01 Jan 1970 00:00:00 GMT' +
						'; domain=<?php echo esc_js( $current_site->domain ); ?>' +
						'; path=<?php echo esc_js( $current_site->path ); ?>';
					jQuery( '#contribute-welcome-toggle' ).text( '<?php esc_attr_e( 'Hide welcome box', 'bporg' ); ?>' );
				} else {
					document.cookie = el.dataset.cookie + '=' + el.dataset.hash +
						'; expires=Fri, 31 Dec 9999 23:59:59 GMT' +
						'; domain=<?php echo esc_js( $current_site->domain ); ?>' +
						'; path=<?php echo esc_js( $current_site->path ); ?>';
					jQuery( '#contribute-welcome-toggle' ).text( '<?php esc_attr_e( 'Show welcome box', 'bporg' ); ?>' );
				}

				jQuery( '.contribute-welcome .entry-content' ).slideToggle();
				jQuery( '.contribute-welcome .post-edit-link' ).toggle();
			} );
		}
	</script>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\inline_scripts' );

function welcome_box() {
	$welcome      = get_page_by_path( 'welcome' );
	$cookie       = 'welcome-' . get_current_blog_id();
	$hash         = isset( $_COOKIE[ $cookie ] ) ? $_COOKIE[ $cookie ] : '';
	$content_hash = $welcome ? md5( $welcome->post_content ) : '';

	if ( ! $welcome ) {
		return;
	}

	if ( ! $hash || $content_hash !== $hash ) {
		$class = '';
		$label = __( 'Hide welcome box', 'bporg' );
	} else {
		$class = 'hidden';
		$label = __( 'Show welcome box', 'bporg' );
	}

	$columns = preg_split( '|<hr\s*/?>|', $welcome->post_content );
	if ( count( $columns ) === 2 ) {
		$welcome->post_content = "<div class='content-area'>\n\n{$columns[0]}</div><div class='widget-area'>\n\n{$columns[1]}</div>";
	}

	setup_postdata( $welcome );

	// Disable Jetpack sharing buttons
	add_filter( 'sharing_show', '__return_false' );
	?>
	<div class="contribute-welcome">
		<div class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'bporg' ), '', '', $welcome->ID, 'post-edit-link ' . $class ); ?>
			<button type="button" id="contribute-welcome-toggle" data-hash="<?php echo $content_hash; ?>" data-cookie="<?php echo $cookie; ?>"><?php echo $label; ?></button>
		</div>
		<div class="entry-content clear <?php echo $class; ?>"">
			<?php the_content(); ?>
		</div>
	</div>
	<?php
	remove_filter( 'sharing_show', '__return_false' );
	wp_reset_postdata();
}
add_action( 'bporg_breathe_after_header', __NAMESPACE__ . '\welcome_box' );

function javascript_notice() {
	?>
	<noscript class="js-disabled-notice">
		<?php _e( 'Please enable JavaScript to view this page properly.', 'bporg' ); ?>
	</noscript>
	<?php
}
add_action( 'wp_footer', __NAMESPACE__ . '\javascript_notice' );

/**
 * Adds each site's slug to the body class, so that CSS rules can target specific sites.
 *
 * @param array $classes Array of CSS classes.
 * @return array Array of CSS classes.
 */
function add_site_slug_to_body_class( $classes ) {
	$current_site = get_site( get_current_blog_id() );

	$classes[] = 'bporg-contribute';
	if ( $current_site ) {
		$classes[] = 'contribute-' . trim( $current_site->path, '/' );
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\add_site_slug_to_body_class' );
