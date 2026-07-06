<?php get_header(); ?>

<article class="cu-article">
  <?php while(have_posts()): the_post(); ?>
    <div class="cu-meta"><?php the_category(', '); ?> &middot; <?php the_date(); ?></div>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  <?php endwhile; ?>
</article>

<?php get_footer(); ?>
