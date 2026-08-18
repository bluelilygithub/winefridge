<?php
/**
 * Situation → recommended product (from Products CPT).
 */
$guides = cw_get_fit_guides();
?>
<div class="cw-fit-guide">
  <h2 class="cw-fit-guide-title">Which configuration fits your space?</h2>
  <p class="cw-fit-guide-intro">Start with your situation — not our internal series names. Each links to the full specification, or <a href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">get a fixed quote</a> and we'll confirm.</p>
  <?php if ( empty( $guides ) ) : ?>
    <p class="cw-empty">Add products in WP Admin and tick suitable situations to populate this guide.</p>
  <?php else : ?>
  <ul class="cw-fit-guide-list">
    <?php foreach ( $guides as $row ) : ?>
      <li class="cw-fit-guide-item">
        <p class="cw-fit-guide-situation"><?php echo esc_html( $row['situation'] ); ?></p>
        <a class="cw-fit-guide-rec" href="<?php echo esc_url( $row['url'] ); ?>">
          <span class="cw-fit-guide-rec-title"><?php echo esc_html( $row['recommend'] ); ?></span>
          <span class="cw-fit-guide-rec-arrow">&rarr;</span>
        </a>
        <?php if ( ! empty( $row['note'] ) ) : ?>
          <p class="cw-fit-guide-note"><?php echo esc_html( $row['note'] ); ?></p>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</div>
