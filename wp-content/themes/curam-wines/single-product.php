<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
	$series       = get_post_meta( get_the_ID(), '_prod_series', true );
	$series_label = cw_get_product_series_label( $series );
	$capacity     = get_post_meta( get_the_ID(), '_prod_capacity', true );
	$price        = get_post_meta( get_the_ID(), '_prod_price', true );
	$install      = get_post_meta( get_the_ID(), '_prod_install', true );
	$dims         = get_post_meta( get_the_ID(), '_prod_dimensions', true );
	$img_meta     = get_post_meta( get_the_ID(), '_prod_img', true );
	$specs        = cw_get_product_specs( get_the_ID() );
	$slug         = get_post()->post_name;
	$related_cs   = cw_get_related_installation( $slug );

	if ( has_post_thumbnail() ) {
		$img_url = get_the_post_thumbnail_url( null, 'full' );
	} elseif ( $img_meta ) {
		$img_url = get_theme_file_uri( 'assets/images/' . $img_meta );
	} else {
		$img_url = get_theme_file_uri( 'assets/images/product-glass-pod.jpg' );
	}

	$enquire_url = add_query_arg(
		[
			'product' => rawurlencode( get_the_title() ),
			'series'  => rawurlencode( $series_label ),
		],
		home_url( '/enquire/' )
	);

	cw_render_plate_hero( [
		'image'    => $img_url,
		'title'    => get_the_title(),
		'subtitle' => trim( ( $price ? $price . ' · ' : '' ) . ( $capacity ?: '' ), ' ·' ),
		'kicker'   => [
			'primary'   => $series_label,
			'secondary' => $install ?: 'Configuration',
		],
	] );
?>

<div class="cw-prod-body cw-wrap">
  <div class="cw-prod-content">
    <?php if ( $dims ) : ?>
      <div class="cw-prod-highlight">
        <strong>External dimensions:</strong> <?php echo esc_html( preg_replace( '/^W\s*/', '', $dims ) ); ?>
        <?php if ( $install ) : ?> &middot; <strong>Install:</strong> <?php echo esc_html( $install ); ?><?php endif; ?>
      </div>
    <?php endif; ?>

    <article class="cw-article">
      <?php the_content(); ?>
    </article>

    <?php get_template_part( 'template-parts/post-media', null, [ 'post_id' => get_the_ID() ] ); ?>

    <?php if ( $related_cs ) : ?>
      <div class="cw-prod-related">
        <p class="cw-eyebrow">Similar installation</p>
        <h3><a href="<?php echo esc_url( get_permalink( $related_cs ) ); ?>"><?php echo esc_html( get_the_title( $related_cs ) ); ?></a></h3>
        <p><?php echo esc_html( get_post_meta( $related_cs->ID, '_cs_location', true ) ); ?> — <?php echo esc_html( get_post_meta( $related_cs->ID, '_cs_bottles', true ) ); ?> bottles</p>
        <a class="cw-link" href="<?php echo esc_url( get_permalink( $related_cs ) ); ?>">View installation <span>&rarr;</span></a>
      </div>
    <?php endif; ?>
  </div>

  <aside class="cw-prod-specs">
    <?php if ( $price ) : ?>
      <p class="cw-prod-price"><?php echo esc_html( $price ); ?></p>
      <p class="cw-prod-price-note">Quote confirmed after we check your space</p>
    <?php endif; ?>
    <p class="cw-prod-specs-label">Specifications</p>
    <?php get_template_part( 'template-parts/product-specs', null, [ 'specs' => $specs ] ); ?>
    <div class="cw-prod-specs-cta">
      <a class="cw-btn" href="<?php echo esc_url( $enquire_url ); ?>"<?php echo cw_gtm_quote_attrs( 'product_sidebar' ); ?>>Get a quote</a>
      <?php if ( cw_get_org_phone() ) : ?>
        <a class="cw-link" href="tel:<?php echo esc_attr( cw_get_org_phone_tel() ); ?>"<?php echo cw_gtm_phone_attrs( 'product_sidebar' ); ?>>Or call <?php echo esc_html( cw_get_org_phone() ); ?></a>
      <?php endif; ?>
    </div>
  </aside>
</div>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>Confirm the fit, get a quote</h2>
    <p>Give us the bottle count and the room dimensions. We'll confirm whether this configuration works for your space, or point you to the one that does.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo esc_url( $enquire_url ); ?>"<?php echo cw_gtm_quote_attrs( 'product_endnote' ); ?>>Get a quote</a>
      <a class="cw-link" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">All configurations <span>&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
