<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>

<?php
$bottles  = get_post_meta( get_the_ID(), '_cs_bottles',  true );
$temp     = get_post_meta( get_the_ID(), '_cs_temp',     true );
$duration = get_post_meta( get_the_ID(), '_cs_duration', true );
$location = get_post_meta( get_the_ID(), '_cs_location', true );
$type     = get_post_meta( get_the_ID(), '_cs_type',     true );
?>

<!-- CASE STUDY HERO -->
<section class="cu-page-hero cu-cs-hero">
  <?php if ( has_post_thumbnail() ) : ?>
    <?php the_post_thumbnail( 'full', [ 'class' => 'cu-cs-hero-img', 'alt' => get_the_title() ] ); ?>
    <div class="cu-hero-scrim"></div>
  <?php endif; ?>
  <div class="cu-cs-hero-inner">
    <p class="cu-eyebrow">Case Study<?php echo $location ? ' &mdash; ' . esc_html( $location ) : ''; ?></p>
    <h1><?php the_title(); ?></h1>
    <?php if ( $type ) : ?>
      <p class="cu-cs-type"><?php echo esc_html( $type ); ?></p>
    <?php endif; ?>
  </div>
</section>

<!-- STATS BAR -->
<?php if ( $bottles || $temp || $duration ) : ?>
<div class="cu-cs-stats-bar">
  <div class="cu-cs-stats-inner">
    <?php if ( $bottles ) : ?>
      <div class="cu-cs-stat"><span class="num"><?php echo esc_html( $bottles ); ?></span><span class="lab">Bottles</span></div>
    <?php endif; ?>
    <?php if ( $temp ) : ?>
      <div class="cu-cs-stat"><span class="num"><?php echo esc_html( $temp ); ?></span><span class="lab">Held ±0.5°C</span></div>
    <?php endif; ?>
    <?php if ( $duration ) : ?>
      <div class="cu-cs-stat"><span class="num"><?php echo esc_html( $duration ); ?></span><span class="lab">Weeks build</span></div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>

<!-- CASE STUDY CONTENT -->
<article class="cu-article cu-cs-article">
  <?php the_content(); ?>
</article>

<?php endwhile; ?>

<!-- CTA BAND -->
<section class="cu-sec cu-cta">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);text-align:center;">
    <p class="cu-eyebrow is-center">Begin</p>
    <h2>Commission your cellar</h2>
    <p>Tell us about your space and collection. We'll design a wine room around it and give you an honest sense of scope before you commit.</p>
    <div class="cu-cta-actions">
      <a class="cu-btn cu-btn--light" href="<?php echo home_url( '/enquire/' ); ?>">Begin a Commission</a>
      <a class="cu-btn cu-btn--light" href="<?php echo home_url( '/case-studies/' ); ?>" style="background:transparent;">View All Case Studies</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
