<?php
get_header();
$status          = isset( $_GET['enquiry'] ) ? sanitize_key( $_GET['enquiry'] ) : '';
$prefill_product = isset( $_GET['product'] ) ? sanitize_text_field( wp_unslash( $_GET['product'] ) ) : '';
?>

<section class="cw-page-hero">
  <h1>Tell us about the space</h1>
  <p class="cw-page-hero-sub">Bottle count, room, and access — that's enough for us to confirm a series and a fixed price.</p>
</section>

<section class="cw-sec" id="enquire">
  <div class="cw-wrap">
    <div class="cw-enquiry">
      <div class="cw-enquiry-intro">
        <h2>What happens after you send this</h2>
        <p>We read it the same day and confirm the right series, the right size, and a fixed price — usually within one business day. There's no site visit required to get a quote, and no cost attached to asking.</p>
        <p>If you're not sure what you need yet, just describe the space. We'll work it out from there.</p>
        <div class="cw-enquiry-contact">
          <strong>Call us</strong><br>
          <a href="tel:1300924671">1300 924 671</a><br><br>
          <strong>Email</strong><br>
          <a href="mailto:enquiries@walkinwinecabinets.com.au">enquiries@walkinwinecabinets.com.au</a>
        </div>
      </div>
      <div>
        <?php if($status === 'sent'): ?>
          <div class="cw-alert cw-alert--ok">Thank you — we'll be in touch within one business day with specifications and pricing.</div>
        <?php elseif($status === 'error'): ?>
          <div class="cw-alert cw-alert--err">Something went wrong. Please check your details and try again.</div>
        <?php endif; ?>

        <?php if ( $prefill_product ) : ?>
          <p style="font-size:var(--fs-sm);color:var(--text-soft);padding:0.85rem 1rem;background:var(--ice);border:1px solid var(--line);margin-bottom:1.5rem;">Enquiring about: <strong style="color:var(--text);"><?php echo esc_html( $prefill_product ); ?></strong></p>
        <?php endif; ?>

        <form class="cw-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
          <input type="hidden" name="action" value="cw_enquiry">
          <?php wp_nonce_field('cw_enquiry','cw_enquiry_nonce'); ?>
          <div style="position:absolute;left:-9999px;"><input type="text" name="cw_website" tabindex="-1" autocomplete="off"></div>

          <div class="cw-field-row">
            <div class="cw-field">
              <label>Name <span class="req">*</span></label>
              <input type="text" name="name" placeholder="Jane Smith" required>
            </div>
            <div class="cw-field">
              <label>Phone</label>
              <input type="tel" name="phone" placeholder="0400 000 000">
            </div>
          </div>

          <div class="cw-field-row">
            <div class="cw-field">
              <label>Email <span class="req">*</span></label>
              <input type="email" name="email" placeholder="jane@email.com" required>
            </div>
            <div class="cw-field">
              <label>City / State</label>
              <input type="text" name="city" placeholder="Melbourne, VIC">
            </div>
          </div>

          <div class="cw-field-row">
            <div class="cw-field">
              <label>Approximate bottle count</label>
              <input type="text" name="bottles" placeholder="e.g. 200–400 bottles">
            </div>
            <div class="cw-field">
              <label>Model / series interest</label>
              <input type="text" name="series" value="<?php echo esc_attr( $prefill_product ); ?>" placeholder="Glass / Panel / Outdoor / Unsure">
            </div>
          </div>

          <div class="cw-field">
            <label>Tell us about your space</label>
            <textarea name="message" rows="5" placeholder="Installation type (apartment, house, balcony, commercial), available dimensions, any constraints…"></textarea>
          </div>

          <button type="submit" class="cw-btn">Request Specifications</button>
          <p class="cw-note">We respond within one business day. No spam, ever.</p>
        </form>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
