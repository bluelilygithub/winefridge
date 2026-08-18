<?php
/**
 * Photos + optional video for a product or installation.
 *
 * @param array $args { 'post_id' => int }
 */
$post_id   = $args['post_id'] ?? get_the_ID();
$video_url = cw_get_post_video_url( $post_id );
$gallery   = cw_get_post_gallery_ids( $post_id );

if ( ! $video_url && empty( $gallery ) ) {
	return;
}

$type_label = get_post_type( $post_id ) === 'product' ? 'Product' : ( get_post_type( $post_id ) === 'rack' ? 'Rack' : 'Installation' );
$permalink  = get_permalink( $post_id );
$title      = get_the_title( $post_id );
?>
<div class="cw-post-media" data-lightbox-group="post-media">
  <?php if ( $video_url ) : ?>
    <div class="cw-post-media-video">
      <h2 class="cw-post-media-title">Video</h2>
      <video class="cw-video" controls playsinline preload="metadata" poster="<?php echo esc_url( get_the_post_thumbnail_url( $post_id, 'large' ) ?: '' ); ?>">
        <source src="<?php echo esc_url( $video_url ); ?>" type="<?php echo esc_attr( get_post_mime_type( cw_get_post_video_id( $post_id ) ) ?: 'video/mp4' ); ?>">
      </video>
    </div>
  <?php endif; ?>

  <?php if ( ! empty( $gallery ) ) : ?>
    <div class="cw-post-media-gallery">
      <h2 class="cw-post-media-title"><?php echo $video_url ? 'More photos' : 'Photos'; ?></h2>
      <div class="cw-post-media-grid">
        <?php foreach ( $gallery as $aid ) :
          $full = wp_get_attachment_image_url( $aid, 'full' );
          if ( ! $full ) {
            continue;
          }
          $thumb = wp_get_attachment_image_url( $aid, 'large' );
          $alt   = get_post_meta( $aid, '_wp_attachment_image_alt', true ) ?: $title;
        ?>
          <button
            type="button"
            class="cw-post-media-item cw-lightbox-trigger"
            data-media="image"
            data-full="<?php echo esc_url( $full ); ?>"
            data-title="<?php echo esc_attr( $title ); ?>"
            data-type-label="<?php echo esc_attr( $type_label ); ?>"
            data-url="<?php echo esc_url( $permalink ); ?>"
            aria-label="View photo: <?php echo esc_attr( $alt ); ?>"
          >
            <img src="<?php echo esc_url( $thumb ?: $full ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy">
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</div>
