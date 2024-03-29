<?php namespace BuddyPressdotorg\Contribute\Breathe;
/**
 * Theme Functions
 *
 * @package bporg-breathe
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'o2_hovercards_add_service' ) ) {
	require __DIR__ . '/inc/o2-hovercards.php';
}

if ( ! is_admin() && file_exists( __DIR__ . '/inc/buddypress.php' ) ) {
	require __DIR__ . '/inc/buddypress.php';
}

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
		// Create initial pages.
		'posts' => array(
			'home' => array(
				'post_title' => __( 'Get Involved', 'bporg-breathe' ),
				'post_type'  => 'page',
				'post_name'  => 'get-involved',
				'post_content' => __( 'Replace this content with the home page content.', 'bporg-breathe' ),
				'template'   => 'full-width.php',
			),
			'updates' => array(
				'post_title' => __( 'Updates', 'bporg-breathe' ),
				'post_type'  => 'page',
				'post_name'  => 'updates',
			),
			'welcome' => array(
				'post_title'   => __( 'Welcome', 'bporg-breathe' ),
				'post_type'    => 'page',
				'post_name'    => 'welcome',
				'post_content' => __( 'Replace this content with the welcome box content.', 'bporg-breathe' ),
			),
			'handbook' => array(
				'post_title' => __( 'BuddyPress Contributor Handbook', 'bporg-breathe' ),
				'post_type'  => 'handbook',
				'post_name'  => 'handbook',
				'menu_order' => 0,
			),
		),

		// Default to a static front page and assign the front and posts pages.
		'options' => array(
			'show_on_front' => 'page',
			'page_on_front' => '{{home}}',
			'page_for_posts' => '{{updates}}',
		),

		// Set the site title and description.
		'theme_mods' => array(
			'blogname'        => __( 'Get Involved', 'bporg-breathe' ),
			'blogdescription' => __( 'Welcome to the BuddyPress open source project.', 'bporg-breathe' ),
		),

		// Set up nav menus
		'nav_menus'   => array(
			// Assign a menu to the "header-nav-menu" location.
			'header-nav-menu' => array(
				'name'  => __( 'BuddyPress.org nav', 'bporg-breathe' ),
				'items' => array(
					'about' => array(
						'type'  => 'custom',
						'title' => __( 'About', 'bporg-breathe' ),
						'url'   => 'https://buddypress.org/about/',
					),
					'documentation' => array(
						'type'  => 'custom',
						'title' => __( 'Documentation', 'bporg-breathe' ),
						'url'   => 'https://codex.buddypress.org/',
					),
					'get_involved' => array(
						'type'      => 'post_type',
						'object'    => 'page',
						'object_id' => '{{home}}',
					),
					'news' => array(
						'type'  => 'custom',
						'title' => __( 'Blog', 'bporg-breathe' ),
						'url'   => 'https://buddypress.org/blog/',
					),
					'support' => array(
						'type'  => 'custom',
						'title' => __( 'Support', 'bporg-breathe' ),
						'url'   => 'https://buddypress.org/themes/',
					),
					'download' => array(
						'type'  => 'custom',
						'title' => __( 'Download', 'bporg-breathe' ),
						'url'   => 'https://buddypress.org/download/',
					),
				),
			),

			// Assign a menu to the "primary" location.
			'primary' => array(
				'name' => __( 'Make BuddyPress Menu', 'bporg-breathe' ),
				'items' => array(
					'news' => array(
						'type'      => 'post_type',
						'title'     => __( 'Project Updates', 'bporg-breathe' ),
						'object'    => 'page',
						'object_id' => '{{updates}}',
					),
					'handbook' => array(
						'type'      => 'post_type',
						'title'     => __( 'Handbook', 'bporg-breathe' ),
						'object'    => 'handbook',
						'object_id' => '{{handbook}}',
					),
					'tickets' => array(
						'type'    => 'custom',
						'title'   => __( 'Tickets', 'bporg-breathe' ),
						'url'     => 'https://buddypress.trac.wordpress.org/report',
						'classes' => 'icon reports',
					),
					'source' => array(
						'type'    => 'custom',
						'title'   => __( 'Browse source', 'bporg-breathe' ),
						'url'     => 'https://buddypress.trac.wordpress.org/browser',
						'classes' => 'icon browser',
					),
					'issue' => array(
						'type'    => 'custom',
						'title'   => __( 'Report a bug', 'bporg-breathe' ),
						'url'     => 'https://buddypress.trac.wordpress.org/newticket',
						'classes' => 'icon report',
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
 * @param \WP_Customize_Manager $wp_customize The customizer object.
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

function scripts() {
	if ( function_exists( 'wporg_is_handbook' ) && wporg_is_handbook() ) {
		wp_enqueue_script(
			'bporg-breathe-chapters',
			get_stylesheet_directory_uri() . '/js/chapters.js',
			array( 'jquery' ),
			'20191005'
		);
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts', 11 );

function inline_scripts() {
	if ( is_multisite() ) {
		$current_site = get_site();
	} else {
		$current_site = (object) array(
			'domain' => str_replace( array( 'https://', 'http://' ), '', site_url() ),
			'path'   => '/',
		);
	}

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
					jQuery( '#contribute-welcome-toggle' ).text( '<?php esc_attr_e( 'Hide welcome box', 'bporg-breathe' ); ?>' );
				} else {
					document.cookie = el.dataset.cookie + '=' + el.dataset.hash +
						'; expires=Fri, 31 Dec 9999 23:59:59 GMT' +
						'; domain=<?php echo esc_js( $current_site->domain ); ?>' +
						'; path=<?php echo esc_js( $current_site->path ); ?>';
					jQuery( '#contribute-welcome-toggle' ).text( '<?php esc_attr_e( 'Show welcome box', 'bporg-breathe' ); ?>' );
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
		$label = __( 'Hide welcome box', 'bporg-breathe' );
	} else {
		$class = 'hidden';
		$label = __( 'Show welcome box', 'bporg-breathe' );
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
			<?php edit_post_link( __( 'Edit', 'bporg-breathe' ), '', '', $welcome->ID, 'post-edit-link ' . $class ); ?>
			<button type="button" id="contribute-welcome-toggle" data-hash="<?php echo $content_hash; ?>" data-cookie="<?php echo $cookie; ?>"><?php echo $label; ?></button>
		</div>
		<div class="entry-content clear <?php echo $class; ?>">
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
		<?php _e( 'Please enable JavaScript to view this page properly.', 'bporg-breathe' ); ?>
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
	$classes[] = 'bporg-make';

	if ( is_multisite() ) {
		$current_site = get_site( get_current_blog_id() );
		if ( $current_site ) {
			$classes[] = trim( $current_site->path, '/' );
		}
	}

	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\add_site_slug_to_body_class' );

/**
 * Defines `link_before` and `link_after` to make icon items accessible for screen readers.
 *
 * @param object  $args  An object of wp_nav_menu() arguments.
 * @param \WP_Post $item  Menu item data object.
 * @return object An object of wp_nav_menu() arguments.
 */
function add_screen_reader_text_for_icon_menu_items( $args, $item ) {
	if ( in_array( 'icon', $item->classes, true ) ) {
		$args->link_before = '<span class="screen-reader-text">';
		$args->link_after  = '</span>';
	}

	return $args;
}
add_filter( 'nav_menu_item_args', __NAMESPACE__ . '\add_screen_reader_text_for_icon_menu_items', 10, 2 );

/**
 * Disables Jetpack Mentions on any handbook page or comment.
 *
 * More precisely, this prevents the linked mentions from being shown. A more
 * involved approach (to include clearing meta-cached data) would be needed to
 * more efficiently prevent mentions from being looked for in the first place.
 *
 * @param string $linked  The linked mention.
 * @param string $mention The term being mentioned.
 * @return string
 */
function disable_mentions_for_handbook( $linked, $mention ) {
	if ( function_exists( 'wporg_is_handbook' ) && wporg_is_handbook() && ! is_single( 'credits' ) ) {
		return '@' . $mention;
	}

	return $linked;
}
add_filter( 'jetpack_mentions_linked_mention', __NAMESPACE__ . '\disable_mentions_for_handbook', 10, 2 );

/**
 * Include a new action to edit the post type within the Block Editor.
 *
 * @since 1.0.0
 *
 * @param array   $actions The registered o2 post actions.
 * @param integer $post_id The Post ID.
 * @return array           The registered post actions.
 */
function get_post_actions( $actions = array(), $post_id = 0 ) {
	if ( current_user_can( 'edit_post', $post_id ) ) {
		$actions[35] = array(
			'action' => 'block-edit',
			'href' => get_edit_post_link( $post_id ),
			'classes' => array( 'edit-post-link', 'o2-edit' ),
			'rel' => $post_id,
			'initialState' => 'default'
		);
	}

	return $actions;
}
add_filter( 'o2_filter_post_actions', __NAMESPACE__ . '\get_post_actions', 20, 2 );

/**
 * Registers a new post action state to allow the Block Edit action in o2.
 *
 * @since 1.0.0
 */
function register_post_action_states() {
	if ( ! function_exists( 'o2_register_post_action_states' ) ) {
		return;
	}

	o2_register_post_action_states( 'block-edit',
		array(
			'default' => array(
				'shortText' => __( 'Edit', 'o2' ),
				'title' => __( 'Edit', 'o2' ),
				'classes' => array( 'no-ajax' ),
				'genericon' => 'genericon-wordpress'
			),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_post_action_states' );

/**
 * Use a regular link instead of Ajax for the Block Edit post action.
 *
 * @since 1.0.0
 *
 * @param string  $html   HTML output for the post action.
 * @param array   $action The available post actions.
 * @return string         HTML output for the Block Edit post action.
 */
function post_action_html( $html = '', $action = array() ) {
	if ( 'block-edit'=== $action[ 'action' ] && ! empty( $html ) ) {
		$html = str_replace( array( 'o2-edit', ' data-action="block-edit"' ), '', $html );
	}

	return $html;
}
add_filter( 'o2_filter_post_action_html', __NAMESPACE__ . '\post_action_html', 15, 2 );

/**
 * Edit o2 options to allow posting from a category page.
 *
 * @since 1.0.1
 *
 * @param array $options The o2 UI options.
 * @return array         The o2 UI options.
 */
function filter_o2_options( $options = array() ) {
    if ( is_category() ) {
        $options['options']['showFrontSidePostBox'] = true;
    }

    return $options;
}
add_filter( 'o2_options', __NAMESPACE__ . '\filter_o2_options' );

/**
 * Assign the category displayed to the post inserted from the front-end.
 *
 * @since 1.0.1
 *
 * @param null|WP_Post The post before it is inserted.
 * @return null|WP_Post The post before it is inserted.
 */
function o2_create_post( $post = null ) {
    $url      = $_SERVER['HTTP_REFERER'];
    $home_url = home_url();

    if ( $url !== $home_url && ! is_null( $post ) ) {
        $parts = wp_parse_url( $url );

        $category_base = 'category';
        if ( $custom_base = get_option( 'category_base' ) ) {
            $category_base = $custom_base;
        }

        $category_part = str_replace( trailingslashit( $home_url ) . $category_base, '', $parts['scheme'] . '://' . $parts['host'] . $parts['path'] );
        $category_slug = explode( '/', trim( $category_part, '/' ) )[0];

        $category = get_category_by_slug( $category_slug );
        if ( isset( $category->term_id ) && $category->term_id ) {
            $post->post_category = array( $category->term_id );
        }
    }

    return $post;
}
add_filter( 'o2_create_post', __NAMESPACE__ . '\o2_create_post', 10, 1 );

