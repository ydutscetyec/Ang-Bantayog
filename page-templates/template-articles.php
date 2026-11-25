<?php
/**
 * Template Name: Lahat ng Artikulo (Articles Index)
 *
 * The InsightPH /articles index: every post, filterable by section, with
 * AJAX load-more rather than pagination.
 *
 * @package Ang_Bantayog
 */

get_header();

$ab_active = isset( $_GET['seksyon'] ) ? sanitize_title( wp_unslash( $_GET['seksyon'] ) ) : '';
$ab_per    = 9;

$ab_args = array(
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'posts_per_page'      => $ab_per,
	'ignore_sticky_posts' => true,
);
if ( $ab_active ) {
	$ab_args['category_name'] = $ab_active;
}
$ab_q = new WP_Query( $ab_args );
?>
<main class="archive" id="top">
  <div class="container">

    <header class="archive__head">
      <h1 class="archive__title"><?php the_title(); ?></h1>
      <?php if ( get_the_content() ) : ?>
        <div class="archive__desc"><?php the_content(); ?></div>
      <?php endif; ?>

      <?php $ab_cats = get_categories( array( 'hide_empty' => true ) ); ?>
      <?php if ( $ab_cats ) : ?>
        <nav class="filterbar" aria-label="<?php esc_attr_e( 'Salain ayon sa seksyon', 'ang-bantayog' ); ?>">
          <a class="chip<?php echo $ab_active ? '' : ' is-active'; ?>" href="<?php echo esc_url( get_permalink() ); ?>">
            <?php esc_html_e( 'Lahat', 'ang-bantayog' ); ?>
          </a>
          <?php foreach ( $ab_cats as $ab_cat ) : ?>
            <a class="chip<?php echo $ab_active === $ab_cat->slug ? ' is-active' : ''; ?>"
               href="<?php echo esc_url( add_query_arg( 'seksyon', $ab_cat->slug, get_permalink() ) ); ?>">
              <?php echo esc_html( $ab_cat->name ); ?>
              <span class="chip__count"><?php echo esc_html( number_format_i18n( $ab_cat->count ) ); ?></span>
            </a>
          <?php endforeach; ?>
        </nav>
      <?php endif; ?>
    </header>

    <?php if ( $ab_q->have_posts() ) : ?>
      <div class="grid grid--3" id="articlesGrid">
        <?php
        $ab_i = 0;
        while ( $ab_q->have_posts() ) :
          $ab_q->the_post();
          set_query_var( 'ab_card_index', $ab_i++ );
          get_template_part( 'template-parts/card-article' );
        endwhile;
        ?>
      </div>

      <?php if ( $ab_q->max_num_pages > 1 ) : ?>
        <button type="button" class="btn btn--ghost loadmore"
                data-loadmore="grid"
                data-target="#articlesGrid"
                data-per-page="<?php echo esc_attr( $ab_per ); ?>"
                data-category="<?php echo esc_attr( $ab_active ); ?>"
                data-page="1">
          <?php esc_html_e( 'Magkarga pa', 'ang-bantayog' ); ?>
        </button>
      <?php endif; ?>

    <?php else : ?>
      <p class="empty"><?php esc_html_e( 'Wala pang artikulo sa seksyong ito.', 'ang-bantayog' ); ?></p>
    <?php endif; ?>

  </div>
</main>
<?php wp_reset_postdata(); ?>
<?php get_footer(); ?>
