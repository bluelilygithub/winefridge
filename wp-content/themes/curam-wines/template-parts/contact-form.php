<?php
/**
 * General contact form — less formal than the quote enquiry form.
 * Shortcode: [cw_contact_form]
 */
$status = isset( $_GET['contact'] ) ? sanitize_key( $_GET['contact'] ) : '';
?>
<div class="cw-enquiry-form-wrap cw-contact-form-wrap">
  <?php if ( $status === 'error' ) : ?>
    <div class="cw-alert cw-alert--err" role="alert">Something went wrong. Please check your details and try again<?php if ( cw_get_org_phone() ) : ?>, or call us on <?php echo esc_html( cw_get_org_phone() ); ?><?php endif; ?>.</div>
  <?php endif; ?>

  <form class="cw-form" id="cw-contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate data-gtm-event="form_view" data-gtm-location="contact_page">
    <input type="hidden" name="action" value="cw_contact">
    <?php wp_nonce_field( 'cw_contact', 'cw_contact_nonce' ); ?>
    <div class="cw-hp-field" aria-hidden="true"><label for="cw_contact_website" class="cw-sr-only">Leave blank</label><input type="text" name="cw_website" id="cw_contact_website" tabindex="-1" autocomplete="off"></div>

    <div class="cw-form-section">
      <h3 class="cw-form-section-title">Send us a message</h3>

      <div class="cw-field-row">
        <div class="cw-field">
          <label for="cw-contact-name">Name <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
          <input type="text" name="name" id="cw-contact-name" placeholder="Jane Smith" required autocomplete="name">
        </div>
        <div class="cw-field">
          <label for="cw-contact-email">Email <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
          <input type="email" name="email" id="cw-contact-email" placeholder="jane@email.com" required autocomplete="email">
        </div>
      </div>

      <div class="cw-field-row">
        <div class="cw-field">
          <label for="cw-contact-phone">Phone <span class="cw-optional">(optional)</span></label>
          <input type="tel" name="phone" id="cw-contact-phone" placeholder="0400 000 000" autocomplete="tel">
        </div>
        <div class="cw-field">
          <label for="cw-contact-topic">What is this about?</label>
          <select name="topic" id="cw-contact-topic">
            <option value="">Select a topic</option>
            <option value="General question">General question</option>
            <option value="Existing quote or order">Existing quote or order</option>
            <option value="Service or support">Service or support</option>
            <option value="Press or partnership">Press or partnership</option>
            <option value="Something else">Something else</option>
          </select>
        </div>
      </div>

      <div class="cw-field">
        <label for="cw-contact-message">Your message <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
        <textarea name="message" id="cw-contact-message" rows="5" placeholder="Tell us how we can help…" required></textarea>
      </div>
    </div>

    <button type="submit" class="cw-btn">Send message</button>
    <p class="cw-note">We usually reply within one business day. Looking for a quote? <a href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Use the quote form</a>.</p>
  </form>
</div>
