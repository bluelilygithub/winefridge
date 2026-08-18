<?php get_header(); ?>

<?php
$intro = cw_get_page_by_slug( 'installations-intro' );
if ( $intro ) {
	cw_render_page_hero_from_post( $intro );
	cw_render_page_content_sections( $intro->post_content );
} else {
	cw_render_plate_hero( [
		'title'    => 'Installations',
		'subtitle' => 'Real installs in apartments, houses, garages, and balconies — filter by your situation.',
		'center'   => true,
	] );
	?>
	<section class="cw-sec">
	  <div class="cw-wrap">
	    <?php
	    $query = cw_query_installations();
	    get_template_part( 'template-parts/installations-grid', null, [ 'query' => $query ] );
	    ?>
	  </div>
	</section>
	<?php
}
?>

<?php get_footer(); ?>
