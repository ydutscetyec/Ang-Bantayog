<?php
/**
 * Numbered headline list, used for Popular and Editors' Pick.
 *
 * Args: query (WP_Query), numbered (bool)
 *
 * @package Ang_Bantayog
 */

$ab_args = wp_parse_args( $args ?? array(), array( 'query' => null, 'numbered' => true ) );
$ab_q    = $ab_args['query'];
if ( ! $ab_q instanceof WP_Query || ! $ab_q->have_posts() ) return;
?>
<ol class="ranked<?php echo $ab_args['numbered'] ? '' : ' ranked--plain'; ?>">
  <?php while ( $ab_q->have_posts() ) : $ab_q->the_post(); ?>
    <li class="ranked__item">
      <div>
        <a class="ranked__badge" href="<?php echo esc_url( angbantayog_badge_url( get_the_ID() ) ); ?>">
          <?php echo esc_html( angbantayog_badge_label( get_the_ID() ) ); ?>
        </a>
        <h3 class="ranked__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        <p class="ranked__meta">
          <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
            <?php echo esc_html( angbantayog_relative_time( get_the_ID() ) ); ?>
          </time>
          <span class="dot" aria-hidden="true">&middot;</span>
          <?php echo esc_html( angbantayog_reading_time( get_the_ID() ) ); ?>
        </p>
      </div>
    </li>
  <?php endwhile; ?>
</ol>
<?php wp_reset_postdata(); ?>
