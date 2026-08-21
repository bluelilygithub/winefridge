<?php
$compact = ! empty( $args['compact'] );
$items   = function_exists( 'cw_get_trust_items' ) ? cw_get_trust_items() : [];
?>
<div class="cw-trust-strip<?php echo $compact ? ' is-compact' : ''; ?>">
  <ul class="cw-trust-strip-list">
    <?php foreach ( $items as $item ) : ?>
      <li><?php if ( $item['strong'] !== '' ) : ?><strong><?php echo esc_html( $item['strong'] ); ?></strong><?php endif; ?><?php echo $item['rest'] !== '' ? ' ' . esc_html( $item['rest'] ) : ''; ?></li>
    <?php endforeach; ?>
  </ul>
</div>
