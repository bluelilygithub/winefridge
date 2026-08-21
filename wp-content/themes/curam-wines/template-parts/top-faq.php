<?php
/**
 * Top buyer questions — from FAQs in the "Home page" category.
 */
$faqs = cw_get_home_faqs();
if ( empty( $faqs ) ) {
	return;
}
?>
<div class="cw-top-faq">
  <h2 class="cw-section-title">Common questions</h2>
  <div class="cw-top-faq-grid">
    <?php foreach ( $faqs as $faq ) : ?>
      <details class="cw-top-faq-item">
        <summary><?php echo esc_html( get_the_title( $faq ) ); ?></summary>
        <p><?php echo esc_html( wp_strip_all_tags( $faq->post_content ) ); ?></p>
        <a class="cw-link" href="<?php echo esc_url( home_url( '/faq/#faq-' . $faq->ID ) ); ?>">More detail <span>&rarr;</span></a>
      </details>
    <?php endforeach; ?>
  </div>
</div>
