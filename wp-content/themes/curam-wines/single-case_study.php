<?php get_header(); ?>

<?php while(have_posts()): the_post();
  $bottles  = get_post_meta(get_the_ID(), '_cs_bottles',  true);
  $temp     = get_post_meta(get_the_ID(), '_cs_temp',     true);
  $duration = get_post_meta(get_the_ID(), '_cs_duration', true);
  $location = get_post_meta(get_the_ID(), '_cs_location', true);
  $type     = get_post_meta(get_the_ID(), '_cs_type',     true);
?>

<section class="cw-cs-hero">
  <?php if(has_post_thumbnail()): the_post_thumbnail('full',['class'=>'cw-cs-hero-img']); ?>
  <div class="cw-cs-hero-scrim"></div>
  <?php endif; ?>
  <div class="cw-cs-hero-inner">
    <p class="cw-eyebrow">Installation<?php echo $location ? ' — '.esc_html($location) : ''; ?></p>
    <h1><?php the_title(); ?></h1>
    <?php if($type): ?><p class="cw-cs-type"><?php echo esc_html($type); ?></p><?php endif; ?>
  </div>
</section>

<?php if($bottles || $temp || $duration): ?>
<div class="cw-cs-stats-bar">
  <div class="cw-cs-stats-inner">
    <?php if($bottles): ?><div class="cw-cs-stat"><span class="num"><?php echo esc_html($bottles); ?></span><span class="lab">Bottles</span></div><?php endif; ?>
    <?php if($temp): ?><div class="cw-cs-stat"><span class="num"><?php echo esc_html($temp); ?></span><span class="lab">Held ±0.5°C</span></div><?php endif; ?>
    <?php if($duration): ?><div class="cw-cs-stat"><span class="num"><?php echo esc_html($duration); ?></span><span class="lab">Hrs install</span></div><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<article class="cw-article">
  <?php the_content(); ?>
  <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--line);">
    <a class="cw-link" href="<?php echo home_url('/installations/'); ?>"><span>←</span> All Installations</a>
  </div>
</article>

<?php endwhile; ?>

<section class="cw-sec cw-cta">
  <div class="cw-wrap" style="text-align:center;">
    <p class="cw-eyebrow is-center">Get Started</p>
    <h2>Find the right unit for your space</h2>
    <p>Tell us your bottle count and installation context — we'll confirm the right series and a fixed price.</p>
    <div class="cw-cta-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get Specifications</a>
      <a class="cw-btn cw-btn--ghost" href="<?php echo home_url('/installations/'); ?>">View All Installations</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
