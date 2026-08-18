<?php
/**
 * Gallery page grid — media from Products + Installations CPTs.
 */
$slides = cw_get_gallery_slides();
if ( empty( $slides ) ) {
	echo '<p class="cw-empty">No gallery items yet. Add photos or video to a Product or Installation in WP Admin, tick <strong>Show on Gallery page</strong>, and publish.</p>';
	return;
}
?>
<div class="cw-gallery-page-wrap" data-lightbox-group="gallery-page">
  <div class="cw-filter cw-filter--gallery" role="group" aria-label="Gallery filter">
    <button type="button" class="cw-chip is-active" data-filter="*" aria-pressed="true">All</button>
    <button type="button" class="cw-chip" data-filter="product" aria-pressed="false">Products</button>
    <button type="button" class="cw-chip" data-filter="installation" aria-pressed="false">Installations</button>
    <button type="button" class="cw-chip" data-filter="video" aria-pressed="false">Videos</button>
  </div>

  <div class="cw-gallery-page-grid">
    <?php foreach ( $slides as $slide ) : ?>
      <button
        type="button"
        class="cw-gallery-tile cw-lightbox-trigger"
        data-cats="<?php echo esc_attr( $slide['cats'] ); ?>"
        data-media="<?php echo esc_attr( $slide['media'] ); ?>"
        data-full="<?php echo esc_url( $slide['full'] ); ?>"
        data-title="<?php echo esc_attr( $slide['title'] ); ?>"
        data-type-label="<?php echo esc_attr( $slide['type_label'] ); ?>"
        data-url="<?php echo esc_url( $slide['url'] ); ?>"
        aria-label="<?php echo esc_attr( ( $slide['media'] === 'video' ? 'Play video: ' : 'View image: ' ) . $slide['title'] ); ?>"
        <?php if ( $slide['media'] === 'video' ) : ?>
          data-video="<?php echo esc_url( $slide['video_url'] ); ?>"
        <?php endif; ?>
      >
        <span class="cw-gallery-tile-img">
          <img src="<?php echo esc_url( $slide['thumb'] ); ?>" alt="" aria-hidden="true" loading="lazy">
          <?php if ( $slide['media'] === 'video' ) : ?>
            <span class="cw-gallery-play" aria-hidden="true"></span>
          <?php endif; ?>
        </span>
        <span class="cw-gallery-tile-body">
          <span class="cw-gallery-tile-type"><?php echo esc_html( $slide['type_label'] ); ?></span>
          <span class="cw-gallery-tile-title"><?php echo esc_html( $slide['title'] ); ?></span>
        </span>
      </button>
    <?php endforeach; ?>
  </div>
</div>
<?php
