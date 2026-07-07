<?php get_header(); ?>

<section class="cw-page-hero">
  <img class="cw-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cw-eyebrow is-center">Our Work</p>
  <h1>Installations</h1>
  <p class="cw-page-hero-sub">Apartments, penthouses, garages, balconies, and commercial venues across Australia.</p>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <?php if(have_posts()): ?>
    <div class="cw-cs-grid">
      <?php while(have_posts()): the_post();
        $location = get_post_meta(get_the_ID(), '_cs_location', true);
        $type     = get_post_meta(get_the_ID(), '_cs_type',     true);
        $bottles  = get_post_meta(get_the_ID(), '_cs_bottles',  true);
      ?>
      <a class="cw-cs-card" href="<?php the_permalink(); ?>">
        <div class="cw-cs-card-img">
          <?php if(has_post_thumbnail()) the_post_thumbnail('large',['alt'=>get_the_title()]);
          else echo '<img src="'.get_theme_file_uri('assets/images/product-panel-walkin.jpg').'" alt="'.esc_attr(get_the_title()).'">'; ?>
        </div>
        <div class="cw-cs-card-body">
          <?php if($location): ?><span class="cw-cs-card-loc"><?php echo esc_html($location); ?></span><?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <?php if($type): ?><span class="cw-cs-card-type"><?php echo esc_html($type); ?></span><?php endif; ?>
          <?php if($bottles): ?><span class="cw-cs-card-bottles"><?php echo esc_html($bottles); ?> bottles</span><?php endif; ?>
          <span class="cw-link" style="margin-top:0.75rem;">View installation <span>&rarr;</span></span>
        </div>
      </a>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(['mid_size'=>2]); ?>
    <?php else: ?>
    <p style="color:var(--text-soft);text-align:center;padding:4rem 0;">No installations published yet.</p>
    <?php endif; ?>
  </div>
</section>

<section class="cw-sec cw-cta">
  <div class="cw-wrap" style="text-align:center;">
    <p class="cw-eyebrow is-center">Get Started</p>
    <h2>Find the right unit for your space</h2>
    <p>Tell us your bottle count and installation context — we'll confirm the right series and a fixed price.</p>
    <div class="cw-cta-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get Specifications</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
