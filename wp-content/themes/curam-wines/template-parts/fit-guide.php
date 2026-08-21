<?php
/**
 * Situation → recommended product (from Products CPT).
 */
$guides   = cw_get_fit_guides();
$defaults = function_exists( 'cw_default_site_copy' ) ? cw_default_site_copy() : [];
$heading  = function_exists( 'cw_get_site_copy_setting' )
	? cw_get_site_copy_setting( 'fit_guide_heading', $defaults['fit_guide_heading'] ?? 'Which configuration fits your space?' )
	: 'Which configuration fits your space?';
$intro    = function_exists( 'cw_get_site_copy_setting' )
	? cw_get_site_copy_setting( 'fit_guide_intro', $defaults['fit_guide_intro'] ?? '' )
	: '';
$enquire  = home_url( '/enquire/' );
$intro_html = $intro !== '' ? preg_replace(
	'/get a quote/i',
	'<a href="' . esc_url( $enquire ) . '">$0</a>',
	esc_html( $intro )
) : '';
?>
<div class="cw-fit-guide">
  <?php if ( $heading ) : ?>
  <h2 class="cw-fit-guide-title"><?php echo esc_html( $heading ); ?></h2>
  <?php endif; ?>
  <?php if ( $intro_html ) : ?>
  <p class="cw-fit-guide-intro"><?php echo wp_kses_post( $intro_html ); ?></p>
  <?php endif; ?>
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
