<?php get_header(); ?>

<section class="cw-page-hero">
  <img class="cw-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cw-eyebrow is-center">The Range</p>
  <h1>Three series. Every installation.</h1>
  <p class="cw-page-hero-sub">Panoramic glass, insulated panel, and outdoor-rated — all sharing the same ±0.5°C climate system.</p>
</section>

<section class="cw-sec">
  <div class="cw-wrap">
    <div class="cw-gallery-wrap">
      <div class="cw-filter">
        <button class="cw-chip is-active" data-filter="*">All</button>
        <button class="cw-chip" data-filter="glass">Panoramic Glass</button>
        <button class="cw-chip" data-filter="panel">Insulated Panel</button>
        <button class="cw-chip" data-filter="outdoor">Weather-Resistant</button>
      </div>
      <div class="cw-grid">
        <div class="cw-gitem" data-cats="glass">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-pod.jpg'); ?>" alt="Panoramic glass wine pod">
          <span class="cw-gcat">Panoramic Glass Series</span>
          <span class="cw-gtitle">Freestanding Glass Pod — up to 400 bottles</span>
        </div>
        <div class="cw-gitem" data-cats="glass">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-niche.jpg'); ?>" alt="Glass unit in apartment niche">
          <span class="cw-gcat">Panoramic Glass Series</span>
          <span class="cw-gtitle">Niche-Fit Glass Unit — apartment install</span>
        </div>
        <div class="cw-gitem" data-cats="glass">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-cellar.jpg'); ?>" alt="High-capacity glass cellar">
          <span class="cw-gcat">Panoramic Glass Series</span>
          <span class="cw-gtitle">High-Capacity Glass Room — 800+ bottles</span>
        </div>
        <div class="cw-gitem" data-cats="panel">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Panel walk-in unit" style="object-position:center 15%;">
          <span class="cw-gcat">Insulated Panel Series</span>
          <span class="cw-gtitle">Panel Walk-In — 1,200 bottle capacity</span>
        </div>
        <div class="cw-gitem" data-cats="panel">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-niche.jpg'); ?>" alt="Built-in alcove panel unit">
          <span class="cw-gcat">Insulated Panel Series</span>
          <span class="cw-gtitle">Alcove Built-In — zero renovation install</span>
        </div>
        <div class="cw-gitem" data-cats="panel">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Large format panel unit">
          <span class="cw-gcat">Insulated Panel Series</span>
          <span class="cw-gtitle">Double-Bay Panel Unit — commercial capacity</span>
        </div>
        <div class="cw-gitem" data-cats="outdoor">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-outdoor.jpg'); ?>" alt="Outdoor wine cabinet on high-rise balcony">
          <span class="cw-gcat">Weather-Resistant Series</span>
          <span class="cw-gtitle">Balcony Unit — high-rise apartment install</span>
        </div>
        <div class="cw-gitem" data-cats="outdoor">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-outdoor.jpg'); ?>" alt="Covered outdoor install">
          <span class="cw-gcat">Weather-Resistant Series</span>
          <span class="cw-gtitle">Semi-Outdoor Install — covered entertaining area</span>
        </div>
        <div class="cw-gitem" data-cats="outdoor">
          <img class="cw-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Outdoor panel unit">
          <span class="cw-gcat">Weather-Resistant Series</span>
          <span class="cw-gtitle">Outdoor Panel Unit — plug-and-play rated</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cw-sec cw-sec--alt">
  <div class="cw-wrap">
    <p class="cw-eyebrow is-center">Specifications</p>
    <h2 style="text-align:center;font-size:var(--fs-h1);letter-spacing:-0.01em;margin-bottom:3.5rem;">What every unit includes</h2>
    <div class="cw-specs-grid">
      <div class="cw-spec-card"><span class="val">±0.5°C</span><h4>Temperature precision</h4><p>Digital compressor with continuous monitoring. Set point held regardless of ambient conditions.</p></div>
      <div class="cw-spec-card"><span class="val">60–70%</span><h4>Humidity managed</h4><p>Active humidity control across the full range. Corks stay sealed; labels stay intact.</p></div>
      <div class="cw-spec-card"><span class="val">10A</span><h4>Standard power</h4><p>Standard 10A or 15A power point. No dedicated circuit, no electrician in most installs.</p></div>
      <div class="cw-spec-card"><span class="val">2,000+</span><h4>Bottle capacity</h4><p>Units from 200 to 2,000+ bottles. Panel series supports modular expansion.</p></div>
    </div>
  </div>
</section>

<section class="cw-sec cw-cta">
  <div class="cw-wrap" style="text-align:center;">
    <p class="cw-eyebrow is-center">Next Step</p>
    <h2>Find the right unit for your space</h2>
    <p>Tell us your bottle count, floor space, and installation context. We'll confirm the right series and configuration.</p>
    <div class="cw-cta-actions">
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Get Specifications</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
