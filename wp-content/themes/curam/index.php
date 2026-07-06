<?php get_header(); ?>

<section class="cu-page-hero">
  <p class="cu-eyebrow is-center">Journal</p>
  <h1>Notes on collecting &amp; craft</h1>
</section>

<section class="cu-sec">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);" class="cu-query">
    <?php if(have_posts()): ?>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:clamp(1.5rem,3vw,2.5rem);">
        <?php while(have_posts()): the_post(); ?>
          <article class="cu-post">
            <a href="<?php the_permalink(); ?>">
              <?php if(has_post_thumbnail()) the_post_thumbnail('large',['class'=>'cu-img','style'=>'aspect-ratio:3/2;object-fit:cover;width:100%;margin-bottom:1.25rem;']);
              else echo '<img class="cu-img" style="aspect-ratio:3/2;object-fit:cover;width:100%;margin-bottom:1.25rem;" src="'.get_theme_file_uri('assets/images/journal-1.png').'" alt="">'; ?>
            </a>
            <div class="meta"><?php the_date(); ?></div>
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo wp_trim_words(get_the_excerpt(),18); ?></p>
          </article>
        <?php endwhile; ?>
      </div>
      <div style="margin-top:3rem;"><?php posts_nav_link(); ?></div>
    <?php else: ?>
      <p>No posts yet.</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
