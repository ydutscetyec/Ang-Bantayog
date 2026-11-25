<?php
/**
 * Widget areas.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_widgets_init() {
	$areas = array(
		'sidebar-1' => array(
			__( 'Sidebar ng Artikulo', 'ang-bantayog' ),
			__( 'Lalabas sa tabi ng mga artikulo at archive.', 'ang-bantayog' ),
		),
		'footer-1'  => array(
			__( 'Footer 1', 'ang-bantayog' ),
			__( 'Unang kolum sa footer.', 'ang-bantayog' ),
		),
		'footer-2'  => array(
			__( 'Footer 2', 'ang-bantayog' ),
			__( 'Pangalawang kolum sa footer.', 'ang-bantayog' ),
		),
	);

	foreach ( $areas as $id => $conf ) {
		register_sidebar( array(
			'name'          => $conf[0],
			'id'            => $id,
			'description'   => $conf[1],
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget__title">',
			'after_title'   => '</h3>',
		) );
	}
}
add_action( 'widgets_init', 'angbantayog_widgets_init' );
