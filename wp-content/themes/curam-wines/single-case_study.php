<?php get_header(); ?>

<?php while(have_posts()): the_post();
  $bottles  = get_post_meta(get_the_ID(), '_cs_bottles',  true);
  $temp     = get_post_meta(get_the_ID(), '_cs_temp',     true);
  $duration = get_post_meta(get_the_ID(), '_cs_duration', true);
  $location = get_post_meta(get_the_ID(), '_cs_location', true);
  $type     = get_post_meta(get_the_ID(), '_cs_type',     true);

  // Build one documentary sentence from whatever meta is present,
  // instead of a row of separate stat chips.
  $facts = [];
  if ($bottles)  $facts[] = esc_html($bottles) . ' bottles';
  if ($temp)     $facts[] = 'held at ' . esc_html($temp);
  if ($duration) $facts[] = 'installed in ' . esc_html($duration);
  $fact_line = !empty($facts) ? ucfirst(implode(', ', $facts)) . '.' : '';
?>

<section class="cw-cs-hero">
  <?php if(has_post_thumbnail()): the_post_thumbnail('full',['class'=>'cw-cs-hero-img']); ?>
  <div class="cw-cs-hero-scrim"></div>
  <?php endif; ?>
  <div class="cw-cs-hero-inner">
    <p class="cw-eyebrow"><?php echo $location ? esc_html($location) : 'Installation'; ?></p>
    <h1><?php the_title(); ?></h1>
    <?php if($type): ?><p class="cw-cs-type"><?php echo esc_html($type); ?></p><?php endif; ?>
  </div>
</section>

<?php if($fact_line): ?>
<div class="cw-note-card">
  <span class="lab">Site record</span>
  <p><?php echo $fact_line; ?></p>
</div>
<?php endif; ?>

<article class="cw-article" style="<?php echo $fact_line ? 'margin-top:2.5rem;' : ''; ?>">
  <?php the_content(); ?>
</article>

<?php endwhile; ?>

<section class="cw-endnote">
  <div class="cw-endnote-inner">
    <h2>If your space is similar</h2>
    <p>Tell us the bottle count and the room — we'll confirm whether the same series works for you, or what would need to change.</p>
    <div class="cw-endnote-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get specifications</a>
      <a class="cw-link" href="<?php echo home_url('/installations/'); ?>">All installations <span>&rarr;</span></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
