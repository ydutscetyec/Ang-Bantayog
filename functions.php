<?php
/**
 * Ang Bantayog — theme bootstrap.
 *
 * Version 3.0 turns the paper into a full publishing platform. The features
 * are drawn from four reference sites:
 *
 *   dost.gov.ph   - announcement ticker, document/transparency library,
 *                   accessibility toolbar, partner strip, hero carousel
 *   rappler.com   - fact checks with verdicts, mega menu, newsletter,
 *                   reading progress, share bar, author box, related posts
 *   insiderph.com - featured/latest split lede, live Timeline with relative
 *                   stamps and load-more, trending chips, Editors' Pick
 *   insightph     - cover + tagline hero, stacked category sections with
 *                   "explore all", cards with byline affiliation and
 *                   reading time, articles index
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ANGBANTAYOG_VERSION', '3.0.0' );

/* -------------------------------------------------------------------------
   Module loading
   ------------------------------------------------------------------------- */
require_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/cpt.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/widgets.php';
require_once get_template_directory() . '/inc/ajax.php';
require_once get_template_directory() . '/inc/starter-content.php';

/* -------------------------------------------------------------------------
   Theme setup
   ------------------------------------------------------------------------- */
function angbantayog_setup() {
	load_theme_textdomain( 'ang-bantayog', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script',
	) );
	add_theme_support( 'custom-logo', array(
		'height'      => 96,
		'width'       => 96,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	set_post_thumbnail_size( 800, 500, true );
	add_image_size( 'angbantayog-featured', 1100, 620, true );
	add_image_size( 'angbantayog-wide', 700, 500, true );
	add_image_size( 'angbantayog-card', 560, 360, true );
	add_image_size( 'angbantayog-thumb', 160, 120, true );

	register_nav_menus( array(
		'primary' => __( 'Pangunahing Menu (Primary)', 'ang-bantayog' ),
		'utility' => __( 'Utility Menu (topbar)', 'ang-bantayog' ),
		'footer'  => __( 'Footer / Legal Menu', 'ang-bantayog' ),
	) );
}
add_action( 'after_setup_theme', 'angbantayog_setup' );

function angbantayog_content_width() {
	$GLOBALS['content_width'] = 820;
}
add_action( 'after_setup_theme', 'angbantayog_content_width', 0 );

/* -------------------------------------------------------------------------
   Assets
   ------------------------------------------------------------------------- */
function angbantayog_scripts() {
	$dir = get_template_directory_uri();

	wp_enqueue_style( 'ang-bantayog-style', get_stylesheet_uri(), array(), ANGBANTAYOG_VERSION );
	wp_enqueue_style( 'ang-bantayog-publishing', $dir . '/assets/css/publishing-v3.css',
		array( 'ang-bantayog-style' ), ANGBANTAYOG_VERSION );

	// The audio player only matters on multimedia content, but it is small and
	// the original theme loaded it globally; keep that behaviour.
	if ( file_exists( get_template_directory() . '/audio-player.css' ) ) {
		wp_enqueue_style( 'ang-bantayog-audio', $dir . '/audio-player.css',
			array( 'ang-bantayog-style' ), ANGBANTAYOG_VERSION );
	}

	wp_enqueue_script( 'ang-bantayog-script', $dir . '/script.js', array(), ANGBANTAYOG_VERSION, true );
	wp_enqueue_script( 'ang-bantayog-publishing', $dir . '/assets/js/publishing-v3.js',
		array(), ANGBANTAYOG_VERSION, true );

	if ( file_exists( get_template_directory() . '/audio-player.js' ) ) {
		wp_enqueue_script( 'ang-bantayog-audio', $dir . '/audio-player.js', array(), ANGBANTAYOG_VERSION, true );
	}

	wp_localize_script( 'ang-bantayog-publishing', 'ABData', array(
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'ab_load_more' ),
		'i18n'    => array(
			'loading' => __( 'Kinakarga…', 'ang-bantayog' ),
			'more'    => __( 'Magkarga pa', 'ang-bantayog' ),
			'noMore'  => __( 'Wala nang iba pang artikulo.', 'ang-bantayog' ),
			'error'   => __( 'May problema sa pagkarga. Subukan muli.', 'ang-bantayog' ),
		),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'angbantayog_scripts' );

/**
 * Restore the reader's colour mode and text settings before first paint, so the
 * page does not flash the wrong theme.
 */
function angbantayog_boot_inline() {
	?>
	<script>
	(function () {
		try {
			var d = document.documentElement;
			var saved = localStorage.getItem('angbantayog-theme');
			var dark = saved ? saved === 'dark'
				: (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
			d.setAttribute('data-theme', dark ? 'dark' : 'light');

			var size = localStorage.getItem('angbantayog-textsize');
			if (size) d.setAttribute('data-textsize', size);
			if (localStorage.getItem('angbantayog-contrast') === 'on') d.setAttribute('data-contrast', 'on');
		} catch (e) {}
	})();
	</script>
	<?php
}
add_action( 'wp_head', 'angbantayog_boot_inline', 1 );

/* -------------------------------------------------------------------------
   Search
   ------------------------------------------------------------------------- */

/** Point the themed search box at real WordPress search. */
function angbantayog_search_form( $form ) {
	return '<form class="search" role="search" method="get" action="' . esc_url( home_url( '/' ) ) . '">
		<label class="screen-reader-text" for="ab-search-field">' . esc_html__( 'Maghanap', 'ang-bantayog' ) . '</label>
		<input id="ab-search-field" type="search" name="s" value="' . esc_attr( get_search_query() ) . '"
		       placeholder="' . esc_attr__( 'Maghanap ng artikulo…', 'ang-bantayog' ) . '" />
		<button type="submit" class="search__submit">' . esc_html__( 'Hanapin', 'ang-bantayog' ) . '</button>
	</form>';
}
add_filter( 'get_search_form', 'angbantayog_search_form' );

/** Let search reach fact checks and documents, not just posts and pages. */
function angbantayog_search_post_types( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) return;
	$query->set( 'post_type', array( 'post', 'page', 'ab_factcheck', 'ab_document' ) );
}
add_action( 'pre_get_posts', 'angbantayog_search_post_types' );

/* -------------------------------------------------------------------------
   Excerpts
   ------------------------------------------------------------------------- */
function angbantayog_excerpt_length( $length ) {
	return 32;
}
add_filter( 'excerpt_length', 'angbantayog_excerpt_length', 999 );

function angbantayog_excerpt_more( $more ) {
	return '…';
}
add_filter( 'excerpt_more', 'angbantayog_excerpt_more' );

/* -------------------------------------------------------------------------
   Body classes, so CSS can react to reader settings and page type
   ------------------------------------------------------------------------- */
function angbantayog_body_class( $classes ) {
	if ( is_singular( array( 'post', 'ab_factcheck' ) ) ) {
		$classes[] = 'has-reading-progress';
	}
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}
	return $classes;
}
add_filter( 'body_class', 'angbantayog_body_class' );
