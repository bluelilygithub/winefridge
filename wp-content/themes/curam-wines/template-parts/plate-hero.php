<?php
/**
 * Shared plate hero — page, single, FAQ, archives.
 *
 * @var array $args {
 *   @type string $title
 *   @type string $subtitle
 *   @type string $image
 *   @type string $alt
 *   @type bool   $center
 *   @type array  $kicker { primary, secondary }
 * }
 */
$title    = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$image    = $args['image'] ?? get_theme_file_uri( 'assets/images/product-glass-cellar.jpg' );
$alt      = $args['alt'] ?? $title;
$center   = ! empty( $args['center'] );
$kicker   = $args['kicker'] ?? [];
$inner    = $center ? 'cw-plate-hero-inner is-center' : 'cw-plate-hero-inner';
?>
<section class="cw-plate-hero" aria-label="<?php echo esc_attr( wp_strip_all_tags( $title ) ?: 'Page header' ); ?>">
  <img class="cw-plate-hero-img" src="<?php echo esc_url( $image ); ?>" alt="" aria-hidden="true">
  <div class="cw-plate-hero-scrim"></div>
  <div class="<?php echo esc_attr( $inner ); ?>">
    <?php if ( ! empty( $kicker['primary'] ) ) : ?>
      <div class="cw-plate-hero-kicker">
        <span class="cat"><?php echo esc_html( $kicker['primary'] ); ?></span>
        <?php if ( ! empty( $kicker['secondary'] ) ) : ?>
          <span>&middot;</span>
          <span><?php echo esc_html( $kicker['secondary'] ); ?></span>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <?php if ( $title ) : ?>
      <h1><?php echo esc_html( $title ); ?></h1>
    <?php endif; ?>
    <?php if ( $subtitle ) : ?>
      <p class="cw-plate-hero-sub"><?php echo esc_html( $subtitle ); ?></p>
    <?php endif; ?>
  </div>
</section>
