<?php get_header(); ?>

<section class="cu-page-hero">
  <h1><?php the_title(); ?></h1>
</section>

<section class="cu-sec">
  <div class="cu-article">
    <?php while(have_posts()): the_post(); the_content(); endwhile; ?>
  </div>
</section>

<?php get_footer(); ?>
