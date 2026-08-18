<?php get_header(); ?>

<?php while ( have_posts() ) : the_post();
  $cat     = get_the_category();
  $catname = ! empty( $cat ) ? $cat[0]->name : 'Journal';
  cw_render_plate_hero( [
    'kicker' => [
      'primary'   => $catname,
      'secondary' => get_the_date( 'j F Y' ),
    ],
  ] );
?>

<article class="cw-article">
  <?php the_content(); ?>
</article>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>If this raised a question about your own space</h2>
    <p>We usually reply within a business day, and there's no cost attached to asking.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo home_url( '/enquire/' ); ?>">Get a fixed quote</a>
      <a class="cw-link" href="<?php echo home_url( '/blog/' ); ?>"><span>&larr;</span> Back to Journal</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
