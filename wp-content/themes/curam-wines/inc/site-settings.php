<?php
/**
 * Settings → SEO & Analytics
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_get_site_settings() {
	$defaults = [
		'ga4_measurement_id'      => '',
		'gtm_container_id'        => '',
		'google_ads_id'           => '',
		'analytics_head'          => '',
		'analytics_body'          => '',
		'default_meta_description'=> 'Australian-made, climate-controlled walk-in wine cabinets — delivered and installed nationwide. Fixed prices from $5,400.',
		'default_og_image_id'     => 0,
		'twitter_handle'          => '',
		'google_site_verification'=> '',
		'bing_site_verification'  => '',
		'org_phone'               => '1300 924 671',
		'org_email'               => 'enquiries@walkinwinecabinets.com.au',
		'enquiry_recipient'       => '',
		'enquiry_brochure_id'     => 0,
		'mail_from_name'          => '',
		'mail_from_email'         => '',
		'smtp_enabled'            => '',
		'smtp_host'               => '',
		'smtp_port'               => '587',
		'smtp_encryption'         => 'tls',
		'smtp_user'               => '',
		'smtp_pass'               => '',
	];

	$stored = get_option( 'cw_site_settings', [] );

	return wp_parse_args( is_array( $stored ) ? $stored : [], $defaults );
}

function cw_get_site_setting( $key, $default = '' ) {
	$settings = cw_get_site_settings();

	return $settings[ $key ] ?? $default;
}

/** Display phone number from Settings → SEO & Analytics. */
function cw_get_org_phone() {
	return cw_get_site_setting( 'org_phone', '1300 924 671' );
}

/** Digits-only phone for tel: links. */
function cw_get_org_phone_tel() {
	return preg_replace( '/[^\d+]/', '', cw_get_org_phone() );
}

/** Business email from Settings → SEO & Analytics. */
function cw_get_org_email() {
	return cw_get_site_setting( 'org_email', '' );
}

/**
 * Swap hardcoded contact details and CTA wording in page/post content.
 */
function cw_replace_site_contact( $content ) {
	if ( ! is_string( $content ) || $content === '' ) {
		return $content;
	}

	$phone = cw_get_org_phone();
	$tel   = cw_get_org_phone_tel();
	$email = cw_get_org_email();

	if ( $tel !== '' ) {
		$content = str_ireplace( 'tel:1300924671', 'tel:' . $tel, $content );
	}
	if ( $phone !== '' ) {
		$content = str_ireplace( '1300 924 671', $phone, $content );
	}
	if ( $email !== '' ) {
		$content = str_ireplace( 'mailto:enquiries@walkinwinecabinets.com.au', 'mailto:' . $email, $content );
		$content = str_ireplace( 'enquiries@walkinwinecabinets.com.au', $email, $content );
	}

	$content = str_replace( 'Get a fixed quote', 'Get a quote', $content );
	$content = str_replace( 'get a fixed quote', 'get a quote', $content );
	$content = str_replace( 'your fixed quote', 'your quote', $content );

	return $content;
}

add_filter( 'the_content', 'cw_replace_site_contact', 20 );
add_filter( 'the_excerpt', 'cw_replace_site_contact', 20 );

add_action( 'admin_menu', function () {
	add_options_page(
		'SEO & Analytics',
		'SEO & Analytics',
		'manage_options',
		'cw-site-settings',
		'cw_render_site_settings_page'
	);
} );

add_action( 'admin_init', function () {
	register_setting( 'cw_site_settings_group', 'cw_site_settings', [
		'type'              => 'array',
		'sanitize_callback' => 'cw_sanitize_site_settings',
	] );
} );

function cw_sanitize_site_settings( $input ) {
	if ( ! is_array( $input ) ) {
		return cw_get_site_settings();
	}

	$clean = cw_get_site_settings();

	$gtm_raw = strtoupper( trim( sanitize_text_field( $input['gtm_container_id'] ?? '' ) ) );
	$ga4_raw = strtoupper( trim( sanitize_text_field( $input['ga4_measurement_id'] ?? '' ) ) );
	$ads_raw = strtoupper( trim( sanitize_text_field( $input['google_ads_id'] ?? '' ) ) );

	[ $gtm_raw, $ga4_raw, $swapped ] = cw_fix_swapped_analytics_ids( $gtm_raw, $ga4_raw );
	if ( $swapped ) {
		set_transient( 'cw_analytics_swapped_notice', $swapped, 30 );
	}

	$clean['gtm_container_id']         = cw_sanitize_gtm_id( $gtm_raw );
	$clean['ga4_measurement_id']       = cw_sanitize_ga4_id( $ga4_raw );
	$clean['google_ads_id']            = cw_sanitize_google_ads_id( $ads_raw );
	$clean['analytics_head']           = cw_sanitize_analytics_snippet( $input['analytics_head'] ?? '' );
	$clean['analytics_body']           = cw_sanitize_analytics_snippet( $input['analytics_body'] ?? '' );
	$clean['default_meta_description'] = sanitize_textarea_field( $input['default_meta_description'] ?? '' );
	$clean['default_og_image_id']      = absint( $input['default_og_image_id'] ?? 0 );
	$clean['twitter_handle']           = sanitize_text_field( ltrim( $input['twitter_handle'] ?? '', '@' ) );
	$clean['google_site_verification'] = sanitize_text_field( $input['google_site_verification'] ?? '' );
	$clean['bing_site_verification']   = sanitize_text_field( $input['bing_site_verification'] ?? '' );
	$clean['org_phone']                = sanitize_text_field( $input['org_phone'] ?? '' );
	$clean['org_email']                = sanitize_email( $input['org_email'] ?? '' );
	$clean['enquiry_recipient']        = sanitize_email( $input['enquiry_recipient'] ?? '' );
	$clean['enquiry_brochure_id']      = cw_sanitize_pdf_attachment_id( $input['enquiry_brochure_id'] ?? 0 );
	$clean['mail_from_name']           = sanitize_text_field( $input['mail_from_name'] ?? '' );
	$clean['mail_from_email']          = sanitize_email( $input['mail_from_email'] ?? '' );
	$clean['smtp_enabled']             = ! empty( $input['smtp_enabled'] ) ? '1' : '';
	$clean['smtp_host']                = sanitize_text_field( $input['smtp_host'] ?? '' );
	$clean['smtp_port']                = sanitize_text_field( $input['smtp_port'] ?? '587' );
	$clean['smtp_encryption']          = in_array( $input['smtp_encryption'] ?? '', [ 'tls', 'ssl', 'none' ], true )
		? $input['smtp_encryption']
		: 'tls';
	$clean['smtp_user']                = sanitize_text_field( $input['smtp_user'] ?? '' );

	$new_smtp_pass = $input['smtp_pass'] ?? '';
	if ( is_string( $new_smtp_pass ) && $new_smtp_pass !== '' ) {
		$clean['smtp_pass'] = $new_smtp_pass;
	}

	return $clean;
}

function cw_sanitize_pdf_attachment_id( $id ) {
	$id = absint( $id );
	if ( ! $id ) {
		return 0;
	}

	if ( get_post_type( $id ) !== 'attachment' ) {
		return 0;
	}

	if ( get_post_mime_type( $id ) !== 'application/pdf' ) {
		return 0;
	}

	return $id;
}

function cw_get_enquiry_brochure_id() {
	return (int) cw_get_site_setting( 'enquiry_brochure_id', 0 );
}

/** Absolute path to the enquiry confirmation PDF, or empty if none set. */
function cw_get_enquiry_brochure_path() {
	$id = cw_get_enquiry_brochure_id();
	if ( ! $id ) {
		return '';
	}

	$path = get_attached_file( $id );
	if ( ! $path || ! is_readable( $path ) ) {
		return '';
	}

	if ( get_post_mime_type( $id ) !== 'application/pdf' ) {
		return '';
	}

	return $path;
}

function cw_get_enquiry_brochure_label() {
	$id = cw_get_enquiry_brochure_id();
	if ( ! $id ) {
		return '';
	}

	$path = get_attached_file( $id );

	return $path ? basename( $path ) : get_the_title( $id );
}

function cw_sanitize_analytics_snippet( $value ) {
	$value = is_string( $value ) ? trim( $value ) : '';
	if ( $value === '' ) {
		return '';
	}

	$allowed = [
		'script'   => [
			'async'           => true,
			'defer'           => true,
			'src'             => true,
			'type'            => true,
			'crossorigin'     => true,
			'integrity'       => true,
			'nonce'           => true,
			'id'              => true,
		],
		'noscript' => [],
		'iframe'   => [
			'src'    => true,
			'height' => true,
			'width'  => true,
			'style'  => true,
		],
		'meta'     => [
			'name'    => true,
			'content' => true,
		],
	];

	return wp_kses( $value, $allowed );
}

function cw_sanitize_gtm_id( $value ) {
	$value = strtoupper( trim( sanitize_text_field( $value ) ) );

	return preg_match( '/^GTM-[A-Z0-9]+$/', $value ) ? $value : '';
}

function cw_sanitize_ga4_id( $value ) {
	$value = strtoupper( trim( sanitize_text_field( $value ) ) );

	return preg_match( '/^G-[A-Z0-9]+$/', $value ) ? $value : '';
}

function cw_sanitize_google_ads_id( $value ) {
	$value = strtoupper( trim( sanitize_text_field( $value ) ) );
	if ( $value === '' ) {
		return '';
	}
	if ( preg_match( '/^AW-\d+$/', $value ) ) {
		return $value;
	}
	if ( preg_match( '/^\d+$/', $value ) ) {
		return 'AW-' . $value;
	}

	return '';
}

/**
 * Swap IDs when GTM/GA4 fields were filled in the wrong box.
 *
 * @return array{0: string, 1: string, 2: string} GTM ID, GA4 ID, notice message
 */
function cw_fix_swapped_analytics_ids( $gtm, $ga4 ) {
	$gtm     = strtoupper( trim( (string) $gtm ) );
	$ga4     = strtoupper( trim( (string) $ga4 ) );
	$notice  = '';
	$gtm_ok  = (bool) preg_match( '/^GTM-[A-Z0-9]+$/', $gtm );
	$ga4_ok  = (bool) preg_match( '/^G-[A-Z0-9]+$/', $ga4 );
	$gtm_ga4 = (bool) preg_match( '/^G-[A-Z0-9]+$/', $gtm );
	$ga4_gtm = (bool) preg_match( '/^GTM-[A-Z0-9]+$/', $ga4 );

	if ( $gtm_ga4 && ! $gtm_ok ) {
		if ( ! $ga4_ok ) {
			$ga4    = $gtm;
			$notice = 'A GA4 measurement ID in the GTM field was moved to the GA4 field on save.';
		}
		$gtm = '';
	}

	if ( $ga4_gtm && ! $ga4_ok ) {
		if ( ! $gtm_ok ) {
			$gtm = $ga4;
			$notice = 'A GTM container ID in the GA4 field was moved to the GTM field on save.';
		}
		$ga4 = '';
	}

	return [ $gtm, $ga4, $notice ];
}

/**
 * Resolve stored analytics IDs, including legacy swapped values not yet re-saved.
 *
 * @return array{gtm: string, ga4: string, google_ads: string}
 */
function cw_get_resolved_analytics_ids() {
	$settings = cw_get_site_settings();
	[ $gtm, $ga4 ] = cw_fix_swapped_analytics_ids(
		$settings['gtm_container_id'] ?? '',
		$settings['ga4_measurement_id'] ?? ''
	);

	return [
		'gtm'         => cw_sanitize_gtm_id( $gtm ),
		'ga4'         => cw_sanitize_ga4_id( $ga4 ),
		'google_ads'  => cw_sanitize_google_ads_id( $settings['google_ads_id'] ?? '' ),
	];
}

function cw_get_analytics_output_summary() {
	$ids = cw_get_resolved_analytics_ids();

	return [
		'gtm'        => $ids['gtm'] ?: 'Not configured',
		'ga4'        => $ids['ga4'] ?: 'Not configured',
		'google_ads' => $ids['google_ads'] ?: 'Not configured',
		'gtm_active' => $ids['gtm'] !== '',
		'ga4_active' => $ids['ga4'] !== '',
		'ads_active' => $ids['google_ads'] !== '',
	];
}

function cw_get_analytics_id_warnings() {
	$settings = cw_get_site_settings();
	$warnings = [];

	$gtm_raw = strtoupper( trim( (string) ( $settings['gtm_container_id'] ?? '' ) ) );
	$ga4_raw = strtoupper( trim( (string) ( $settings['ga4_measurement_id'] ?? '' ) ) );

	if ( $gtm_raw !== '' && ! preg_match( '/^GTM-[A-Z0-9]+$/', $gtm_raw ) ) {
		if ( preg_match( '/^G-[A-Z0-9]+$/', $gtm_raw ) ) {
			$warnings[] = 'The GTM field contains a GA4 measurement ID (<code>' . esc_html( $gtm_raw ) . '</code>). Move it to the GA4 field. GTM IDs start with <code>GTM-</code>.';
		} else {
			$warnings[] = 'The GTM field value is not a valid container ID (<code>' . esc_html( $gtm_raw ) . '</code>). Use <code>GTM-XXXXXXX</code>.';
		}
	}

	if ( $ga4_raw !== '' && ! preg_match( '/^G-[A-Z0-9]+$/', $ga4_raw ) ) {
		if ( preg_match( '/^GTM-[A-Z0-9]+$/', $ga4_raw ) ) {
			$warnings[] = 'The GA4 field contains a GTM container ID (<code>' . esc_html( $ga4_raw ) . '</code>). Move it to the GTM field.';
		} elseif ( preg_match( '/^\d+$/', $ga4_raw ) ) {
			$warnings[] = 'The GA4 field contains a numeric ID (<code>' . esc_html( $ga4_raw ) . '</code>). That is not a GA4 measurement ID — use <code>G-XXXXXXXXXX</code> here, or paste Google Ads tags in Custom head snippets.';
		} else {
			$warnings[] = 'The GA4 field value is not a valid measurement ID (<code>' . esc_html( $ga4_raw ) . '</code>). Use <code>G-XXXXXXXXXX</code>.';
		}
	}

	return $warnings;
}

add_action( 'admin_notices', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || $screen->id !== 'settings_page_cw-site-settings' ) {
		return;
	}
	$notice = get_transient( 'cw_analytics_swapped_notice' );
	if ( $notice ) {
		delete_transient( 'cw_analytics_swapped_notice' );
		echo '<div class="notice notice-info is-dismissible"><p><strong>Analytics IDs corrected:</strong> ' . esc_html( $notice ) . '</p></div>';
	}
} );

function cw_render_site_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = cw_get_site_settings();
	$og_url   = $settings['default_og_image_id'] ? wp_get_attachment_image_url( $settings['default_og_image_id'], 'medium' ) : '';
	$mail_test = isset( $_GET['cw_mail_test'] ) ? sanitize_key( wp_unslash( $_GET['cw_mail_test'] ) ) : '';
	$id_warnings = cw_get_analytics_id_warnings();
	$output      = cw_get_analytics_output_summary();
	$brochure_id = (int) ( $settings['enquiry_brochure_id'] ?? 0 );
	$brochure_url = $brochure_id ? wp_get_attachment_url( $brochure_id ) : '';
	$brochure_label = $brochure_id ? cw_get_enquiry_brochure_label() : '';
	?>
	<div class="wrap">
		<h1>SEO &amp; Analytics</h1>
		<p>Global defaults for search and social previews, plus tracking snippets injected on every public page.</p>
		<div class="notice notice-info" style="max-width:52rem;">
			<p><strong>Currently output on the public site:</strong></p>
			<ul style="list-style:disc;padding-left:1.4em;margin:0.4em 0 0;">
				<li>GTM: <?php echo $output['gtm_active'] ? '<code>' . esc_html( $output['gtm'] ) . '</code>' : '<em>not configured — add a <code>GTM-</code> ID below</em>'; ?></li>
				<li>GA4: <?php echo $output['ga4_active'] ? '<code>' . esc_html( $output['ga4'] ) . '</code>' : '<em>not configured — add a <code>G-</code> ID below</em>'; ?></li>
				<li>Google Ads: <?php echo $output['ads_active'] ? '<code>' . esc_html( $output['google_ads'] ) . '</code>' : '<em>optional</em>'; ?></li>
			</ul>
		</div>
		<?php foreach ( $id_warnings as $warning ) : ?>
			<div class="notice notice-warning"><p><?php echo wp_kses_post( $warning ); ?></p></div>
		<?php endforeach; ?>
		<?php if ( $mail_test === 'sent' ) : ?>
			<div class="notice notice-success is-dismissible"><p>Test email sent successfully.</p></div>
		<?php elseif ( $mail_test === 'failed' ) : ?>
			<div class="notice notice-error is-dismissible"><p>Test email could not be sent. Check your SMTP settings below.</p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'cw_site_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr><th colspan="2"><h2 class="title" style="margin:0;">Analytics</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_gtm">GTM Container ID</label></th>
					<td><input type="text" class="regular-text" id="cw_gtm" name="cw_site_settings[gtm_container_id]" value="<?php echo esc_attr( $settings['gtm_container_id'] ); ?>" placeholder="GTM-XXXXXXX">
					<p class="description">Google Tag Manager container — loads first in <code>&lt;head&gt;</code>. Must start with <code>GTM-</code> (not <code>G-</code>). Find it in Tag Manager → Admin → Container ID.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_ga4">GA4 Measurement ID</label></th>
					<td><input type="text" class="regular-text" id="cw_ga4" name="cw_site_settings[ga4_measurement_id]" value="<?php echo esc_attr( $settings['ga4_measurement_id'] ); ?>" placeholder="G-XXXXXXXXXX">
					<p class="description">Google Analytics 4 — loads after GTM via gtag.js. Must start with <code>G-</code>. Find it in GA4 → Admin → Data streams → Measurement ID. Both GTM and GA4 can run together.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_google_ads">Google Ads conversion ID</label></th>
					<td><input type="text" class="regular-text" id="cw_google_ads" name="cw_site_settings[google_ads_id]" value="<?php echo esc_attr( $settings['google_ads_id'] ?? '' ); ?>" placeholder="AW-XXXXXXXXX or 15461742069">
					<p class="description">Optional. Loads as an extra <code>gtag('config')</code> alongside GA4. Use the <code>AW-</code> ID from Google Ads → Tools → Conversions, or the numeric ID.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_head">Custom &lt;head&gt; snippets</label></th>
					<td><textarea class="large-text code" rows="6" id="cw_head" name="cw_site_settings[analytics_head]"><?php echo esc_textarea( $settings['analytics_head'] ); ?></textarea>
					<p class="description">Paste Meta Pixel, Hotjar, or other tracking scripts. Allowed tags: script, noscript, meta, iframe.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_body">Custom body snippets</label></th>
					<td><textarea class="large-text code" rows="4" id="cw_body" name="cw_site_settings[analytics_body]"><?php echo esc_textarea( $settings['analytics_body'] ); ?></textarea>
					<p class="description">Rendered at the top of the page (after &lt;body&gt;). Use for GTM noscript fallbacks, etc.</p></td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Global SEO defaults</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_default_desc">Default meta description</label></th>
					<td><textarea class="large-text" rows="3" id="cw_default_desc" name="cw_site_settings[default_meta_description]"><?php echo esc_textarea( $settings['default_meta_description'] ); ?></textarea>
					<p class="description">Used when a page or product has no custom SEO description (aim for 140–160 characters).</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_default_og">Default social image ID</label></th>
					<td>
						<input type="number" class="small-text" id="cw_default_og" name="cw_site_settings[default_og_image_id]" value="<?php echo esc_attr( $settings['default_og_image_id'] ); ?>" min="0">
						<?php if ( $og_url ) : ?><br><img src="<?php echo esc_url( $og_url ); ?>" alt="" style="max-width:240px;margin-top:0.5rem;"><?php endif; ?>
						<p class="description">Media Library attachment ID for Open Graph / Twitter fallback image.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_twitter">Twitter / X handle</label></th>
					<td><input type="text" class="regular-text" id="cw_twitter" name="cw_site_settings[twitter_handle]" value="<?php echo esc_attr( $settings['twitter_handle'] ); ?>" placeholder="walkinwinecabinets"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_gsc">Google site verification</label></th>
					<td><input type="text" class="regular-text" id="cw_gsc" name="cw_site_settings[google_site_verification]" value="<?php echo esc_attr( $settings['google_site_verification'] ); ?>"></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_bing">Bing site verification</label></th>
					<td><input type="text" class="regular-text" id="cw_bing" name="cw_site_settings[bing_site_verification]" value="<?php echo esc_attr( $settings['bing_site_verification'] ); ?>"></td>
				</tr>
				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Contact details</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_phone">Business phone</label></th>
					<td><input type="text" class="regular-text" id="cw_phone" name="cw_site_settings[org_phone]" value="<?php echo esc_attr( $settings['org_phone'] ); ?>" placeholder="1300 924 671">
					<p class="description">Shown in the header, footer, mobile sticky bar, and enquiry error messages.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_email">Business email</label></th>
					<td><input type="email" class="regular-text" id="cw_email" name="cw_site_settings[org_email]" value="<?php echo esc_attr( $settings['org_email'] ); ?>">
					<p class="description">Shown in the footer and used in structured data. Also the default enquiry recipient if none is set below.</p></td>
				</tr>

				<tr><th colspan="2"><h2 class="title" style="margin:1.5rem 0 0;">Email &amp; enquiry form</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_enquiry_recipient">Enquiry recipient</label></th>
					<td><input type="email" class="regular-text" id="cw_enquiry_recipient" name="cw_site_settings[enquiry_recipient]" value="<?php echo esc_attr( $settings['enquiry_recipient'] ); ?>" placeholder="<?php echo esc_attr( $settings['org_email'] ?: get_option( 'admin_email' ) ); ?>">
					<p class="description">Where quote request submissions are sent. Leave blank to use the business email above, then the WordPress admin email.</p></td>
				</tr>
				<tr>
					<th scope="row">Confirmation email brochure</th>
					<td>
						<input type="hidden" id="cw_enquiry_brochure_id" name="cw_site_settings[enquiry_brochure_id]" value="<?php echo esc_attr( $brochure_id ); ?>">
						<div id="cw-brochure-preview" class="cw-admin-brochure-preview" style="margin-bottom:0.5rem;">
							<?php if ( $brochure_url ) : ?>
								<p style="margin:0;"><span class="dashicons dashicons-media-document" style="vertical-align:text-bottom;"></span>
								<a href="<?php echo esc_url( $brochure_url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $brochure_label ); ?></a></p>
							<?php endif; ?>
						</div>
						<button type="button" class="button" id="cw-brochure-select">Select PDF</button>
						<button type="button" class="button" id="cw-brochure-remove"<?php echo $brochure_id ? '' : ' style="display:none;"'; ?>>Remove</button>
						<p class="description">Optional PDF attached to the <strong>customer confirmation</strong> email only (not the admin notification).</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_mail_from_name">From name</label></th>
					<td><input type="text" class="regular-text" id="cw_mail_from_name" name="cw_site_settings[mail_from_name]" value="<?php echo esc_attr( $settings['mail_from_name'] ); ?>" placeholder="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
					<p class="description">Name shown as the sender on outgoing emails (admin notification and customer confirmation).</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_mail_from_email">From email</label></th>
					<td><input type="email" class="regular-text" id="cw_mail_from_email" name="cw_site_settings[mail_from_email]" value="<?php echo esc_attr( $settings['mail_from_email'] ); ?>" placeholder="<?php echo esc_attr( $settings['org_email'] ?: get_option( 'admin_email' ) ); ?>">
					<p class="description">Must match your SMTP mailbox domain for reliable delivery (e.g. enquiries@yourdomain.com).</p></td>
				</tr>
				<tr>
					<th scope="row">SMTP</th>
					<td>
						<label>
							<input type="checkbox" name="cw_site_settings[smtp_enabled]" value="1" <?php checked( $settings['smtp_enabled'], '1' ); ?>>
							Enable SMTP for outgoing mail
						</label>
						<p class="description">Required on SiteGround shared hosting — PHP <code>mail()</code> is unreliable without authenticated SMTP.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_smtp_host">SMTP host</label></th>
					<td><input type="text" class="regular-text" id="cw_smtp_host" name="cw_site_settings[smtp_host]" value="<?php echo esc_attr( $settings['smtp_host'] ); ?>" placeholder="mail.yourdomain.com">
					<p class="description">SiteGround: <strong>Site Tools → Email → Accounts</strong> → ⋮ → <strong>Mail Configuration</strong> → Manual Settings → copy the <strong>Outgoing server</strong> hostname.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_smtp_port">SMTP port</label></th>
					<td><input type="number" class="small-text" id="cw_smtp_port" name="cw_site_settings[smtp_port]" value="<?php echo esc_attr( $settings['smtp_port'] ); ?>" min="1" max="65535">
					<p class="description">SiteGround: use <strong>465</strong> with SSL, or <strong>587</strong> with TLS — match the Manual Settings tab for your mailbox.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_smtp_encryption">Encryption</label></th>
					<td>
						<select id="cw_smtp_encryption" name="cw_site_settings[smtp_encryption]">
							<option value="tls" <?php selected( $settings['smtp_encryption'], 'tls' ); ?>>TLS (STARTTLS)</option>
							<option value="ssl" <?php selected( $settings['smtp_encryption'], 'ssl' ); ?>>SSL</option>
							<option value="none" <?php selected( $settings['smtp_encryption'], 'none' ); ?>>None</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_smtp_user">SMTP username</label></th>
					<td><input type="text" class="regular-text" id="cw_smtp_user" name="cw_site_settings[smtp_user]" value="<?php echo esc_attr( $settings['smtp_user'] ); ?>" placeholder="enquiries@yourdomain.com" autocomplete="off">
					<p class="description">Your full SiteGround mailbox address (same as From email).</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_smtp_pass">SMTP password</label></th>
					<td><input type="password" class="regular-text" id="cw_smtp_pass" name="cw_site_settings[smtp_pass]" value="" autocomplete="new-password" placeholder="<?php echo $settings['smtp_pass'] ? '•••••••• (saved — leave blank to keep)' : ''; ?>">
					<p class="description">The mailbox password from Site Tools → Email → Accounts (not your SiteGround login).</p></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>

		<h2>SiteGround setup checklist</h2>
		<ol style="max-width:52rem;line-height:1.7;">
			<li>In <strong>Site Tools → Email → Accounts</strong>, create a mailbox (e.g. <code>enquiries@yourdomain.com</code>).</li>
			<li>Open <strong>Mail Configuration → Manual Settings</strong> and note the <strong>Outgoing server</strong>, port, and encryption.</li>
			<li>In the form above: enable SMTP, paste those values, and set username/password to that mailbox.</li>
			<li>Set <strong>From email</strong>, <strong>Enquiry recipient</strong>, and <strong>Business email</strong> to the same address.</li>
			<li>Save, then use <strong>Send test email</strong> below. Submit a test enquiry on the Enquire page.</li>
		</ol>
		<p class="description">SiteGround’s built-in mail works well for enquiry forms. If deliverability is still an issue later, consider a dedicated sender (SendGrid, Brevo, etc.) — the same SMTP fields apply.</p>

		<h2>Test email</h2>
		<p>Send a test message to confirm SMTP and From settings before going live.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'cw_test_mail' ); ?>
			<input type="hidden" name="action" value="cw_test_mail">
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="cw_test_recipient">Send test to</label></th>
					<td>
						<input type="email" class="regular-text" id="cw_test_recipient" name="test_recipient" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>">
						<?php submit_button( 'Send test email', 'secondary', 'cw_send_test', false ); ?>
					</td>
				</tr>
			</table>
		</form>

		<p><strong>Enquiry form:</strong> Submissions send an email to the enquiry recipient <em>and</em> a confirmation to the person who filled in the form.</p>
		<p><strong>Per-page SEO:</strong> Edit any Page, Product, or Installation — use the <strong>SEO</strong> box in the sidebar.</p>
	</div>
	<?php
}

add_action( 'wp_head', 'cw_output_site_verification_meta', 1 );

function cw_output_site_verification_meta() {
	if ( is_admin() ) {
		return;
	}

	$settings = cw_get_site_settings();

	if ( ! empty( $settings['google_site_verification'] ) ) {
		printf(
			'<meta name="google-site-verification" content="%s">' . "\n",
			esc_attr( $settings['google_site_verification'] )
		);
	}

	if ( ! empty( $settings['bing_site_verification'] ) ) {
		printf(
			'<meta name="msvalidate.01" content="%s">' . "\n",
			esc_attr( $settings['bing_site_verification'] )
		);
	}
}

add_action( 'wp_body_open', 'cw_output_analytics_body', 1 );

/**
 * Google tags — as high in <head> as possible (called from header.php before wp_head).
 */
function cw_output_analytics_head() {
	if ( is_admin() ) {
		return;
	}

	$settings = cw_get_site_settings();
	$ids      = cw_get_resolved_analytics_ids();
	$gtm      = $ids['gtm'];
	$ga4      = $ids['ga4'];
	$ads      = $ids['google_ads'];
	$gtag_ids = array_values( array_filter( [ $ga4, $ads ] ) );

	if ( $gtm || $gtag_ids ) {
		echo "<script>window.dataLayer=window.dataLayer||[];</script>\n";
	}

	if ( $gtm ) {
		$gtm_js = esc_js( $gtm );
		echo "<!-- Google Tag Manager -->\n";
		echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtm_js}');</script>\n";
		echo "<!-- End Google Tag Manager -->\n";
	}

	if ( $gtag_ids ) {
		$loader_id = $ga4 ?: $ads;
		echo "<!-- Google tag (gtag.js) -->\n";
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . esc_attr( $loader_id ) . '"></script>' . "\n";
		echo "<script>\n";
		echo "window.dataLayer=window.dataLayer||[];\n";
		echo "function gtag(){dataLayer.push(arguments);}\n";
		echo "gtag('js',new Date());\n";
		foreach ( $gtag_ids as $tag_id ) {
			echo "gtag('config','" . esc_js( $tag_id ) . "');\n";
		}
		echo "</script>\n";
		echo "<!-- End Google tag (gtag.js) -->\n";
	}

	if ( ! empty( $settings['analytics_head'] ) ) {
		echo $settings['analytics_head'] . "\n";
	}
}

function cw_output_analytics_body() {
	if ( is_admin() ) {
		return;
	}

	$settings = cw_get_site_settings();
	$gtm      = cw_get_resolved_analytics_ids()['gtm'];

	if ( $gtm ) {
		echo '<!-- Google Tag Manager (noscript) -->' . "\n";
		echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . esc_attr( $gtm ) . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
		echo '<!-- End Google Tag Manager (noscript) -->' . "\n";
	}

	if ( ! empty( $settings['analytics_body'] ) ) {
		echo $settings['analytics_body'] . "\n";
	}
}

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( $hook !== 'settings_page_cw-site-settings' ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'cw-admin-settings',
		get_theme_file_uri( 'assets/js/admin-settings.js' ),
		[ 'jquery' ],
		CW_VERSION,
		true
	);
} );
