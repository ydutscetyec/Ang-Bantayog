<?php
/**
 * Article sidebar. Renders a popular rail even when no widgets are set, so the
 * column is never a blank gutter.
 *
 * @package Ang_Bantayog
 */
?>
<aside class="sidebar">
  <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
    <?php dynamic_sidebar( 'sidebar-1' ); ?>
  <?php endif; ?>

  <?php
  $ab_popular = angbantayog_popular( 5, array( get_the_ID() ) );
  if ( $ab_popular->have_posts() ) : ?>
    <section class="panel">
      <?php get_template_part( 'template-parts/section-heading', null, array(
        'title' => __( 'Pinakatinatalakay', 'ang-bantayog' ),
      ) ); ?>
      <?php get_template_part( 'template-parts/ranked-list', null, array( 'query' => $ab_popular ) ); ?>
    </section>
  <?php endif; wp_reset_postdata(); ?>

  <?php
  $ab_terms = get_terms( array(
    'taxonomy' => 'ab_topic', 'number' => 10, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true,
  ) );
  if ( ! is_wp_error( $ab_terms ) && $ab_terms ) : ?>
    <section class="panel">
      <h3 class="widget__title"><?php esc_html_e( 'Mga Paksa', 'ang-bantayog' ); ?></h3>
      <div class="chips chips--stack">
        <?php foreach ( $ab_terms as $ab_t ) : ?>
          <a class="chip" href="<?php echo esc_url( get_term_link( $ab_t ) ); ?>"><?php echo esc_html( $ab_t->name ); ?></a>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</aside>
