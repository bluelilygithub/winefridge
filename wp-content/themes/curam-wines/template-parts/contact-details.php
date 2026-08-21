<?php
/**
 * Contact details block for the Contact page.
 * Shortcode: [cw_contact_details]
 */
$phone = cw_get_org_phone();
$email = cw_get_org_email();
?>
<div class="cw-contact-details">
  <p class="cw-contact-details-lead">Prefer to talk or email? Reach us directly — or send a quick message with the form below.</p>
  <ul class="cw-contact-details-list">
    <?php if ( $phone ) : ?>
      <li>
        <span class="cw-contact-details-label">Phone</span>
        <a href="tel:<?php echo esc_attr( cw_get_org_phone_tel() ); ?>"<?php echo cw_gtm_phone_attrs( 'contact_page', 'gtm-phone-contact' ); ?>><?php echo esc_html( $phone ); ?></a>
      </li>
    <?php endif; ?>
    <?php if ( $email ) : ?>
      <li>
        <span class="cw-contact-details-label">Email</span>
        <a href="mailto:<?php echo esc_attr( $email ); ?>"<?php echo cw_gtm_email_attrs( 'contact_page', 'gtm-email-contact' ); ?>><?php echo esc_html( $email ); ?></a>
      </li>
    <?php endif; ?>
  </ul>
  <p class="cw-contact-details-note">Need a price for a cabinet? <a href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>"<?php echo cw_gtm_quote_attrs( 'contact_page', 'gtm-quote-contact' ); ?>>Get a quote</a> — that form collects the details we need for a quote.</p>
</div>
