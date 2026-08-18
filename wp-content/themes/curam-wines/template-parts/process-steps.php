<?php
/**
 * Walk-in definition + seven-step process (list + illustration).
 *
 * @var array $args {
 *   @type string $heading Section heading.
 *   @type string $intro   Definition / supporting copy.
 *   @type string $cta     Optional CTA label under the steps.
 * }
 */
$heading = $args['heading'] ?? 'What is a walk-in wine cellar?';
$intro   = $args['intro'] ?? 'A climate-controlled walk-in room, purpose-built to cellar wine long-term at a steady 12–14°C. The fit-out is yours to set — maximise bottle capacity, put the collection on display, or balance both.';
$cta     = $args['cta'] ?? '';

$steps = [
	[
		'title' => 'Initial consultation',
		'body'  => 'Space, bottle count, access, and what matters most — capacity, display, or both.',
	],
	[
		'title' => 'Concept & design',
		'body'  => 'Layout options and visual direction shaped around your room and collection.',
	],
	[
		'title' => 'Site visit',
		'body'  => 'On-site measure and assessment. Available Australia-wide at cost.',
	],
	[
		'title' => 'Technical & engineering assessment',
		'body'  => 'Climate load, power, structure, and install path confirmed before manufacture.',
	],
	[
		'title' => 'Racking and colours',
		'body'  => 'Choose materials, finishes, and racking configuration for storage or display.',
	],
	[
		'title' => 'Product specification and quote',
		'body'  => 'Fixed scope and installed price — clear before you commit.',
	],
	[
		'title' => 'Supply and install',
		'body'  => 'Built, delivered, positioned, and commissioned by our team.',
	],
];

$illustration = get_theme_file_uri( 'assets/images/racking/process-illustration.jpg' );
foreach ( [ 'webp', 'png', 'jpeg', 'jpg' ] as $ext ) {
	$path = get_theme_file_path( "assets/images/racking/process-illustration.{$ext}" );
	if ( $path && file_exists( $path ) ) {
		$illustration = get_theme_file_uri( "assets/images/racking/process-illustration.{$ext}" );
		break;
	}
}
?>
<section class="cw-sec cw-sec--dark cw-process" aria-labelledby="cw-process-heading">
  <div class="cw-wrap">
    <h2 id="cw-process-heading" class="cw-section-title"><?php echo esc_html( $heading ); ?></h2>
    <p class="cw-section-intro cw-process-intro"><?php echo esc_html( $intro ); ?></p>

    <div class="cw-process-layout">
      <ol class="cw-timeline">
        <?php foreach ( $steps as $i => $step ) : ?>
          <li class="cw-timeline-step">
            <span class="n" aria-hidden="true"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
            <div>
              <h4><?php echo esc_html( $step['title'] ); ?></h4>
              <p><?php echo esc_html( $step['body'] ); ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

      <figure class="cw-process-figure">
        <img src="<?php echo esc_url( $illustration ); ?>" alt="Climate-controlled walk-in wine cellar with custom racking" loading="lazy" decoding="async">
        <figcaption>Purpose-built for long-term cellaring — climate first, fit-out to suit.</figcaption>
      </figure>
    </div>

    <div class="cw-section-cta cw-process-cta">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Start with a consultation</a>
      <a class="cw-link cw-link--on-dark" href="<?php echo esc_url( home_url( '/racking/' ) ); ?>">Browse racking styles <span>&rarr;</span></a>
    </div>
    <?php if ( $cta ) : ?>
      <p class="cw-process-note"><?php echo esc_html( $cta ); ?></p>
    <?php endif; ?>
  </div>
</section>
