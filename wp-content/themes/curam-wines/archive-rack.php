<?php get_header(); ?>

<?php
$intro = cw_get_rack_archive_intro_page();

if ( $intro ) {
	cw_render_page_hero_from_post( $intro, [ 'center' => true ] );
} else {
	cw_render_plate_hero( [
		'title'    => 'Racking styles',
		'subtitle' => 'High-density storage, label-forward display, mixed layouts, diamond bins, magnums, and custom fit-outs.',
		'image'    => get_theme_file_uri( 'assets/images/racking/process-illustration.jpg' ),
		'center'   => true,
	] );
}
?>

<section class="cw-sec cw-rack-archive-intro">
  <div class="cw-wrap cw-rack-archive-head">
    <div>
      <p class="cw-eyebrow">Fit-out direction</p>
      <h2>Choose the balance between capacity and display</h2>
      <p class="cw-rack-archive-copy">
        Every cellar ends up with a different brief. Some collectors want maximum bottle density,
        others want a label-forward feature, and most land somewhere in between. Start with a style,
        then we tailor materials, spacing, and special bays around your collection.
      </p>
    </div>
    <figure class="cw-rack-archive-fig">
      <img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/racking/process-illustration.jpg' ) ); ?>" alt="Walk-in wine cellar with multiple racking styles" loading="lazy" decoding="async">
      <figcaption>Use the archive to compare rack directions before the final specification is locked.</figcaption>
    </figure>
  </div>
</section>

<section class="cw-sec cw-racking-sec">
  <div class="cw-wrap">
    <?php
    $query = cw_query_racks();
    get_template_part( 'template-parts/rack-grid', null, [ 'query' => $query ] );
    ?>
  </div>
</section>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>Need help narrowing the fit-out?</h2>
    <p>Tell us how you want the cellar to behave: maximum capacity, more display, or a blend of both. We can map the rack style into the broader concept and quote.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Talk through your fit-out</a>
      <a class="cw-link" href="<?php echo esc_url( home_url( '/engineering/' ) ); ?>">How it works <span>&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
