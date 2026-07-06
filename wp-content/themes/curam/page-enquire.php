<?php
get_header();
$status = isset($_GET['enquiry']) ? sanitize_key($_GET['enquiry']) : '';
?>

<section class="cu-page-hero">
  <img class="cu-page-hero-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="">
  <p class="cu-eyebrow is-center">Get in Touch</p>
  <h1>Enquiries</h1>
</section>

<section class="cu-sec">
  <div style="max-width:640px;margin:0 auto;padding:0 var(--gutter);">

    <?php if($status === 'sent'): ?>
      <div class="cu-alert cu-alert--ok">Thank you — we'll be in touch within one business day.</div>
    <?php elseif($status === 'error'): ?>
      <div class="cu-alert cu-alert--err">Something went wrong. Please check your details and try again.</div>
    <?php endif; ?>

    <form class="cu-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
      <input type="hidden" name="action" value="curam_enquiry">
      <?php wp_nonce_field('curam_enquiry','curam_enquiry_nonce'); ?>
      <div style="position:absolute;left:-9999px;"><input type="text" name="curam_website" tabindex="-1" autocomplete="off"></div>

      <div class="cu-field-row">
        <div class="cu-field">
          <label>Name <span class="req">*</span></label>
          <input type="text" name="name" placeholder="Jane Smith" required>
        </div>
        <div class="cu-field">
          <label>Phone</label>
          <input type="tel" name="phone" placeholder="0400 000 000">
        </div>
      </div>

      <div class="cu-field-row">
        <div class="cu-field">
          <label>Email <span class="req">*</span></label>
          <input type="email" name="email" placeholder="jane@email.com" required>
        </div>
        <div class="cu-field">
          <label>City</label>
          <input type="text" name="city" placeholder="Melbourne">
        </div>
      </div>

      <div class="cu-field">
        <label>Deadline</label>
        <input type="date" name="deadline">
      </div>

      <div class="cu-field">
        <label>Message</label>
        <textarea name="message" rows="5" placeholder="Tell us about your project…"></textarea>
      </div>

      <button type="submit" class="cu-btn" style="width:100%;margin-top:0.5rem;">Send Enquiry</button>
    </form>

  </div>
</section>

<?php get_footer(); ?>
