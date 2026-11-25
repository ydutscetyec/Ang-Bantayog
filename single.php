<?php
/**
 * Single article.
 *
 * Carries the Rappler-style reading furniture: progress bar (printed in the
 * header), deck, byline with affiliation, reading time, share bar, topic tags,
 * author box and related posts.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="single" id="top">
  <div class="container single__wrap">

    <article class="article">
      <?php while ( have_posts() ) : the_post(); ?>

        <header class="article__head">
          <p class="article__kicker">
            <a class="badge" href="<?php echo esc_url( angbantayog_badge_url( get_the_ID() ) ); ?>">
              <?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?>
            </a>
            <?php $ab_series = angbantayog_series_label( get_the_ID() ); ?>
            <?php if ( $ab_series ) : ?><span class="series"><?php echo esc_html( $ab_series ); ?></span><?php endif; ?>
            <?php if ( angbantayog_is_live( get_the_ID() ) ) : ?>
              <span class="badge badge--live"><?php esc_html_e( 'LIVE', 'ang-bantayog' ); ?></span>
            <?php endif; ?>
          </p>

          <h1 class="article__title"><?php the_title(); ?></h1>

          <?php $ab_deck = get_post_meta( get_the_ID(), '_ab_deck', true ); ?>
          <?php if ( $ab_deck ) : ?>
            <p class="article__deck"><?php echo esc_html( $ab_deck ); ?></p>
          <?php endif; ?>

          <div class="article__meta">
            <span class="byline"><?php echo wp_kses_post( angbantayog_byline( get_the_ID() ) ); ?></span>
            <span class="dot" aria-hidden="true">&middot;</span>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <span class="dot" aria-hidden="true">&middot;</span>
            <span class="readtime"><?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?></span>
            <?php if ( get_the_modified_date( 'U' ) > get_the_date( 'U' ) + HOUR_IN_SECONDS ) : ?>
              <span class="dot" aria-hidden="true">&middot;</span>
              <span class="article__updated">
                <?php
                /* translators: %s is a date. */
                printf( esc_html__( 'Na-update %s', 'ang-bantayog' ), esc_html( get_the_modified_date() ) );
                ?>
              </span>
            <?php endif; ?>
          </div>

          <?php get_template_part( 'template-parts/share-bar' ); ?>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
          <figure class="article__hero">
            <?php the_post_thumbnail( 'angbantayog-featured' ); ?>
            <?php $ab_caption = get_the_post_thumbnail_caption(); ?>
            <?php if ( $ab_caption ) : ?>
              <figcaption><?php echo esc_html( $ab_caption ); ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

        <div class="article__body">
          <?php the_content(); ?>
          <?php
          wp_link_pages( array(
            'before' => '<nav class="article__pages">' . esc_html__( 'Pahina:', 'ang-bantayog' ),
            'after'  => '</nav>',
          ) );
          ?>
        </div>

        <?php /* Topics and tags */ ?>
        <?php
        $ab_topics = get_the_terms( get_the_ID(), 'ab_topic' );
        $ab_tags   = get_the_tags();
        ?>
        <?php if ( ( ! is_wp_error( $ab_topics ) && $ab_topics ) || $ab_tags ) : ?>
          <div class="article__tags">
            <span class="chips__label"><?php esc_html_e( 'Mga paksa:', 'ang-bantayog' ); ?></span>
            <?php if ( ! is_wp_error( $ab_topics ) && $ab_topics ) : ?>
              <?php foreach ( $ab_topics as $ab_t ) : ?>
                <a class="chip" href="<?php echo esc_url( get_term_link( $ab_t ) ); ?>"><?php echo esc_html( $ab_t->name ); ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
            <?php if ( $ab_tags ) : ?>
              <?php foreach ( $ab_tags as $ab_t ) : ?>
                <a class="chip chip--tag" href="<?php echo esc_url( get_tag_link( $ab_t ) ); ?>">#<?php echo esc_html( $ab_t->name ); ?></a>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <?php get_template_part( 'template-parts/share-bar' ); ?>
        <?php get_template_part( 'template-parts/author-box' ); ?>

        <nav class="article__nav" aria-label="<?php esc_attr_e( 'Pag-navigate ng artikulo', 'ang-bantayog' ); ?>">
          <?php
          previous_post_link( '<span class="article__nav-prev">%link</span>', '&larr; %title' );
          next_post_link( '<span class="article__nav-next">%link</span>', '%title &rarr;' );
          ?>
        </nav>

        <?php get_template_part( 'template-parts/related' ); ?>

        <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>

      <?php endwhile; ?>
    </article>

    <?php get_sidebar(); ?>
  </div>
</main>
<?php get_footer(); ?>
