<?php
/**
 * Shortcodes for editable page content + dynamic product/installation data.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_shortcode_buffer( $callback ) {
	ob_start();
	call_user_func( $callback );
	return ob_get_clean();
}

add_shortcode( 'cw_trust_strip', function ( $atts ) {
	$atts = shortcode_atts( [ 'compact' => '' ], $atts, 'cw_trust_strip' );
	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/trust-strip', null, [
			'compact' => $atts['compact'] === '1' || $atts['compact'] === 'true',
		] );
	} );
} );

add_shortcode( 'cw_fit_guide', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/fit-guide' );
	} );
} );

add_shortcode( 'cw_product_grid', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/product-grid' );
	} );
} );

add_shortcode( 'cw_product_compare', function () {
	$rows = cw_get_product_compare_rows();
	if ( empty( $rows ) ) {
		return '';
	}
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/product-compare' );
	} );
} );

add_shortcode( 'cw_series_cards', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/series-cards' );
	} );
} );

add_shortcode( 'cw_shared_specs', function ( $atts ) {
	$atts = shortcode_atts( [
		'fields'  => '',
		'context' => 'shared',
	], $atts, 'cw_shared_specs' );

	$keys = array_filter( array_map( 'trim', explode( ',', $atts['fields'] ) ) );

	if ( $atts['context'] === 'engineering' && empty( $keys ) ) {
		$keys = [ 'temp_range', 'temp_accuracy', 'humidity', 'power', 'noise', 'refrigerant' ];
	}

	if ( $atts['context'] === 'shared' && empty( $keys ) ) {
		$keys = [ 'temp_range', 'temp_accuracy', 'humidity', 'power' ];
	}

	$specs = cw_get_spec_rows( $keys );
	if ( empty( $specs ) && $atts['context'] !== 'engineering' ) {
		return '';
	}

	if ( $atts['context'] === 'engineering' ) {
		$specs[] = [ 'label' => 'Recovery after door open (30s)', 'value' => 'Under 90 seconds' ];
		$specs[] = [ 'label' => 'Cooling type',                'value' => 'Compressor-based (not thermoelectric)' ];
	}

	if ( empty( $specs ) ) {
		return '';
	}

	return cw_shortcode_buffer( function () use ( $specs ) {
		get_template_part( 'template-parts/product-specs', null, [ 'specs' => $specs ] );
	} );
} );

add_shortcode( 'cw_installations_grid', function () {
	return cw_shortcode_buffer( function () {
		$query = cw_query_installations();
		get_template_part( 'template-parts/installations-grid', null, [ 'query' => $query ] );
	} );
} );

add_shortcode( 'cw_gallery_grid', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/gallery-grid' );
	} );
} );

add_shortcode( 'cw_top_faq', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/top-faq' );
	} );
} );

add_shortcode( 'cw_video_block', function ( $atts ) {
	$atts = shortcode_atts( [
		'title'   => 'See a cabinet installed',
		'intro'   => '',
		'caption' => 'A finished unit positioned, connected, and commissioned — no building work on site.',
	], $atts, 'cw_video_block' );

	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/video-block', null, $atts );
	} );
} );

add_shortcode( 'cw_enquiry_form', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/enquiry-form' );
	} );
} );

add_shortcode( 'cw_home_hero', function ( $atts ) {
	$atts = shortcode_atts( [
		'eyebrow' => 'Walk-In Wine Cabinets Australia',
	], $atts, 'cw_home_hero' );

	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/home-hero', null, $atts );
	} );
} );

add_shortcode( 'cw_featured_installation', function ( $atts ) {
	$atts = shortcode_atts( [ 'count' => '1' ], $atts, 'cw_featured_installation' );

	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/featured-installation', null, [
			'count' => (int) $atts['count'],
		] );
	} );
} );

add_shortcode( 'cw_process_steps', function ( $atts ) {
	$atts = shortcode_atts( [
		'heading' => 'What is a walk-in wine cellar?',
		'intro'   => 'A climate-controlled walk-in room, purpose-built to cellar wine long-term at a steady 12–14°C. The fit-out is yours to set — maximise bottle capacity, put the collection on display, or balance both.',
		'cta'     => '',
	], $atts, 'cw_process_steps' );

	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/process-steps', null, $atts );
	} );
} );

add_shortcode( 'cw_racking_styles', function () {
	$query = cw_query_racks();
	if ( ! $query->have_posts() ) {
		return '';
	}

	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/racking-styles' );
	} );
} );

add_shortcode( 'cw_min_price', function () {
	$query = cw_query_products();
	$min   = PHP_INT_MAX;
	$label = '';

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$price = get_post_meta( get_the_ID(), '_prod_price', true );
			$amt   = cw_parse_price_amount( $price );
			if ( $amt < $min ) {
				$min   = $amt;
				$label = $price;
			}
		}
		wp_reset_postdata();
	}

	return esc_html( $label ?: 'From $5,400 installed' );
} );
