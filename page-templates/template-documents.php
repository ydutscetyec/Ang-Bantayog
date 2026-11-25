<?php
/**
 * Template Name: Aklatan ng Dokumento (Document Library)
 *
 * The DOST-style transparency / downloads page: documents grouped by type,
 * each with a reference number, date and download link.
 *
 * @package Ang_Bantayog
 */

get_header();

$ab_types = get_terms( array( 'taxonomy' => 'ab_doc_type', 'hide_empty' => true ) );
?>
<main class="archive" id="top">
  <div class="container">

    <header class="archive__head">
      <h1 class="archive__title"><?php the_title(); ?></h1>
      <?php if ( get_the_content() ) : ?>
        <div class="archive__desc"><?php the_content(); ?></div>
      <?php endif; ?>
    </header>

    <?php if ( ! is_wp_error( $ab_types ) && $ab_types ) : ?>

      <?php foreach ( $ab_types as $ab_type ) :
        $ab_q = new WP_Query( array(
          'post_type'      => 'ab_document',
          'post_status'    => 'publish',
          'posts_per_page' => 20,
          'no_found_rows'  => true,
          'tax_query'      => array(
            array( 'taxonomy' => 'ab_doc_type', 'field' => 'term_id', 'terms' => $ab_type->term_id ),
          ),
        ) );
        if ( ! $ab_q->have_posts() ) { wp_reset_postdata(); continue; }
        ?>
        <section class="docsec">
          <?php get_template_part( 'template-parts/section-heading', null, array(
            'title' => $ab_type->name,
            'sub'   => $ab_type->description ? wp_strip_all_tags( $ab_type->description ) : '',
          ) ); ?>
          <ul class="doclist">
            <?php while ( $ab_q->have_posts() ) : $ab_q->the_post();
              get_template_part( 'template-parts/card-document' );
            endwhile; ?>
          </ul>
        </section>
        <?php wp_reset_postdata(); ?>
      <?php endforeach; ?>

      <?php /* Anything with no type assigned */
      $ab_untyped = new WP_Query( array(
        'post_type'      => 'ab_document',
        'post_status'    => 'publish',
        'posts_per_page' => 20,
        'no_found_rows'  => true,
        'tax_query'      => array(
          array( 'taxonomy' => 'ab_doc_type', 'operator' => 'NOT EXISTS' ),
        ),
      ) );
      if ( $ab_untyped->have_posts() ) : ?>
        <section class="docsec">
          <?php get_template_part( 'template-parts/section-heading', null, array(
            'title' => __( 'Iba pang Dokumento', 'ang-bantayog' ),
          ) ); ?>
          <ul class="doclist">
            <?php while ( $ab_untyped->have_posts() ) : $ab_untyped->the_post();
              get_template_part( 'template-parts/card-document' );
            endwhile; ?>
          </ul>
        </section>
      <?php endif; wp_reset_postdata(); ?>

    <?php else :
      $ab_all = angbantayog_cpt_query( 'ab_document', 40 );
      if ( $ab_all->have_posts() ) : ?>
        <ul class="doclist">
          <?php while ( $ab_all->have_posts() ) : $ab_all->the_post();
            get_template_part( 'template-parts/card-document' );
          endwhile; ?>
        </ul>
      <?php else : ?>
        <p class="empty"><?php esc_html_e( 'Wala pang nai-upload na dokumento.', 'ang-bantayog' ); ?></p>
      <?php endif; wp_reset_postdata(); ?>
    <?php endif; ?>

  </div>
</main>
<?php get_footer(); ?>
