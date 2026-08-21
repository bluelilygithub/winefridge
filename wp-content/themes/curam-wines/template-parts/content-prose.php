<?php
/**
 * Standard readable content — centred column for pages and blog posts.
 */
?>
<section class="cw-sec cw-prose-sec">
  <div class="cw-wrap">
    <article <?php post_class( 'cw-prose' ); ?>>
      <?php the_content(); ?>
    </article>
  </div>
</section>
