<?php
/**
 * Page editor helpers — shortcode reference for site pages.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'cw_page_blocks',
		'Dynamic content blocks',
		'cw_render_page_blocks_meta_box',
		'page',
		'side',
		'high'
	);
}, 20 );

function cw_render_page_blocks_meta_box( $post ) {
	echo '<p class="description" style="margin-top:0;">Paste these shortcodes into the page editor where you want live product or installation data. Edit headings and copy as normal HTML.</p>';
	echo '<ul style="margin:0;padding-left:1.2em;font-size:12px;line-height:1.6;">';
	$shortcodes = [
		'[cw_home_hero]'              => 'Homepage hero (front page only)',
		'[cw_trust_strip]'            => 'Trust strip (price pulled from products)',
		'[cw_trust_strip compact="1"]'=> 'Compact trust strip',
		'[cw_fit_guide]'              => 'Situation → product guide',
		'[cw_product_grid]'           => 'Filterable product cards',
		'[cw_product_compare]'        => 'Compare table (from products)',
		'[cw_series_cards]'           => 'Series overview cards',
		'[cw_shared_specs]'           => 'Shared climate specs',
		'[cw_shared_specs context="engineering"]' => 'Engineering spec table',
		'[cw_installations_grid]'     => 'Installation cards',
		'[cw_gallery_grid]'           => 'Gallery grid + lightbox',
		'[cw_top_faq]'                => 'Common questions strip',
		'[cw_video_block title="…"]'  => 'Featured video',
		'[cw_featured_installation]'  => 'Latest installation',
		'[cw_enquiry_form]'           => 'Quote request form',
		'[cw_process_steps]'          => 'Walk-in definition + 7-step process',
		'[cw_racking_styles]'         => 'Racking styles grid (fallback / embeds)',
		'[cw_min_price]'              => 'Lowest product price inline',
	];
	foreach ( $shortcodes as $code => $label ) {
		printf( '<li><code>%s</code><br>%s</li>', esc_html( $code ), esc_html( $label ) );
	}
	echo '</ul>';
	echo '<p class="description"><strong>Hero:</strong> Page title, <strong>Excerpt</strong> (subtitle), and <strong>Featured image</strong> control the plate hero on standard pages.</p>';
	echo '<p class="description"><strong>Installations archive</strong> (/installations/) intro is edited on the page titled <strong>Installations</strong> (slug <code>installations-intro</code>) — not shown in the main nav.</p>';
	echo '<p class="description"><strong>Racking archive</strong> (/racking/) is now driven by the <strong>Racks</strong> post type. Edit archive hero/SEO on the page with slug <code>racking-intro</code>.</p>';
	echo '<p class="description"><strong>Products, installations, and racks</strong> are edited under their own admin menus — tables and grids update automatically.</p>';
}

add_action( 'admin_notices', function () {
	$screen = get_current_screen();
	if ( ! $screen || $screen->base !== 'post' || $screen->post_type !== 'page' ) {
		return;
	}
	echo '<div class="notice notice-info"><p><strong>Editing a site page:</strong> Write your headings and paragraphs in the main editor. Use shortcodes from the <strong>Dynamic content blocks</strong> box (right sidebar) wherever you want product grids, compare tables, or the gallery. Product specs and prices always come from <strong>Products</strong>, <strong>Installations</strong>, and <strong>Racks</strong>.</p></div>';
} );
