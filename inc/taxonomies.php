<?php
/**
 * Taxonomies.
 *
 *   ab_topic  - cross-section tags that power the InsiderPH "Trending" chip row
 *               and Rappler's topic hubs. Shared by posts and fact checks.
 *   ab_series - recurring columns/franchises, the equivalent of InsiderPH's
 *               INSIDER INFO / INSIDER VIEW labels and Rappler's franchises.
 *   ab_doc_type - categories for the DOST-style document library.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_register_taxonomies() {

	register_taxonomy( 'ab_topic', array( 'post', 'ab_factcheck' ), array(
		'labels' => array(
			'name'          => __( 'Mga Paksa', 'ang-bantayog' ),
			'singular_name' => __( 'Paksa', 'ang-bantayog' ),
			'menu_name'     => __( 'Mga Paksa', 'ang-bantayog' ),
		),
		'public'            => true,
		'hierarchical'      => false,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'paksa', 'with_front' => false ),
	) );

	register_taxonomy( 'ab_series', array( 'post', 'ab_factcheck' ), array(
		'labels' => array(
			'name'          => __( 'Mga Kolum / Serye', 'ang-bantayog' ),
			'singular_name' => __( 'Kolum', 'ang-bantayog' ),
			'menu_name'     => __( 'Mga Kolum', 'ang-bantayog' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'kolum', 'with_front' => false ),
	) );

	register_taxonomy( 'ab_doc_type', array( 'ab_document' ), array(
		'labels' => array(
			'name'          => __( 'Uri ng Dokumento', 'ang-bantayog' ),
			'singular_name' => __( 'Uri', 'ang-bantayog' ),
		),
		'public'            => true,
		'hierarchical'      => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'uri-ng-dokumento', 'with_front' => false ),
	) );
}
add_action( 'init', 'angbantayog_register_taxonomies' );
