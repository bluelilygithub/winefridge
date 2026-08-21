<?php
/**
 * FAQ, Gallery, and Process custom post types — edited in WP Admin, shown on the site.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/seed-content-data.php';

/* -------------------------------------------------------------------------
 * Register post types + categories
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_taxonomy( 'faq_category', 'cw_faq', [
		'labels'            => [
			'name'          => 'FAQ categories',
			'singular_name' => 'FAQ category',
			'menu_name'     => 'Categories',
			'add_new_item'  => 'Add FAQ category',
			'search_items'  => 'Search categories',
		],
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'rewrite'           => false,
	] );

	register_post_type( 'cw_faq', [
		'labels'              => [
			'name'               => 'FAQs',
			'singular_name'      => 'FAQ',
			'menu_name'          => 'FAQs',
			'add_new'            => 'Add question',
			'add_new_item'       => 'Add question',
			'edit_item'          => 'Edit question',
			'new_item'           => 'New question',
			'view_item'          => 'View question',
			'search_items'       => 'Search FAQs',
			'not_found'          => 'No questions yet',
			'not_found_in_trash' => 'No questions in trash',
			'all_items'          => 'All questions',
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-editor-help',
		'menu_position'       => 26,
		'show_in_rest'        => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'supports'            => [ 'title', 'editor', 'page-attributes' ],
		'rewrite'             => false,
	] );

	register_taxonomy( 'gallery_category', 'cw_gallery', [
		'labels'            => [
			'name'          => 'Gallery categories',
			'singular_name' => 'Gallery category',
			'menu_name'     => 'Categories',
			'add_new_item'  => 'Add gallery category',
			'search_items'  => 'Search categories',
		],
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'hierarchical'      => true,
		'show_in_rest'      => true,
		'rewrite'           => false,
	] );

	register_post_type( 'cw_gallery', [
		'labels'              => [
			'name'               => 'Gallery',
			'singular_name'      => 'Gallery item',
			'menu_name'          => 'Gallery',
			'add_new'            => 'Add item',
			'add_new_item'       => 'Add gallery item',
			'edit_item'          => 'Edit gallery item',
			'new_item'           => 'New gallery item',
			'search_items'       => 'Search gallery',
			'not_found'          => 'No gallery items yet',
			'not_found_in_trash' => 'No gallery items in trash',
			'all_items'          => 'All items',
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-format-gallery',
		'menu_position'       => 27,
		'show_in_rest'        => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'supports'            => [ 'title', 'excerpt', 'thumbnail', 'page-attributes' ],
		'rewrite'             => false,
	] );

	register_post_type( 'cw_process', [
		'labels'              => [
			'name'               => 'Process steps',
			'singular_name'      => 'Process step',
			'menu_name'          => 'Process',
			'add_new'            => 'Add step',
			'add_new_item'       => 'Add process step',
			'edit_item'          => 'Edit process step',
			'search_items'       => 'Search steps',
			'not_found'          => 'No process steps yet',
			'all_items'          => 'All steps',
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_icon'           => 'dashicons-list-view',
		'menu_position'       => 28,
		'show_in_rest'        => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'supports'            => [ 'title', 'excerpt', 'page-attributes' ],
		'rewrite'             => false,
	] );
}, 5 );

function cw_default_faq_categories() {
	return [
		'home'         => [ 'name' => 'Home page',                   'description' => 'Shown in the Common questions strip.' ],
		'products'     => [ 'name' => 'About Our Products',          'description' => 'FAQ page group.' ],
		'sizing'       => [ 'name' => 'Sizing & Capacity',           'description' => 'FAQ page group.' ],
		'climate'      => [ 'name' => 'Climate Control',             'description' => 'FAQ page group.' ],
		'reliability'  => [ 'name' => 'Reliability & Running Costs', 'description' => 'FAQ page group.' ],
		'installation' => [ 'name' => 'Installation & Delivery',     'description' => 'FAQ page group.' ],
		'commercial'   => [ 'name' => 'Commercial & Hospitality',    'description' => 'FAQ page group.' ],
	];
}

function cw_default_gallery_categories() {
	return [
		'product'      => [ 'name' => 'Products',      'description' => 'Filter chip on the Gallery page.' ],
		'installation' => [ 'name' => 'Installations', 'description' => 'Filter chip on the Gallery page.' ],
		'video'        => [ 'name' => 'Videos',        'description' => 'Filter chip on the Gallery page.' ],
		'rack'         => [ 'name' => 'Racks',         'description' => 'Filter chip on the Gallery page.' ],
	];
}

function cw_ensure_content_terms() {
	foreach ( cw_default_faq_categories() as $slug => $term ) {
		if ( ! term_exists( $slug, 'faq_category' ) ) {
			wp_insert_term( $term['name'], 'faq_category', [
				'slug'        => $slug,
				'description' => $term['description'],
			] );
		}
	}

	foreach ( cw_default_gallery_categories() as $slug => $term ) {
		if ( ! term_exists( $slug, 'gallery_category' ) ) {
			wp_insert_term( $term['name'], 'gallery_category', [
				'slug'        => $slug,
				'description' => $term['description'],
			] );
		}
	}
}

add_action( 'init', 'cw_ensure_content_terms', 20 );

/* -------------------------------------------------------------------------
 * Queries
 * ---------------------------------------------------------------------- */
function cw_query_faqs( $args = [] ) {
	$defaults = [
		'post_type'      => 'cw_faq',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	];

	return get_posts( array_merge( $defaults, $args ) );
}

function cw_get_faq_groups() {
	$terms = get_terms( [
		'taxonomy'   => 'faq_category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	] );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return [];
	}

	$groups = [];
	foreach ( $terms as $term ) {
		if ( $term->slug === 'home' ) {
			continue;
		}

		$posts = cw_query_faqs( [
			'tax_query' => [
				[
					'taxonomy' => 'faq_category',
					'field'    => 'term_id',
					'terms'    => $term->term_id,
				],
			],
		] );

		if ( $posts ) {
			$groups[] = [
				'term'  => $term,
				'posts' => $posts,
			];
		}
	}

	if ( empty( $groups ) ) {
		$ungrouped = cw_query_faqs();
		if ( $ungrouped ) {
			$groups[] = [
				'term'  => (object) [ 'name' => 'Questions', 'slug' => 'all' ],
				'posts' => $ungrouped,
			];
		}
	}

	return $groups;
}

function cw_get_home_faqs() {
	$posts = cw_query_faqs( [
		'tax_query' => [
			[
				'taxonomy' => 'faq_category',
				'field'    => 'slug',
				'terms'    => 'home',
			],
		],
	] );

	if ( $posts ) {
		return $posts;
	}

	return array_slice( cw_query_faqs(), 0, 5 );
}

function cw_query_gallery_items( $args = [] ) {
	$defaults = [
		'post_type'      => 'cw_gallery',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
	];

	return get_posts( array_merge( $defaults, $args ) );
}

function cw_get_gallery_filter_terms() {
	$terms = get_terms( [
		'taxonomy'   => 'gallery_category',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	] );

	return is_wp_error( $terms ) ? [] : $terms;
}

/**
 * Gallery page slides from the Gallery CPT.
 *
 * @return array<int, array<string, mixed>>
 */
function cw_get_gallery_cpt_slides() {
	$slides   = [];
	$fallback = get_theme_file_uri( 'assets/images/product-glass-pod.jpg' );

	foreach ( cw_query_gallery_items() as $post ) {
		$terms = get_the_terms( $post->ID, 'gallery_category' );
		$slugs = [];
		$label = 'Gallery';

		if ( $terms && ! is_wp_error( $terms ) ) {
			$slugs = wp_list_pluck( $terms, 'slug' );
			$names = array_values( array_filter( wp_list_pluck( $terms, 'name' ), function ( $name ) {
				return strtolower( $name ) !== 'videos';
			} ) );
			$label = $names[0] ?? $terms[0]->name;
		}

		$video_url = cw_get_post_video_url( $post->ID );
		$is_video  = (bool) $video_url;
		if ( $is_video && ! in_array( 'video', $slugs, true ) ) {
			$slugs[] = 'video';
		}

		$thumb_id = get_post_thumbnail_id( $post->ID );
		$full     = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'full' ) : '';
		$thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : '';

		$slides[] = [
			'title'      => get_the_title( $post ),
			'type'       => $slugs[0] ?? 'gallery',
			'type_label' => $label,
			'cats'       => implode( ' ', $slugs ),
			'url'        => '',
			'media'      => $is_video ? 'video' : 'image',
			'full'       => $full ?: $fallback,
			'thumb'      => $thumb ?: ( $full ?: $fallback ),
			'video_url'  => $video_url,
			'alt'        => get_the_title( $post ),
		];
	}

	return $slides;
}

function cw_get_gallery_slides() {
	$slides = cw_get_gallery_cpt_slides();
	if ( $slides ) {
		return $slides;
	}

	if ( function_exists( 'cw_get_legacy_gallery_slides' ) ) {
		return cw_get_legacy_gallery_slides();
	}

	return [];
}

/* -------------------------------------------------------------------------
 * Seed starter FAQs + copy existing product/installation media into Gallery
 * ---------------------------------------------------------------------- */
function cw_existing_faq_titles() {
	$titles = [];
	foreach ( cw_query_faqs( [ 'post_status' => [ 'publish', 'draft', 'private' ] ] ) as $post ) {
		$titles[ strtolower( trim( $post->post_title ) ) ] = $post->ID;
	}
	return $titles;
}

function cw_seed_faq_posts() {
	cw_ensure_content_terms();
	$existing = cw_existing_faq_titles();
	$order    = 10;
	$created  = 0;

	foreach ( cw_get_seed_faq_items() as $item ) {
		$key = strtolower( trim( $item['title'] ) );
		if ( isset( $existing[ $key ] ) ) {
			wp_set_object_terms( $existing[ $key ], $item['cats'], 'faq_category' );
			$order += 10;
			continue;
		}

		$id = wp_insert_post( [
			'post_type'    => 'cw_faq',
			'post_status'  => 'publish',
			'post_title'   => $item['title'],
			'post_content' => $item['content'],
			'menu_order'   => $order,
		], true );

		if ( ! is_wp_error( $id ) && $id ) {
			wp_set_object_terms( $id, $item['cats'], 'faq_category' );
			$existing[ $key ] = $id;
			$created++;
		}
		$order += 10;
	}

	update_option( 'cw_faq_cpt_seeded', '2' );
	return $created;
}

function cw_existing_gallery_keys() {
	$keys = [];
	foreach ( cw_query_gallery_items( [ 'post_status' => [ 'publish', 'draft', 'private' ] ] ) as $post ) {
		$thumb = (int) get_post_thumbnail_id( $post->ID );
		$keys[ strtolower( trim( $post->post_title ) ) . '|' . $thumb ] = $post->ID;
	}
	return $keys;
}

function cw_seed_gallery_from_legacy() {
	cw_ensure_content_terms();

	if ( ! function_exists( 'cw_get_legacy_gallery_slides' ) ) {
		update_option( 'cw_gallery_cpt_seeded', '2' );
		return 0;
	}

	$existing = cw_existing_gallery_keys();
	$order    = 10;
	$created  = 0;

	foreach ( cw_get_legacy_gallery_slides() as $slide ) {
		$title    = $slide['title'] ?: 'Gallery item';
		$image_id = absint( $slide['image_id'] ?? 0 );
		if ( ! $image_id && ! empty( $slide['full'] ) ) {
			$image_id = attachment_url_to_postid( $slide['full'] );
		}
		if ( ! $image_id && ! empty( $slide['thumb'] ) ) {
			$image_id = attachment_url_to_postid( $slide['thumb'] );
		}

		$key = strtolower( trim( $title ) ) . '|' . $image_id;
		if ( isset( $existing[ $key ] ) ) {
			$order += 10;
			continue;
		}

		$id = wp_insert_post( [
			'post_type'    => 'cw_gallery',
			'post_status'  => 'publish',
			'post_title'   => $title,
			'post_excerpt' => $slide['type_label'] ?? '',
			'menu_order'   => $order,
		], true );

		if ( is_wp_error( $id ) || ! $id ) {
			continue;
		}

		$cats = [ $slide['type'] ?? 'product' ];
		if ( ( $slide['media'] ?? '' ) === 'video' ) {
			$cats[] = 'video';
			$video_id = absint( $slide['video_id'] ?? 0 );
			if ( ! $video_id && ! empty( $slide['video_url'] ) ) {
				$video_id = attachment_url_to_postid( $slide['video_url'] );
			}
			if ( $video_id ) {
				update_post_meta( $id, '_cw_video_id', $video_id );
			}
		}

		wp_set_object_terms( $id, array_unique( array_filter( $cats ) ), 'gallery_category' );
		if ( $image_id ) {
			set_post_thumbnail( $id, $image_id );
		}

		$existing[ $key ] = $id;
		$created++;
		$order += 10;
	}

	update_option( 'cw_gallery_cpt_seeded', '2' );
	return $created;
}

function cw_default_process_steps() {
	return [
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
			'body'  => 'Scope and installed price — clear before you commit.',
		],
		[
			'title' => 'Supply and install',
			'body'  => 'Built, delivered, positioned, and commissioned by our team.',
		],
	];
}

function cw_get_process_steps() {
	$query = new WP_Query( [
		'post_type'              => 'cw_process',
		'post_status'            => 'publish',
		'posts_per_page'         => -1,
		'orderby'                => 'menu_order title',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
	] );

	$steps = [];
	foreach ( $query->posts as $post ) {
		$body = trim( (string) $post->post_excerpt );
		if ( $body === '' ) {
			$body = wp_strip_all_tags( $post->post_content );
		}
		$steps[] = [
			'title' => get_the_title( $post ),
			'body'  => $body,
		];
	}

	return $steps ?: cw_default_process_steps();
}

function cw_seed_process_steps() {
	$existing = get_posts( [
		'post_type'      => 'cw_process',
		'post_status'    => 'any',
		'posts_per_page' => 1,
		'fields'         => 'ids',
	] );

	if ( ! empty( $existing ) ) {
		update_option( 'cw_process_cpt_seeded', '1' );
		return 0;
	}

	$created = 0;
	$order   = 10;
	foreach ( cw_default_process_steps() as $step ) {
		$id = wp_insert_post( [
			'post_type'    => 'cw_process',
			'post_status'  => 'publish',
			'post_title'   => $step['title'],
			'post_excerpt' => $step['body'],
			'menu_order'   => $order,
		], true );

		if ( ! is_wp_error( $id ) && $id ) {
			$created++;
			$order += 10;
		}
	}

	update_option( 'cw_process_cpt_seeded', '1' );
	return $created;
}

add_action( 'init', function () {
	if ( get_option( 'cw_faq_cpt_seeded' ) !== '2' ) {
		cw_seed_faq_posts();
	}
	if ( get_option( 'cw_gallery_cpt_seeded' ) !== '2' ) {
		cw_seed_gallery_from_legacy();
	}
	if ( get_option( 'cw_process_cpt_seeded' ) !== '1' ) {
		cw_seed_process_steps();
	}
}, 40 );

add_filter( 'enter_title_here', function ( $title, $post ) {
	if ( $post instanceof WP_Post && $post->post_type === 'cw_process' ) {
		return 'Step name';
	}
	return $title;
}, 10, 2 );

add_filter( 'manage_cw_process_posts_columns', function ( $columns ) {
	$new = [];
	foreach ( $columns as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['cw_step_body'] = 'Description';
		}
	}
	return $new;
} );

add_action( 'manage_cw_process_posts_custom_column', function ( $column, $post_id ) {
	if ( $column !== 'cw_step_body' ) {
		return;
	}
	echo esc_html( get_post_field( 'post_excerpt', $post_id ) );
}, 10, 2 );

/* -------------------------------------------------------------------------
 * FAQ page: hide leftover HTML questions once CPT content exists
 * ---------------------------------------------------------------------- */
add_filter( 'the_content', function ( $content ) {
	if ( is_admin() || ! is_page() ) {
		return $content;
	}

	$page = get_queried_object();
	if ( ! $page || $page->post_name !== 'faq' ) {
		return $content;
	}

	if ( empty( cw_get_faq_groups() ) ) {
		return $content;
	}

	$content = preg_replace( '#<div[^>]*class="[^"]*cw-faq[^"]*"[^>]*>.*?</div>#si', '', $content );
	$content = preg_replace( '#<section[^>]*class="[^"]*cw-faq[^"]*"[^>]*>.*?</section>#si', '', $content );
	$content = preg_replace( '#<details\b[^>]*>.*?</details>#si', '', $content );

	return $content;
}, 8 );
