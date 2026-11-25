<?php
/**
 * One-time setup on theme activation.
 *
 * Creates the sections, document types and standing pages so the site looks
 * like a publication the moment the theme is switched on, instead of an empty
 * shell the editor has to assemble by hand.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_after_switch_theme() {

	/* Sections */
	$sections = array(
		'balita'               => __( 'Balita', 'ang-bantayog' ),
		'opinyon'              => __( 'Opinyon', 'ang-bantayog' ),
		'lathalain'            => __( 'Lathalain', 'ang-bantayog' ),
		'isports'              => __( 'Isports', 'ang-bantayog' ),
		'multimedia'           => __( 'Multimedia', 'ang-bantayog' ),
		'agham-teknolohiya'    => __( 'Agham at Teknolohiya', 'ang-bantayog' ),
		'literatura'           => __( 'Literatura', 'ang-bantayog' ),
		'editorial'            => __( 'Editorial', 'ang-bantayog' ),
	);
	foreach ( $sections as $slug => $name ) {
		if ( ! get_category_by_slug( $slug ) ) {
			wp_insert_term( $name, 'category', array( 'slug' => $slug ) );
		}
	}

	/* Document types, mirroring a transparency page */
	$doc_types = array(
		'patakaran'    => __( 'Patakaran', 'ang-bantayog' ),
		'ulat'         => __( 'Ulat', 'ang-bantayog' ),
		'isyu-ng-pdf'  => __( 'Isyu ng Pahayagan (PDF)', 'ang-bantayog' ),
		'form'         => __( 'Mga Form', 'ang-bantayog' ),
	);
	foreach ( $doc_types as $slug => $name ) {
		if ( ! term_exists( $slug, 'ab_doc_type' ) ) {
			wp_insert_term( $name, 'ab_doc_type', array( 'slug' => $slug ) );
		}
	}

	/* Columns / series */
	$series = array(
		'bantayog-view' => __( 'BANTAYOG VIEW', 'ang-bantayog' ),
		'bantayog-info' => __( 'BANTAYOG INFO', 'ang-bantayog' ),
		'mga-buhay'     => __( 'MGA BUHAY NA MAY SAYSAY', 'ang-bantayog' ),
	);
	foreach ( $series as $slug => $name ) {
		if ( ! term_exists( $slug, 'ab_series' ) ) {
			wp_insert_term( $name, 'ab_series', array( 'slug' => $slug ) );
		}
	}

	/* Standing pages */
	$pages = array(
		'tungkol-sa-amin' => array(
			'title'    => __( 'Tungkol Sa Amin', 'ang-bantayog' ),
			'template' => 'page-templates/template-about.php',
			'content'  => '<p><strong>ANG BANTAYOG</strong> ang opisyal na pampaaralang pahayagan ng Libertad National High School. Layunin nitong maghatid ng balita, opinyon, lathalain, isports, at multimedia na malinaw, makabuluhan, at responsable.</p>',
		),
		'mga-artikulo' => array(
			'title'    => __( 'Mga Artikulo', 'ang-bantayog' ),
			'template' => 'page-templates/template-articles.php',
			'content'  => '',
		),
		'aklatan-ng-dokumento' => array(
			'title'    => __( 'Aklatan ng Dokumento', 'ang-bantayog' ),
			'template' => 'page-templates/template-documents.php',
			'content'  => '<p>Mga patakaran, ulat, at nakaraang isyu ng pahayagan na maaaring i-download.</p>',
		),
		'makipag-ugnayan' => array(
			'title'    => __( 'Makipag-ugnayan', 'ang-bantayog' ),
			'template' => 'page-templates/template-contact.php',
			'content'  => '<p>May balita, tip, o pagwawasto? Makipag-ugnayan sa patnugutan.</p>',
		),
	);

	foreach ( $pages as $slug => $conf ) {
		if ( get_page_by_path( $slug ) ) continue;
		wp_insert_post( array(
			'post_title'    => $conf['title'],
			'post_name'     => $slug,
			'post_content'  => $conf['content'],
			'post_status'   => 'publish',
			'post_type'     => 'page',
			'page_template' => $conf['template'],
		) );
	}

	/* CPT rewrite rules only exist after a flush. */
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'angbantayog_after_switch_theme' );
