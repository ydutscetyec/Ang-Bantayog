<?php
/**
 * Document row for the transparency / downloads library.
 *
 * @package Ang_Bantayog
 */

$ab_id   = get_the_ID();
$ab_file = get_post_meta( $ab_id, '_ab_doc_file', true );
$ab_ref  = get_post_meta( $ab_id, '_ab_doc_ref', true );
$ab_date = get_post_meta( $ab_id, '_ab_doc_date', true );
$ab_types = get_the_terms( $ab_id, 'ab_doc_type' );
$ab_ext  = $ab_file ? strtoupper( pathinfo( wp_parse_url( $ab_file, PHP_URL_PATH ), PATHINFO_EXTENSION ) ) : '';
?>
<li class="doc">
  <span class="doc__icon" aria-hidden="true"><?php echo esc_html( $ab_ext ? $ab_ext : 'DOC' ); ?></span>

  <div class="doc__body">
    <h3 class="doc__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="doc__meta">
      <?php if ( $ab_ref ) : ?>
        <span class="doc__ref"><?php echo esc_html( $ab_ref ); ?></span>
        <span class="dot" aria-hidden="true">&middot;</span>
      <?php endif; ?>
      <?php if ( $ab_date ) : ?>
        <time datetime="<?php echo esc_attr( $ab_date ); ?>">
          <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $ab_date ) ) ); ?>
        </time>
      <?php else : ?>
        <time datetime="<?php echo esc_attr( get_the_date( 'c', $ab_id ) ); ?>"><?php echo esc_html( get_the_date( '', $ab_id ) ); ?></time>
      <?php endif; ?>
      <?php if ( ! is_wp_error( $ab_types ) && $ab_types ) : ?>
        <span class="dot" aria-hidden="true">&middot;</span>
        <span class="doc__type"><?php echo esc_html( $ab_types[0]->name ); ?></span>
      <?php endif; ?>
    </p>
  </div>

  <?php if ( $ab_file ) : ?>
    <a class="btn btn--ghost doc__dl" href="<?php echo esc_url( $ab_file ); ?>" download>
      <?php esc_html_e( 'I-download', 'ang-bantayog' ); ?>
    </a>
  <?php endif; ?>
</li>
