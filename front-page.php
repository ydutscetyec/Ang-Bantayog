<?php
/**
 * Homepage.
 *
 * Block order, and where each one comes from:
 *
 *   1. Hero carousel ............ DOST rotating featured banner
 *   2. Cover + tagline .......... InsightPH hero cover
 *   3. Lede (featured + latest) .. InsiderPH "Featured From" / "Latest From"
 *   4. Trending chips ........... InsiderPH topic row
 *   5. Timeline ................. InsiderPH live feed with Load More
 *   6. Editors' Pick / Popular .. InsiderPH curated + most-read rails
 *   7. Fact Check ............... Rappler verdict cards
 *   8. Category sections ........ InsightPH stacked sections, "explore all"
 *   9. Document library ......... DOST transparency downloads
 *  10. Partners ................. DOST affiliate strip / InsightPH network block
 *
 * Every block is individually switchable in Appearance → Customize, and each
 * one self-hides when it has no content, so a fresh install is never a page of
 * empty headings.
 *
 * @package Ang_Bantayog
 */

get_header();

/* IDs already placed on the page, so nothing repeats further down. */
$ab_used = array();
?>

<main class="home" id="top">

  <?php /* ============================== 1. Hero carousel ============================== */ ?>
  <?php
  if ( angbantayog_show( 'ab_show_carousel' ) ) :
    $ab_carousel = new WP_Query( array(
      'posts_per_page'      => 4,
      'post_status'         => 'publish',
      'ignore_sticky_posts' => false,
      'no_found_rows'       => true,
      'meta_query'          => array(
        'relation' => 'OR',
        array( 'key' => '_ab_editors_pick', 'value' => '1' ),
        array( 'key' => '_ab_editors_pick', 'compare' => 'NOT EXISTS' ),
      ),
    ) );

    if ( $ab_carousel->have_posts() ) : ?>
      <section class="carousel" aria-label="<?php esc_attr_e( 'Mga pangunahing balita', 'ang-bantayog' ); ?>">
        <div class="carousel__viewport" id="heroCarousel">
          <?php
          $ab_slide = 0;
          while ( $ab_carousel->have_posts() ) :
            $ab_carousel->the_post();
            $ab_used[] = get_the_ID();
            ?>
            <article class="slide<?php echo 0 === $ab_slide ? ' is-active' : ''; ?>"
                     role="group" aria-roledescription="slide"
                     aria-label="<?php echo esc_attr( sprintf( '%d / %d', $ab_slide + 1, $ab_carousel->post_count ) ); ?>">
              <div class="slide__media"><?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', $ab_slide ); ?></div>
              <div class="slide__wash" aria-hidden="true"></div>
              <div class="container slide__body">
                <p class="slide__kicker">
                  <a class="badge badge--solid" href="<?php echo esc_url( angbantayog_badge_url( get_the_ID() ) ); ?>">
                    <?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?>
                  </a>
                  <?php if ( angbantayog_is_live( get_the_ID() ) ) : ?>
                    <span class="badge badge--live"><?php esc_html_e( 'LIVE', 'ang-bantayog' ); ?></span>
                  <?php endif; ?>
                </p>
                <h2 class="slide__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                <p class="slide__deck"><?php echo esc_html( angbantayog_deck( get_the_ID(), 34 ) ); ?></p>
                <p class="slide__meta">
                  <span class="byline"><?php echo wp_kses_post( angbantayog_byline( get_the_ID() ) ); ?></span>
                  <span class="dot" aria-hidden="true">&middot;</span>
                  <?php echo esc_html( angbantayog_relative_time( get_the_ID() ) ); ?>
                </p>
              </div>
            </article>
            <?php
            $ab_slide++;
          endwhile;
          ?>
        </div>

        <button class="carousel__nav carousel__nav--prev" data-carousel-prev
                aria-label="<?php esc_attr_e( 'Nakaraang balita', 'ang-bantayog' ); ?>">&#8249;</button>
        <button class="carousel__nav carousel__nav--next" data-carousel-next
                aria-label="<?php esc_attr_e( 'Susunod na balita', 'ang-bantayog' ); ?>">&#8250;</button>

        <div class="carousel__dots" role="tablist" aria-label="<?php esc_attr_e( 'Pumili ng slide', 'ang-bantayog' ); ?>">
          <?php for ( $i = 0; $i < $ab_slide; $i++ ) : ?>
            <button role="tab" class="carousel__dot<?php echo 0 === $i ? ' is-active' : ''; ?>"
                    data-carousel-dot="<?php echo esc_attr( $i ); ?>"
                    aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
                    aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d', 'ang-bantayog' ), $i + 1 ) ); ?>"></button>
          <?php endfor; ?>
        </div>
      </section>
    <?php endif;
    wp_reset_postdata();
  endif;
  ?>

  <?php /* ============================== 2. Cover + tagline ============================= */ ?>
  <?php if ( angbantayog_show( 'ab_show_cover' ) ) : ?>
    <section class="cover" aria-label="<?php esc_attr_e( 'Tungkol sa pahayagan', 'ang-bantayog' ); ?>">
      <img class="cover__bg" src="<?php echo esc_url( angbantayog_cover_url() ); ?>" alt="" loading="lazy" />
      <div class="cover__wash" aria-hidden="true"></div>
      <div class="container cover__inner">
        <h1 class="cover__title"><?php bloginfo( 'name' ); ?></h1>
        <p class="cover__tagline">
          <?php echo wp_kses_post( get_theme_mod( 'ab_tagline', __( 'Lampas sa headline, lampas sa ingay — kung saan nagtatagpo ang lalim, katotohanan, at matapang na pagkukuwento.', 'ang-bantayog' ) ) ); ?>
        </p>
        <?php $ab_socials = angbantayog_social_links(); ?>
        <?php if ( $ab_socials ) : ?>
          <div class="cover__social">
            <?php foreach ( $ab_socials as $ab_s ) : ?>
              <a href="<?php echo esc_url( $ab_s['url'] ); ?>" target="_blank" rel="noopener me">
                <?php echo esc_html( $ab_s['label'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <div class="container">

    <?php /* ========================= 3. Lede: featured + latest ====================== */ ?>
    <?php
    $ab_featured = angbantayog_feed( 1, $ab_used );
    if ( $ab_featured->have_posts() ) : ?>
      <section class="lede" aria-label="<?php esc_attr_e( 'Mga itinatampok', 'ang-bantayog' ); ?>">
        <div class="lede__main">
          <?php get_template_part( 'template-parts/section-heading', null, array(
            'title' => __( 'Itinatampok', 'ang-bantayog' ),
          ) ); ?>
          <?php
          while ( $ab_featured->have_posts() ) :
            $ab_featured->the_post();
            $ab_used[] = get_the_ID();
            ?>
            <article class="feature">
              <a class="feature__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                <?php angbantayog_post_thumb( get_the_ID(), 'angbantayog-featured', 1 ); ?>
              </a>
              <p class="card__kicker">
                <a class="badge" href="<?php echo esc_url( angbantayog_badge_url( get_the_ID() ) ); ?>">
                  <?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?>
                </a>
                <?php $ab_ser = angbantayog_series_label( get_the_ID() ); ?>
                <?php if ( $ab_ser ) : ?><span class="series"><?php echo esc_html( $ab_ser ); ?></span><?php endif; ?>
              </p>
              <h2 class="feature__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <p class="feature__deck"><?php echo esc_html( angbantayog_deck( get_the_ID(), 42 ) ); ?></p>
              <p class="card__meta">
                <span class="byline"><?php echo wp_kses_post( angbantayog_byline( get_the_ID() ) ); ?></span>
                <span class="dot" aria-hidden="true">&middot;</span>
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( angbantayog_relative_time( get_the_ID() ) ); ?></time>
                <span class="dot" aria-hidden="true">&middot;</span>
                <?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?>
              </p>
            </article>
          <?php endwhile; ?>
        </div>

        <aside class="lede__rail">
          <?php
          $ab_latest = angbantayog_feed( 5, $ab_used );
          if ( $ab_latest->have_posts() ) : ?>
            <?php get_template_part( 'template-parts/section-heading', null, array(
              'title' => __( 'Pinakabago', 'ang-bantayog' ),
              'url'   => get_page_by_path( 'mga-artikulo' ) ? get_permalink( get_page_by_path( 'mga-artikulo' ) ) : '',
            ) ); ?>
            <?php get_template_part( 'template-parts/ranked-list', null, array(
              'query'    => $ab_latest,
              'numbered' => false,
            ) ); ?>
            <?php
            // ranked-list resets postdata; collect the IDs it used.
            foreach ( $ab_latest->posts as $ab_p ) { $ab_used[] = $ab_p->ID; }
          endif;
          ?>
        </aside>
      </section>
    <?php endif;
    wp_reset_postdata();
    ?>

    <?php /* ============================ 4. Trending chips ============================ */ ?>
    <?php if ( angbantayog_show( 'ab_show_trending' ) ) : ?>
      <?php get_template_part( 'template-parts/chips' ); ?>
    <?php endif; ?>

    <?php /* ======================= 5. Timeline + 6. rails (two-up) ==================== */ ?>
    <div class="homegrid">

      <?php if ( angbantayog_show( 'ab_show_timeline' ) ) :
        $ab_tl_count = (int) get_theme_mod( 'ab_timeline_count', 8 );
        $ab_timeline = angbantayog_feed( $ab_tl_count, $ab_used );
        if ( $ab_timeline->have_posts() ) : ?>
          <section class="timeline" id="timeline" aria-label="<?php esc_attr_e( 'Timeline', 'ang-bantayog' ); ?>">
            <?php get_template_part( 'template-parts/section-heading', null, array(
              'title' => __( 'Ang Timeline', 'ang-bantayog' ),
              'sub'   => date_i18n( 'l, F j, Y' ),
            ) ); ?>

            <ul class="timeline__list" id="timelineList">
              <?php
              $ab_i = 0;
              while ( $ab_timeline->have_posts() ) :
                $ab_timeline->the_post();
                $ab_used[] = get_the_ID();
                set_query_var( 'ab_card_index', $ab_i++ );
                get_template_part( 'template-parts/card-timeline' );
              endwhile;
              ?>
            </ul>

            <button type="button" class="btn btn--ghost loadmore"
                    data-loadmore="timeline"
                    data-target="#timelineList"
                    data-per-page="<?php echo esc_attr( $ab_tl_count ); ?>"
                    data-page="1">
              <?php esc_html_e( 'Magkarga pa', 'ang-bantayog' ); ?>
            </button>
          </section>
        <?php endif;
        wp_reset_postdata();
      endif; ?>

      <aside class="homerail">
        <?php /* Editors' Pick */ ?>
        <?php if ( angbantayog_show( 'ab_show_picks' ) ) :
          $ab_picks = angbantayog_editors_picks( 5 );
          if ( $ab_picks->have_posts() ) : ?>
            <section class="panel">
              <?php get_template_part( 'template-parts/section-heading', null, array(
                'title' => __( "Editors' Pick", 'ang-bantayog' ),
              ) ); ?>
              <?php get_template_part( 'template-parts/ranked-list', null, array( 'query' => $ab_picks ) ); ?>
            </section>
          <?php endif;
          wp_reset_postdata();
        endif; ?>

        <?php /* Most read */ ?>
        <?php
        $ab_popular = angbantayog_popular( 5 );
        if ( $ab_popular->have_posts() ) : ?>
          <section class="panel">
            <?php get_template_part( 'template-parts/section-heading', null, array(
              'title' => __( 'Pinakatinatalakay', 'ang-bantayog' ),
            ) ); ?>
            <?php get_template_part( 'template-parts/ranked-list', null, array( 'query' => $ab_popular ) ); ?>
          </section>
        <?php endif;
        wp_reset_postdata();
        ?>
      </aside>
    </div>

    <?php /* ============================== 7. Fact Check ============================== */ ?>
    <?php if ( angbantayog_show( 'ab_show_factcheck' ) ) :
      $ab_facts = angbantayog_cpt_query( 'ab_factcheck', 3 );
      if ( $ab_facts->have_posts() ) : ?>
        <section class="factsec" aria-label="<?php esc_attr_e( 'Fact Check', 'ang-bantayog' ); ?>">
          <?php get_template_part( 'template-parts/section-heading', null, array(
            'title' => __( 'Fact Check', 'ang-bantayog' ),
            'sub'   => __( 'Sinusuri ang mga pahayag na kumakalat.', 'ang-bantayog' ),
            'url'   => get_post_type_archive_link( 'ab_factcheck' ),
          ) ); ?>
          <div class="grid grid--3">
            <?php while ( $ab_facts->have_posts() ) : $ab_facts->the_post();
              get_template_part( 'template-parts/card-factcheck' );
            endwhile; ?>
          </div>
        </section>
      <?php endif;
      wp_reset_postdata();
    endif; ?>

    <?php /* ========================== 8. Stacked category sections =================== */ ?>
    <?php foreach ( angbantayog_home_sections() as $ab_section ) :
      $ab_sq = angbantayog_section_query( $ab_section->slug, 3, $ab_used );
      if ( ! $ab_sq->have_posts() ) { wp_reset_postdata(); continue; }
      ?>
      <section class="homesection" id="<?php echo esc_attr( $ab_section->slug ); ?>">
        <?php get_template_part( 'template-parts/section-heading', null, array(
          'title' => $ab_section->name,
          'url'   => get_category_link( $ab_section ),
          'sub'   => $ab_section->description ? wp_strip_all_tags( $ab_section->description ) : '',
        ) ); ?>
        <div class="grid grid--3">
          <?php
          $ab_i = 0;
          while ( $ab_sq->have_posts() ) :
            $ab_sq->the_post();
            $ab_used[] = get_the_ID();
            set_query_var( 'ab_card_index', $ab_i++ );
            get_template_part( 'template-parts/card-article' );
          endwhile;
          ?>
        </div>
      </section>
      <?php wp_reset_postdata(); ?>
    <?php endforeach; ?>

    <?php /* ============================ 9. Document library =========================== */ ?>
    <?php if ( angbantayog_show( 'ab_show_documents' ) ) :
      $ab_docs = angbantayog_cpt_query( 'ab_document', 5, array(
        'meta_key' => '_ab_doc_date',
        'orderby'  => array( 'meta_value' => 'DESC', 'date' => 'DESC' ),
      ) );
      if ( ! $ab_docs->have_posts() ) {
        wp_reset_postdata();
        $ab_docs = angbantayog_cpt_query( 'ab_document', 5 );
      }
      if ( $ab_docs->have_posts() ) : ?>
        <section class="docsec" aria-label="<?php esc_attr_e( 'Mga dokumento', 'ang-bantayog' ); ?>">
          <?php
          $ab_doc_page = get_page_by_path( 'aklatan-ng-dokumento' );
          get_template_part( 'template-parts/section-heading', null, array(
            'title' => __( 'Aklatan ng Dokumento', 'ang-bantayog' ),
            'sub'   => __( 'Mga patakaran, ulat, at nakaraang isyu na maaaring i-download.', 'ang-bantayog' ),
            'url'   => $ab_doc_page ? get_permalink( $ab_doc_page ) : get_post_type_archive_link( 'ab_document' ),
          ) );
          ?>
          <ul class="doclist">
            <?php while ( $ab_docs->have_posts() ) : $ab_docs->the_post();
              get_template_part( 'template-parts/card-document' );
            endwhile; ?>
          </ul>
        </section>
      <?php endif;
      wp_reset_postdata();
    endif; ?>

    <?php /* ================================ 10. Partners ============================= */ ?>
    <?php if ( angbantayog_show( 'ab_show_partners' ) ) :
      $ab_partners = angbantayog_cpt_query( 'ab_partner', 12, array( 'orderby' => 'title', 'order' => 'ASC' ) );
      if ( $ab_partners->have_posts() ) : ?>
        <section class="partners" aria-label="<?php esc_attr_e( 'Mga kasosyo', 'ang-bantayog' ); ?>">
          <h2 class="partners__title"><?php esc_html_e( 'Mga Kasosyo at Kaalyado', 'ang-bantayog' ); ?></h2>
          <ul class="partners__strip">
            <?php while ( $ab_partners->have_posts() ) : $ab_partners->the_post();
              $ab_purl = get_post_meta( get_the_ID(), '_ab_partner_url', true );
              ?>
              <li>
                <?php if ( $ab_purl ) : ?><a href="<?php echo esc_url( $ab_purl ); ?>" target="_blank" rel="noopener"><?php endif; ?>
                  <?php if ( has_post_thumbnail() ) : ?>
                    <?php the_post_thumbnail( 'angbantayog-thumb', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
                  <?php else : ?>
                    <span class="partners__name"><?php the_title(); ?></span>
                  <?php endif; ?>
                <?php if ( $ab_purl ) : ?></a><?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
        </section>
      <?php endif;
      wp_reset_postdata();
    endif; ?>

  </div><!-- .container -->
</main>

<?php get_footer(); ?>
