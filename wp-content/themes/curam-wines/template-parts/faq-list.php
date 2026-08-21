<?php
/**
 * Full FAQ page — questions grouped by FAQ category.
 */
$groups = cw_get_faq_groups();
if ( empty( $groups ) ) {
	echo '<p class="cw-empty">No questions yet. Add them under <strong>FAQs</strong> in WP Admin and assign a category.</p>';
	return;
}
?>
<div class="cw-faq">
  <?php foreach ( $groups as $group ) : ?>
    <div class="cw-faq-group">
      <h2 class="cw-faq-group-title"><?php echo esc_html( $group['term']->name ); ?></h2>
      <?php foreach ( $group['posts'] as $faq ) : ?>
        <details id="faq-<?php echo (int) $faq->ID; ?>">
          <summary><?php echo esc_html( get_the_title( $faq ) ); ?></summary>
          <div class="cw-faq-a">
            <?php echo wp_kses_post( wpautop( $faq->post_content ) ); ?>
          </div>
        </details>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>
