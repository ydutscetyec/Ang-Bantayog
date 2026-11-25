<?php
/**
 * Section heading with the InsightPH-style "Explore all →" link.
 *
 * Args: title (string), url (string, optional), id (string, optional anchor),
 *       sub (string, optional standfirst).
 *
 * @package Ang_Bantayog
 */

$ab_args  = wp_parse_args( $args ?? array(), array( 'title' => '', 'url' => '', 'id' => '', 'sub' => '' ) );
?>
<div class="sechead"<?php echo $ab_args['id'] ? ' id="' . esc_attr( $ab_args['id'] ) . '"' : ''; ?>>
  <div>
    <h2 class="sechead__title"><?php echo esc_html( $ab_args['title'] ); ?></h2>
    <?php if ( $ab_args['sub'] ) : ?>
      <p class="sechead__sub"><?php echo esc_html( $ab_args['sub'] ); ?></p>
    <?php endif; ?>
  </div>
  <?php if ( $ab_args['url'] ) : ?>
    <a class="sechead__more" href="<?php echo esc_url( $ab_args['url'] ); ?>">
      <?php
      /* translators: %s is a section name. */
      printf( esc_html__( 'Tingnan lahat ng %s', 'ang-bantayog' ), esc_html( $ab_args['title'] ) );
      ?> &rarr;
    </a>
  <?php endif; ?>
</div>
