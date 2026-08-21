<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
	$image = cw_get_rack_card_image( get_the_ID() );
	$style = get_post_meta( get_the_ID(), '_rack_style', true );

	cw_render_plate_hero( [
		'image'    => $image['url'],
		'title'    => get_the_title(),
		'subtitle' => get_the_excerpt(),
		'kicker'   => [
			'primary' => $style ?: 'Racking style',
		],
	] );
?>

<div class="cw-rack-single cw-wrap">
  <div class="cw-rack-single-main">
    <article class="cw-article">
      <?php the_content(); ?>
    </article>

    <?php get_template_part( 'template-parts/post-media', null, [ 'post_id' => get_the_ID() ] ); ?>
  </div>

  <aside class="cw-rack-single-aside">
    <p class="cw-rack-single-label">Why choose this direction</p>
    <p>This style becomes the brief for the final fit-out: spacing, bottle orientation, large-format handling, and where the collection is stored versus displayed.</p>
    <div class="cw-prod-specs-cta">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>"<?php echo cw_gtm_quote_attrs( 'rack_single' ); ?>>Discuss this rack style</a>
      <a class="cw-link" href="<?php echo esc_url( get_post_type_archive_link( 'rack' ) ); ?>">All rack styles <span>&rarr;</span></a>
    </div>
  </aside>
</div>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>Ready to match racking to your cellar?</h2>
    <p>We can pair this fit-out direction with the right enclosure, materials, and final bottle count once we know the room and collection.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>"<?php echo cw_gtm_quote_attrs( 'rack_single_cta' ); ?>>Start a consultation</a>
      <a class="cw-link" href="<?php echo esc_url( home_url( '/racking/' ) ); ?>">Back to archive <span>&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
