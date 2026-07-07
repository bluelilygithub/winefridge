<?php get_header(); ?>

<!-- PAGE HERO -->
<section class="cu-page-hero">
  <img class="cu-page-hero-crest" src="<?php echo get_theme_file_uri( 'assets/images/crest-white.png' ); ?>" alt="">
  <p class="cu-eyebrow is-center">Our Work</p>
  <h1>Installations</h1>
  <p class="cu-page-hero-sub">Apartments, penthouses, commercial venues, and outdoor installs across Australia.</p>
</section>

<!-- CASE STUDY GRID -->
<section class="cu-sec">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);">

    <?php if ( have_posts() ) : ?>
    <div class="cu-cs-grid">
      <?php while ( have_posts() ) : the_post();
        $location = get_post_meta( get_the_ID(), '_cs_location', true );
        $type     = get_post_meta( get_the_ID(), '_cs_type',     true );
        $bottles  = get_post_meta( get_the_ID(), '_cs_bottles',  true );
      ?>
      <a class="cu-cs-card" href="<?php the_permalink(); ?>">
        <div class="cu-cs-card-img">
          <?php if ( has_post_thumbnail() ) :
            the_post_thumbnail( 'large', [ 'alt' => get_the_title() ] );
          else : ?>
            <img src="<?php echo get_theme_file_uri( 'assets/images/case.png' ); ?>" alt="<?php the_title_attribute(); ?>">
          <?php endif; ?>
        </div>
        <div class="cu-cs-card-body">
          <?php if ( $location ) : ?>
            <span class="cu-cs-card-loc"><?php echo esc_html( $location ); ?></span>
          <?php endif; ?>
          <h3><?php the_title(); ?></h3>
          <?php if ( $type ) : ?>
            <span class="cu-cs-card-type"><?php echo esc_html( $type ); ?></span>
          <?php endif; ?>
          <?php if ( $bottles ) : ?>
            <span class="cu-cs-card-bottles"><?php echo esc_html( $bottles ); ?> bottles</span>
          <?php endif; ?>
          <span class="cu-link">View case study <span>&rarr;</span></span>
        </div>
      </a>
      <?php endwhile; ?>
    </div>

    <?php the_posts_pagination( [ 'mid_size' => 2 ] ); ?>

    <?php else : ?>
    <p style="color:var(--text-soft);text-align:center;padding:4rem 0;">No case studies published yet. Check back soon.</p>
    <?php endif; ?>

  </div>
</section>

<!-- CTA BAND -->
<section class="cu-sec cu-cta">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);text-align:center;">
    <p class="cu-eyebrow is-center">Begin</p>
    <h2>Commission your cellar</h2>
    <p>Tell us about your space and collection. We'll design a wine room around it and give you an honest sense of scope before you commit.</p>
    <div class="cu-cta-actions">
      <a class="cu-btn cu-btn--light" href="<?php echo home_url( '/enquire/' ); ?>">Begin a Commission</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
