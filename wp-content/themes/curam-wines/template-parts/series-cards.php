<?php
/**
 * Series overview cards — prices/images from Products CPT.
 */
$cards = cw_get_series_cards_data();
if ( empty( $cards ) ) {
	echo '<p class="cw-empty">Publish products with a series assigned to show series cards.</p>';
	return;
}
?>
<div class="cw-series-cards">
  <?php foreach ( $cards as $card ) : ?>
    <a class="cw-series-card" href="<?php echo esc_url( $card['filter'] ); ?>">
      <img class="cw-series-card-img" src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['label'] ); ?>">
      <div class="cw-series-card-body">
        <?php if ( ! empty( $card['price'] ) ) : ?>
          <span class="cw-series-card-price"><?php echo esc_html( $card['price'] ); ?></span>
        <?php endif; ?>
        <h3><?php echo esc_html( $card['label'] ); ?></h3>
        <?php if ( ! empty( $card['copy'] ) ) : ?>
          <p><?php echo esc_html( $card['copy'] ); ?></p>
        <?php endif; ?>
        <span class="cw-link">View configurations <span>&rarr;</span></span>
      </div>
    </a>
  <?php endforeach; ?>
</div>
