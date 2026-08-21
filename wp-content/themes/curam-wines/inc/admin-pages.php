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
		'[cw_trust_strip]'            => 'Trust strip (lines from Settings → Site copy)',
		'[cw_trust_strip compact="1"]'=> 'Compact trust strip',
		'[cw_fit_guide]'              => 'Situation → product guide',
		'[cw_product_grid]'           => 'Filterable product cards',
		'[cw_product_compare]'        => 'Compare table (from products)',
		'[cw_series_cards]'           => 'Series overview cards',
		'[cw_shared_specs]'           => 'Shared climate specs',
		'[cw_shared_specs context="engineering"]' => 'Engineering spec table',
		'[cw_installations_grid]'     => 'Installation cards',
		'[cw_gallery_grid]'           => 'Gallery grid + lightbox (from Gallery items)',
		'[cw_top_faq]'                => 'Common questions strip (FAQs in Home page category)',
		'[cw_faq_list]'               => 'Full FAQ list grouped by category',
		'[cw_video_block]'            => 'Featured video (title/caption from Site copy)',
		'[cw_featured_installation]'  => 'Latest installation',
		'[cw_enquiry_form]'           => 'Quote request form',
		'[cw_contact_details]'        => 'Contact page phone / email block',
		'[cw_contact_form]'           => 'General contact form',
		'[cw_enquiry_contact]'        => 'Phone + email from site settings',
		'[cw_phone]'                  => 'Business phone (from settings)',
		'[cw_email]'                  => 'Business email (from settings)',
		'[cw_process_steps]'          => 'Walk-in definition + process steps',
		'[cw_racking_styles]'         => 'Racking styles grid (fallback / embeds)',
		'[cw_min_price]'              => 'Lowest product price inline',
	];
	foreach ( $shortcodes as $code => $label ) {
		printf( '<li><code>%s</code><br>%s</li>', esc_html( $code ), esc_html( $label ) );
	}
	echo '</ul>';
	echo '<p class="description"><strong>Hero:</strong> Page title, <strong>Excerpt</strong> (subtitle), and <strong>Featured image</strong> control the plate hero on standard pages.</p>';
	echo '<p class="description"><strong>Standard pages</strong> (e.g. Thank you) use the default template — content is centred in a readable column. Choose <strong>Full width (shortcodes)</strong> in Page attributes if the page is built from shortcodes only.</p>';
	echo '<p class="description"><strong>Installations archive</strong> (/installations/) intro is edited on the page titled <strong>Installations</strong> (slug <code>installations-intro</code>) — not shown in the main nav.</p>';
	echo '<p class="description"><strong>Racking archive</strong> (/racking/) is now driven by the <strong>Racks</strong> post type. Edit archive hero/SEO on the page with slug <code>racking-intro</code>.</p>';
	echo '<p class="description"><strong>FAQs</strong> are edited under <strong>FAQs</strong> in the admin menu — title is the question, editor is the answer, categories group them on /faq/. Assign the <strong>Home page</strong> category to show a question in the homepage strip.</p>';
	echo '<p class="description"><strong>Gallery</strong> items are edited under <strong>Gallery</strong>. Featured image + gallery category control the public gallery filters. The Gallery page intro is still the page titled Gallery.</p>';
	echo '<p class="description"><strong>Process steps</strong> are edited under <strong>Process</strong> (title + excerpt, drag to reorder). Heading, intro, trust strip, video copy, and fit-guide headings are under <strong>Settings → Site copy</strong>.</p>';
	echo '<p class="description"><strong>Products, installations, and racks</strong> are edited under their own admin menus — tables and grids update automatically.</p>';
}

add_action( 'admin_notices', function () {
	$screen = get_current_screen();
	if ( ! $screen || $screen->base !== 'post' || $screen->post_type !== 'page' ) {
		return;
	}
	echo '<div class="notice notice-info"><p><strong>Editing a site page:</strong> Write your headings and paragraphs in the main editor. Use shortcodes from the <strong>Dynamic content blocks</strong> box (right sidebar) wherever you want product grids, compare tables, or the gallery. Product specs and prices always come from <strong>Products</strong>, <strong>Installations</strong>, and <strong>Racks</strong>. Questions come from <strong>FAQs</strong>. Photos on /gallery/ come from <strong>Gallery</strong>. Process steps come from <strong>Process</strong>. One-off headings (trust strip, video, fit guide) are under <strong>Settings → Site copy</strong>.</p></div>';
} );
