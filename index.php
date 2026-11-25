<?php
/**
 * Fallback index — used for the blog posts page and anything without a more
 * specific template.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="archive" id="top">
  <div class="container">
    <header class="archive__head">
      <h1 class="archive__title">
        <?php
        if ( is_home() && ! is_front_page() ) {
          single_post_title();
        } else {
          esc_html_e( 'Pinakabagong Balita', 'ang-bantayog' );
        }
        ?>
      </h1>
    </header>

    <?php if ( have_posts() ) : ?>
      <div class="grid grid--3">
        <?php
        $ab_i = 0;
        while ( have_posts() ) :
          the_post();
          set_query_var( 'ab_card_index', $ab_i++ );
          get_template_part( 'template-parts/card-article' );
        endwhile;
        ?>
      </div>
      <?php
      the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => '&larr; ' . esc_html__( 'Nakaraan', 'ang-bantayog' ),
        'next_text' => esc_html__( 'Susunod', 'ang-bantayog' ) . ' &rarr;',
      ) );
      ?>
    <?php else : ?>
      <p class="empty"><?php esc_html_e( 'Wala pang naipublikong artikulo.', 'ang-bantayog' ); ?></p>
    <?php endif; ?>
  </div>
</main>
<?php get_footer(); ?>
