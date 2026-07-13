<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
	$series   = get_post_meta( get_the_ID(), '_prod_series',     true );
	$capacity = get_post_meta( get_the_ID(), '_prod_capacity',   true );
	$price    = get_post_meta( get_the_ID(), '_prod_price',      true );
	$power    = get_post_meta( get_the_ID(), '_prod_power',      true );
	$dims     = get_post_meta( get_the_ID(), '_prod_dimensions', true );
	$finish   = get_post_meta( get_the_ID(), '_prod_finish',     true );
	$img_meta = get_post_meta( get_the_ID(), '_prod_img',        true );

	$series_labels = [
		'glass'   => 'Panoramic Glass Series',
		'panel'   => 'Insulated Panel Series',
		'outdoor' => 'Weather-Resistant Series',
	];
	$series_label = $series_labels[ $series ] ?? 'Wine Cabinet Range';

	if ( has_post_thumbnail() ) {
		$img_url = get_the_post_thumbnail_url( null, 'full' );
	} elseif ( $img_meta ) {
		$img_url = get_theme_file_uri( 'assets/images/' . $img_meta );
	} else {
		$img_url = get_theme_file_uri( 'assets/images/product-glass-pod.jpg' );
	}

	$enquire_url = add_query_arg( 'product', rawurlencode( get_the_title() ), home_url( '/enquire/' ) );
?>

<section class="cw-plate-hero" style="min-height:58vh;">
  <img class="cw-plate-hero-img" src="<?php echo esc_url( $img_url ); ?>" alt="">
  <div class="cw-plate-hero-scrim"></div>
  <div class="cw-plate-hero-inner">
    <div class="cw-plate-hero-kicker">
      <span class="cat"><?php echo esc_html( $series_label ); ?></span>
      <span>&mdash;</span>
      <a href="<?php echo esc_url( home_url( '/products/' ) ); ?>" style="color:var(--on-dark-soft);text-decoration:none;">Full range</a>
    </div>
    <h1><?php the_title(); ?></h1>
    <?php if ( $capacity ) : ?>
      <p class="cw-plate-hero-sub"><?php echo esc_html( $capacity ); ?></p>
    <?php endif; ?>
  </div>
</section>

<div class="cw-prod-body cw-wrap">
  <div class="cw-prod-content">
    <article class="cw-article">
      <?php the_content(); ?>
    </article>
  </div>

  <aside class="cw-prod-specs">
    <p class="cw-prod-specs-label">Specifications</p>
    <table class="cw-ledger">
      <tbody>
        <?php if ( $capacity ) : ?>
          <tr><th>Capacity</th><td><?php echo esc_html( $capacity ); ?></td></tr>
        <?php endif; ?>
        <tr><th>Temperature</th><td>Holds within ±0.5°C</td></tr>
        <tr><th>Humidity</th><td>60–70% RH, actively managed</td></tr>
        <?php if ( $power ) : ?>
          <tr><th>Power</th><td><?php echo esc_html( $power ); ?></td></tr>
        <?php endif; ?>
        <?php if ( $dims ) : ?>
          <tr><th>Dimensions</th><td><?php echo esc_html( $dims ); ?></td></tr>
        <?php endif; ?>
        <?php if ( $finish ) : ?>
          <tr><th>Finish</th><td><?php echo esc_html( $finish ); ?></td></tr>
        <?php endif; ?>
        <?php if ( $price ) : ?>
          <tr><th>Price</th><td><?php echo esc_html( $price ); ?></td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div style="margin-top:2.5rem;">
      <a class="cw-btn" href="<?php echo esc_url( $enquire_url ); ?>">Get specifications</a>
    </div>
  </aside>
</div>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>Confirm the fit, get a fixed price</h2>
    <p>Give us the bottle count and the room dimensions. We'll confirm whether this model works for your space, or point you to the one that does — with a price attached.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo esc_url( $enquire_url ); ?>">Enquire</a>
      <a class="cw-link" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">All models <span>&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
