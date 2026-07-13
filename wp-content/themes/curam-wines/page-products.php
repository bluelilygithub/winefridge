<?php get_header(); ?>

<section class="cw-page-hero">
  <h1>Three build series, one climate system underneath</h1>
  <p class="cw-page-hero-sub">Glass, panel, and weather-resistant shells — the compressor, controls, and precision are identical across all three.</p>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <div class="cw-gallery-wrap">
      <div class="cw-filter">
        <button class="cw-chip is-active" data-filter="*">All</button>
        <button class="cw-chip" data-filter="glass">Panoramic Glass</button>
        <button class="cw-chip" data-filter="panel">Insulated Panel</button>
        <button class="cw-chip" data-filter="outdoor">Weather-Resistant</button>
      </div>

      <?php
        $products = new WP_Query( [
          'post_type'      => 'product',
          'posts_per_page' => -1,
          'post_status'    => 'publish',
          'meta_key'       => '_prod_series',
          'orderby'        => 'menu_order',
          'order'          => 'ASC',
        ] );
      ?>

      <?php if ( $products->have_posts() ) : ?>
        <div class="cw-grid">
          <?php while ( $products->have_posts() ) : $products->the_post();
            $series   = get_post_meta( get_the_ID(), '_prod_series', true );
            $img_meta = get_post_meta( get_the_ID(), '_prod_img',    true );
            $capacity = get_post_meta( get_the_ID(), '_prod_capacity', true );

            $series_labels = [
              'glass'   => 'Panoramic Glass Series',
              'panel'   => 'Insulated Panel Series',
              'outdoor' => 'Weather-Resistant Series',
            ];
            $series_label = $series_labels[ $series ] ?? 'Range';

            if ( has_post_thumbnail() ) {
              $img_url = get_the_post_thumbnail_url( null, 'large' );
              $img_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );
            } elseif ( $img_meta ) {
              $img_url = get_theme_file_uri( 'assets/images/' . $img_meta );
              $img_alt = get_the_title();
            } else {
              $img_url = get_theme_file_uri( 'assets/images/product-glass-pod.jpg' );
              $img_alt = get_the_title();
            }
          ?>
            <a class="cw-gitem" href="<?php the_permalink(); ?>" data-cats="<?php echo esc_attr( $series ); ?>">
              <img class="cw-gimg" src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>">
              <span class="cw-gcat"><?php echo esc_html( $series_label ); ?></span>
              <span class="cw-gtitle"><?php the_title(); ?><?php if ( $capacity ) echo ' — ' . esc_html( $capacity ); ?></span>
            </a>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p style="color:var(--text-soft);text-align:center;padding:3rem 0;">No products found.</p>
      <?php endif; ?>

    </div>
  </div>
</section>

<section class="cw-sec cw-sec--alt">
  <div class="cw-wrap" style="max-width:900px;">
    <h2 style="font-size:var(--fs-h1);letter-spacing:-0.01em;margin-bottom:1rem;">What doesn't change between series</h2>
    <p style="color:var(--text-soft);max-width:56ch;margin-bottom:2.5rem;">Whichever shell you pick, the same compressor and control board sit inside it. This is what's spec'd across all nine models on this page.</p>
    <table class="cw-ledger">
      <tbody>
        <tr><th>Temperature precision</th><td>Holds within ±0.5°C regardless of ambient conditions</td></tr>
        <tr><th>Humidity</th><td>Actively managed, 60–70% RH — keeps corks sealed and labels intact</td></tr>
        <tr><th>Power</th><td>Standard 10A or 15A point — no dedicated circuit in most installs</td></tr>
        <tr><th>Capacity range</th><td>120 to 2,000+ bottles; panel series supports modular expansion</td></tr>
      </tbody>
    </table>
  </div>
</section>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>Narrowing down which model fits</h2>
    <p>Tell us the bottle count and the space, and we'll narrow the nine models down to one or two with a fixed price attached.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Get specifications</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
