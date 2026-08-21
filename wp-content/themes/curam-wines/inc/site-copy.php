<?php
/**
 * Settings → Site copy — one-off headings and lines that are not a post type.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_default_site_copy() {
	return [
		'hero_eyebrow'       => 'Walk-In Wine Cabinets Australia',
		'trust_items'        => "2-year | parts & warranty\nAustralia-wide | installations\n6–9 weeks | start to finish\nAustralian-made | since 2011",
		'process_heading'    => 'What is a walk-in wine cellar?',
		'process_intro'      => 'A climate-controlled walk-in room, purpose-built to cellar wine long-term at a steady 12–14°C. The fit-out is yours to set — maximise bottle capacity, put the collection on display, or balance both.',
		'process_caption'    => 'Purpose-built for long-term cellaring — climate first, fit-out to suit.',
		'process_image_id'   => 0,
		'video_title'        => 'See a cabinet installed',
		'video_intro'        => '',
		'video_caption'      => 'A finished unit positioned, connected, and commissioned — no building work on site.',
		'video_poster_id'    => 0,
		'fit_guide_heading'  => 'Which configuration fits your space?',
		'fit_guide_intro'    => 'Start with your situation — not our internal series names. Each links to the full specification, or get a quote and we\'ll confirm.',
		'min_price_fallback' => 'From $5,400 installed',
		'engineering_specs'  => "Recovery after door open (30s) | Under 90 seconds\nCooling type | Compressor-based (not thermoelectric)",
	];
}

function cw_get_site_copy() {
	$stored = get_option( 'cw_site_copy', [] );

	return wp_parse_args( is_array( $stored ) ? $stored : [], cw_default_site_copy() );
}

function cw_get_site_copy_setting( $key, $default = '' ) {
	$copy = cw_get_site_copy();

	if ( ! array_key_exists( $key, $copy ) ) {
		return $default;
	}

	$value = $copy[ $key ];
	if ( $value === '' || $value === null ) {
		return $default;
	}

	return $value;
}

/**
 * Parse "Label | rest" lines from a setting. Lines without a pipe are a label only.
 *
 * @return array<int, array{label: string, value: string}>
 */
function cw_parse_label_value_lines( $raw ) {
	$rows = [];
	$raw  = is_string( $raw ) ? $raw : '';

	foreach ( preg_split( '/\r\n|\r|\n/', $raw ) as $line ) {
		$line = trim( $line );
		if ( $line === '' ) {
			continue;
		}

		if ( strpos( $line, '|' ) !== false ) {
			[ $label, $value ] = array_map( 'trim', explode( '|', $line, 2 ) );
		} else {
			$label = $line;
			$value = '';
		}

		if ( $label === '' && $value === '' ) {
			continue;
		}

		$rows[] = [
			'label' => $label,
			'value' => $value,
		];
	}

	return $rows;
}

function cw_get_trust_items() {
	$defaults = cw_default_site_copy();
	$rows     = cw_parse_label_value_lines( cw_get_site_copy_setting( 'trust_items', $defaults['trust_items'] ) );

	if ( empty( $rows ) ) {
		$rows = cw_parse_label_value_lines( $defaults['trust_items'] );
	}

	$items = [];
	foreach ( $rows as $row ) {
		$items[] = [
			'strong' => $row['label'],
			'rest'   => $row['value'],
		];
	}

	return $items;
}

function cw_get_engineering_extra_specs() {
	$defaults = cw_default_site_copy();

	return cw_parse_label_value_lines(
		cw_get_site_copy_setting( 'engineering_specs', $defaults['engineering_specs'] )
	);
}

add_action( 'admin_menu', function () {
	add_options_page(
		'Site copy',
		'Site copy',
		'manage_options',
		'cw-site-copy',
		'cw_render_site_copy_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'cw_site_copy_group', 'cw_site_copy', [
		'type'              => 'array',
		'sanitize_callback' => 'cw_sanitize_site_copy',
	] );
} );

function cw_sanitize_site_copy( $input ) {
	$clean = cw_get_site_copy();
	if ( ! is_array( $input ) ) {
		return $clean;
	}

	$clean['hero_eyebrow']       = sanitize_text_field( $input['hero_eyebrow'] ?? '' );
	$clean['trust_items']        = sanitize_textarea_field( $input['trust_items'] ?? '' );
	$clean['process_heading']    = sanitize_text_field( $input['process_heading'] ?? '' );
	$clean['process_intro']      = sanitize_textarea_field( $input['process_intro'] ?? '' );
	$clean['process_caption']    = sanitize_text_field( $input['process_caption'] ?? '' );
	$clean['process_image_id']   = absint( $input['process_image_id'] ?? 0 );
	$clean['video_title']        = sanitize_text_field( $input['video_title'] ?? '' );
	$clean['video_intro']        = sanitize_textarea_field( $input['video_intro'] ?? '' );
	$clean['video_caption']      = sanitize_textarea_field( $input['video_caption'] ?? '' );
	$clean['video_poster_id']    = absint( $input['video_poster_id'] ?? 0 );
	$clean['fit_guide_heading']  = sanitize_text_field( $input['fit_guide_heading'] ?? '' );
	$clean['fit_guide_intro']    = sanitize_textarea_field( $input['fit_guide_intro'] ?? '' );
	$clean['min_price_fallback'] = sanitize_text_field( $input['min_price_fallback'] ?? '' );
	$clean['engineering_specs']  = sanitize_textarea_field( $input['engineering_specs'] ?? '' );

	return $clean;
}

function cw_render_site_copy_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$copy           = cw_get_site_copy();
	$process_img    = $copy['process_image_id'] ? wp_get_attachment_image_url( (int) $copy['process_image_id'], 'medium' ) : '';
	$poster_img     = $copy['video_poster_id'] ? wp_get_attachment_image_url( (int) $copy['video_poster_id'], 'medium' ) : '';
	$process_url    = admin_url( 'edit.php?post_type=cw_process' );
	?>
	<div class="wrap">
		<h1>Site copy</h1>
		<p>Headings and one-off lines used by homepage shortcodes. Repeating lists (process steps, FAQs, products) are edited as post types, not here.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'cw_site_copy_group' ); ?>
			<table class="form-table" role="presentation">
				<tr><th colspan="2"><h2 class="title" style="margin:0;">Homepage hero</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_hero_eyebrow">Eyebrow</label></th>
					<td>
						<input type="text" class="regular-text" id="cw_hero_eyebrow" name="cw_site_copy[hero_eyebrow]" value="<?php echo esc_attr( $copy['hero_eyebrow'] ); ?>">
						<p class="description">Small label above the homepage headline. Headline, subtitle, and photo still come from the front page title, excerpt, and featured image. Override on the shortcode with <code>eyebrow="…"</code> if needed.</p>
					</td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Trust strip</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_trust_items">Lines</label></th>
					<td>
						<textarea class="large-text" rows="6" id="cw_trust_items" name="cw_site_copy[trust_items]"><?php echo esc_textarea( $copy['trust_items'] ); ?></textarea>
						<p class="description">One item per line. Use a pipe to split the bold lead from the rest: <code>2-year | parts &amp; warranty</code>.</p>
					</td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Process section</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_process_heading">Heading</label></th>
					<td><input type="text" class="large-text" id="cw_process_heading" name="cw_site_copy[process_heading]" value="<?php echo esc_attr( $copy['process_heading'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_process_intro">Intro</label></th>
					<td>
						<textarea class="large-text" rows="4" id="cw_process_intro" name="cw_site_copy[process_intro]"><?php echo esc_textarea( $copy['process_intro'] ); ?></textarea>
						<p class="description">The seven steps themselves are edited under <a href="<?php echo esc_url( $process_url ); ?>">Process</a> (title + excerpt, drag to reorder).</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_process_caption">Figure caption</label></th>
					<td><input type="text" class="large-text" id="cw_process_caption" name="cw_site_copy[process_caption]" value="<?php echo esc_attr( $copy['process_caption'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_process_image_id">Illustration image ID</label></th>
					<td>
						<input type="number" class="small-text" id="cw_process_image_id" name="cw_site_copy[process_image_id]" value="<?php echo esc_attr( $copy['process_image_id'] ); ?>" min="0">
						<?php if ( $process_img ) : ?><br><img src="<?php echo esc_url( $process_img ); ?>" alt="" style="max-width:240px;margin-top:0.5rem;"><?php endif; ?>
						<p class="description">Media Library attachment ID. Leave 0 to use the theme illustration.</p>
					</td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Featured video</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_video_title">Title</label></th>
					<td><input type="text" class="large-text" id="cw_video_title" name="cw_site_copy[video_title]" value="<?php echo esc_attr( $copy['video_title'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_video_intro">Intro</label></th>
					<td><textarea class="large-text" rows="2" id="cw_video_intro" name="cw_site_copy[video_intro]"><?php echo esc_textarea( $copy['video_intro'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_video_caption">Caption</label></th>
					<td><textarea class="large-text" rows="2" id="cw_video_caption" name="cw_site_copy[video_caption]"><?php echo esc_textarea( $copy['video_caption'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_video_poster_id">Poster image ID</label></th>
					<td>
						<input type="number" class="small-text" id="cw_video_poster_id" name="cw_site_copy[video_poster_id]" value="<?php echo esc_attr( $copy['video_poster_id'] ); ?>" min="0">
						<?php if ( $poster_img ) : ?><br><img src="<?php echo esc_url( $poster_img ); ?>" alt="" style="max-width:240px;margin-top:0.5rem;"><?php endif; ?>
						<p class="description">Media Library attachment ID for the video thumbnail. Leave 0 to use the default living-room photo.</p>
					</td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Fit guide</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_fit_heading">Heading</label></th>
					<td><input type="text" class="large-text" id="cw_fit_heading" name="cw_site_copy[fit_guide_heading]" value="<?php echo esc_attr( $copy['fit_guide_heading'] ); ?>">
					<p class="description">The situation → product rows still come from Products (situation checkboxes).</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_fit_intro">Intro</label></th>
					<td><textarea class="large-text" rows="3" id="cw_fit_intro" name="cw_site_copy[fit_guide_intro]"><?php echo esc_textarea( $copy['fit_guide_intro'] ); ?></textarea></td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Prices &amp; engineering extras</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_min_price_fallback">Minimum price fallback</label></th>
					<td>
						<input type="text" class="regular-text" id="cw_min_price_fallback" name="cw_site_copy[min_price_fallback]" value="<?php echo esc_attr( $copy['min_price_fallback'] ); ?>">
						<p class="description">Used by <code>[cw_min_price]</code> only if no published product has a price.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_engineering_specs">Engineering extra rows</label></th>
					<td>
						<textarea class="large-text" rows="4" id="cw_engineering_specs" name="cw_site_copy[engineering_specs]"><?php echo esc_textarea( $copy['engineering_specs'] ); ?></textarea>
						<p class="description">Appended to <code>[cw_shared_specs context="engineering"]</code>. One row per line as <code>Label | Value</code>. Climate figures still come from product specs.</p>
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
