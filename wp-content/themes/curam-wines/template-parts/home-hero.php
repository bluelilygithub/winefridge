<?php
/**
 * Homepage hero — title/excerpt/image from the front page in WP Admin.
 *
 * @var array $args { eyebrow, image }
 */
$page    = get_queried_object();
$eyebrow = $args['eyebrow'] ?? 'Walk-In Wine Cabinets Australia';
$image   = $args['image'] ?? cw_get_plate_hero_image( $page );
$title   = $page instanceof WP_Post ? get_the_title( $page ) : '';
$sub     = $page instanceof WP_Post ? get_the_excerpt( $page ) : '';
?>
<section class="cw-home-hero" aria-label="<?php echo esc_attr( wp_strip_all_tags( $title ) ?: 'Homepage' ); ?>">
  <img class="cw-home-hero-img" src="<?php echo esc_url( $image ); ?>" alt="" aria-hidden="true">
  <div class="cw-home-hero-scrim"></div>
  <div class="cw-home-hero-inner">
    <?php if ( $eyebrow ) : ?>
      <p class="cw-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
    <?php endif; ?>
    <?php if ( $title ) : ?>
      <h1><?php echo wp_kses_post( $title ); ?></h1>
    <?php endif; ?>
    <?php if ( $sub ) : ?>
      <p class="cw-home-hero-sub"><?php echo wp_kses_post( $sub ); ?></p>
    <?php endif; ?>
    <div class="cw-home-hero-actions">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Get a fixed quote</a>
      <a class="cw-btn cw-btn--ghost" href="<?php echo esc_url( home_url( '/products/' ) ); ?>">See the range</a>
    </div>
  </div>
</section>
