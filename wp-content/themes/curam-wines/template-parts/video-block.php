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

$defaults = function_exists( 'cw_default_site_copy' ) ? cw_default_site_copy() : [];
$title    = $args['title'] ?? ( $defaults['video_title'] ?? 'See a cabinet installed' );
$caption  = $args['caption'] ?? ( $defaults['video_caption'] ?? '' );
$intro    = $args['intro'] ?? '';
$mime     = get_post_mime_type( $video_id ) ?: 'video/mp4';
$poster   = content_url( '/uploads/2026/07/wine-cabinet-living-room.jpg' );
$poster_id = function_exists( 'cw_get_site_copy_setting' ) ? (int) cw_get_site_copy_setting( 'video_poster_id', 0 ) : 0;
if ( $poster_id ) {
	$from_media = wp_get_attachment_image_url( $poster_id, 'full' );
	if ( $from_media ) {
		$poster = $from_media;
	}
}
?>
<div class="cw-video-block">
  <?php if ( $title ) : ?>
    <h2 class="cw-section-title"><?php echo esc_html( $title ); ?></h2>
  <?php endif; ?>
  <?php if ( $intro ) : ?>
    <p class="cw-section-intro"><?php echo esc_html( $intro ); ?></p>
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
