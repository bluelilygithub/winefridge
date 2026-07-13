<?php get_header(); ?>
<?php
/*
 * ---------------------------------------------------------------
 * REDESIGN — "The Catalogue" concept.
 * Deliberately breaks from the eyebrow/heading/CTA-band rhythm of
 * the previous homepage. Five distinct movements, each with its
 * own visual grammar, modelled loosely on a manufacturer's spec
 * catalogue rather than a software landing page.
 *
 * PLACEHOLDER DETAILS — replace before launch:
 *   Phone:            1300 924 671
 *   Email:            enquiries@walkinwinecabinets.com.au
 *   Founded year:     2011
 *   Site record #114: Brisbane penthouse install, Feb 2023
 *   All spec figures (temp range, accuracy, humidity, noise, etc.)
 *   All prices ($5,400 / $7,800)
 * ---------------------------------------------------------------
 */
$img_opening = content_url('/uploads/2026/07/wine-cabinet-living-room.jpg');
$img_climate  = content_url('/uploads/2026/07/wine-cabinet-interior.jpg');
$img_record   = get_theme_file_uri('assets/images/product-glass-niche.jpg');
?>


<!-- ============================================================
     MOVEMENT I — OPENING PLATE
     A photograph, not a hero. No headline, no fragment-pair,
     no button pair. A printed-label-style plaque sits over the
     bottom-left corner of a real installed cabinet.
     ============================================================ -->
<section class="cw-hp2-plate">
  <img class="cw-hp2-plate-img" src="<?php echo esc_url($img_opening); ?>" alt="Freestanding glass wine cabinet, fully stocked and lit, installed in an open-plan dining room">
  <div class="cw-hp2-plate-scrim"></div>

  <div class="cw-hp2-tag">
    <p class="cw-hp2-tag-label">Walk-In Wine Cabinets Australia — est. 2011</p>
    <p class="cw-hp2-tag-line">This one holds four hundred and twelve bottles in a Toorak dining room, at fourteen degrees, since March 2023.</p>
    <a class="cw-hp2-tag-link" href="<?php echo home_url('/products/'); ?>">See what's in the range <span>&rarr;</span></a>
  </div>
</section>


<!-- ============================================================
     MOVEMENT II — SPECIFICATION PLATE
     Ledger-table spec sheet. No icons, no stat chips, no big
     numbers in isolation. Framed like a page from a manual.
     ============================================================ -->
<section class="cw-hp2-spec" id="craft">
  <div class="cw-hp2-spec-inner">

    <figure class="cw-hp2-spec-fig">
      <img src="<?php echo esc_url($img_climate); ?>" alt="Interior of a wine cabinet showing the digital temperature display and timber racking">
      <figcaption>Plate — cooling unit and control head, ceiling-mounted</figcaption>
    </figure>

    <div class="cw-hp2-spec-body">
      <h2>The climate system</h2>
      <p>A bar fridge left open for ninety seconds on a hot afternoon can swing four degrees before it recovers. Repeated over a decade of storage, that's the kind of drift that turns a cellared Shiraz into a disappointing one. The compressor in every cabinet we build corrects within ninety seconds and doesn't overshoot on the way back down.</p>

      <table class="cw-hp2-ledger">
        <tbody>
          <tr><th>Temperature range</th><td>8&ndash;18&deg;C, user-set</td></tr>
          <tr><th>Accuracy</th><td>Holds within 0.5&deg;C</td></tr>
          <tr><th>Humidity</th><td>60&ndash;75% RH, actively managed</td></tr>
          <tr><th>Recovery after door open (30s)</th><td>Under 90 seconds</td></tr>
          <tr><th>Power</th><td>Standard 10A or 15A point</td></tr>
          <tr><th>Noise at 1 metre</th><td>34 dB(A) &mdash; quieter than a kitchen fridge</td></tr>
        </tbody>
      </table>
    </div>

  </div>
</section>


<!-- ============================================================
     MOVEMENT III — TWO READERS
     Typography only. No images — the page needs to breathe
     after the spec plate's photograph. Two prose columns,
     divided by a rule, like a spread in a trade catalogue.
     ============================================================ -->
<section class="cw-hp2-readers">
  <div class="cw-hp2-readers-inner">
    <div class="cw-hp2-reader">
      <h2>For the collection</h2>
      <p>If you're holding wine for ten years or more, the cabinet is what stands between a good bottle and a wasted one. Consistent temperature and controlled humidity protect the cork seal and the label — both matter if you ever plan to sell. We hold to the 12&ndash;14&deg;C range most serious collectors already cellar to, and rarely go above it.</p>
      <a class="cw-hp2-split-link" href="<?php echo home_url('/blog/'); ?>">On long-term storage and resale value <span>&rarr;</span></a>
    </div>
    <div class="cw-hp2-reader">
      <h2>For the room</h2>
      <p>Some of these are bought to be looked at as much as opened. The glass series is lit from inside and has no visible compressor housing — it sits as comfortably against a dining table as it would in a cellar.</p>
      <a class="cw-hp2-split-link" href="<?php echo home_url('/products/?series=glass'); ?>">See the Glass Series <span>&rarr;</span></a>
    </div>
  </div>
</section>


<!-- ============================================================
     MOVEMENT IV — SITE RECORD
     One install, told as prose. A small figure beside the text —
     not another full-bleed photograph. Same grammar as the
     spec plate, but the image is subordinate to the story.
     ============================================================ -->
<section class="cw-hp2-record">
  <div class="cw-hp2-record-inner">
    <figure class="cw-hp2-record-fig">
      <img src="<?php echo esc_url($img_record); ?>" alt="Glass wine cabinet built into an existing wall niche, Brisbane penthouse">
      <figcaption>Site record 114 &middot; Brisbane</figcaption>
    </figure>
    <div class="cw-hp2-record-body">
      <p class="cw-hp2-record-label">Installation note</p>
      <p>Built into an existing wall niche in a Brisbane penthouse, February 2023. Four hundred bottles, delivered pre-assembled and connected to the existing power point. The client's only request was that it run quietly enough to sit behind the dining table — measured at 33 decibels from a metre away, three years on.</p>
      <a class="cw-hp2-split-link" href="<?php echo home_url('/installations/'); ?>">More installations <span>&rarr;</span></a>
    </div>
  </div>
</section>


<!-- ============================================================
     MOVEMENT V — CLOSING PLATE
     The last page of the manual. Plain range summary, plain
     contact details, one modest button. No CTA band.
     ============================================================ -->
<section class="cw-hp2-close">
  <div class="cw-hp2-close-inner">

    <div class="cw-hp2-close-range">
      <h2>The range, briefly</h2>
      <p><strong>Panoramic Glass Series</strong> — from $5,400 installed. Freestanding, glass, lit from inside. 120&ndash;800 bottles.</p>
      <p><strong>Insulated Panel Series</strong> — from $7,800 installed. Built-in or freestanding, higher capacity. 200&ndash;1,200 bottles.</p>
      <a class="cw-hp2-split-link" href="<?php echo home_url('/products/'); ?>">Full specifications and pricing <span>&rarr;</span></a>
    </div>

    <div class="cw-hp2-close-contact">
      <h2>Get in touch</h2>
      <p>Call or email — we'll ask about the room, the collection, and when you need it in.</p>
      <div class="cw-hp2-close-details">
        <a href="tel:1300924671">1300 924 671</a>
        <a href="mailto:enquiries@walkinwinecabinets.com.au">enquiries@walkinwinecabinets.com.au</a>
      </div>
      <a class="cw-btn" href="<?php echo home_url('/enquire/'); ?>">Request a quote</a>
      <p class="cw-hp2-close-fine">Australia-wide delivery. Metro installs typically booked within three weeks.</p>
    </div>

  </div>
</section>


<?php get_footer(); ?>
