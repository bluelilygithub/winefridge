<?php
/**
 * Enquiry form — rendered via [cw_enquiry_form] on the Enquire page.
 */
$status          = isset( $_GET['enquiry'] ) ? sanitize_key( $_GET['enquiry'] ) : '';
$prefill_product = isset( $_GET['product'] ) ? sanitize_text_field( wp_unslash( $_GET['product'] ) ) : '';
$prefill_series  = isset( $_GET['series'] ) ? sanitize_text_field( wp_unslash( $_GET['series'] ) ) : '';
?>
<div class="cw-enquiry-form-wrap">
  <?php if ( $status === 'sent' ) : ?>
    <div class="cw-alert cw-alert--ok" role="status">Thank you — we'll be in touch within one business day with your fixed quote.</div>
  <?php elseif ( $status === 'error' ) : ?>
    <div class="cw-alert cw-alert--err" role="alert">Something went wrong. Please check your details and try again, or call us on <?php echo esc_html( cw_get_org_phone() ); ?>.</div>
  <?php endif; ?>

  <?php if ( $prefill_product ) : ?>
    <p class="cw-enquiry-prefill">Enquiring about: <strong><?php echo esc_html( $prefill_product ); ?></strong></p>
  <?php endif; ?>

  <form class="cw-form" id="cw-enquiry-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" novalidate>
    <input type="hidden" name="action" value="cw_enquiry">
    <input type="hidden" name="enquiry_mode" value="quick" id="cw-enquiry-mode">
    <?php wp_nonce_field( 'cw_enquiry', 'cw_enquiry_nonce' ); ?>
    <div class="cw-hp-field" aria-hidden="true"><label for="cw_website" class="cw-sr-only">Leave blank</label><input type="text" name="cw_website" id="cw_website" tabindex="-1" autocomplete="off"></div>

    <div class="cw-form-section">
      <h3 class="cw-form-section-title" id="cw-form-essentials">Essentials</h3>

      <div class="cw-field-row">
        <div class="cw-field">
          <label for="cw-enquiry-name">Name <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
          <input type="text" name="name" id="cw-enquiry-name" placeholder="Jane Smith" required autocomplete="name">
        </div>
        <div class="cw-field">
          <label for="cw-enquiry-phone">Phone <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
          <input type="tel" name="phone" id="cw-enquiry-phone" placeholder="0400 000 000" required autocomplete="tel">
        </div>
      </div>

      <div class="cw-field-row">
        <div class="cw-field">
          <label for="cw-enquiry-email">Email <span class="req" aria-hidden="true">*</span><span class="cw-sr-only"> (required)</span></label>
          <input type="email" name="email" id="cw-enquiry-email" placeholder="jane@email.com" required autocomplete="email">
        </div>
        <div class="cw-field">
          <label for="cw-enquiry-city">City / State</label>
          <input type="text" name="city" id="cw-enquiry-city" placeholder="Melbourne, VIC" autocomplete="address-level2">
        </div>
      </div>

      <div class="cw-field-row">
        <div class="cw-field">
          <label for="cw-enquiry-bottles">How many bottles?</label>
          <select name="bottle_capacity" id="cw-enquiry-bottles">
            <option value="">Select a range</option>
            <option value="Under 200 bottles">Under 200 bottles</option>
            <option value="200–500 bottles">200–500 bottles</option>
            <option value="500–1,000 bottles">500–1,000 bottles</option>
            <option value="1,000–2,000 bottles">1,000–2,000 bottles</option>
            <option value="2,000+ bottles">2,000+ bottles</option>
            <option value="Not sure yet">Not sure yet</option>
          </select>
        </div>
        <div class="cw-field">
          <label for="cw-enquiry-property">Where will it go?</label>
          <select name="property_type" id="cw-enquiry-property">
            <option value="">Select your situation</option>
            <?php foreach ( cw_get_situation_filters() as $slug => $label ) : ?>
              <option value="<?php echo esc_attr( $label ); ?>"><?php echo esc_html( $label ); ?></option>
            <?php endforeach; ?>
            <option value="Not sure yet">Not sure yet</option>
          </select>
        </div>
      </div>

      <div class="cw-field">
        <label for="cw-enquiry-message">Anything else we should know?</label>
        <textarea name="message" id="cw-enquiry-message" rows="4" placeholder="Room dimensions, doorway access, target install date, display vs bulk storage…"></textarea>
      </div>
    </div>

    <button type="submit" class="cw-btn">Get a fixed quote</button>
    <p class="cw-note">We respond within one business day. No spam, ever.</p>

    <button type="button" class="cw-form-toggle" id="cw-form-toggle" aria-expanded="false" aria-controls="cw-form-detail">
      Add more detail (optional)
    </button>

    <div class="cw-form-detail" id="cw-form-detail" hidden>
      <div class="cw-form-section">
        <h3 class="cw-form-section-title">Series &amp; dimensions</h3>

        <div class="cw-field-row">
          <div class="cw-field">
            <label for="cw-enquiry-series">Series interest</label>
            <select name="series" id="cw-enquiry-series">
              <option value="">Not sure yet</option>
              <option value="Panoramic Glass Series"<?php selected( $prefill_series, 'Panoramic Glass Series' ); ?>>Panoramic Glass Series</option>
              <option value="Insulated Panel Series"<?php selected( $prefill_series, 'Insulated Panel Series' ); ?>>Insulated Panel Series</option>
              <option value="Weather-Resistant Series"<?php selected( $prefill_series, 'Weather-Resistant Series' ); ?>>Weather-Resistant Series</option>
            </select>
          </div>
          <div class="cw-field">
            <label for="cw-enquiry-install">Installation type</label>
            <select name="installation_type" id="cw-enquiry-install">
              <option value="">Select</option>
              <option value="Freestanding">Freestanding</option>
              <option value="Built-in / niche">Built-in / niche</option>
              <option value="Garage / utility space">Garage / utility space</option>
              <option value="Outdoor / covered balcony">Outdoor / covered balcony</option>
              <option value="Commercial / hospitality">Commercial / hospitality</option>
            </select>
          </div>
        </div>

        <div class="cw-field-row">
          <div class="cw-field">
            <label for="cw-enquiry-width">Available width</label>
            <select name="width" id="cw-enquiry-width">
              <option value="">Not sure yet</option>
              <option value="Under 1.2 m">Under 1.2 m</option>
              <option value="1.2–1.8 m">1.2–1.8 m</option>
              <option value="1.8–2.4 m">1.8–2.4 m</option>
              <option value="2.4 m+">2.4 m+</option>
            </select>
          </div>
          <div class="cw-field">
            <label for="cw-enquiry-height">Available height</label>
            <select name="height" id="cw-enquiry-height">
              <option value="">Not sure yet</option>
              <option value="Under 2.0 m">Under 2.0 m</option>
              <option value="2.0–2.4 m">2.0–2.4 m</option>
              <option value="2.4–2.7 m">2.4–2.7 m</option>
              <option value="2.7 m+">2.7 m+</option>
            </select>
          </div>
        </div>

        <div class="cw-field-row">
          <div class="cw-field">
            <label for="cw-enquiry-depth">Available depth</label>
            <select name="depth" id="cw-enquiry-depth">
              <option value="">Not sure yet</option>
              <option value="Under 600 mm">Under 600 mm</option>
              <option value="600–800 mm">600–800 mm</option>
              <option value="800–1,000 mm">800–1,000 mm</option>
              <option value="1,000 mm+">1,000 mm+</option>
            </select>
          </div>
          <div class="cw-field">
            <label for="cw-enquiry-deadline">Target install date</label>
            <input type="date" name="deadline" id="cw-enquiry-deadline">
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
