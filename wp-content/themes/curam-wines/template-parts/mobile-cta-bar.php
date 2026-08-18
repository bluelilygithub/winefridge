<?php
/**
 * Sticky phone + CTA bar — mobile only.
 */
$phone     = cw_get_org_phone();
$phone_tel = cw_get_org_phone_tel();
?>
<aside class="cw-mobile-cta" aria-label="Quick contact">
  <a class="cw-mobile-cta-phone" href="tel:<?php echo esc_attr( $phone_tel ); ?>" aria-label="<?php echo esc_attr( 'Call ' . $phone ); ?>">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" aria-hidden="true" focusable="false"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
    <span><?php echo esc_html( $phone ); ?></span>
  </a>
  <a class="cw-btn cw-mobile-cta-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Get a fixed quote</a>
</aside>
