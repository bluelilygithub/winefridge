<?php get_header(); ?>

<section class="cw-page-hero">
  <p class="cw-eyebrow is-center">&nbsp;</p>
  <h1><?php the_title(); ?></h1>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <div class="cw-page-content">
      <?php while(have_posts()): the_post(); the_content(); endwhile; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
