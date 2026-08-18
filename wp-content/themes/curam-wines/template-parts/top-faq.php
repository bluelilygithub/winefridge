<?php
/**
 * Top buyer questions — scannable FAQ strip.
 */
$faqs = [
	[
		'q' => 'Do I need a builder or renovation?',
		'a' => 'No. Every unit arrives as a finished, self-contained cabinet. Our team positions it, connects power, and commissions the climate controls.',
		'link' => home_url( '/faq/' ) . '#builder',
	],
	[
		'q' => 'What does installed price include?',
		'a' => 'The unit, delivery, and installation by our own team. Metro areas are covered as standard — we confirm regional costs upfront before you commit.',
		'link' => home_url( '/faq/' ),
	],
	[
		'q' => 'How long from order to working cabinet?',
		'a' => 'Usually six to nine weeks door to door. Build is four to eight weeks; metro install is typically booked within three weeks of completion.',
		'link' => home_url( '/about/' ),
	],
	[
		'q' => 'Will it fit my apartment / garage / balcony?',
		'a' => 'Tell us your bottle count and room dimensions — we confirm the right configuration before anything is manufactured. Flat-packed delivery is available for tight access.',
		'link' => home_url( '/products/' ),
	],
	[
		'q' => 'Where do you deliver?',
		'a' => 'Nationwide. Sydney, Melbourne, Brisbane, Perth, Adelaide, and Canberra are standard. Regional freight and install are quoted upfront.',
		'link' => home_url( '/faq/' ),
	],
];
?>
<div class="cw-top-faq">
  <h2 class="cw-section-title">Common questions</h2>
  <div class="cw-top-faq-grid">
    <?php foreach ( $faqs as $item ) : ?>
      <details class="cw-top-faq-item">
        <summary><?php echo esc_html( $item['q'] ); ?></summary>
        <p><?php echo esc_html( $item['a'] ); ?></p>
        <a class="cw-link" href="<?php echo esc_url( $item['link'] ); ?>">More detail <span>&rarr;</span></a>
      </details>
    <?php endforeach; ?>
  </div>
</div>
