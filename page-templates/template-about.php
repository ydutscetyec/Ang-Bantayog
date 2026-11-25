<?php
/**
 * Template Name: Tungkol Sa Amin (About)
 *
 * Masthead page: the publication's statement, the editorial board pulled from
 * site users, and the network/affiliation block.
 *
 * @package Ang_Bantayog
 */

get_header();
?>
<main class="single" id="top">

  <section class="cover cover--short">
    <img class="cover__bg" src="<?php echo esc_url( angbantayog_cover_url() ); ?>" alt="" loading="lazy" />
    <div class="cover__wash" aria-hidden="true"></div>
    <div class="container cover__inner">
      <h1 class="cover__title"><?php the_title(); ?></h1>
      <p class="cover__tagline">
        <?php echo wp_kses_post( get_theme_mod( 'ab_tagline', __( 'Balitang may saysay. Bantayog ng katotohanan.', 'ang-bantayog' ) ) ); ?>
      </p>
    </div>
  </section>

  <div class="container">
    <article class="article article--wide">
      <?php while ( have_posts() ) : the_post(); ?>
        <div class="article__body"><?php the_content(); ?></div>
      <?php endwhile; ?>

      <?php /* -------- Editorial board, from users who have published -------- */ ?>
      <?php
      // 'capability' rather than the 'who' argument, which WP deprecated in 5.9.
      $ab_staff = get_users( array(
        'capability'          => array( 'edit_posts' ),
        'has_published_posts' => array( 'post', 'ab_factcheck' ),
        'orderby'             => 'display_name',
        'number'              => 24,
      ) );
      if ( $ab_staff ) : ?>
        <section class="staff">
          <?php get_template_part( 'template-parts/section-heading', null, array(
            'title' => __( 'Ang Patnugutan', 'ang-bantayog' ),
            'sub'   => __( 'Ang mga nagsusulat at nag-edit ng pahayagan.', 'ang-bantayog' ),
          ) ); ?>
          <ul class="staff__grid">
            <?php foreach ( $ab_staff as $ab_member ) : ?>
              <li class="staff__card">
                <?php echo get_avatar( $ab_member->ID, 80, '', '', array( 'class' => 'staff__avatar' ) ); ?>
                <h3 class="staff__name">
                  <a href="<?php echo esc_url( get_author_posts_url( $ab_member->ID ) ); ?>">
                    <?php echo esc_html( $ab_member->display_name ); ?>
                  </a>
                </h3>
                <?php $ab_role = get_the_author_meta( 'description', $ab_member->ID ); ?>
                <?php if ( $ab_role ) : ?>
                  <p class="staff__role"><?php echo esc_html( wp_trim_words( $ab_role, 16, '…' ) ); ?></p>
                <?php endif; ?>
                <p class="staff__count">
                  <?php
                  $ab_n = (int) count_user_posts( $ab_member->ID, 'post' );
                  printf(
                    esc_html( _n( '%s artikulo', '%s na artikulo', $ab_n, 'ang-bantayog' ) ),
                    esc_html( number_format_i18n( $ab_n ) )
                  );
                  ?>
                </p>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>
      <?php endif; ?>

      <?php /* -------- Network / affiliation block -------- */ ?>
      <?php
      $ab_partners = angbantayog_cpt_query( 'ab_partner', 12, array( 'orderby' => 'title', 'order' => 'ASC' ) );
      if ( $ab_partners->have_posts() ) : ?>
        <section class="partners partners--panel">
          <h2 class="partners__title"><?php esc_html_e( 'Mga Kasosyo at Kaalyado', 'ang-bantayog' ); ?></h2>
          <ul class="partners__strip">
            <?php while ( $ab_partners->have_posts() ) : $ab_partners->the_post();
              $ab_purl = get_post_meta( get_the_ID(), '_ab_partner_url', true ); ?>
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
      <?php endif; wp_reset_postdata(); ?>
    </article>
  </div>
</main>
<?php get_footer(); ?>
