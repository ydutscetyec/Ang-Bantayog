<?php
/**
 * Template tags — the small display helpers every template leans on.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/* =========================================================================
   Images
   ========================================================================= */

/** Themed placeholder so the site never shows a broken frame before photos exist. */
function angbantayog_fallback_thumb( $index = 0 ) {
	$n = ( abs( (int) $index ) % 6 ) + 1;
	return get_template_directory_uri() . '/assets/img/article-' . $n . '.svg';
}

function angbantayog_seal_url() {
	return get_template_directory_uri() . '/assets/img/seal.svg';
}

function angbantayog_cover_url() {
	$custom = get_theme_mod( 'ab_cover_image' );
	return $custom ? $custom : get_template_directory_uri() . '/assets/img/cover.svg';
}

/** Featured image if set, otherwise a placeholder keyed off the post ID. */
function angbantayog_post_thumb( $post_id, $size = 'angbantayog-featured', $fallback_index = 0, $alt = '' ) {
	$alt = $alt ? $alt : get_the_title( $post_id );
	if ( has_post_thumbnail( $post_id ) ) {
		echo get_the_post_thumbnail( $post_id, $size, array(
			'alt'     => esc_attr( $alt ),
			'loading' => 'lazy',
		) );
		return;
	}
	printf(
		'<img src="%s" alt="%s" loading="lazy" width="1100" height="620" />',
		esc_url( angbantayog_fallback_thumb( $fallback_index ? $fallback_index : $post_id ) ),
		esc_attr( $alt )
	);
}

/* =========================================================================
   Article metadata
   ========================================================================= */

/** "X min read", as on InsightPH and Rappler. */
function angbantayog_reading_time( $post_id ) {
	$content    = get_post_field( 'post_content', $post_id );
	$word_count = str_word_count( wp_strip_all_tags( strip_shortcodes( $content ) ) );
	$minutes    = max( 1, (int) ceil( $word_count / 200 ) );
	/* translators: %d is a number of minutes. */
	return sprintf( _n( '%d min read', '%d min read', $minutes, 'ang-bantayog' ), $minutes );
}

/**
 * Relative timestamp ("8 minuto ang nakalipas") for anything under a day old,
 * falling back to an absolute date after that. This is the InsiderPH timeline
 * behaviour; showing "3 months ago" on an old article is just noise.
 */
function angbantayog_relative_time( $post_id ) {
	$posted = get_post_time( 'U', true, $post_id );
	$diff   = time() - $posted;

	if ( $diff < 0 || $diff > DAY_IN_SECONDS ) {
		return get_the_date( '', $post_id );
	}
	if ( $diff < MINUTE_IN_SECONDS ) {
		return __( 'ngayon lang', 'ang-bantayog' );
	}
	/* translators: %s is a human-readable time difference, e.g. "8 minuto". */
	return sprintf( __( '%s ang nakalipas', 'ang-bantayog' ), human_time_diff( $posted, time() ) );
}

/** Primary category name, used for the card badge. */
function angbantayog_badge_label( $post_id ) {
	if ( 'ab_factcheck' === get_post_type( $post_id ) ) {
		return __( 'Fact Check', 'ang-bantayog' );
	}
	$cats = get_the_category( $post_id );
	if ( ! empty( $cats ) ) {
		return $cats[0]->name;
	}
	return __( 'Balita', 'ang-bantayog' );
}

/** Link for that badge. */
function angbantayog_badge_url( $post_id ) {
	if ( 'ab_factcheck' === get_post_type( $post_id ) ) {
		return get_post_type_archive_link( 'ab_factcheck' );
	}
	$cats = get_the_category( $post_id );
	if ( ! empty( $cats ) ) {
		return get_category_link( $cats[0] );
	}
	return home_url( '/' );
}

/** The column/series label, e.g. "BANTAYOG VIEW". */
function angbantayog_series_label( $post_id ) {
	$terms = get_the_terms( $post_id, 'ab_series' );
	if ( is_wp_error( $terms ) || empty( $terms ) ) return '';
	return $terms[0]->name;
}

/** Deck/standfirst, falling back to the excerpt. */
function angbantayog_deck( $post_id, $words = 28 ) {
	$deck = get_post_meta( $post_id, '_ab_deck', true );
	if ( $deck ) return $deck;
	$excerpt = get_the_excerpt( $post_id );
	return wp_trim_words( $excerpt, $words, '…' );
}

/** Author name plus the InsightPH-style affiliation line. */
function angbantayog_byline( $post_id, $linked = true ) {
	$author_id = (int) get_post_field( 'post_author', $post_id );
	$name      = get_the_author_meta( 'display_name', $author_id );
	$affil     = get_post_meta( $post_id, '_ab_byline_affiliation', true );

	$out = $linked
		? sprintf( '<a href="%s">%s</a>', esc_url( get_author_posts_url( $author_id ) ), esc_html( $name ) )
		: esc_html( $name );

	if ( $affil ) {
		$out .= ' <span class="byline__affil">' . esc_html( $affil ) . '</span>';
	}
	return $out;
}

function angbantayog_is_live( $post_id ) {
	return '1' === get_post_meta( $post_id, '_ab_live', true );
}

/* =========================================================================
   Fact check
   ========================================================================= */

function angbantayog_verdict_key( $post_id ) {
	return get_post_meta( $post_id, '_ab_verdict', true );
}

function angbantayog_verdict_label( $post_id ) {
	$key      = angbantayog_verdict_key( $post_id );
	$verdicts = angbantayog_verdicts();
	return isset( $verdicts[ $key ] ) ? $verdicts[ $key ] : '';
}

/* =========================================================================
   Queries
   Shared so the homepage, archives and AJAX handler cannot drift apart.
   ========================================================================= */

/** Latest posts, skipping anything already shown higher up the page. */
function angbantayog_feed( $count, $exclude = array(), $args = array() ) {
	return new WP_Query( wp_parse_args( $args, array(
		'posts_per_page'      => $count,
		'post__not_in'        => $exclude,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
	) ) );
}

function angbantayog_section_query( $slug, $count, $exclude = array() ) {
	return angbantayog_feed( $count, $exclude, array( 'category_name' => $slug ) );
}

/** Most-discussed posts; comment count is the only popularity signal core gives us. */
function angbantayog_popular( $count, $exclude = array() ) {
	return angbantayog_feed( $count, $exclude, array(
		'orderby' => array( 'comment_count' => 'DESC', 'date' => 'DESC' ),
	) );
}

function angbantayog_editors_picks( $count, $exclude = array() ) {
	return angbantayog_feed( $count, $exclude, array(
		'meta_key'   => '_ab_editors_pick',
		'meta_value' => '1',
	) );
}

function angbantayog_cpt_query( $type, $count, $args = array() ) {
	return new WP_Query( wp_parse_args( $args, array(
		'post_type'           => $type,
		'posts_per_page'      => $count,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
		'post_status'         => 'publish',
	) ) );
}

/**
 * Sections shown on the homepage, in order.
 * Only categories that actually exist and hold posts are returned, so a fresh
 * install does not render a column of empty headings.
 */
function angbantayog_home_sections() {
	$wanted = array( 'balita', 'opinyon', 'lathalain', 'isports', 'multimedia' );

	$extra = get_theme_mod( 'ab_extra_sections' );
	if ( $extra ) {
		foreach ( explode( ',', $extra ) as $slug ) {
			$slug = sanitize_title( trim( $slug ) );
			if ( $slug && ! in_array( $slug, $wanted, true ) ) {
				$wanted[] = $slug;
			}
		}
	}

	$sections = array();
	foreach ( $wanted as $slug ) {
		$term = get_category_by_slug( $slug );
		if ( $term && $term->count > 0 ) {
			$sections[] = $term;
		}
	}
	return $sections;
}

/** Resolve a section slug to its category archive, or a homepage anchor. */
function angbantayog_section_url( $slug ) {
	$term = get_category_by_slug( $slug );
	if ( $term ) {
		return get_category_link( $term );
	}
	return is_front_page() ? '#' . $slug : home_url( '/#' . $slug );
}

/* =========================================================================
   Navigation
   ========================================================================= */

function angbantayog_fallback_menu() {
	$links = array(
		array( 'label' => __( 'Home', 'ang-bantayog' ), 'url' => home_url( '/' ) ),
		array( 'label' => __( 'Balita', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'balita' ) ),
		array( 'label' => __( 'Opinyon', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'opinyon' ) ),
		array( 'label' => __( 'Lathalain', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'lathalain' ) ),
		array( 'label' => __( 'Isports', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'isports' ) ),
		array( 'label' => __( 'Multimedia', 'ang-bantayog' ), 'url' => angbantayog_section_url( 'multimedia' ) ),
		array( 'label' => __( 'Fact Check', 'ang-bantayog' ), 'url' => get_post_type_archive_link( 'ab_factcheck' ) ),
		array( 'label' => __( 'Mga Dokumento', 'ang-bantayog' ), 'url' => get_post_type_archive_link( 'ab_document' ) ),
	);

	$about = get_page_by_path( 'tungkol-sa-amin' );
	if ( ! $about ) $about = get_page_by_path( 'about' );
	$links[] = array(
		'label' => __( 'Tungkol Sa Amin', 'ang-bantayog' ),
		'url'   => $about ? get_permalink( $about ) : home_url( '/tungkol-sa-amin/' ),
	);

	echo '<ul id="mainMenu" class="nav__menu">';
	foreach ( $links as $link ) {
		if ( ! $link['url'] ) continue;
		$active = ( is_front_page() && $link['url'] === home_url( '/' ) ) ? ' class="active"' : '';
		printf( '<li><a%s href="%s">%s</a></li>', $active, esc_url( $link['url'] ), esc_html( $link['label'] ) );
	}
	echo '</ul>';
}

function angbantayog_primary_menu() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'container'      => false,
			'items_wrap'     => '<ul id="mainMenu" class="nav__menu">%3$s</ul>',
			'fallback_cb'    => 'angbantayog_fallback_menu',
			'depth'          => 2,
		) );
	} else {
		angbantayog_fallback_menu();
	}
}

/** Secondary (utility) menu in the top bar, if one is assigned. */
function angbantayog_utility_menu() {
	if ( ! has_nav_menu( 'utility' ) ) return;
	wp_nav_menu( array(
		'theme_location' => 'utility',
		'container'      => false,
		'items_wrap'     => '<ul class="topbar__menu">%3$s</ul>',
		'depth'          => 1,
	) );
}

/** Footer menu (legal/policies column), if one is assigned. */
function angbantayog_footer_menu() {
	if ( ! has_nav_menu( 'footer' ) ) return false;
	wp_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'items_wrap'     => '<ul class="footer__menu">%3$s</ul>',
		'depth'          => 1,
	) );
	return true;
}

/* =========================================================================
   Social links
   ========================================================================= */

/** Returns only the social links the editor has actually filled in. */
function angbantayog_social_links() {
	$fields = array(
		'facebook'  => __( 'Facebook', 'ang-bantayog' ),
		'instagram' => __( 'Instagram', 'ang-bantayog' ),
		'youtube'   => __( 'YouTube', 'ang-bantayog' ),
		'tiktok'    => __( 'TikTok', 'ang-bantayog' ),
		'x'         => __( 'X', 'ang-bantayog' ),
	);
	$out = array();
	foreach ( $fields as $key => $label ) {
		$url = get_theme_mod( 'ab_social_' . $key );
		if ( $url ) {
			$out[] = array( 'key' => $key, 'label' => $label, 'url' => $url );
		}
	}
	$email = get_theme_mod( 'ab_contact_email' );
	if ( $email ) {
		$out[] = array( 'key' => 'email', 'label' => __( 'Email', 'ang-bantayog' ), 'url' => 'mailto:' . $email );
	}
	return $out;
}
