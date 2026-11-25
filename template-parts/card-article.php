<?php
/**
 * Article card — thumbnail, section badge, series label, headline, deck,
 * byline with affiliation, relative time and reading time.
 *
 * Modelled on the InsightPH card, which carries the most metadata of the four
 * reference sites.
 *
 * @package Ang_Bantayog
 */

$ab_id    = get_the_ID();
$ab_index = (int) get_query_var( 'ab_card_index', 0 );
$ab_series = angbantayog_series_label( $ab_id );
?>
<article class="card" id="post-<?php echo esc_attr( $ab_id ); ?>">
  <a class="card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
    <?php angbantayog_post_thumb( $ab_id, 'angbantayog-card', $ab_index ); ?>
    <?php if ( angbantayog_is_live( $ab_id ) ) : ?>
      <span class="badge badge--live"><?php esc_html_e( 'LIVE', 'ang-bantayog' ); ?></span>
    <?php endif; ?>
  </a>

  <div class="card__body">
    <p class="card__kicker">
      <a class="badge" href="<?php echo esc_url( angbantayog_badge_url( $ab_id ) ); ?>">
        <?php echo esc_html( angbantayog_badge_label( $ab_id ) ); ?>
      </a>
      <?php if ( $ab_series ) : ?>
        <span class="series"><?php echo esc_html( $ab_series ); ?></span>
      <?php endif; ?>
    </p>

    <h3 class="card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

    <p class="card__deck"><?php echo esc_html( angbantayog_deck( $ab_id ) ); ?></p>

    <p class="card__meta">
      <span class="byline"><?php echo wp_kses_post( angbantayog_byline( $ab_id ) ); ?></span>
      <span class="dot" aria-hidden="true">&middot;</span>
      <time datetime="<?php echo esc_attr( get_the_date( 'c', $ab_id ) ); ?>">
        <?php echo esc_html( angbantayog_relative_time( $ab_id ) ); ?>
      </time>
      <span class="dot" aria-hidden="true">&middot;</span>
      <span class="readtime"><?php echo esc_html( angbantayog_reading_time( $ab_id ) ); ?></span>
    </p>
  </div>
</article>
