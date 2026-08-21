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
$defaults = function_exists( 'cw_default_site_copy' ) ? cw_default_site_copy() : [];
$heading  = $args['heading'] ?? ( $defaults['process_heading'] ?? 'What is a walk-in wine cellar?' );
$intro    = $args['intro'] ?? ( $defaults['process_intro'] ?? '' );
$cta      = $args['cta'] ?? '';
$caption  = function_exists( 'cw_get_site_copy_setting' )
	? cw_get_site_copy_setting( 'process_caption', $defaults['process_caption'] ?? '' )
	: 'Purpose-built for long-term cellaring — climate first, fit-out to suit.';
$steps    = function_exists( 'cw_get_process_steps' ) ? cw_get_process_steps() : [];

$illustration = get_theme_file_uri( 'assets/images/racking/process-illustration.jpg' );
$image_id     = function_exists( 'cw_get_site_copy_setting' ) ? (int) cw_get_site_copy_setting( 'process_image_id', 0 ) : 0;
if ( $image_id ) {
	$from_media = wp_get_attachment_image_url( $image_id, 'full' );
	if ( $from_media ) {
		$illustration = $from_media;
	}
} else {
	foreach ( [ 'webp', 'png', 'jpeg', 'jpg' ] as $ext ) {
		$path = get_theme_file_path( "assets/images/racking/process-illustration.{$ext}" );
		if ( $path && file_exists( $path ) ) {
			$illustration = get_theme_file_uri( "assets/images/racking/process-illustration.{$ext}" );
			break;
		}
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
        <img src="<?php echo esc_url( $illustration ); ?>" alt="<?php echo esc_attr( $caption ?: 'Climate-controlled walk-in wine cellar with custom racking' ); ?>" loading="lazy" decoding="async">
        <?php if ( $caption ) : ?>
          <figcaption><?php echo esc_html( $caption ); ?></figcaption>
        <?php endif; ?>
      </figure>
    </div>

    <div class="cw-section-cta cw-process-cta">
      <a class="cw-btn" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>"<?php echo cw_gtm_quote_attrs( 'process', 'gtm-quote-process' ); ?>>Start with a consultation</a>
      <a class="cw-link cw-link--on-dark" href="<?php echo esc_url( home_url( '/racking/' ) ); ?>">Browse racking styles <span>&rarr;</span></a>
    </div>
    <?php if ( $cta ) : ?>
      <p class="cw-process-note"><?php echo esc_html( $cta ); ?></p>
    <?php endif; ?>
  </div>
</section>
