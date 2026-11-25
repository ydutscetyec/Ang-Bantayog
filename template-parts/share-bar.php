<?php
/**
 * Share bar. Plain share URLs rather than vendor SDKs, so no third-party
 * script runs on the reader's page and nothing breaks when an SDK changes.
 *
 * @package Ang_Bantayog
 */

$ab_url   = get_permalink();
$ab_title = get_the_title();
$ab_enc_u = rawurlencode( $ab_url );
$ab_enc_t = rawurlencode( $ab_title );
?>
<div class="share" aria-label="<?php esc_attr_e( 'Ibahagi ang artikulo', 'ang-bantayog' ); ?>">
  <span class="share__label"><?php esc_html_e( 'Ibahagi', 'ang-bantayog' ); ?></span>

  <a class="share__btn" target="_blank" rel="noopener"
     href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $ab_enc_u ); ?>">
    <span aria-hidden="true">f</span>
    <span class="screen-reader-text"><?php esc_html_e( 'Ibahagi sa Facebook', 'ang-bantayog' ); ?></span>
  </a>

  <a class="share__btn" target="_blank" rel="noopener"
     href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $ab_enc_u ); ?>&amp;text=<?php echo esc_attr( $ab_enc_t ); ?>">
    <span aria-hidden="true">X</span>
    <span class="screen-reader-text"><?php esc_html_e( 'Ibahagi sa X', 'ang-bantayog' ); ?></span>
  </a>

  <a class="share__btn" target="_blank" rel="noopener"
     href="https://api.whatsapp.com/send?text=<?php echo esc_attr( $ab_enc_t . '%20' . $ab_enc_u ); ?>">
    <span aria-hidden="true">W</span>
    <span class="screen-reader-text"><?php esc_html_e( 'Ibahagi sa WhatsApp', 'ang-bantayog' ); ?></span>
  </a>

  <a class="share__btn"
     href="mailto:?subject=<?php echo esc_attr( $ab_enc_t ); ?>&amp;body=<?php echo esc_attr( $ab_enc_u ); ?>">
    <span aria-hidden="true">@</span>
    <span class="screen-reader-text"><?php esc_html_e( 'Ipadala sa email', 'ang-bantayog' ); ?></span>
  </a>

  <button type="button" class="share__btn share__copy" data-url="<?php echo esc_url( $ab_url ); ?>">
    <span aria-hidden="true">&#128279;</span>
    <span class="screen-reader-text"><?php esc_html_e( 'Kopyahin ang link', 'ang-bantayog' ); ?></span>
  </button>
</div>
