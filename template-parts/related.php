<?php
/**
 * Related posts — the InsiderPH "topic cluster" idea. Matches on the shared
 * topic taxonomy first and falls back to the category, because topics are the
 * tighter signal when they have been used.
 *
 * @package Ang_Bantayog
 */

$ab_id     = get_the_ID();
$ab_topics = wp_get_post_terms( $ab_id, 'ab_topic', array( 'fields' => 'ids' ) );

$ab_args = array(
	'post_type'           => get_post_type( $ab_id ),
	'posts_per_page'      => 3,
	'post__not_in'        => array( $ab_id ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
);

if ( ! is_wp_error( $ab_topics ) && $ab_topics ) {
	$ab_args['tax_query'] = array(
		array( 'taxonomy' => 'ab_topic', 'field' => 'term_id', 'terms' => $ab_topics ),
	);
} else {
	$ab_cats = wp_get_post_categories( $ab_id );
	if ( $ab_cats ) {
		$ab_args['category__in'] = $ab_cats;
	}
}

$ab_related = new WP_Query( $ab_args );
if ( ! $ab_related->have_posts() ) { wp_reset_postdata(); return; }
?>
<section class="related" aria-label="<?php esc_attr_e( 'Kaugnay na artikulo', 'ang-bantayog' ); ?>">
  <?php get_template_part( 'template-parts/section-heading', null, array(
    'title' => __( 'Kaugnay na Artikulo', 'ang-bantayog' ),
  ) ); ?>

  <div class="grid grid--3">
    <?php
    $ab_i = 0;
    while ( $ab_related->have_posts() ) :
      $ab_related->the_post();
      set_query_var( 'ab_card_index', $ab_i++ );
      get_template_part( 'template-parts/card-article' );
    endwhile;
    ?>
  </div>
</section>
<?php wp_reset_postdata(); ?>
