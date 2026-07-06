<?php get_header(); ?>

<section class="cu-page-hero">
  <img class="cu-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cu-eyebrow is-center">The Collection</p>
  <h1>Walk-in cellars, wine walls &amp; cabinets</h1>
</section>

<section class="cu-sec">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);">

    <div class="cu-gallery">
      <div class="cu-filter" role="tablist">
        <button class="cu-chip is-active" data-filter="*">All</button>
        <button class="cu-chip" data-filter="walk-in-cellars">Walk-In Cellars</button>
        <button class="cu-chip" data-filter="wine-walls">Wine Walls</button>
        <button class="cu-chip" data-filter="bespoke-cabinets">Bespoke Cabinets</button>
      </div>

      <div class="cu-grid">
        <?php
        $items = [
          ['img'=>'cellars.png',      'cat'=>'walk-in-cellars',  'label'=>'Walk-In Cellars',  'title'=>'Malvern East Residence'],
          ['img'=>'wine-walls.png',   'cat'=>'wine-walls',       'label'=>'Wine Walls',        'title'=>'South Yarra Townhouse'],
          ['img'=>'bespoke.png',      'cat'=>'bespoke-cabinets', 'label'=>'Bespoke Cabinets',  'title'=>'St Kilda Road Penthouse'],
          ['img'=>'case.png',         'cat'=>'walk-in-cellars',  'label'=>'Walk-In Cellars',   'title'=>'Toorak Estate'],
          ['img'=>'intro-detail.png', 'cat'=>'wine-walls',       'label'=>'Wine Walls',        'title'=>'Brighton Beach House'],
          ['img'=>'journal-1.png',    'cat'=>'bespoke-cabinets', 'label'=>'Bespoke Cabinets',  'title'=>'Surry Hills Studio'],
          ['img'=>'craft.png',        'cat'=>'walk-in-cellars',  'label'=>'Walk-In Cellars',   'title'=>'Hawthorn Heritage Home'],
          ['img'=>'hero.png',         'cat'=>'wine-walls',       'label'=>'Wine Walls',        'title'=>'Double Bay Collection'],
        ];
        foreach($items as $item): ?>
          <a class="cu-gitem" data-cats="<?php echo $item['cat']; ?>" href="<?php echo home_url('/enquire/'); ?>">
            <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/'.$item['img']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
            <span class="cu-gmeta">
              <span class="cu-gcat"><?php echo $item['label']; ?></span>
              <span class="cu-gtitle"><?php echo $item['title']; ?></span>
            </span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

  </div>
</section>

<?php get_footer(); ?>
