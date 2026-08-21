<?php
/**
 * Per-post SEO meta + front-end meta tag output.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CW_SEO_BRAND', 'Walk-In Wine Cabinets Australia' );

add_action( 'init', function () {
	foreach ( [ 'page', 'product', 'case_study', 'rack' ] as $post_type ) {
		register_post_meta( $post_type, '_cw_meta_title', [
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		] );
		register_post_meta( $post_type, '_cw_meta_description', [
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_textarea_field',
		] );
		register_post_meta( $post_type, '_cw_meta_robots', [
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
		] );
		register_post_meta( $post_type, '_cw_og_image_id', [
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'absint',
		] );
	}
} );

add_action( 'add_meta_boxes', function () {
	foreach ( [ 'page', 'product', 'case_study', 'rack' ] as $post_type ) {
		add_meta_box(
			'cw_seo_meta',
			'SEO',
			'cw_render_seo_meta_box',
			$post_type,
			'side',
			'default'
		);
	}
}, 25 );

function cw_render_seo_meta_box( $post ) {
	wp_nonce_field( 'cw_save_seo_meta', 'cw_seo_meta_nonce' );

	$title       = get_post_meta( $post->ID, '_cw_meta_title', true );
	$description = get_post_meta( $post->ID, '_cw_meta_description', true );
	$robots      = get_post_meta( $post->ID, '_cw_meta_robots', true );
	$og_image_id = (int) get_post_meta( $post->ID, '_cw_og_image_id', true );

	echo '<p class="description" style="margin-top:0;">Overrides the auto-generated title and description. Leave blank to use page title / excerpt.</p>';

	echo '<p><label for="_cw_meta_title"><strong>SEO title</strong></label><br>';
	printf(
		'<input type="text" class="widefat" id="_cw_meta_title" name="_cw_meta_title" value="%s" maxlength="70">',
		esc_attr( $title )
	);
	echo '<span class="description">~60 chars. Shown in browser tab and search results.</span></p>';

	echo '<p><label for="_cw_meta_description"><strong>Meta description</strong></label><br>';
	printf(
		'<textarea class="widefat" id="_cw_meta_description" name="_cw_meta_description" rows="4" maxlength="320">%s</textarea>',
		esc_textarea( $description )
	);
	echo '<span class="description">~150 chars for Google snippets.</span></p>';

	echo '<p><label for="_cw_meta_robots"><strong>Robots</strong></label><br>';
	echo '<select class="widefat" id="_cw_meta_robots" name="_cw_meta_robots">';
	foreach ( [
		''            => 'Index, follow (default)',
		'noindex'     => 'Noindex, follow',
		'noindex,nofollow' => 'Noindex, nofollow',
	] as $value => $label ) {
		printf(
			'<option value="%s"%s>%s</option>',
			esc_attr( $value ),
			selected( $robots, $value, false ),
			esc_html( $label )
		);
	}
	echo '</select></p>';

	echo '<p><label for="_cw_og_image_id"><strong>Social image ID</strong></label><br>';
	printf(
		'<input type="number" class="widefat" id="_cw_og_image_id" name="_cw_og_image_id" value="%s" min="0">',
		$og_image_id ? esc_attr( $og_image_id ) : ''
	);
	echo '<span class="description">Media Library attachment ID. Defaults to featured image.</span></p>';
}

add_action( 'save_post', 'cw_save_seo_meta', 20 );

function cw_save_seo_meta( $post_id ) {
	if ( ! isset( $_POST['cw_seo_meta_nonce'] ) || ! wp_verify_nonce( $_POST['cw_seo_meta_nonce'], 'cw_save_seo_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$post_type = get_post_type( $post_id );
	if ( ! in_array( $post_type, [ 'page', 'product', 'case_study', 'rack' ], true ) ) {
		return;
	}

	update_post_meta( $post_id, '_cw_meta_title', sanitize_text_field( wp_unslash( $_POST['_cw_meta_title'] ?? '' ) ) );
	update_post_meta( $post_id, '_cw_meta_description', sanitize_textarea_field( wp_unslash( $_POST['_cw_meta_description'] ?? '' ) ) );
	update_post_meta( $post_id, '_cw_meta_robots', sanitize_text_field( wp_unslash( $_POST['_cw_meta_robots'] ?? '' ) ) );
	update_post_meta( $post_id, '_cw_og_image_id', absint( $_POST['_cw_og_image_id'] ?? 0 ) );
}

function cw_trim_meta_description( $text, $length = 160 ) {
	$text = wp_strip_all_tags( (string) $text );
	$text = preg_replace( '/\s+/', ' ', trim( $text ) );

	if ( strlen( $text ) <= $length ) {
		return $text;
	}

	return rtrim( substr( $text, 0, $length - 1 ) ) . '…';
}

function cw_build_auto_meta_description( $post ) {
	if ( ! $post instanceof WP_Post ) {
		return '';
	}

	if ( $post->post_excerpt ) {
		return cw_trim_meta_description( $post->post_excerpt );
	}

	if ( $post->post_type === 'product' ) {
		$parts = array_filter( [
			get_post_meta( $post->ID, '_prod_price', true ),
			get_post_meta( $post->ID, '_prod_capacity', true ),
			get_post_meta( $post->ID, '_prod_install', true ),
		] );
		if ( $parts ) {
			return cw_trim_meta_description( get_the_title( $post ) . ' — ' . implode( '. ', $parts ) . '.' );
		}
	}

	if ( $post->post_type === 'case_study' ) {
		$parts = array_filter( [
			get_post_meta( $post->ID, '_cs_location', true ),
			get_post_meta( $post->ID, '_cs_type', true ),
			get_post_meta( $post->ID, '_cs_bottles', true ) ? get_post_meta( $post->ID, '_cs_bottles', true ) . ' bottles' : '',
		] );
		if ( $parts ) {
			return cw_trim_meta_description( get_the_title( $post ) . ' — ' . implode( '. ', $parts ) . '.' );
		}
	}

	if ( $post->post_content ) {
		return cw_trim_meta_description( $post->post_content );
	}

	return '';
}

function cw_get_seo_context() {
	$brand = CW_SEO_BRAND;
	$ctx   = [
		'title'       => $brand,
		'description' => cw_get_site_setting( 'default_meta_description' ),
		'image'       => '',
		'url'         => home_url( '/' ),
		'type'        => 'website',
		'robots'      => 'index,follow',
	];

	$default_image_id = (int) cw_get_site_setting( 'default_og_image_id', 0 );
	if ( $default_image_id ) {
		$ctx['image'] = wp_get_attachment_image_url( $default_image_id, 'large' ) ?: '';
	}

	if ( is_singular() ) {
		$post = get_queried_object();
		if ( $post instanceof WP_Post ) {
			$custom_title = get_post_meta( $post->ID, '_cw_meta_title', true );
			$custom_desc  = get_post_meta( $post->ID, '_cw_meta_description', true );
			$robots       = get_post_meta( $post->ID, '_cw_meta_robots', true );
			$og_id        = (int) get_post_meta( $post->ID, '_cw_og_image_id', true );

			$ctx['title'] = $custom_title ?: ( get_the_title( $post ) . ' | ' . $brand );
			$ctx['description'] = $custom_desc ?: cw_build_auto_meta_description( $post );
			$ctx['url']   = get_permalink( $post );
			$ctx['type']  = 'article';

			if ( $robots ) {
				$ctx['robots'] = $robots;
			}

			if ( $og_id ) {
				$ctx['image'] = wp_get_attachment_image_url( $og_id, 'large' ) ?: $ctx['image'];
			} elseif ( has_post_thumbnail( $post ) ) {
				$ctx['image'] = get_the_post_thumbnail_url( $post, 'large' ) ?: $ctx['image'];
			}
		}
	} elseif ( is_post_type_archive( 'case_study' ) ) {
		$intro = cw_get_page_by_slug( 'installations-intro' );
		if ( $intro ) {
			$ctx['title']       = get_post_meta( $intro->ID, '_cw_meta_title', true ) ?: ( 'Wine Cabinet Installations | ' . $brand );
			$ctx['description'] = get_post_meta( $intro->ID, '_cw_meta_description', true ) ?: cw_build_auto_meta_description( $intro );
			$ctx['url']         = get_post_type_archive_link( 'case_study' );
			if ( has_post_thumbnail( $intro ) ) {
				$ctx['image'] = get_the_post_thumbnail_url( $intro, 'large' ) ?: $ctx['image'];
			}
		} else {
			$ctx['title']       = 'Wine Cabinet Installations | ' . $brand;
			$ctx['description'] = 'Real walk-in wine cabinet installations in apartments, houses, garages, and balconies across Australia.';
			$ctx['url']         = get_post_type_archive_link( 'case_study' );
		}
	} elseif ( is_post_type_archive( 'rack' ) ) {
		$intro = cw_get_rack_archive_intro_page();
		if ( $intro ) {
			$ctx['title']       = get_post_meta( $intro->ID, '_cw_meta_title', true ) ?: ( 'Wine Racking Styles | ' . $brand );
			$ctx['description'] = get_post_meta( $intro->ID, '_cw_meta_description', true ) ?: cw_build_auto_meta_description( $intro );
			$ctx['url']         = get_post_type_archive_link( 'rack' );
			if ( has_post_thumbnail( $intro ) ) {
				$ctx['image'] = get_the_post_thumbnail_url( $intro, 'large' ) ?: $ctx['image'];
			}
		} else {
			$ctx['title']       = 'Wine Racking Styles | ' . $brand;
			$ctx['description'] = 'Wine racking styles for high-density storage, label-forward display, mixed layouts, diamond bins, magnums, and custom fit-outs.';
			$ctx['url']         = get_post_type_archive_link( 'rack' );
		}
	} elseif ( is_front_page() ) {
		$front_id = (int) get_option( 'page_on_front' );
		if ( $front_id ) {
			$front = get_post( $front_id );
			if ( $front ) {
				$custom_title = get_post_meta( $front_id, '_cw_meta_title', true );
				$custom_desc  = get_post_meta( $front_id, '_cw_meta_description', true );
				$ctx['title'] = $custom_title ?: $brand;
				$ctx['description'] = $custom_desc ?: cw_build_auto_meta_description( $front );
				if ( has_post_thumbnail( $front ) ) {
					$ctx['image'] = get_the_post_thumbnail_url( $front, 'large' ) ?: $ctx['image'];
				}
			}
		}
	}

	if ( $ctx['description'] === '' ) {
		$ctx['description'] = cw_get_site_setting( 'default_meta_description' );
	}

	$ctx['description'] = cw_trim_meta_description( $ctx['description'] );

	return $ctx;
}

add_filter( 'document_title_parts', function ( $parts ) {
	$ctx = cw_get_seo_context();

	if ( is_front_page() ) {
		$parts['title'] = $ctx['title'];
		$parts['tagline'] = '';
	} elseif ( is_singular() || is_post_type_archive( 'case_study' ) || is_post_type_archive( 'rack' ) ) {
		$parts['title'] = preg_replace( '/\s*\|\s*' . preg_quote( CW_SEO_BRAND, '/' ) . '$/', '', $ctx['title'] );
		$parts['site']  = CW_SEO_BRAND;
	}

	return $parts;
}, 20 );

add_action( 'wp_head', function () {
	$ctx = cw_get_seo_context();

	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $ctx['description'] ) );
	printf( '<meta name="robots" content="%s">' . "\n", esc_attr( $ctx['robots'] ) );
	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $ctx['url'] ) );

	printf( '<meta property="og:locale" content="%s">' . "\n", esc_attr( str_replace( '_', '-', get_locale() ) ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( CW_SEO_BRAND ) );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $ctx['title'] ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $ctx['description'] ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $ctx['url'] ) );
	printf( '<meta property="og:type" content="%s">' . "\n", esc_attr( $ctx['type'] ) );

	if ( $ctx['image'] ) {
		printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $ctx['image'] ) );
		printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $ctx['image'] ) );
	}

	printf( '<meta name="twitter:card" content="summary_large_image">' . "\n" );
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $ctx['title'] ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $ctx['description'] ) );

	$twitter = cw_get_site_setting( 'twitter_handle', '' );
	if ( $twitter ) {
		printf( '<meta name="twitter:site" content="@%s">' . "\n", esc_attr( $twitter ) );
	}
}, 5 );

add_action( 'wp_head', function () {
	if ( ! is_front_page() ) {
		return;
	}

	$schema   = [
		'@context'    => 'https://schema.org',
		'@type'       => 'LocalBusiness',
		'name'        => CW_SEO_BRAND,
		'url'         => home_url( '/' ),
		'telephone'   => cw_get_org_phone(),
		'email'       => cw_get_org_email(),
		'areaServed'  => 'AU',
		'description' => cw_get_site_setting( 'default_meta_description' ),
	];

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}, 20 );

function cw_set_seo_meta( $post_id, $title, $description, $robots = '' ) {
	update_post_meta( $post_id, '_cw_meta_title', $title );
	update_post_meta( $post_id, '_cw_meta_description', cw_trim_meta_description( $description ) );
	if ( $robots ) {
		update_post_meta( $post_id, '_cw_meta_robots', $robots );
	}
}
