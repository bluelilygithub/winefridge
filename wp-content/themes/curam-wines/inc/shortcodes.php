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
		foreach ( cw_get_engineering_extra_specs() as $row ) {
			$specs[] = $row;
		}
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

add_shortcode( 'cw_faq_list', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/faq-list' );
	} );
} );

add_shortcode( 'cw_top_faq', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/top-faq' );
	} );
} );

add_shortcode( 'cw_video_block', function ( $atts ) {
	$atts     = shortcode_atts( [
		'title'   => '',
		'intro'   => '',
		'caption' => '',
	], $atts, 'cw_video_block' );
	$defaults = cw_default_site_copy();

	if ( $atts['title'] === '' ) {
		$atts['title'] = cw_get_site_copy_setting( 'video_title', $defaults['video_title'] );
	}
	if ( $atts['intro'] === '' ) {
		$atts['intro'] = cw_get_site_copy_setting( 'video_intro', $defaults['video_intro'] );
	}
	if ( $atts['caption'] === '' ) {
		$atts['caption'] = cw_get_site_copy_setting( 'video_caption', $defaults['video_caption'] );
	}

	return cw_shortcode_buffer( function () use ( $atts ) {
		get_template_part( 'template-parts/video-block', null, $atts );
	} );
} );

add_shortcode( 'cw_enquiry_form', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/enquiry-form' );
	} );
} );

add_shortcode( 'cw_contact_form', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/contact-form' );
	} );
} );

add_shortcode( 'cw_contact_details', function () {
	return cw_shortcode_buffer( function () {
		get_template_part( 'template-parts/contact-details' );
	} );
} );

add_shortcode( 'cw_phone', function () {
	return esc_html( cw_get_org_phone() );
} );

add_shortcode( 'cw_email', function () {
	return esc_html( cw_get_org_email() );
} );

add_shortcode( 'cw_enquiry_contact', function () {
	$phone = cw_get_org_phone();
	$email = cw_get_org_email();
	if ( $phone === '' && $email === '' ) {
		return '';
	}

	ob_start();
	echo '<div class="cw-enquiry-contact">';
	if ( $phone !== '' ) {
		printf(
			'<a href="%s"%s>%s</a>',
			esc_url( 'tel:' . cw_get_org_phone_tel() ),
			cw_gtm_phone_attrs( 'enquire_sidebar', 'gtm-phone-enquire' ),
			esc_html( $phone )
		);
	}
	if ( $phone !== '' && $email !== '' ) {
		echo '<br>';
	}
	if ( $email !== '' ) {
		printf(
			'<a href="%s"%s>%s</a>',
			esc_url( 'mailto:' . $email ),
			cw_gtm_email_attrs( 'enquire_sidebar', 'gtm-email-enquire' ),
			esc_html( $email )
		);
	}
	echo '</div>';
	return ob_get_clean();
} );

add_shortcode( 'cw_home_hero', function ( $atts ) {
	$atts     = shortcode_atts( [
		'eyebrow' => '',
	], $atts, 'cw_home_hero' );
	$defaults = cw_default_site_copy();

	if ( $atts['eyebrow'] === '' ) {
		$atts['eyebrow'] = cw_get_site_copy_setting( 'hero_eyebrow', $defaults['hero_eyebrow'] );
	}

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
	$atts     = shortcode_atts( [
		'heading' => '',
		'intro'   => '',
		'cta'     => '',
	], $atts, 'cw_process_steps' );
	$defaults = cw_default_site_copy();

	if ( $atts['heading'] === '' ) {
		$atts['heading'] = cw_get_site_copy_setting( 'process_heading', $defaults['process_heading'] );
	}
	if ( $atts['intro'] === '' ) {
		$atts['intro'] = cw_get_site_copy_setting( 'process_intro', $defaults['process_intro'] );
	}

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

	$fallback = cw_get_site_copy_setting( 'min_price_fallback', 'From $5,400 installed' );

	return esc_html( $label ?: $fallback );
} );
