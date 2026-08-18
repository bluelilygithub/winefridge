<?php
/**
 * Featured site video — converted.mp4 from the media library.
 *
 * @param array $args { 'title', 'intro', 'caption' }
 */
$video_id  = cw_get_feature_video_id();
$video_url = $video_id ? wp_get_attachment_url( $video_id ) : '';

if ( ! $video_url ) {
	return;
}

$title   = $args['title'] ?? 'See a cabinet installed';
$caption = $args['caption'] ?? 'A finished unit positioned, connected, and commissioned — no building work on site.';
$mime    = get_post_mime_type( $video_id ) ?: 'video/mp4';
$poster  = content_url( '/uploads/2026/07/wine-cabinet-living-room.jpg' );
?>
<div class="cw-video-block">
  <?php if ( $title ) : ?>
    <h2 class="cw-section-title"><?php echo esc_html( $title ); ?></h2>
  <?php endif; ?>
  <?php if ( ! empty( $args['intro'] ) ) : ?>
    <p class="cw-section-intro"><?php echo esc_html( $args['intro'] ); ?></p>
  <?php endif; ?>
  <figure class="cw-video-figure">
    <video class="cw-video" controls playsinline preload="metadata" poster="<?php echo esc_url( $poster ); ?>" aria-label="<?php echo esc_attr( $title ); ?>">
      <source src="<?php echo esc_url( $video_url ); ?>" type="<?php echo esc_attr( $mime ); ?>">
      Your browser does not support embedded video.
    </video>
    <?php if ( $caption ) : ?>
      <figcaption><?php echo esc_html( $caption ); ?></figcaption>
    <?php endif; ?>
  </figure>
</div>
