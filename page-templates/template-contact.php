<?php
/**
 * Template Name: Makipag-ugnayan (Contact)
 *
 * The InsiderPH contact cluster: newsroom address, tip line, correction
 * request and contribution routes. Each card points at the newsroom email with
 * a pre-filled subject, so no form plugin is needed.
 *
 * @package Ang_Bantayog
 */

get_header();

$ab_email = get_theme_mod( 'ab_contact_email' );
$ab_routes = array(
	array(
		'title' => __( 'Magpadala ng News Tip', 'ang-bantayog' ),
		'desc'  => __( 'May nalalaman kang balita? Ipaalam sa patnugutan.', 'ang-bantayog' ),
		'subj'  => __( 'News Tip', 'ang-bantayog' ),
	),
	array(
		'title' => __( 'Humiling ng Pagwawasto', 'ang-bantayog' ),
		'desc'  => __( 'May mali sa aming naipublika? Itutuwid namin ito.', 'ang-bantayog' ),
		'subj'  => __( 'Kahilingan sa Pagwawasto', 'ang-bantayog' ),
	),
	array(
		'title' => __( 'Mag-ambag ng Artikulo', 'ang-bantayog' ),
		'desc'  => __( 'Para sa mga mag-aaral na nais magsulat para sa Ang Bantayog.', 'ang-bantayog' ),
		'subj'  => __( 'Pag-ambag ng Artikulo', 'ang-bantayog' ),
	),
	array(
		'title' => __( 'Pangkalahatang Katanungan', 'ang-bantayog' ),
		'desc'  => __( 'Iba pang usapin, pakikipagtulungan, o puna.', 'ang-bantayog' ),
		'subj'  => __( 'Katanungan', 'ang-bantayog' ),
	),
);
?>
<main class="single" id="top">
  <div class="container">
    <article class="article article--wide">

      <header class="article__head">
        <h1 class="article__title"><?php the_title(); ?></h1>
      </header>

      <?php while ( have_posts() ) : the_post(); ?>
        <div class="article__body"><?php the_content(); ?></div>
      <?php endwhile; ?>

      <div class="grid grid--2 contactgrid">
        <?php foreach ( $ab_routes as $ab_route ) : ?>
          <section class="contactcard">
            <h2 class="contactcard__title"><?php echo esc_html( $ab_route['title'] ); ?></h2>
            <p class="contactcard__desc"><?php echo esc_html( $ab_route['desc'] ); ?></p>
            <?php if ( $ab_email ) : ?>
              <a class="btn btn--ghost"
                 href="mailto:<?php echo esc_attr( $ab_email ); ?>?subject=<?php echo esc_attr( rawurlencode( $ab_route['subj'] ) ); ?>">
                <?php esc_html_e( 'Mag-email', 'ang-bantayog' ); ?>
              </a>
            <?php endif; ?>
          </section>
        <?php endforeach; ?>
      </div>

      <?php if ( ! $ab_email ) : ?>
        <p class="notice">
          <?php esc_html_e( 'Walang nakatakdang email ng newsroom. Idagdag ito sa Appearance → Customize → Ang Bantayog: Social at Kontak.', 'ang-bantayog' ); ?>
        </p>
      <?php endif; ?>

      <?php $ab_socials = angbantayog_social_links(); ?>
      <?php if ( $ab_socials ) : ?>
        <section class="contactsocial">
          <h2><?php esc_html_e( 'Sundan kami', 'ang-bantayog' ); ?></h2>
          <div class="chips">
            <?php foreach ( $ab_socials as $ab_s ) : ?>
              <a class="chip" href="<?php echo esc_url( $ab_s['url'] ); ?>" target="_blank" rel="noopener me">
                <?php echo esc_html( $ab_s['label'] ); ?>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

    </article>
  </div>
</main>
<?php get_footer(); ?>
