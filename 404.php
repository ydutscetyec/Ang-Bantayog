<?php
/**
 * 404.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="archive" id="top">
  <div class="container error404">
    <p class="error404__code">404</p>
    <h1 class="archive__title"><?php esc_html_e( 'Hindi natagpuan ang pahina', 'ang-bantayog' ); ?></h1>
    <p><?php esc_html_e( 'Maaaring inilipat o tinanggal ang hinahanap mo.', 'ang-bantayog' ); ?></p>
    <div class="archive__search"><?php get_search_form(); ?></div>
    <p>
      <a class="btn btn--gold" href="<?php echo esc_url( home_url( '/' ) ); ?>">
        <?php esc_html_e( 'Bumalik sa homepage', 'ang-bantayog' ); ?>
      </a>
    </p>

    <?php
    $ab_recent = angbantayog_feed( 3 );
    if ( $ab_recent->have_posts() ) : ?>
      <section class="error404__recent">
        <?php get_template_part( 'template-parts/section-heading', null, array(
          'title' => __( 'Pinakabago', 'ang-bantayog' ),
        ) ); ?>
        <div class="grid grid--3">
          <?php
          $ab_i = 0;
          while ( $ab_recent->have_posts() ) :
            $ab_recent->the_post();
            set_query_var( 'ab_card_index', $ab_i++ );
            get_template_part( 'template-parts/card-article' );
          endwhile;
          ?>
        </div>
      </section>
    <?php endif; wp_reset_postdata(); ?>
  </div>
</main>
<?php get_footer(); ?>
