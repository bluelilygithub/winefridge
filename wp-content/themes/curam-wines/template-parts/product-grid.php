<?php
/**
 * Filterable product grid — data from Products CPT.
 */
$products = cw_query_products();
?>
<div class="cw-gallery-wrap">
  <p class="cw-filter-label" id="cw-prod-filter-situation">Your situation</p>
  <div class="cw-filter cw-filter--situation" role="group" aria-labelledby="cw-prod-filter-situation">
    <button type="button" class="cw-chip is-active" data-filter="*" aria-pressed="true">All</button>
    <?php foreach ( cw_get_situation_filters() as $slug => $label ) : ?>
      <button type="button" class="cw-chip" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="false"><?php echo esc_html( $label ); ?></button>
    <?php endforeach; ?>
  </div>

  <p class="cw-filter-label" id="cw-prod-filter-series">Series</p>
  <div class="cw-filter cw-filter--series" role="group" aria-labelledby="cw-prod-filter-series">
    <button type="button" class="cw-chip" data-filter="glass" aria-pressed="false">Panoramic Glass</button>
    <button type="button" class="cw-chip" data-filter="panel" aria-pressed="false">Insulated Panel</button>
    <button type="button" class="cw-chip" data-filter="outdoor" aria-pressed="false">Weather-Resistant</button>
  </div>

  <?php if ( $products->have_posts() ) : ?>
    <div class="cw-grid">
      <?php while ( $products->have_posts() ) : $products->the_post();
        $post_id      = get_the_ID();
        $series       = get_post_meta( $post_id, '_prod_series', true );
        $capacity     = get_post_meta( $post_id, '_prod_capacity', true );
        $install      = get_post_meta( $post_id, '_prod_install', true );
        $price        = get_post_meta( $post_id, '_prod_price', true );
        $series_label = cw_get_product_series_label( $series );
        $filter_cats  = cw_get_product_filter_cats( $post_id );
        $img          = cw_get_product_card_image( $post_id );
      ?>
        <a class="cw-gitem" href="<?php the_permalink(); ?>" data-cats="<?php echo esc_attr( $filter_cats ); ?>">
          <img class="cw-gimg" src="<?php echo esc_url( $img['url'] ); ?>" alt="<?php echo esc_attr( $img['alt'] ); ?>">
          <div class="cw-gitem-body">
            <span class="cw-gcat"><?php echo esc_html( $series_label ); ?></span>
            <span class="cw-gtitle"><?php the_title(); ?></span>
            <?php if ( $capacity || $install ) : ?>
              <span class="cw-gmeta"><?php echo esc_html( trim( ( $install ? $install . ' · ' : '' ) . ( $capacity ?: '' ), ' ·' ) ); ?></span>
            <?php endif; ?>
            <?php if ( $price ) : ?>
              <span class="cw-gprice"><?php echo esc_html( $price ); ?></span>
            <?php endif; ?>
          </div>
        </a>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  <?php else : ?>
    <p class="cw-empty">Configurations are being published — contact us for current specifications.</p>
  <?php endif; ?>
</div>
