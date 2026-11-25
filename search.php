<?php
/**
 * Search results — mixes posts, fact checks and documents.
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
        /* translators: %s is the search term. */
        printf( esc_html__( 'Resulta para sa “%s”', 'ang-bantayog' ), esc_html( get_search_query() ) );
        ?>
      </h1>
      <p class="archive__desc">
        <?php
        printf(
          /* translators: %s is a number of results. */
          esc_html( _n( '%s resulta ang nahanap.', '%s na resulta ang nahanap.', (int) $wp_query->found_posts, 'ang-bantayog' ) ),
          esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
        );
        ?>
      </p>
      <div class="archive__search"><?php get_search_form(); ?></div>
    </header>

    <?php if ( have_posts() ) : ?>
      <div class="grid grid--3">
        <?php
        $ab_i = 0;
        while ( have_posts() ) :
          the_post();
          set_query_var( 'ab_card_index', $ab_i++ );
          if ( 'ab_document' === get_post_type() ) {
            echo '<ul class="doclist doclist--inline">';
            get_template_part( 'template-parts/card-document' );
            echo '</ul>';
          } elseif ( 'ab_factcheck' === get_post_type() ) {
            get_template_part( 'template-parts/card-factcheck' );
          } else {
            get_template_part( 'template-parts/card-article' );
          }
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
      <p class="empty"><?php esc_html_e( 'Walang tumugma sa paghahanap. Subukan ang ibang salita.', 'ang-bantayog' ); ?></p>
    <?php endif; ?>

  </div>
</main>
<?php get_footer(); ?>
