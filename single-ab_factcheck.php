<?php
/**
 * Single fact check.
 *
 * Leads with a verdict panel holding the claim and its source, then the
 * explanation. The verdict is the headline finding, so it sits above the body.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="single" id="top">
  <div class="container single__wrap">

    <article class="article article--factcheck">
      <?php while ( have_posts() ) : the_post();
        $ab_verdict = angbantayog_verdict_key( get_the_ID() );
        $ab_label   = angbantayog_verdict_label( get_the_ID() );
        $ab_claim   = get_post_meta( get_the_ID(), '_ab_claim', true );
        $ab_source  = get_post_meta( get_the_ID(), '_ab_claim_source', true );
        ?>

        <header class="article__head">
          <p class="article__kicker">
            <a class="badge badge--solid" href="<?php echo esc_url( get_post_type_archive_link( 'ab_factcheck' ) ); ?>">
              <?php esc_html_e( 'FACT CHECK', 'ang-bantayog' ); ?>
            </a>
          </p>

          <h1 class="article__title"><?php the_title(); ?></h1>

          <div class="article__meta">
            <span class="byline"><?php echo wp_kses_post( angbantayog_byline( get_the_ID() ) ); ?></span>
            <span class="dot" aria-hidden="true">&middot;</span>
            <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <span class="dot" aria-hidden="true">&middot;</span>
            <?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?>
          </div>
        </header>

        <?php if ( $ab_claim || $ab_label ) : ?>
          <section class="verdict verdict--<?php echo esc_attr( $ab_verdict ? $ab_verdict : 'wala' ); ?>">
            <?php if ( $ab_claim ) : ?>
              <div class="verdict__claim">
                <p class="verdict__eyebrow"><?php esc_html_e( 'ANG PAHAYAG', 'ang-bantayog' ); ?></p>
                <blockquote><?php echo esc_html( $ab_claim ); ?></blockquote>
                <?php if ( $ab_source ) : ?>
                  <p class="verdict__source">
                    <?php
                    /* translators: %s is the origin of a claim. */
                    printf( esc_html__( 'Pinagmulan: %s', 'ang-bantayog' ), esc_html( $ab_source ) );
                    ?>
                  </p>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if ( $ab_label ) : ?>
              <div class="verdict__stamp">
                <p class="verdict__eyebrow"><?php esc_html_e( 'HATOL', 'ang-bantayog' ); ?></p>
                <p class="verdict__value"><?php echo esc_html( $ab_label ); ?></p>
              </div>
            <?php endif; ?>
          </section>
        <?php endif; ?>

        <?php get_template_part( 'template-parts/share-bar' ); ?>

        <div class="article__body">
          <?php the_content(); ?>
        </div>

        <?php $ab_topics = get_the_terms( get_the_ID(), 'ab_topic' ); ?>
        <?php if ( ! is_wp_error( $ab_topics ) && $ab_topics ) : ?>
          <div class="article__tags">
            <span class="chips__label"><?php esc_html_e( 'Mga paksa:', 'ang-bantayog' ); ?></span>
            <?php foreach ( $ab_topics as $ab_t ) : ?>
              <a class="chip" href="<?php echo esc_url( get_term_link( $ab_t ) ); ?>"><?php echo esc_html( $ab_t->name ); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <?php get_template_part( 'template-parts/author-box' ); ?>
        <?php get_template_part( 'template-parts/related' ); ?>
        <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>

      <?php endwhile; ?>
    </article>

    <?php get_sidebar(); ?>
  </div>
</main>
<?php get_footer(); ?>
