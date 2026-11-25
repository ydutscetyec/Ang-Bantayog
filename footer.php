<?php
/**
 * Site footer: newsletter call-out, four-column navigation, socials, legal row.
 *
 * @package Ang_Bantayog
 */
?>
  <?php get_template_part( 'template-parts/newsletter' ); ?>

  <footer class="footer">
    <div class="container footer__grid">

      <div class="footer__brand">
        <img src="<?php echo esc_url( angbantayog_seal_url() ); ?>" alt="" class="footer__seal" width="72" height="72" />
        <h2><?php bloginfo( 'name' ); ?></h2>
        <p>
          <?php
          $desc = get_bloginfo( 'description', 'display' );
          echo esc_html( $desc ? $desc : __( 'Opisyal na Pampaaralang Pahayagan ng Libertad National High School.', 'ang-bantayog' ) );
          ?>
        </p>
        <?php $socials = angbantayog_social_links(); ?>
        <?php if ( $socials ) : ?>
          <div class="footer__social">
            <?php foreach ( $socials as $s ) : ?>
              <a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener me">
                <span class="screen-reader-text"><?php echo esc_html( $s['label'] ); ?></span>
                <span aria-hidden="true"><?php echo esc_html( strtoupper( substr( $s['label'], 0, 2 ) ) ); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="footer__col">
        <h3><?php esc_html_e( 'Mga Seksyon', 'ang-bantayog' ); ?></h3>
        <?php
        $foot_sections = array( 'balita', 'opinyon', 'lathalain', 'isports', 'multimedia' );
        foreach ( $foot_sections as $slug ) {
          $term = get_category_by_slug( $slug );
          if ( ! $term ) continue;
          printf( '<a href="%s">%s</a>', esc_url( get_category_link( $term ) ), esc_html( $term->name ) );
        }
        ?>
      </div>

      <div class="footer__col">
        <h3><?php esc_html_e( 'Patnugutan', 'ang-bantayog' ); ?></h3>
        <?php
        $foot_pages = array(
          'tungkol-sa-amin'      => __( 'Tungkol Sa Amin', 'ang-bantayog' ),
          'makipag-ugnayan'      => __( 'Makipag-ugnayan', 'ang-bantayog' ),
          'aklatan-ng-dokumento' => __( 'Aklatan ng Dokumento', 'ang-bantayog' ),
          'mga-artikulo'         => __( 'Lahat ng Artikulo', 'ang-bantayog' ),
        );
        foreach ( $foot_pages as $slug => $label ) {
          $page = get_page_by_path( $slug );
          if ( ! $page ) continue;
          printf( '<a href="%s">%s</a>', esc_url( get_permalink( $page ) ), esc_html( $label ) );
        }
        $fc = get_post_type_archive_link( 'ab_factcheck' );
        if ( $fc ) printf( '<a href="%s">%s</a>', esc_url( $fc ), esc_html__( 'Fact Check', 'ang-bantayog' ) );
        ?>
      </div>

      <div class="footer__col">
        <h3><?php esc_html_e( 'Iba pa', 'ang-bantayog' ); ?></h3>
        <?php if ( ! angbantayog_footer_menu() ) : ?>
          <a href="<?php echo esc_url( home_url( '/?s=' ) ); ?>"><?php esc_html_e( 'Maghanap', 'ang-bantayog' ); ?></a>
          <a href="<?php echo esc_url( get_feed_link() ); ?>"><?php esc_html_e( 'RSS Feed', 'ang-bantayog' ); ?></a>
        <?php endif; ?>
        <?php if ( is_active_sidebar( 'footer-1' ) ) dynamic_sidebar( 'footer-1' ); ?>
        <?php if ( is_active_sidebar( 'footer-2' ) ) dynamic_sidebar( 'footer-2' ); ?>
      </div>
    </div>

    <div class="footer__bottom container">
      <span>
        &copy; <span id="year"><?php echo esc_html( date_i18n( 'Y' ) ); ?></span>
        <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Lahat ng karapatan ay nakalaan.', 'ang-bantayog' ); ?>
      </span>
      <a class="footer__totop" href="#top"><?php esc_html_e( 'Bumalik sa itaas', 'ang-bantayog' ); ?> &uarr;</a>
    </div>
  </footer>
</div><!-- #content -->

<button id="themeToggle" class="theme-toggle" aria-pressed="false" title="<?php esc_attr_e( 'Lumipat sa dark mode', 'ang-bantayog' ); ?>">
  <span class="theme-toggle__knob" aria-hidden="true">&#9728;</span>
  <span class="screen-reader-text"><?php esc_html_e( 'Palitan ang light o dark mode', 'ang-bantayog' ); ?></span>
</button>

<button id="backToTop" class="back-to-top" aria-label="<?php esc_attr_e( 'Bumalik sa itaas', 'ang-bantayog' ); ?>">&uarr;</button>

<?php wp_footer(); ?>
</body>
</html>
