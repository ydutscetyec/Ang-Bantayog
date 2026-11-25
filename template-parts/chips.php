<?php
/**
 * Trending topic chips — the InsiderPH tag row.
 *
 * @package Ang_Bantayog
 */

$ab_terms = get_terms( array(
	'taxonomy'   => 'ab_topic',
	'number'     => 12,
	'orderby'    => 'count',
	'order'      => 'DESC',
	'hide_empty' => true,
) );
if ( is_wp_error( $ab_terms ) || ! $ab_terms ) return;
?>
<div class="chips">
  <span class="chips__label"><?php esc_html_e( 'Trending:', 'ang-bantayog' ); ?></span>
  <?php foreach ( $ab_terms as $ab_term ) : ?>
    <a class="chip" href="<?php echo esc_url( get_term_link( $ab_term ) ); ?>">
      <?php echo esc_html( $ab_term->name ); ?>
      <span class="chip__count"><?php echo esc_html( number_format_i18n( $ab_term->count ) ); ?></span>
    </a>
  <?php endforeach; ?>
</div>
