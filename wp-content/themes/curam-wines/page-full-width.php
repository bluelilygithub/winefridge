<?php
/**
 * Template Name: Full width (shortcodes)
 * Description: No prose wrapper — for pages built from shortcodes and cw-sec sections in the editor.
 */
get_header();

while ( have_posts() ) :
	the_post();
	cw_render_plate_hero( [ 'center' => true ] );
	?>
	<div class="cw-page-sections">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;

get_footer();
