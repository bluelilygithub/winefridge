<?php get_header(); ?>

<!-- PAGE HERO -->
<section class="cu-page-hero">
  <img class="cu-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cu-eyebrow is-center">The Range</p>
  <h1>Three series. Every installation.</h1>
  <p class="cu-page-hero-sub">Glass pods, insulated panel units, and outdoor-rated cabinets — all sharing the same precision climate system.</p>
</section>

<!-- FILTER + GALLERY -->
<section class="cu-sec">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);">

    <div class="cu-filter cu-gallery" style="display:block;">
      <div class="cu-filter" style="margin-bottom:3rem;">
        <button class="cu-chip is-active" data-filter="*">All</button>
        <button class="cu-chip" data-filter="glass">Panoramic Glass</button>
        <button class="cu-chip" data-filter="panel">Insulated Panel</button>
        <button class="cu-chip" data-filter="outdoor">Weather-Resistant</button>
      </div>

      <div class="cu-grid cu-gallery">

        <!-- Panoramic Glass Series -->
        <div class="cu-gitem" data-cats="glass">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-pod.jpg'); ?>" alt="Panoramic glass wine pod — freestanding">
          <span class="cu-gcat">Panoramic Glass Series</span>
          <span class="cu-gtitle">Freestanding Glass Pod — up to 400 bottles</span>
        </div>
        <div class="cu-gitem" data-cats="glass">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-niche.jpg'); ?>" alt="Glass sliding door unit in apartment niche">
          <span class="cu-gcat">Panoramic Glass Series</span>
          <span class="cu-gtitle">Niche-Fit Glass Unit — apartment install</span>
        </div>
        <div class="cu-gitem" data-cats="glass">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-cellar.jpg'); ?>" alt="High-capacity glass cellar">
          <span class="cu-gcat">Panoramic Glass Series</span>
          <span class="cu-gtitle">High-Capacity Glass Room — 800+ bottles</span>
        </div>

        <!-- Insulated Panel Series -->
        <div class="cu-gitem" data-cats="panel">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Insulated panel walk-in unit">
          <span class="cu-gcat">Insulated Panel Series</span>
          <span class="cu-gtitle">Panel Walk-In — 1,200 bottle capacity</span>
        </div>
        <div class="cu-gitem" data-cats="panel">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-glass-niche.jpg'); ?>" alt="Built-in panel unit in alcove">
          <span class="cu-gcat">Insulated Panel Series</span>
          <span class="cu-gtitle">Alcove Built-In — retrofit install</span>
        </div>
        <div class="cu-gitem" data-cats="panel">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Panel series large format unit">
          <span class="cu-gcat">Insulated Panel Series</span>
          <span class="cu-gtitle">Double-Bay Panel Unit — commercial capacity</span>
        </div>

        <!-- Weather-Resistant Series -->
        <div class="cu-gitem" data-cats="outdoor">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-outdoor.jpg'); ?>" alt="Outdoor rated wine cabinet on high-rise balcony">
          <span class="cu-gcat">Weather-Resistant Series</span>
          <span class="cu-gtitle">Balcony Unit — high-rise apartment, Brisbane</span>
        </div>
        <div class="cu-gitem" data-cats="outdoor">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-outdoor.jpg'); ?>" alt="Covered outdoor wine cabinet">
          <span class="cu-gcat">Weather-Resistant Series</span>
          <span class="cu-gtitle">Semi-Outdoor Install — covered entertaining area</span>
        </div>
        <div class="cu-gitem" data-cats="outdoor">
          <img class="cu-gimg" src="<?php echo get_theme_file_uri('assets/images/product-panel-walkin.jpg'); ?>" alt="Outdoor panel unit">
          <span class="cu-gcat">Weather-Resistant Series</span>
          <span class="cu-gtitle">Outdoor Panel Unit — plug-and-play rated</span>
        </div>

      </div>
    </div>

  </div>
</section>

<!-- SERIES SPECS -->
<section class="cu-sec cu-sec--alt">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);">
    <div class="cu-head is-center" style="margin-bottom:3.5rem;">
      <p class="cu-eyebrow is-center">Specifications</p>
      <h2>What every unit includes</h2>
    </div>
    <div class="cu-specs-grid">
      <div class="cu-spec-item">
        <div class="cu-spec-icon">±0.5°C</div>
        <h4>Temperature precision</h4>
        <p>Digital compressor system with continuous monitoring. Set point held within ±0.5°C regardless of ambient conditions.</p>
      </div>
      <div class="cu-spec-item">
        <div class="cu-spec-icon">60–70%</div>
        <h4>Humidity managed</h4>
        <p>Active humidity control across the full operating range. Corks stay sealed; labels stay intact over long-term storage.</p>
      </div>
      <div class="cu-spec-item">
        <div class="cu-spec-icon">10A</div>
        <h4>Standard power</h4>
        <p>Connects to a standard 10A or 15A power point. No dedicated circuit, no electrician required in most installations.</p>
      </div>
      <div class="cu-spec-item">
        <div class="cu-spec-icon">2,000+</div>
        <h4>Bottle capacity</h4>
        <p>Units from 200 to 2,000+ bottles. Panel series supports modular expansion without additional footprint.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cu-sec cu-cta">
  <div style="max-width:1240px;margin:0 auto;padding:0 var(--gutter);text-align:center;">
    <p class="cu-eyebrow is-center">Next Step</p>
    <h2>Find the right unit for your space</h2>
    <p>Tell us your bottle count, available floor space, and installation context. We'll confirm the right series and configuration.</p>
    <div class="cu-cta-actions">
      <a class="cu-btn cu-btn--light" href="<?php echo home_url('/enquire/'); ?>">Get Specifications</a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
