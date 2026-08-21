<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
  <?php cw_render_plate_hero( [ 'center' => true ] ); ?>
  <?php get_template_part( 'template-parts/content', 'prose' ); ?>
<?php endwhile; ?>

<?php get_footer(); ?>
