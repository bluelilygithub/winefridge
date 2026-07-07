<?php get_header(); ?>

<?php while(have_posts()): the_post();
  $cat = get_the_category(); $catname = !empty($cat) ? $cat[0]->name : 'Journal';
?>
<article class="cw-article">
  <div class="cw-meta"><?php echo esc_html($catname); ?> &middot; <?php the_date(); ?></div>
  <h1><?php the_title(); ?></h1>
  <?php the_content(); ?>
  <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--line);">
    <a class="cw-link" href="<?php echo home_url('/blog/'); ?>"><span>←</span> Back to Journal</a>
  </div>
</article>
<?php endwhile; ?>

<section class="cw-sec cw-cta">
  <div class="cw-wrap" style="text-align:center;">
    <p class="cw-eyebrow is-center">Get Started</p>
    <h2>Find the right unit for your space</h2>
    <p>Tell us your bottle count and installation context — we'll come back with specifications and pricing.</p>
    <div class="cw-cta-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get Specifications</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
