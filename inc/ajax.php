<?php
/**
 * AJAX endpoints.
 *
 * Powers the "Load More" buttons on the InsiderPH-style timeline and on the
 * archive templates. Both are public reads, so the nonce is there to stop
 * drive-by hammering rather than to protect anything secret.
 *
 * @package Ang_Bantayog
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function angbantayog_ajax_load_more() {
	check_ajax_referer( 'ab_load_more', 'nonce' );

	$page    = isset( $_POST['page'] ) ? max( 1, absint( $_POST['page'] ) ) : 1;
	$per     = isset( $_POST['per_page'] ) ? min( 24, max( 1, absint( $_POST['per_page'] ) ) ) : 8;
	$mode    = isset( $_POST['mode'] ) ? sanitize_key( $_POST['mode'] ) : 'timeline';
	$cat     = isset( $_POST['category'] ) ? sanitize_title( $_POST['category'] ) : '';
	$exclude = array();

	if ( ! empty( $_POST['exclude'] ) ) {
		$raw = is_array( $_POST['exclude'] ) ? $_POST['exclude'] : explode( ',', $_POST['exclude'] );
		$exclude = array_filter( array_map( 'absint', $raw ) );
	}

	$args = array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => $per,
		'paged'               => $page,
		'post__not_in'        => $exclude,
		'ignore_sticky_posts' => true,
	);
	if ( $cat ) {
		$args['category_name'] = $cat;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		wp_send_json_success( array( 'html' => '', 'has_more' => false ) );
	}

	$part = ( 'timeline' === $mode ) ? 'template-parts/card-timeline' : 'template-parts/card-article';

	ob_start();
	$i = 0;
	while ( $query->have_posts() ) {
		$query->the_post();
		set_query_var( 'ab_card_index', $i++ );
		get_template_part( $part );
	}
	wp_reset_postdata();
	$html = ob_get_clean();

	wp_send_json_success( array(
		'html'     => $html,
		'has_more' => $page < (int) $query->max_num_pages,
	) );
}
add_action( 'wp_ajax_ab_load_more', 'angbantayog_ajax_load_more' );
add_action( 'wp_ajax_nopriv_ab_load_more', 'angbantayog_ajax_load_more' );
