<?php cw_close_main(); ?>
<?php get_template_part( 'template-parts/gallery-lightbox' ); ?>
<footer class="cw-footer" role="contentinfo">
  <div class="cw-footer-inner">
    <div class="cw-footer-top">
      <div>
        <a class="cw-footer-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <img class="cw-footer-crest" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/crest-white.png' ) ); ?>" alt="" width="44" height="44" decoding="async">
          <span class="cw-brand-text">
            <span class="b1">Walk-In Wine Cabinets</span>
            <span class="b2">Australia</span>
          </span>
        </a>
        <p class="cw-footer-blurb">Precision climate-controlled wine units — freestanding, built-in, and outdoor-rated. Delivered and installed across Australia.</p>
      </div>
      <nav aria-label="Product series">
        <h5>The Range</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/products/?series=glass' ) ); ?>">Panoramic Glass Series</a></li>
          <li><a href="<?php echo esc_url( home_url( '/products/?series=panel' ) ); ?>">Insulated Panel Series</a></li>
          <li><a href="<?php echo esc_url( home_url( '/products/?series=outdoor' ) ); ?>">Weather-Resistant Series</a></li>
          <li><a href="<?php echo esc_url( get_post_type_archive_link( 'rack' ) ?: home_url( '/racking/' ) ); ?>">Racking styles</a></li>
        </ul>
      </nav>
      <nav aria-label="Company">
        <h5>Company</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
          <li><a href="<?php echo esc_url( home_url( '/installations/' ) ); ?>">Installations</a></li>
          <li><a href="<?php echo esc_url( get_post_type_archive_link( 'rack' ) ?: home_url( '/racking/' ) ); ?>">Racking</a></li>
          <li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>">Gallery</a></li>
          <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>">FAQ</a></li>
          <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
        </ul>
      </nav>
      <nav aria-label="Contact">
        <h5>Contact</h5>
        <ul>
          <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
          <li><a href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>"<?php echo cw_gtm_quote_attrs( 'footer', 'gtm-quote-footer' ); ?>>Get a quote</a></li>
          <?php if ( cw_get_org_phone() ) : ?>
            <li><a href="tel:<?php echo esc_attr( cw_get_org_phone_tel() ); ?>" aria-label="<?php echo esc_attr( 'Call ' . cw_get_org_phone() ); ?>"<?php echo cw_gtm_phone_attrs( 'footer', 'gtm-phone-footer' ); ?>><?php echo esc_html( cw_get_org_phone() ); ?></a></li>
          <?php endif; ?>
          <?php if ( cw_get_org_email() ) : ?>
            <li><a href="mailto:<?php echo esc_attr( cw_get_org_email() ); ?>"<?php echo cw_gtm_email_attrs( 'footer', 'gtm-email-footer' ); ?>><?php echo esc_html( cw_get_org_email() ); ?></a></li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
    <div class="cw-footer-bottom">
      <span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Walk-In Wine Cabinets Australia</span>
      <span>Engineered &amp; installed in Australia</span>
    </div>
  </div>
</footer>
<?php get_template_part( 'template-parts/mobile-cta-bar' ); ?>
<?php wp_footer(); ?>
</body>
</html>
