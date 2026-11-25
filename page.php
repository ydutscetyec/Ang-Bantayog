<?php
/**
 * Default page.
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
          <h1 class="article__title"><?php the_title(); ?></h1>
        </header>
        <?php if ( has_post_thumbnail() ) : ?>
          <figure class="article__hero"><?php the_post_thumbnail( 'angbantayog-featured' ); ?></figure>
        <?php endif; ?>
        <div class="article__body">
          <?php the_content(); ?>
          <?php wp_link_pages(); ?>
        </div>
        <?php if ( comments_open() || get_comments_number() ) comments_template(); ?>
      <?php endwhile; ?>
    </article>
    <?php get_sidebar(); ?>
  </div>
</main>
<?php get_footer(); ?>
