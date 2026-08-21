<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
  <?php cw_render_plate_hero( [ 'center' => true ] ); ?>
  <div class="cw-page-sections">
    <?php
    $intro = apply_filters( 'the_content', get_the_content() );
    if ( trim( wp_strip_all_tags( $intro ) ) !== '' ) :
      ?>
      <section class="cw-sec">
        <div class="cw-wrap">
          <?php echo $intro; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- filtered content ?>
        </div>
      </section>
    <?php endif; ?>
    <section class="cw-sec">
      <div class="cw-wrap">
        <?php get_template_part( 'template-parts/faq-list' ); ?>
      </div>
    </section>
  </div>
<?php endwhile; ?>

<?php get_footer(); ?>
