<?php
/**
 * Latest installation highlight — data from Installations CPT.
 */
$count = isset( $args['count'] ) ? max( 1, (int) $args['count'] ) : 1;
$posts = get_posts( [
	'post_type'      => 'case_study',
	'posts_per_page' => $count,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
] );

if ( empty( $posts ) ) {
	return;
}

foreach ( $posts as $featured_install ) :
	$fi_loc = get_post_meta( $featured_install->ID, '_cs_location', true );
	$fi_img = get_the_post_thumbnail_url( $featured_install, 'large' )
		?: get_theme_file_uri( 'assets/images/product-glass-niche.jpg' );
?>
<section class="cw-sec">
  <div class="cw-wrap cw-home-split">
    <figure class="cw-home-split-fig">
      <img src="<?php echo esc_url( $fi_img ); ?>" alt="<?php echo esc_attr( get_the_title( $featured_install ) ); ?>">
      <?php if ( $fi_loc ) : ?><figcaption><?php echo esc_html( $fi_loc ); ?></figcaption><?php endif; ?>
    </figure>
    <div class="cw-home-split-body">
      <p class="cw-eyebrow">Recent installation</p>
      <h2><?php echo esc_html( get_the_title( $featured_install ) ); ?></h2>
      <p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $featured_install->post_content ), 36 ) ); ?></p>
      <a class="cw-link" href="<?php echo esc_url( get_permalink( $featured_install ) ); ?>">Read the install note <span>&rarr;</span></a>
      <a class="cw-link" href="<?php echo esc_url( home_url( '/installations/' ) ); ?>" style="margin-left:1.5rem;">All installations <span>&rarr;</span></a>
    </div>
  </div>
</section>
<?php
endforeach;
