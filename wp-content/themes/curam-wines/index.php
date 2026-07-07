<?php get_header(); ?>

<section class="cw-page-hero">
  <img class="cw-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cw-eyebrow is-center">Journal</p>
  <h1>Storage, spec &amp; the serious collector</h1>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <div class="cw-journal">
      <?php if(have_posts()): while(have_posts()): the_post();
        $cat = get_the_category(); $catname = !empty($cat) ? $cat[0]->name : 'Journal'; ?>
        <article class="cw-post">
          <a href="<?php the_permalink(); ?>">
            <?php if(has_post_thumbnail()) the_post_thumbnail('large',['class'=>'cw-post-img']);
            else echo '<img class="cw-post-img" src="'.get_theme_file_uri('assets/images/product-glass-pod.jpg').'" alt="">'; ?>
          </a>
          <div class="cw-post-meta"><?php echo esc_html($catname); ?> &middot; <?php echo get_the_date(); ?></div>
          <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p><?php echo wp_trim_words(get_the_excerpt(),20); ?></p>
        </article>
      <?php endwhile; else: ?>
        <p style="color:var(--text-soft);grid-column:1/-1;">No posts yet. Check back soon.</p>
      <?php endif; ?>
    </div>
    <div style="margin-top:3rem;"><?php the_posts_pagination(['mid_size'=>2]); ?></div>
  </div>
</section>

<?php get_footer(); ?>
