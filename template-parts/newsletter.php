<?php
/**
 * Newsletter / subscribe call-out. Renders nothing unless a subscribe URL has
 * been set in the Customizer, so it never ships as a dead button.
 *
 * @package Ang_Bantayog
 */

$ab_url = get_theme_mod( 'ab_subscribe_url' );
if ( ! $ab_url ) return;

$ab_blurb = get_theme_mod( 'ab_newsletter_blurb', __( 'Makakuha ng pinakabagong balita ng Ang Bantayog diretso sa inyong inbox.', 'ang-bantayog' ) );
?>
<section class="newsletter" aria-label="<?php esc_attr_e( 'Subscribe', 'ang-bantayog' ); ?>">
  <div class="container newsletter__inner">
    <div>
      <h2 class="newsletter__title"><?php esc_html_e( 'Manatiling updated', 'ang-bantayog' ); ?></h2>
      <p class="newsletter__blurb"><?php echo esc_html( $ab_blurb ); ?></p>
    </div>
    <a class="btn btn--gold btn--lg" href="<?php echo esc_url( $ab_url ); ?>" target="_blank" rel="noopener">
      <?php esc_html_e( 'Subscribe', 'ang-bantayog' ); ?>
    </a>
  </div>
</section>
