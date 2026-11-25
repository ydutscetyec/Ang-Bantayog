<?php
/**
 * Generic archive: date, post-type and fallback archives.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="archive" id="top">
  <div class="container">

    <header class="archive__head">
      <h1 class="archive__title"><?php the_archive_title(); ?></h1>
      <?php
      $ab_desc = get_the_archive_description();
      if ( $ab_desc ) : ?>
        <div class="archive__desc"><?php echo wp_kses_post( $ab_desc ); ?></div>
      <?php endif; ?>
    </header>

    <?php if ( have_posts() ) : ?>
      <?php
      $ab_is_doc  = is_post_type_archive( 'ab_document' );
      $ab_is_fact = is_post_type_archive( 'ab_factcheck' );
      ?>

      <?php if ( $ab_is_doc ) : ?>
        <ul class="doclist">
          <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card-document' ); endwhile; ?>
        </ul>
      <?php elseif ( $ab_is_fact ) : ?>
        <div class="grid grid--3">
          <?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/card-factcheck' ); endwhile; ?>
        </div>
      <?php else : ?>
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
      <?php endif; ?>

      <?php
      the_posts_pagination( array(
        'mid_size'  => 2,
        'prev_text' => '&larr; ' . esc_html__( 'Nakaraan', 'ang-bantayog' ),
        'next_text' => esc_html__( 'Susunod', 'ang-bantayog' ) . ' &rarr;',
      ) );
      ?>

    <?php else : ?>
      <p class="empty"><?php esc_html_e( 'Wala pang nilalaman sa seksyong ito.', 'ang-bantayog' ); ?></p>
    <?php endif; ?>

  </div>
</main>
<?php get_footer(); ?>
