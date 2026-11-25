<?php
/**
 * Timeline row — the InsiderPH live feed item: a relative stamp, the section,
 * and the headline, with no image so the feed stays dense and scannable.
 *
 * @package Ang_Bantayog
 */

$ab_id = get_the_ID();
?>
<li class="timeline__item<?php echo angbantayog_is_live( $ab_id ) ? ' is-live' : ''; ?>">
  <time class="timeline__time" datetime="<?php echo esc_attr( get_the_date( 'c', $ab_id ) ); ?>">
    <?php echo esc_html( angbantayog_relative_time( $ab_id ) ); ?>
  </time>
  <div class="timeline__body">
    <a class="timeline__badge" href="<?php echo esc_url( angbantayog_badge_url( $ab_id ) ); ?>">
      <?php echo esc_html( angbantayog_badge_label( $ab_id ) ); ?>
    </a>
    <h3 class="timeline__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <?php if ( angbantayog_is_live( $ab_id ) ) : ?>
      <span class="badge badge--live badge--sm"><?php esc_html_e( 'LIVE', 'ang-bantayog' ); ?></span>
    <?php endif; ?>
  </div>
</li>
