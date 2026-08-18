<?php
$compact = ! empty( $args['compact'] );
$from_price = cw_get_lowest_product_price_label();
?>
<div class="cw-trust-strip<?php echo $compact ? ' is-compact' : ''; ?>">
  <ul class="cw-trust-strip-list">
    <?php if ( $from_price ) : ?>
      <li><strong><?php echo esc_html( $from_price ); ?></strong></li>
    <?php else : ?>
      <li><strong>From $5,400 installed</strong></li>
    <?php endif; ?>
    <li><strong>2-year</strong> parts &amp; labour warranty</li>
    <li><strong>Australia-wide</strong> delivery</li>
    <li><strong>6–9 weeks</strong> door to door</li>
    <li><strong>Australian-made</strong> since 2011</li>
  </ul>
</div>
