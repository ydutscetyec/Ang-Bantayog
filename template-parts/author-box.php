<?php
/**
 * Author box below an article.
 *
 * @package Ang_Bantayog
 */

$ab_author = (int) get_post_field( 'post_author', get_the_ID() );
$ab_bio    = get_the_author_meta( 'description', $ab_author );
$ab_affil  = get_post_meta( get_the_ID(), '_ab_byline_affiliation', true );
?>
<aside class="authorbox">
  <?php echo get_avatar( $ab_author, 72, '', '', array( 'class' => 'authorbox__avatar' ) ); ?>
  <div>
    <p class="authorbox__eyebrow"><?php esc_html_e( 'Isinulat ni', 'ang-bantayog' ); ?></p>
    <h3 class="authorbox__name">
      <a href="<?php echo esc_url( get_author_posts_url( $ab_author ) ); ?>">
        <?php echo esc_html( get_the_author_meta( 'display_name', $ab_author ) ); ?>
      </a>
    </h3>
    <?php if ( $ab_affil ) : ?>
      <p class="authorbox__affil"><?php echo esc_html( $ab_affil ); ?></p>
    <?php endif; ?>
    <?php if ( $ab_bio ) : ?>
      <p class="authorbox__bio"><?php echo esc_html( $ab_bio ); ?></p>
    <?php endif; ?>
    <a class="authorbox__more" href="<?php echo esc_url( get_author_posts_url( $ab_author ) ); ?>">
      <?php esc_html_e( 'Lahat ng artikulo ng awtor', 'ang-bantayog' ); ?> &rarr;
    </a>
  </div>
</aside>
