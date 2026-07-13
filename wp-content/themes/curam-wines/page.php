<?php get_header(); ?>

<?php while(have_posts()): the_post();
  $hero_img = has_post_thumbnail()
    ? get_the_post_thumbnail_url(null, 'full')
    : get_theme_file_uri('assets/images/product-glass-cellar.jpg');
?>

<section class="cw-plate-hero">
  <img class="cw-plate-hero-img" src="<?php echo esc_url($hero_img); ?>" alt="">
  <div class="cw-plate-hero-scrim"></div>
  <div class="cw-plate-hero-inner is-center">
    <h1><?php the_title(); ?></h1>
  </div>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <div class="cw-page-content">
      <?php the_content(); ?>
    </div>
  </div>
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
