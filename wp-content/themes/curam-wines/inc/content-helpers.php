<?php
/**
 * Queries and data assembly for editable pages + shortcodes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_query_products( $args = [] ) {
	$defaults = [
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_key'       => '_prod_series',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	];

	return new WP_Query( array_merge( $defaults, $args ) );
}

function cw_get_product_card_image( $post_id ) {
	$img_meta = get_post_meta( $post_id, '_prod_img', true );

	if ( has_post_thumbnail( $post_id ) ) {
		return [
			'url' => get_the_post_thumbnail_url( $post_id, 'large' ),
			'alt' => get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ),
		];
	}

	if ( $img_meta ) {
		return [
			'url' => get_theme_file_uri( 'assets/images/' . $img_meta ),
			'alt' => get_the_title( $post_id ),
		];
	}

	return [
		'url' => get_theme_file_uri( 'assets/images/product-glass-pod.jpg' ),
		'alt' => get_the_title( $post_id ),
	];
}

function cw_parse_price_amount( $price ) {
	if ( preg_match( '/[\d,]+/', (string) $price, $match ) ) {
		return (int) str_replace( ',', '', $match[0] );
	}

	return PHP_INT_MAX;
}

function cw_get_product_compare_rows() {
	$query = cw_query_products();
	$rows  = [];

	if ( ! $query->have_posts() ) {
		return $rows;
	}

	while ( $query->have_posts() ) {
		$query->the_post();
		$post_id = get_the_ID();
		$series  = get_post_meta( $post_id, '_prod_series', true );

		$rows[] = [
			'title'    => get_the_title(),
			'series'   => cw_get_product_series_label( $series ),
			'capacity' => get_post_meta( $post_id, '_prod_capacity', true ),
			'dims'     => get_post_meta( $post_id, '_prod_dimensions', true ),
			'install'  => get_post_meta( $post_id, '_prod_install', true ),
			'price'    => get_post_meta( $post_id, '_prod_price', true ),
			'url'      => get_permalink(),
		];
	}

	wp_reset_postdata();

	return $rows;
}

function cw_get_fit_guide_headlines() {
	return [
		'apartment'  => 'Apartment or tight access',
		'house'      => 'Living or dining room',
		'commercial' => 'Large collection or hospitality',
		'garage'     => 'Garage or utility space',
		'outdoor'    => 'Covered balcony or courtyard',
	];
}

function cw_get_recommended_product_for_situation( $situation_slug ) {
	$query = cw_query_products();

	if ( ! $query->have_posts() ) {
		return null;
	}

	while ( $query->have_posts() ) {
		$query->the_post();
		$situations = cw_get_product_situations( get_the_ID() );
		if ( in_array( $situation_slug, $situations, true ) ) {
			$post = get_post();
			wp_reset_postdata();
			return $post;
		}
	}

	wp_reset_postdata();

	return null;
}

function cw_get_series_cards_data() {
	$labels   = cw_get_series_labels();
	$fallback = [
		'glass'   => get_theme_file_uri( 'assets/images/product-glass-pod.jpg' ),
		'panel'   => get_theme_file_uri( 'assets/images/product-panel-walkin.jpg' ),
		'outdoor' => get_theme_file_uri( 'assets/images/product-panel-outdoor.jpg' ),
	];
	$cards = [];

	foreach ( array_keys( $labels ) as $series ) {
		$query = cw_query_products( [
			'meta_query' => [
				[
					'key'   => '_prod_series',
					'value' => $series,
				],
			],
		] );

		if ( ! $query->have_posts() ) {
			continue;
		}

		$cheapest     = null;
		$cheapest_amt = PHP_INT_MAX;
		$first        = null;
		$capacities   = [];

		$count    = 0;

		while ( $query->have_posts() ) {
			$query->the_post();
			$count++;
			$post_id = get_the_ID();

			if ( ! $first ) {
				$first = get_post();
			}

			$price = get_post_meta( $post_id, '_prod_price', true );
			$amt   = cw_parse_price_amount( $price );
			if ( $amt < $cheapest_amt ) {
				$cheapest_amt = $amt;
				$cheapest     = $price;
			}

			$cap = get_post_meta( $post_id, '_prod_capacity', true );
			if ( $cap ) {
				$capacities[] = $cap;
			}
		}

		wp_reset_postdata();

		if ( ! $first ) {
			continue;
		}

		$img = cw_get_product_card_image( $first->ID );

		$cards[] = [
			'series'  => $series,
			'label'   => $labels[ $series ],
			'price'   => $cheapest ?: get_post_meta( $first->ID, '_prod_price', true ),
			'copy'    => $first->post_excerpt ?: wp_trim_words( wp_strip_all_tags( $first->post_content ), 24 ),
			'image'   => $img['url'] ?: ( $fallback[ $series ] ?? '' ),
			'filter'  => home_url( '/products/?series=' . $series ),
			'count'   => $count,
			'capacity'=> $capacities,
		];
	}

	return $cards;
}

function cw_get_spec_rows( $keys = [] ) {
	$schema = cw_get_product_spec_schema();
	$rows   = [];

	foreach ( $schema as $slug => $field ) {
		if ( $keys && ! in_array( $slug, $keys, true ) ) {
			continue;
		}

		if ( in_array( $slug, [ 'price', 'capacity', 'install', 'dimensions', 'volume', 'finish' ], true ) ) {
			continue;
		}

		$value = isset( $field['default'] ) ? $field['default'] : '';

		if ( $slug === 'noise' ) {
			$value = cw_get_product_spec_range( '_prod_noise' ) ?: $value;
		}

		if ( $value === '' ) {
			continue;
		}

		$rows[] = [
			'label' => $field['label'],
			'value' => $value,
		];
	}

	return $rows;
}

function cw_get_product_spec_range( $meta_key ) {
	$query = cw_query_products();
	$values = [];

	if ( ! $query->have_posts() ) {
		return '';
	}

	while ( $query->have_posts() ) {
		$query->the_post();
		$val = get_post_meta( get_the_ID(), $meta_key, true );
		if ( $val !== '' ) {
			$values[] = $val;
		}
	}

	wp_reset_postdata();

	$values = array_values( array_unique( $values ) );
	if ( count( $values ) === 1 ) {
		return $values[0];
	}

	if ( count( $values ) > 1 ) {
		return implode( ' · ', $values );
	}

	return '';
}

function cw_get_page_by_slug( $slug ) {
	$page = get_page_by_path( $slug );

	return ( $page && $page->post_status === 'publish' ) ? $page : null;
}

/** URL for post-enquiry redirect (page slug: thank-you). */
function cw_get_enquiry_thank_you_url() {
	$page = cw_get_page_by_slug( 'thank-you' );

	return $page ? get_permalink( $page ) : home_url( '/thank-you/' );
}

function cw_render_page_hero_from_post( $post = null, $args = [] ) {
	$post = get_post( $post );
	if ( ! $post ) {
		return;
	}

	$hero_args = wp_parse_args( $args, [
		'title'    => get_the_title( $post ),
		'subtitle' => get_the_excerpt( $post ),
		'image'    => cw_get_plate_hero_image( $post ),
		'center'   => true,
	] );

	cw_render_plate_hero( $hero_args );
}

function cw_get_lowest_product_price_label() {
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

	return $label;
}

function cw_query_installations( $args = [] ) {
	$defaults = [
		'post_type'      => 'case_study',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	];

	return new WP_Query( array_merge( $defaults, $args ) );
}

function cw_query_racks( $args = [] ) {
	$defaults = [
		'post_type'      => 'rack',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	];

	return new WP_Query( array_merge( $defaults, $args ) );
}

function cw_get_rack_archive_intro_page() {
	foreach ( [ 'racking-intro', 'racking' ] as $slug ) {
		$page = cw_get_page_by_slug( $slug );
		if ( $page ) {
			return $page;
		}
	}

	return null;
}

function cw_get_rack_card_image( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return [
			'url' => get_the_post_thumbnail_url( $post_id, 'large' ),
			'alt' => get_post_meta( get_post_thumbnail_id( $post_id ), '_wp_attachment_image_alt', true ) ?: get_the_title( $post_id ),
		];
	}

	$theme_slug = get_post_meta( $post_id, '_rack_theme_image', true );
	if ( $theme_slug ) {
		foreach ( [ 'jpg', 'jpeg', 'png', 'webp' ] as $ext ) {
			$rel = 'assets/images/racking/' . $theme_slug . '.' . $ext;
			$path = get_theme_file_path( $rel );
			if ( $path && file_exists( $path ) ) {
				return [
					'url' => get_theme_file_uri( $rel ),
					'alt' => get_the_title( $post_id ),
				];
			}
		}
	}

	return [
		'url' => get_theme_file_uri( 'assets/images/product-glass-cellar.jpg' ),
		'alt' => get_the_title( $post_id ),
	];
}

function cw_render_page_content_sections( $content ) {
	if ( trim( $content ) === '' ) {
		return;
	}

	echo '<div class="cw-page-sections">';
	echo apply_filters( 'the_content', $content );
	echo '</div>';
}
