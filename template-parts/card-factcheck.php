<?php
/**
 * Fact-check card — leads with the verdict, because that is the thing a reader
 * is scanning for.
 *
 * @package Ang_Bantayog
 */

$ab_id      = get_the_ID();
$ab_verdict = angbantayog_verdict_key( $ab_id );
$ab_label   = angbantayog_verdict_label( $ab_id );
$ab_claim   = get_post_meta( $ab_id, '_ab_claim', true );
?>
<article class="factcard factcard--<?php echo esc_attr( $ab_verdict ? $ab_verdict : 'wala' ); ?>">
  <?php if ( $ab_label ) : ?>
    <p class="factcard__verdict"><?php echo esc_html( $ab_label ); ?></p>
  <?php endif; ?>

  <h3 class="factcard__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

  <?php if ( $ab_claim ) : ?>
    <blockquote class="factcard__claim"><?php echo esc_html( wp_trim_words( $ab_claim, 24, '…' ) ); ?></blockquote>
  <?php endif; ?>

  <p class="card__meta">
    <span class="byline"><?php echo wp_kses_post( angbantayog_byline( $ab_id ) ); ?></span>
    <span class="dot" aria-hidden="true">&middot;</span>
    <time datetime="<?php echo esc_attr( get_the_date( 'c', $ab_id ) ); ?>">
      <?php echo esc_html( angbantayog_relative_time( $ab_id ) ); ?>
    </time>
  </p>
</article>
