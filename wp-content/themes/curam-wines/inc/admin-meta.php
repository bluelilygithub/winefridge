<?php
/**
 * Admin meta boxes for Products and Installations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Register meta keys
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	$string_meta = [
		'product'     => [ '_prod_series', '_prod_capacity', '_prod_price', '_prod_install', '_prod_dimensions', '_prod_volume', '_prod_cooling', '_prod_power_draw', '_prod_power', '_prod_noise', '_prod_refrigerant', '_prod_airflow', '_prod_finish', '_prod_temp_range', '_prod_temp_accuracy', '_prod_humidity' ],
		'case_study'  => [ '_cs_property', '_cs_location', '_cs_type', '_cs_bottles', '_cs_temp', '_cs_duration' ],
		'rack'        => [ '_rack_style', '_rack_theme_image' ],
	];

	foreach ( $string_meta as $post_type => $keys ) {
		foreach ( $keys as $key ) {
			register_post_meta( $post_type, $key, [
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'auth_callback'     => function () {
					return current_user_can( 'edit_posts' );
				},
				'sanitize_callback' => 'sanitize_text_field',
			] );
		}
	}

	register_post_meta( 'product', '_prod_situations', [
		'type'              => 'array',
		'single'            => true,
		'show_in_rest'      => [
			'schema' => [
				'type'  => 'array',
				'items' => [ 'type' => 'string' ],
			],
		],
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
		'sanitize_callback' => 'cw_sanitize_situation_array',
	] );

	foreach ( [ 'product', 'case_study', 'rack', 'cw_gallery' ] as $post_type ) {
		register_post_meta( $post_type, '_cw_video_id', [
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'absint',
		] );

		register_post_meta( $post_type, '_cw_gallery_ids', [
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => [
				'schema' => [
					'type'  => 'array',
					'items' => [ 'type' => 'integer' ],
				],
			],
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => 'cw_sanitize_gallery_ids',
		] );

		register_post_meta( $post_type, '_cw_show_in_gallery', [
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
			'sanitize_callback' => function ( $value ) {
				return $value === '1' ? '1' : '';
			},
		] );
	}
} );

function cw_sanitize_situation_array( $value ) {
	if ( ! is_array( $value ) ) {
		return [];
	}
	$allowed = array_keys( cw_get_situation_filters() );
	return array_values( array_intersect( array_map( 'sanitize_key', $value ), $allowed ) );
}

function cw_sanitize_gallery_ids( $value ) {
	if ( ! is_array( $value ) ) {
		$value = explode( ',', (string) $value );
	}
	return array_values( array_filter( array_map( 'absint', $value ) ) );
}

/* -------------------------------------------------------------------------
 * Meta boxes — classic editor for easier non-technical editing
 * ---------------------------------------------------------------------- */
add_filter( 'use_block_editor_for_post_type', function ( $use, $post_type ) {
	if ( in_array( $post_type, [ 'product', 'case_study', 'rack', 'page', 'cw_faq', 'cw_gallery', 'cw_process' ], true ) ) {
		return false;
	}
	return $use;
}, 10, 2 );

add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'cw_product_details',
		'Product details',
		'cw_render_product_meta_box',
		'product',
		'side',
		'high'
	);

	add_meta_box(
		'cw_product_media',
		'Photos & video',
		'cw_render_media_meta_box',
		'product',
		'normal',
		'high'
	);

	add_meta_box(
		'cw_installation_details',
		'Installation details',
		'cw_render_installation_meta_box',
		'case_study',
		'side',
		'high'
	);

	add_meta_box(
		'cw_installation_media',
		'Photos & video',
		'cw_render_media_meta_box',
		'case_study',
		'normal',
		'high'
	);

	add_meta_box(
		'cw_rack_details',
		'Rack details',
		'cw_render_rack_meta_box',
		'rack',
		'side',
		'high'
	);

	add_meta_box(
		'cw_rack_media',
		'Photos & video',
		'cw_render_media_meta_box',
		'rack',
		'normal',
		'high'
	);

	add_meta_box(
		'cw_gallery_media',
		'Video (optional)',
		'cw_render_gallery_item_meta_box',
		'cw_gallery',
		'normal',
		'high'
	);
}, 10 );

add_action( 'admin_notices', function () {
	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->base, [ 'post', 'post-new' ], true ) ) {
		return;
	}
	if ( ! in_array( $screen->post_type, [ 'product', 'case_study', 'rack', 'cw_faq', 'cw_gallery', 'cw_process' ], true ) ) {
		return;
	}

	if ( $screen->post_type === 'cw_process' ) {
		echo '<div class="notice notice-info"><p><strong>Editing a process step:</strong> The <strong>title</strong> is the step name. The <strong>excerpt</strong> is the short description shown on the site. Drag steps on the Process list to change order. Heading, intro, and caption are under <strong>Settings → Site copy</strong>.</p></div>';
		return;
	}

	if ( $screen->post_type === 'cw_faq' ) {
		echo '<div class="notice notice-info"><p><strong>Editing an FAQ:</strong> The <strong>title</strong> is the question. The editor is the answer. Assign a <strong>category</strong> in the sidebar — those groups appear on the FAQ page. Assign <strong>Home page</strong> as well to show it in the homepage strip.</p></div>';
		return;
	}

	if ( $screen->post_type === 'cw_gallery' ) {
		echo '<div class="notice notice-info"><p><strong>Editing a gallery item:</strong> Set the <strong>featured image</strong> in the sidebar. Assign a <strong>Gallery category</strong> — those names become the filter buttons on the Gallery page. Add a video only if this tile should play in the lightbox.</p></div>';
		return;
	}

	$label = $screen->post_type === 'product' ? 'product' : ( $screen->post_type === 'case_study' ? 'installation' : 'rack' );
	echo '<div class="notice notice-info"><p><strong>Tip:</strong> Set the ' . esc_html( $label ) . ' fields in the <strong>sidebar on the right</strong> (series, price, property type). Add extra photos and video in the <strong>Photos &amp; video</strong> box below the description. Use <strong>Featured image</strong> in the sidebar for the main photo.</p></div>';
} );

function cw_admin_field( $id, $label, $value, $type = 'text', $options = [] ) {
	$name = esc_attr( $id );
	echo '<p class="cw-admin-field"><label for="' . $name . '"><strong>' . esc_html( $label ) . '</strong></label>';

	if ( $type === 'select' ) {
		echo '<select id="' . $name . '" name="' . $name . '" class="widefat">';
		foreach ( $options as $opt_val => $opt_label ) {
			printf(
				'<option value="%s"%s>%s</option>',
				esc_attr( $opt_val ),
				selected( $value, $opt_val, false ),
				esc_html( $opt_label )
			);
		}
		echo '</select>';
	} else {
		printf(
			'<input type="%s" id="%s" name="%s" value="%s" class="widefat" />',
			esc_attr( $type ),
			$name,
			$name,
			esc_attr( $value )
		);
	}

	echo '</p>';
}

function cw_render_product_meta_box( $post ) {
	wp_nonce_field( 'cw_save_product_meta', 'cw_product_meta_nonce' );

	$series     = get_post_meta( $post->ID, '_prod_series', true );
	$situations = cw_get_product_situations( $post->ID );

	echo '<p class="description" style="margin-top:0;">Fill in these fields — they control how the product appears on the website.</p>';

	cw_admin_field( '_prod_series', 'Series', $series, 'select', [
		''        => '— Select series —',
		'glass'   => 'Panoramic Glass Series',
		'panel'   => 'Insulated Panel Series',
		'outdoor' => 'Weather-Resistant Series',
	] );

	cw_admin_field( '_prod_price', 'Installed price (from)', get_post_meta( $post->ID, '_prod_price', true ), 'text' );
	cw_admin_field( '_prod_capacity', 'Bottle capacity', get_post_meta( $post->ID, '_prod_capacity', true ) );
	cw_admin_field( '_prod_install', 'Installation type', get_post_meta( $post->ID, '_prod_install', true ) );
	cw_admin_field( '_prod_dimensions', 'Dimensions (W×H×D)', get_post_meta( $post->ID, '_prod_dimensions', true ) );

	echo '<p class="cw-admin-field"><strong>Suitable situations</strong><br><span class="description">Controls filters on the Products page.</span></p>';
	echo '<div class="cw-admin-checkboxes cw-admin-checkboxes--stacked">';
	foreach ( cw_get_situation_filters() as $slug => $label ) {
		printf(
			'<label class="cw-admin-check"><input type="checkbox" name="_prod_situations[]" value="%s"%s> %s</label>',
			esc_attr( $slug ),
			checked( in_array( $slug, $situations, true ), true, false ),
			esc_html( $label )
		);
	}
	echo '</div>';

	echo '<details class="cw-admin-more"><summary>More specifications</summary>';
	foreach ( [
		'_prod_volume'        => 'Conditioned volume',
		'_prod_temp_range'    => 'Temperature range',
		'_prod_temp_accuracy' => 'Temperature accuracy',
		'_prod_humidity'      => 'Humidity',
		'_prod_cooling'       => 'Cooling capacity',
		'_prod_power_draw'    => 'Power draw',
		'_prod_power'         => 'Power supply',
		'_prod_noise'         => 'Noise level',
		'_prod_refrigerant'   => 'Refrigerant',
		'_prod_airflow'       => 'Air circulation',
		'_prod_finish'        => 'Enclosure finish',
	] as $key => $label ) {
		cw_admin_field( $key, $label, get_post_meta( $post->ID, $key, true ) );
	}
	echo '</details>';

	printf(
		'<p class="cw-admin-field"><label class="cw-admin-check"><input type="checkbox" name="_cw_show_in_gallery" value="1"%s> Show on Gallery page</label></p>',
		checked( get_post_meta( $post->ID, '_cw_show_in_gallery', true ) !== '0', true, false )
	);
}

function cw_render_installation_meta_box( $post ) {
	wp_nonce_field( 'cw_save_installation_meta', 'cw_installation_meta_nonce' );

	$property_options = [ '' => '— Select property type —' ] + cw_get_situation_filters();

	echo '<p class="description" style="margin-top:0;">Property type controls the filter buttons on the Installations page.</p>';

	cw_admin_field( '_cs_property', 'Property type', get_post_meta( $post->ID, '_cs_property', true ), 'select', $property_options );
	cw_admin_field( '_cs_location', 'Location (suburb, state)', get_post_meta( $post->ID, '_cs_location', true ) );
	cw_admin_field( '_cs_type', 'Configuration installed', get_post_meta( $post->ID, '_cs_type', true ) );
	cw_admin_field( '_cs_bottles', 'Bottle count', get_post_meta( $post->ID, '_cs_bottles', true ) );
	cw_admin_field( '_cs_temp', 'Storage temperature', get_post_meta( $post->ID, '_cs_temp', true ) );
	cw_admin_field( '_cs_duration', 'Installed (month/year)', get_post_meta( $post->ID, '_cs_duration', true ) );

	$show = get_post_meta( $post->ID, '_cw_show_in_gallery', true );
	printf(
		'<p class="cw-admin-field"><label class="cw-admin-check"><input type="checkbox" name="_cw_show_in_gallery" value="1"%s> Show on Gallery page</label></p>',
		checked( $show !== '0', true, false )
	);
}

function cw_render_rack_meta_box( $post ) {
	wp_nonce_field( 'cw_save_rack_meta', 'cw_rack_meta_nonce' );

	echo '<p class="description" style="margin-top:0;">Use the title for the rack name, the excerpt for a short summary, and the main editor for fuller notes.</p>';
	cw_admin_field( '_rack_style', 'Style label', get_post_meta( $post->ID, '_rack_style', true ) );
	cw_admin_field( '_rack_theme_image', 'Theme image fallback', get_post_meta( $post->ID, '_rack_theme_image', true ) );

	$show = get_post_meta( $post->ID, '_cw_show_in_gallery', true );
	printf(
		'<p class="cw-admin-field"><label class="cw-admin-check"><input type="checkbox" name="_cw_show_in_gallery" value="1"%s> Show on Gallery page</label></p>',
		checked( $show !== '0', true, false )
	);
}

function cw_render_media_meta_box( $post ) {
	$video_id    = (int) get_post_meta( $post->ID, '_cw_video_id', true );
	$gallery_ids = cw_get_post_gallery_ids( $post->ID );
	$video_url   = $video_id ? wp_get_attachment_url( $video_id ) : '';

	echo '<p class="description"><strong>Featured image</strong> (right sidebar) is the main photo. Add extra photos and an optional video below.</p>';

	echo '<div class="cw-admin-media-block">';
	echo '<p><strong>Video</strong></p>';
	echo '<input type="hidden" id="cw_video_id" name="_cw_video_id" value="' . esc_attr( $video_id ) . '">';
	echo '<div id="cw-video-preview" class="cw-admin-preview">';
	if ( $video_url ) {
		echo '<video src="' . esc_url( $video_url ) . '" controls style="max-width:100%;max-height:200px;"></video>';
	}
	echo '</div>';
	echo '<p><button type="button" class="button" id="cw-video-select">Select / upload video</button> ';
	echo '<button type="button" class="button" id="cw-video-remove"' . ( $video_id ? '' : ' style="display:none;"' ) . '>Remove video</button></p>';
	echo '</div>';

	echo '<div class="cw-admin-media-block">';
	echo '<p><strong>Additional photos</strong></p>';
	echo '<input type="hidden" id="cw_gallery_ids" name="_cw_gallery_ids" value="' . esc_attr( implode( ',', $gallery_ids ) ) . '">';
	echo '<ul id="cw-gallery-preview" class="cw-admin-gallery">';
	foreach ( $gallery_ids as $aid ) {
		$thumb = wp_get_attachment_image_url( $aid, 'thumbnail' );
		if ( $thumb ) {
			printf( '<li data-id="%d"><img src="%s" alt=""></li>', $aid, esc_url( $thumb ) );
		}
	}
	echo '</ul>';
	echo '<p><button type="button" class="button" id="cw-gallery-select">Add photos</button> ';
	echo '<button type="button" class="button" id="cw-gallery-clear">Clear all</button></p>';
	echo '</div>';
}

function cw_render_gallery_item_meta_box( $post ) {
	wp_nonce_field( 'cw_save_gallery_item_meta', 'cw_gallery_item_meta_nonce' );

	$video_id  = (int) get_post_meta( $post->ID, '_cw_video_id', true );
	$video_url = $video_id ? wp_get_attachment_url( $video_id ) : '';

	echo '<p class="description">Optional. If set, this tile plays as video on the Gallery page. The featured image is used as the poster frame.</p>';
	echo '<input type="hidden" id="cw_video_id" name="_cw_video_id" value="' . esc_attr( $video_id ) . '">';
	echo '<div id="cw-video-preview" class="cw-admin-preview">';
	if ( $video_url ) {
		echo '<video src="' . esc_url( $video_url ) . '" controls style="max-width:100%;max-height:200px;"></video>';
	}
	echo '</div>';
	echo '<p><button type="button" class="button" id="cw-video-select">Select / upload video</button> ';
	echo '<button type="button" class="button" id="cw-video-remove"' . ( $video_id ? '' : ' style="display:none;"' ) . '>Remove video</button></p>';
}

/* -------------------------------------------------------------------------
 * Save handlers
 * ---------------------------------------------------------------------- */
add_action( 'save_post_product', 'cw_save_product_meta' );
add_action( 'save_post_case_study', 'cw_save_installation_meta' );
add_action( 'save_post_rack', 'cw_save_rack_meta' );
add_action( 'save_post_cw_gallery', 'cw_save_gallery_item_meta' );

function cw_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['cw_product_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cw_product_meta_nonce'], 'cw_save_product_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$series = sanitize_key( wp_unslash( $_POST['_prod_series'] ?? '' ) );
	update_post_meta( $post_id, '_prod_series', $series );

	$situations = isset( $_POST['_prod_situations'] ) ? cw_sanitize_situation_array( wp_unslash( $_POST['_prod_situations'] ) ) : [];
	update_post_meta( $post_id, '_prod_situations', $situations );

	foreach ( cw_get_product_spec_schema() as $field ) {
		$key = $field['meta'];
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	cw_save_shared_media_meta( $post_id );
}

function cw_save_installation_meta( $post_id ) {
	if ( ! isset( $_POST['cw_installation_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cw_installation_meta_nonce'], 'cw_save_installation_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$property = sanitize_key( wp_unslash( $_POST['_cs_property'] ?? '' ) );
	update_post_meta( $post_id, '_cs_property', $property );

	foreach ( [ '_cs_location', '_cs_type', '_cs_bottles', '_cs_temp', '_cs_duration' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	cw_save_shared_media_meta( $post_id );
}

function cw_save_rack_meta( $post_id ) {
	if ( ! isset( $_POST['cw_rack_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cw_rack_meta_nonce'], 'cw_save_rack_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( [ '_rack_style', '_rack_theme_image' ] as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}

	cw_save_shared_media_meta( $post_id );
}

function cw_save_gallery_item_meta( $post_id ) {
	if ( ! isset( $_POST['cw_gallery_item_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cw_gallery_item_meta_nonce'], 'cw_save_gallery_item_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	update_post_meta( $post_id, '_cw_video_id', absint( $_POST['_cw_video_id'] ?? 0 ) );
}

function cw_save_shared_media_meta( $post_id ) {
	$video_id = absint( $_POST['_cw_video_id'] ?? 0 );
	update_post_meta( $post_id, '_cw_video_id', $video_id );

	$gallery_raw = wp_unslash( $_POST['_cw_gallery_ids'] ?? '' );
	$gallery_ids = cw_sanitize_gallery_ids( is_string( $gallery_raw ) ? explode( ',', $gallery_raw ) : $gallery_raw );
	update_post_meta( $post_id, '_cw_gallery_ids', $gallery_ids );

	$show = isset( $_POST['_cw_show_in_gallery'] ) ? '1' : '0';
	update_post_meta( $post_id, '_cw_show_in_gallery', $show );
}

/* -------------------------------------------------------------------------
 * Admin assets
 * ---------------------------------------------------------------------- */
add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->post_type, [ 'product', 'case_study', 'rack', 'cw_gallery' ], true ) ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_style(
		'cw-admin-meta',
		get_template_directory_uri() . '/assets/css/admin-meta.css',
		[],
		CW_VERSION
	);
	wp_enqueue_script(
		'cw-admin-meta',
		get_template_directory_uri() . '/assets/js/admin-meta.js',
		[ 'jquery' ],
		CW_VERSION,
		true
	);
} );

/* -------------------------------------------------------------------------
 * Admin list columns
 * ---------------------------------------------------------------------- */
add_filter( 'manage_product_posts_columns', function ( $cols ) {
	$new = [];
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['cw_series'] = 'Series';
			$new['cw_price']  = 'Price';
		}
	}
	return $new;
} );

add_action( 'manage_product_posts_custom_column', function ( $col, $post_id ) {
	if ( $col === 'cw_series' ) {
		echo esc_html( cw_get_product_series_label( get_post_meta( $post_id, '_prod_series', true ) ) );
	}
	if ( $col === 'cw_price' ) {
		echo esc_html( get_post_meta( $post_id, '_prod_price', true ) ?: '—' );
	}
}, 10, 2 );

add_filter( 'manage_case_study_posts_columns', function ( $cols ) {
	$new = [];
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['cw_property'] = 'Property';
			$new['cw_location'] = 'Location';
		}
	}
	return $new;
} );

add_action( 'manage_case_study_posts_custom_column', function ( $col, $post_id ) {
	if ( $col === 'cw_property' ) {
		$slug = get_post_meta( $post_id, '_cs_property', true );
		$filters = cw_get_situation_filters();
		echo esc_html( $filters[ $slug ] ?? '—' );
	}
	if ( $col === 'cw_location' ) {
		echo esc_html( get_post_meta( $post_id, '_cs_location', true ) ?: '—' );
	}
}, 10, 2 );

add_filter( 'manage_rack_posts_columns', function ( $cols ) {
	$new = [];
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['cw_rack_style'] = 'Style';
		}
	}
	return $new;
} );

add_action( 'manage_rack_posts_custom_column', function ( $col, $post_id ) {
	if ( $col === 'cw_rack_style' ) {
		echo esc_html( get_post_meta( $post_id, '_rack_style', true ) ?: '—' );
	}
}, 10, 2 );

add_filter( 'enter_title_here', function ( $title, $post ) {
	if ( $post->post_type === 'cw_faq' ) {
		return 'Add the question';
	}
	if ( $post->post_type === 'cw_gallery' ) {
		return 'Add a short title for this photo or video';
	}
	return $title;
}, 10, 2 );

add_filter( 'manage_cw_gallery_posts_columns', function ( $cols ) {
	$new = [];
	foreach ( $cols as $key => $label ) {
		if ( $key === 'title' ) {
			$new['cw_thumb'] = 'Image';
		}
		$new[ $key ] = $label;
	}
	return $new;
} );

add_action( 'manage_cw_gallery_posts_custom_column', function ( $col, $post_id ) {
	if ( $col !== 'cw_thumb' ) {
		return;
	}
	$thumb = get_the_post_thumbnail( $post_id, [ 48, 48 ] );
	echo $thumb ? $thumb : '—';
}, 10, 2 );

add_filter( 'manage_cw_faq_posts_columns', function ( $cols ) {
	$new = [];
	foreach ( $cols as $key => $label ) {
		$new[ $key ] = $label;
		if ( $key === 'title' ) {
			$new['cw_home'] = 'Home strip';
		}
	}
	return $new;
} );

add_action( 'manage_cw_faq_posts_custom_column', function ( $col, $post_id ) {
	if ( $col !== 'cw_home' ) {
		return;
	}
	echo has_term( 'home', 'faq_category', $post_id ) ? 'Yes' : '—';
}, 10, 2 );
