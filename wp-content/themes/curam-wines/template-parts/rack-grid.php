<?php
/**
 * Rack archive / shortcode grid.
 *
 * @var array $args { 'query' => WP_Query }
 */
$query = $args['query'] ?? cw_query_racks();
?>
<div class="cw-racking">
  <div class="cw-racking-grid">
    <?php if ( $query->have_posts() ) : ?>
      <?php while ( $query->have_posts() ) : $query->the_post(); ?>
        <?php
        $image = cw_get_rack_card_image( get_the_ID() );
        $style = get_post_meta( get_the_ID(), '_rack_style', true );
        $copy  = get_the_excerpt() ?: wp_trim_words( wp_strip_all_tags( get_the_content() ), 26 );
        ?>
        <article class="cw-rack-card">
          <a class="cw-rack-card-link" href="<?php the_permalink(); ?>">
            <div class="cw-rack-card-media">
              <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" loading="lazy" decoding="async">
            </div>
            <div class="cw-rack-card-body">
              <?php if ( $style ) : ?>
                <span class="cw-rack-card-kicker"><?php echo esc_html( $style ); ?></span>
              <?php endif; ?>
              <h3><?php the_title(); ?></h3>
              <p><?php echo esc_html( $copy ); ?></p>
              <span class="cw-link">View style <span>&rarr;</span></span>
            </div>
          </a>
        </article>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    <?php else : ?>
      <p class="cw-empty">Racking styles will appear here once they are added in WordPress.</p>
    <?php endif; ?>
  </div>
</div>
