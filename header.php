<?php
/**
 * Site header: accessibility toolbar, topbar, announcement ticker, masthead,
 * mega-menu navigation and the search panel.
 *
 * @package Ang_Bantayog
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> data-theme="light">
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="<?php echo esc_url( angbantayog_seal_url() ); ?>" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#content"><?php esc_html_e( 'Dumiretso sa nilalaman', 'ang-bantayog' ); ?></a>

<?php if ( is_singular( array( 'post', 'ab_factcheck' ) ) ) : ?>
  <div class="reading-progress" id="readingProgress" role="presentation"><span></span></div>
<?php endif; ?>

<header class="site-header">

  <?php /* ---------- Accessibility toolbar (DOST-style) ---------- */ ?>
  <div class="a11ybar">
    <div class="container a11ybar__inner">
      <div class="a11ybar__group" role="group" aria-label="<?php esc_attr_e( 'Sukat ng teksto', 'ang-bantayog' ); ?>">
        <span class="a11ybar__label"><?php esc_html_e( 'Teksto', 'ang-bantayog' ); ?></span>
        <button type="button" class="a11ybar__btn" data-textsize="sm" aria-label="<?php esc_attr_e( 'Maliit na teksto', 'ang-bantayog' ); ?>">A<sup>&minus;</sup></button>
        <button type="button" class="a11ybar__btn" data-textsize="md" aria-label="<?php esc_attr_e( 'Karaniwang teksto', 'ang-bantayog' ); ?>">A</button>
        <button type="button" class="a11ybar__btn" data-textsize="lg" aria-label="<?php esc_attr_e( 'Malaking teksto', 'ang-bantayog' ); ?>">A<sup>+</sup></button>
        <button type="button" class="a11ybar__btn" id="contrastToggle" aria-pressed="false"><?php esc_html_e( 'Mataas na kontrast', 'ang-bantayog' ); ?></button>
      </div>
      <?php angbantayog_utility_menu(); ?>
    </div>
  </div>

  <?php /* ---------- Topbar ---------- */ ?>
  <div class="topbar">
    <div class="container topbar__inner">
      <span class="topbar__name">
        <?php bloginfo( 'name' ); ?>
        <?php
        $line = get_theme_mod( 'ab_masthead_line', __( 'Opisyal na Pampaaralang Pahayagan ng Libertad National High School', 'ang-bantayog' ) );
        if ( $line ) { echo ' &mdash; ' . esc_html( $line ); }
        ?>
      </span>
      <time class="topbar__date" id="today"><?php echo esc_html( date_i18n( 'l, F j, Y' ) ); ?></time>
    </div>
  </div>

  <?php /* ---------- Announcement ticker (DOST-style) ---------- */ ?>
  <?php
  if ( angbantayog_show( 'ab_show_ticker' ) ) :
    $ticker = angbantayog_cpt_query( 'ab_announcement', 6 );
    if ( $ticker->have_posts() ) : ?>
      <div class="ticker" aria-label="<?php esc_attr_e( 'Mga anunsyo', 'ang-bantayog' ); ?>">
        <div class="container ticker__inner">
          <span class="ticker__tag"><?php esc_html_e( 'ANUNSYO', 'ang-bantayog' ); ?></span>
          <div class="ticker__viewport">
            <ul class="ticker__track" id="tickerTrack">
              <?php while ( $ticker->have_posts() ) : $ticker->the_post(); ?>
                <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
              <?php endwhile; ?>
            </ul>
          </div>
          <button type="button" class="ticker__pause" id="tickerPause" aria-pressed="false"
                  aria-label="<?php esc_attr_e( 'Ihinto ang anunsyo', 'ang-bantayog' ); ?>">&#10073;&#10073;</button>
        </div>
      </div>
    <?php endif;
    wp_reset_postdata();
  endif;
  ?>

  <?php /* ---------- Masthead ---------- */ ?>
  <section class="masthead" aria-label="<?php esc_attr_e( 'Masthead', 'ang-bantayog' ); ?>">
    <div class="masthead__wash" aria-hidden="true"></div>
    <div class="container masthead__inner">
      <a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
        <?php if ( has_custom_logo() ) : ?>
          <?php the_custom_logo(); ?>
        <?php else : ?>
          <img src="<?php echo esc_url( angbantayog_seal_url() ); ?>" alt="" class="brand__seal" width="96" height="96" />
        <?php endif; ?>
        <span class="brand__text">
          <span class="brand__kicker"><?php esc_html_e( 'ANG', 'ang-bantayog' ); ?></span>
          <strong class="brand__word"><?php esc_html_e( 'BANTAYOG', 'ang-bantayog' ); ?></strong>
          <small class="brand__tagline"><?php esc_html_e( 'Balitang may saysay. Bantayog ng katotohanan.', 'ang-bantayog' ); ?></small>
        </span>
      </a>

      <div class="masthead__actions">
        <?php $subscribe = get_theme_mod( 'ab_subscribe_url' ); ?>
        <?php if ( $subscribe ) : ?>
          <a class="btn btn--gold" href="<?php echo esc_url( $subscribe ); ?>" target="_blank" rel="noopener">
            <?php esc_html_e( 'Subscribe', 'ang-bantayog' ); ?>
          </a>
        <?php endif; ?>
        <?php foreach ( angbantayog_social_links() as $s ) : ?>
          <a class="social social--<?php echo esc_attr( $s['key'] ); ?>" href="<?php echo esc_url( $s['url'] ); ?>"
             target="_blank" rel="noopener me">
            <span class="screen-reader-text"><?php echo esc_html( $s['label'] ); ?></span>
            <span aria-hidden="true"><?php echo esc_html( strtoupper( substr( $s['label'], 0, 1 ) ) ); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php /* ---------- Navigation ---------- */ ?>
  <nav class="navbar" id="navbar" aria-label="<?php esc_attr_e( 'Pangunahing menu', 'ang-bantayog' ); ?>">
    <div class="container nav__inner">
      <button class="nav__toggle" id="navToggle" aria-expanded="false" aria-controls="mainMenu">
        <span class="nav__toggle-bars" aria-hidden="true"></span>
        <span class="screen-reader-text"><?php esc_html_e( 'Buksan ang menu', 'ang-bantayog' ); ?></span>
      </button>

      <?php angbantayog_primary_menu(); ?>

      <button class="nav__search-toggle" id="searchToggle" aria-expanded="false" aria-controls="searchPanel">
        <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
          <circle cx="10.5" cy="10.5" r="6.5" fill="none" stroke="currentColor" stroke-width="2" />
          <line x1="15.5" y1="15.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
        </svg>
        <span class="screen-reader-text"><?php esc_html_e( 'Maghanap', 'ang-bantayog' ); ?></span>
      </button>
    </div>

    <div class="nav__search-panel" id="searchPanel" hidden>
      <div class="container">
        <?php get_search_form(); ?>
        <?php
        $chips = get_terms( array( 'taxonomy' => 'ab_topic', 'number' => 8, 'orderby' => 'count', 'order' => 'DESC', 'hide_empty' => true ) );
        if ( ! is_wp_error( $chips ) && $chips ) : ?>
          <div class="chips chips--search">
            <span class="chips__label"><?php esc_html_e( 'Mabilis na paksa:', 'ang-bantayog' ); ?></span>
            <?php foreach ( $chips as $chip ) : ?>
              <a class="chip" href="<?php echo esc_url( get_term_link( $chip ) ); ?>"><?php echo esc_html( $chip->name ); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </nav>
</header>

<div id="content">
