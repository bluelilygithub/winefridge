<?php
/**
 * Contact page — always renders details + form (does not rely on empty editor content).
 * Template Name is not required; slug contact uses this file via page-contact.php.
 */
get_header();

while ( have_posts() ) :
	the_post();
	cw_render_plate_hero( [ 'center' => true ] );
	?>
	<div class="cw-page-sections">
		<section class="cw-sec" id="contact">
			<div class="cw-wrap cw-enquiry">
				<?php
				// Optional intro from the page editor (above the form).
				$extra = trim( (string) get_the_content( null, false ) );
				if ( $extra !== '' && ! has_shortcode( $extra, 'cw_contact_form' ) && ! has_shortcode( $extra, 'cw_contact_details' ) ) {
					echo '<div class="cw-contact-editor-intro">' . apply_filters( 'the_content', $extra ) . '</div>';
				}
				get_template_part( 'template-parts/contact-details' );
				get_template_part( 'template-parts/contact-form' );
				?>
			</div>
		</section>
	</div>
	<?php
endwhile;

get_footer();
