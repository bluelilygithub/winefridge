<?php
define( 'CW_VERSION', '1.12.3' );

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
} );

/* -------------------------------------------------------------------------
 * Shared plate hero
 * ---------------------------------------------------------------------- */
function cw_get_plate_hero_image( $post = null ) {
	$post = get_post( $post );

	if ( $post && has_post_thumbnail( $post ) ) {
		return get_the_post_thumbnail_url( $post, 'full' );
	}

	return get_theme_file_uri( 'assets/images/product-glass-cellar.jpg' );
}

function cw_render_plate_hero( $args = [] ) {
	if ( empty( $args['image'] ) ) {
		$args['image'] = cw_get_plate_hero_image( get_queried_object_id() );
	}

	if ( empty( $args['title'] ) && in_the_loop() ) {
		$args['title'] = get_the_title();
	}

	if ( empty( $args['subtitle'] ) ) {
		$post = get_post( get_queried_object_id() );
		if ( $post && $post->post_excerpt ) {
			$args['subtitle'] = $post->post_excerpt;
		}
	}

	if ( empty( $args['alt'] ) && ! empty( $args['title'] ) ) {
		$args['alt'] = $args['title'];
	}

	get_template_part( 'template-parts/plate-hero', null, $args );
}

/* -------------------------------------------------------------------------
 * Enqueue styles and scripts
 * ---------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'curam-wines-fonts',
		// Bricolage Grotesque — variable font (opsz + weight axes)
		// To test alternatives, swap this URL:
		//   Space Grotesk: https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap
		//   Archivo:        https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,300;0,400;0,500;0,700;0,900;1,400&display=swap
		'https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&display=swap',
		[],
		null
	);
	wp_enqueue_style(
		'curam-wines-theme',
		get_template_directory_uri() . '/assets/css/theme.css',
		[ 'curam-wines-fonts' ],
		CW_VERSION
	);
	wp_enqueue_script(
		'curam-wines-theme',
		get_template_directory_uri() . '/assets/js/theme.js',
		[],
		CW_VERSION,
		true
	);
} );

/* -------------------------------------------------------------------------
 * Product custom post type
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_post_type( 'product', [
		'labels'       => [
			'name'          => 'Products',
			'singular_name' => 'Product',
			'menu_name'     => 'Products',
			'add_new_item'  => 'Add New Product',
			'edit_item'     => 'Edit Product',
		],
		'public'       => true,
		'has_archive'  => false,
		'menu_icon'    => 'dashicons-archive',
		'rewrite'      => [ 'slug' => 'range' ],
		'show_in_rest' => true,
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
	] );
} );

/* -------------------------------------------------------------------------
 * Product spec schema (field labels + meta keys)
 * ---------------------------------------------------------------------- */
function cw_get_series_labels() {
	return [
		'glass'   => 'Panoramic Glass Series',
		'panel'   => 'Insulated Panel Series',
		'outdoor' => 'Weather-Resistant Series',
	];
}

function cw_get_product_spec_schema() {
	return [
		'capacity'      => [ 'label' => 'Bottle capacity',      'meta' => '_prod_capacity' ],
		'volume'        => [ 'label' => 'Conditioned volume',   'meta' => '_prod_volume' ],
		'install'       => [ 'label' => 'Installation type',    'meta' => '_prod_install' ],
		'dimensions'    => [ 'label' => 'External dimensions',  'meta' => '_prod_dimensions' ],
		'temp_range'    => [ 'label' => 'Temperature range',    'meta' => '_prod_temp_range',    'default' => '8–18°C, user-set' ],
		'temp_accuracy' => [ 'label' => 'Temperature accuracy', 'meta' => '_prod_temp_accuracy', 'default' => 'Holds within ±0.5°C' ],
		'humidity'      => [ 'label' => 'Humidity',             'meta' => '_prod_humidity',      'default' => '60–70% RH, actively managed' ],
		'cooling'       => [ 'label' => 'Cooling capacity',     'meta' => '_prod_cooling' ],
		'power_draw'    => [ 'label' => 'Power draw',           'meta' => '_prod_power_draw' ],
		'power'         => [ 'label' => 'Power supply',         'meta' => '_prod_power',         'default' => 'Standard 10A point' ],
		'noise'         => [ 'label' => 'Noise level',          'meta' => '_prod_noise' ],
		'refrigerant'   => [ 'label' => 'Refrigerant',          'meta' => '_prod_refrigerant' ],
		'airflow'       => [ 'label' => 'Air circulation',      'meta' => '_prod_airflow' ],
		'finish'        => [ 'label' => 'Enclosure finish',     'meta' => '_prod_finish' ],
		'price'         => [ 'label' => 'From (installed)',     'meta' => '_prod_price' ],
	];
}

function cw_get_product_specs( $post_id ) {
	$specs  = [];
	$schema = cw_get_product_spec_schema();

	foreach ( $schema as $field ) {
		$value = get_post_meta( $post_id, $field['meta'], true );

		if ( $value === '' && isset( $field['default'] ) ) {
			$value = $field['default'];
		}

		if ( $value !== '' ) {
			$specs[] = [
				'label' => $field['label'],
				'value' => $value,
			];
		}
	}

	return $specs;
}

function cw_get_product_series_label( $series ) {
	$labels = cw_get_series_labels();
	return $labels[ $series ] ?? 'Wine Cabinet Range';
}

add_filter( 'show_admin_bar', '__return_false' );

/* -------------------------------------------------------------------------
 * Situation-based navigation (buyer language, not internal series names)
 * ---------------------------------------------------------------------- */
function cw_get_situation_filters() {
	return [
		'apartment'  => 'Apartment',
		'house'      => 'House',
		'garage'     => 'Garage',
		'outdoor'    => 'Balcony / outdoor',
		'commercial' => 'Commercial',
	];
}

function cw_get_fit_guides() {
	$guides    = [];
	$headlines = cw_get_fit_guide_headlines();

	foreach ( $headlines as $slug => $situation_label ) {
		$product = cw_get_recommended_product_for_situation( $slug );
		if ( ! $product ) {
			continue;
		}

		$note = $product->post_excerpt;
		if ( ! $note ) {
			$note = wp_trim_words( wp_strip_all_tags( $product->post_content ), 18 );
		}

		$guides[] = [
			'situation' => $situation_label,
			'filter'    => $slug,
			'recommend' => get_the_title( $product ),
			'slug'      => $product->post_name,
			'note'      => $note,
			'url'       => get_permalink( $product ),
		];
	}

	return $guides;
}

function cw_get_product_situations( $post_id ) {
	$stored = get_post_meta( $post_id, '_prod_situations', true );
	if ( is_array( $stored ) && ! empty( $stored ) ) {
		return array_values( array_intersect( array_map( 'sanitize_key', $stored ), array_keys( cw_get_situation_filters() ) ) );
	}

	$post = get_post( $post_id );
	if ( $post ) {
		return cw_get_product_situation_slug_defaults( $post->post_name );
	}

	return [];
}

/** Default situation map — used when admin checkboxes are not set yet. */
function cw_get_product_situation_slug_defaults( $slug ) {
	$map = [
		'glass-niche'               => [ 'apartment' ],
		'glass-pod'                 => [ 'house', 'apartment' ],
		'glass-display-cellar'      => [ 'house', 'commercial' ],
		'panel-walk-in'             => [ 'garage', 'house' ],
		'weather-resistant-outdoor' => [ 'outdoor' ],
	];

	return $map[ $slug ] ?? [];
}

/** @deprecated Use cw_get_product_situations() */
function cw_get_product_situation_slugs( $slug ) {
	return cw_get_product_situation_slug_defaults( $slug );
}

function cw_get_product_filter_cats( $post_id ) {
	$cats   = [];
	$series = get_post_meta( $post_id, '_prod_series', true );
	if ( $series ) {
		$cats[] = $series;
	}
	$cats = array_merge( $cats, cw_get_product_situations( $post_id ) );

	return implode( ' ', array_unique( array_filter( $cats ) ) );
}

function cw_get_post_gallery_ids( $post_id ) {
	$ids = get_post_meta( $post_id, '_cw_gallery_ids', true );
	if ( ! is_array( $ids ) ) {
		$ids = array_filter( array_map( 'absint', explode( ',', (string) $ids ) ) );
	}
	return array_values( array_filter( array_map( 'absint', $ids ) ) );
}

function cw_get_post_video_id( $post_id ) {
	return (int) get_post_meta( $post_id, '_cw_video_id', true );
}

function cw_get_post_video_url( $post_id ) {
	$id = cw_get_post_video_id( $post_id );
	return $id ? wp_get_attachment_url( $id ) : '';
}

function cw_post_has_gallery_media( $post_id ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return true;
	}
	if ( ! empty( cw_get_post_gallery_ids( $post_id ) ) ) {
		return true;
	}
	if ( cw_get_post_video_id( $post_id ) ) {
		return true;
	}
	return false;
}

function cw_is_in_public_gallery( $post_id ) {
	if ( ! cw_post_has_gallery_media( $post_id ) ) {
		return false;
	}
	$show = get_post_meta( $post_id, '_cw_show_in_gallery', true );
	if ( $show === '0' ) {
		return false;
	}
	if ( $show === '1' ) {
		return true;
	}
	return true;
}

function cw_get_gallery_items() {
	$items = [];

	foreach ( [ 'product', 'case_study', 'rack' ] as $post_type ) {
		$posts = get_posts( [
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );

		foreach ( $posts as $post ) {
			if ( ! cw_is_in_public_gallery( $post->ID ) ) {
				continue;
			}

			$thumb_id = get_post_thumbnail_id( $post->ID );
			$thumb    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : '';
			if ( ! $thumb ) {
				$gallery = cw_get_post_gallery_ids( $post->ID );
				if ( ! empty( $gallery ) ) {
					$thumb = wp_get_attachment_image_url( $gallery[0], 'large' );
				}
			}

			$items[] = [
				'id'        => $post->ID,
				'title'     => get_the_title( $post ),
				'url'       => get_permalink( $post ),
				'type'      => $post_type === 'product' ? 'product' : ( $post_type === 'case_study' ? 'installation' : 'rack' ),
				'thumb'     => $thumb ?: get_theme_file_uri( 'assets/images/product-glass-pod.jpg' ),
				'video_url' => cw_get_post_video_url( $post->ID ),
				'has_video' => (bool) cw_get_post_video_id( $post->ID ),
			];
		}
	}

	return $items;
}

/**
 * Flat list of gallery images and videos for the public gallery page.
 *
 * @return array<int, array<string, mixed>>
 */
function cw_get_gallery_slides() {
	$slides = [];
	$fallback = get_theme_file_uri( 'assets/images/product-glass-pod.jpg' );

	foreach ( [ 'product', 'case_study', 'rack' ] as $post_type ) {
		$posts = get_posts( [
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );

		foreach ( $posts as $post ) {
			if ( ! cw_is_in_public_gallery( $post->ID ) ) {
				continue;
			}

			$type       = $post_type === 'product' ? 'product' : ( $post_type === 'case_study' ? 'installation' : 'rack' );
			$type_label = $post_type === 'product' ? 'Product' : ( $post_type === 'case_study' ? 'Installation' : 'Rack' );
			$cats       = $type;
			$has_video  = (bool) cw_get_post_video_id( $post->ID );
			if ( $has_video ) {
				$cats .= ' video';
			}

			$base = [
				'title'      => get_the_title( $post ),
				'type'       => $type,
				'type_label' => $type_label,
				'cats'       => $cats,
				'url'        => get_permalink( $post ),
			];

			$gallery  = cw_get_post_gallery_ids( $post->ID );
			$thumb_id = get_post_thumbnail_id( $post->ID );
			$post_slides = [];

			if ( $thumb_id && ! in_array( $thumb_id, $gallery, true ) ) {
				$full  = wp_get_attachment_image_url( $thumb_id, 'full' );
				$thumb = wp_get_attachment_image_url( $thumb_id, 'large' );
				if ( $full ) {
					$post_slides[] = array_merge( $base, [
						'media' => 'image',
						'full'  => $full,
						'thumb' => $thumb ?: $full,
						'alt'   => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post ),
					] );
				}
			}

			foreach ( $gallery as $aid ) {
				$full  = wp_get_attachment_image_url( $aid, 'full' );
				$thumb = wp_get_attachment_image_url( $aid, 'large' );
				if ( ! $full ) {
					continue;
				}

				$post_slides[] = array_merge( $base, [
					'media' => 'image',
					'full'  => $full,
					'thumb' => $thumb ?: $full,
					'alt'   => get_post_meta( $aid, '_wp_attachment_image_alt', true ) ?: get_the_title( $post ),
				] );
			}

			if ( empty( $post_slides ) && $thumb_id ) {
				$full  = wp_get_attachment_image_url( $thumb_id, 'full' );
				$thumb = wp_get_attachment_image_url( $thumb_id, 'large' );
				if ( $full ) {
					$post_slides[] = array_merge( $base, [
						'media' => 'image',
						'full'  => $full,
						'thumb' => $thumb ?: $full,
						'alt'   => get_post_meta( $thumb_id, '_wp_attachment_image_alt', true ) ?: get_the_title( $post ),
					] );
				}
			}

			$slides = array_merge( $slides, $post_slides );

			if ( $has_video ) {
				$video_url = cw_get_post_video_url( $post->ID );
				$poster    = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : '';
				if ( ! $poster && ! empty( $gallery ) ) {
					$poster = wp_get_attachment_image_url( $gallery[0], 'large' );
				}

				$slides[] = array_merge( $base, [
					'media'     => 'video',
					'full'      => $poster ?: $fallback,
					'thumb'     => $poster ?: $fallback,
					'video_url' => $video_url,
					'alt'       => get_the_title( $post ) . ' video',
				] );
			}
		}
	}

	return $slides;
}

/**
 * Property-type filter for installations — inferred from title,
 * location, and configuration type until `_cs_property` is set.
 */
function cw_get_installation_situation_slug( $post_id ) {
	$stored = get_post_meta( $post_id, '_cs_property', true );
	if ( $stored ) {
		return sanitize_key( $stored );
	}

	$haystack = strtolower(
		get_the_title( $post_id ) . ' ' .
		get_post_meta( $post_id, '_cs_location', true ) . ' ' .
		get_post_meta( $post_id, '_cs_type', true )
	);

	if ( preg_match( '/commercial|restaurant|bar|hotel|hospitality/', $haystack ) ) {
		return 'commercial';
	}
	if ( preg_match( '/balcony|outdoor|courtyard|weather/', $haystack ) ) {
		return 'outdoor';
	}
	if ( preg_match( '/garage|utility|basement|panel walk/', $haystack ) ) {
		return 'garage';
	}
	if ( preg_match( '/apartment|penthouse|niche|darlinghurst|unit/', $haystack ) ) {
		return 'apartment';
	}
	if ( preg_match( '/house|townhouse|toorak|dining|living|residential/', $haystack ) ) {
		return 'house';
	}

	return 'other';
}

/** @deprecated Use cw_get_installation_situation_slug() */
function cw_get_installation_filter_slug( $type ) {
	return cw_get_installation_situation_slug( get_the_ID() );
}

function cw_get_feature_video_id() {
	static $id = null;
	if ( $id !== null ) {
		return $id;
	}

	$stored = (int) get_option( 'cw_feature_video_id', 0 );
	if ( $stored && get_post( $stored ) ) {
		$id = $stored;
		return $id;
	}

	$attachments = get_posts( [
		'post_type'      => 'attachment',
		'post_status'    => 'inherit',
		'posts_per_page' => 1,
		'name'           => 'converted',
	] );

	if ( empty( $attachments ) ) {
		$attachments = get_posts( [
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'posts_per_page' => 1,
			'post_mime_type' => 'video',
			'orderby'        => 'date',
			'order'          => 'DESC',
		] );
	}

	$id = ! empty( $attachments ) ? (int) $attachments[0]->ID : 0;

	if ( $id ) {
		update_option( 'cw_feature_video_id', $id, false );
	}

	return $id;
}

function cw_get_feature_video_url() {
	$video_id = cw_get_feature_video_id();

	return $video_id ? wp_get_attachment_url( $video_id ) : '';
}

function cw_get_related_installation( $product_slug ) {
	$map = [
		'glass-niche'               => 'brisbane-penthouse-niche',
		'glass-pod'                 => 'toorak-dining-room-glass-pod',
		'glass-display-cellar'      => 'toorak-dining-room-glass-pod',
		'panel-walk-in'             => 'melbourne-garage-panel-walkin',
		'weather-resistant-outdoor' => 'sydney-balcony-outdoor-unit',
	];

	if ( empty( $map[ $product_slug ] ) ) {
		return null;
	}

	$posts = get_posts( [
		'name'           => $map[ $product_slug ],
		'post_type'      => 'case_study',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
	] );

	return ! empty( $posts ) ? $posts[0] : null;
}

/* -------------------------------------------------------------------------
 * Case Study custom post type
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_post_type( 'case_study', [
		'labels'       => [
			'name'          => 'Installations',
			'singular_name' => 'Installation',
			'menu_name'     => 'Installations',
			'add_new_item'  => 'Add New Installation',
			'edit_item'     => 'Edit Installation',
		],
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-portfolio',
		'rewrite'      => [ 'slug' => 'installations' ],
		'show_in_rest' => true,
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail' ],
	] );
} );

/* -------------------------------------------------------------------------
 * Rack custom post type
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_post_type( 'rack', [
		'labels'       => [
			'name'          => 'Racks',
			'singular_name' => 'Rack',
			'menu_name'     => 'Racks',
			'add_new_item'  => 'Add New Rack',
			'edit_item'     => 'Edit Rack',
		],
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-screenoptions',
		'rewrite'      => [ 'slug' => 'racking' ],
		'show_in_rest' => true,
		'supports'     => [ 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ],
	] );
} );

/* -------------------------------------------------------------------------
 * Enquiry form handler
 * ---------------------------------------------------------------------- */
add_action( 'admin_post_nopriv_cw_enquiry', 'cw_handle_enquiry' );
add_action( 'admin_post_cw_enquiry',        'cw_handle_enquiry' );

function cw_handle_enquiry() {
	$redirect = wp_get_referer() ?: home_url( '/enquire/' );

	if ( empty( $_POST['cw_enquiry_nonce'] ) || ! wp_verify_nonce( $_POST['cw_enquiry_nonce'], 'cw_enquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	if ( ! empty( $_POST['cw_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) );
		exit;
	}

	$name               = sanitize_text_field( wp_unslash( $_POST['name']               ?? '' ) );
	$email              = sanitize_email(      wp_unslash( $_POST['email']              ?? '' ) );
	$phone              = sanitize_text_field( wp_unslash( $_POST['phone']              ?? '' ) );
	$city               = sanitize_text_field( wp_unslash( $_POST['city']               ?? '' ) );
	$bottle_capacity    = sanitize_text_field( wp_unslash( $_POST['bottle_capacity']    ?? '' ) );
	$series             = sanitize_text_field( wp_unslash( $_POST['series']             ?? '' ) );
	$installation_type  = sanitize_text_field( wp_unslash( $_POST['installation_type']  ?? '' ) );
	$width              = sanitize_text_field( wp_unslash( $_POST['width']              ?? '' ) );
	$height             = sanitize_text_field( wp_unslash( $_POST['height']             ?? '' ) );
	$depth              = sanitize_text_field( wp_unslash( $_POST['depth']              ?? '' ) );
	$finish             = sanitize_text_field( wp_unslash( $_POST['finish']             ?? '' ) );
	$property_type      = sanitize_text_field( wp_unslash( $_POST['property_type']      ?? '' ) );
	$deadline           = sanitize_text_field( wp_unslash( $_POST['deadline']           ?? '' ) );
	$message            = sanitize_textarea_field( wp_unslash( $_POST['message']        ?? '' ) );

	if ( empty( $name ) || empty( $email ) || ! is_email( $email ) || empty( $phone ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	$mode = sanitize_text_field( wp_unslash( $_POST['enquiry_mode'] ?? 'quick' ) );
	$data = [
		'mode'              => $mode,
		'name'              => $name,
		'email'             => $email,
		'phone'             => $phone,
		'city'              => $city,
		'bottle_capacity'   => $bottle_capacity,
		'series'            => $series,
		'installation_type' => $installation_type,
		'width'             => $width,
		'height'            => $height,
		'depth'             => $depth,
		'finish'            => $finish,
		'property_type'     => $property_type,
		'deadline'          => $deadline,
		'message'           => $message,
	];

	if ( ! cw_send_enquiry_notifications( $data ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) . '#enquire' );
	exit;
}

require get_template_directory() . '/inc/a11y.php';
require get_template_directory() . '/inc/site-settings.php';
require get_template_directory() . '/inc/mail.php';
require get_template_directory() . '/inc/seo.php';
require get_template_directory() . '/inc/content-helpers.php';
require get_template_directory() . '/inc/shortcodes.php';
require get_template_directory() . '/inc/admin-meta.php';
require get_template_directory() . '/inc/admin-pages.php';
