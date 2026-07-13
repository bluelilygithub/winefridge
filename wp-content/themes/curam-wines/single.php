<?php get_header(); ?>

<?php while(have_posts()): the_post();
  $cat      = get_the_category();
  $catname  = !empty($cat) ? $cat[0]->name : 'Journal';
  $hero_img = has_post_thumbnail()
    ? get_the_post_thumbnail_url(null, 'full')
    : get_theme_file_uri('assets/images/product-glass-cellar.jpg');
?>

<section class="cw-plate-hero">
  <img class="cw-plate-hero-img" src="<?php echo esc_url($hero_img); ?>" alt="<?php the_title_attribute(); ?>">
  <div class="cw-plate-hero-scrim"></div>
  <div class="cw-plate-hero-inner">
    <div class="cw-plate-hero-kicker">
      <span class="cat"><?php echo esc_html($catname); ?></span>
      <span>&middot;</span>
      <span><?php the_date('j F Y'); ?></span>
    </div>
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<article class="cw-article">
  <?php the_content(); ?>
</article>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>If this raised a question about your own space</h2>
    <p>We usually reply within a business day, and there's no cost attached to asking.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get specifications</a>
      <a class="cw-link" href="<?php echo home_url('/blog/'); ?>"><span>←</span> Back to Journal</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
