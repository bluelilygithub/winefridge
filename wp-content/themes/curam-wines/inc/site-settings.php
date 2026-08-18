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

	$clean['ga4_measurement_id']       = sanitize_text_field( $input['ga4_measurement_id'] ?? '' );
	$clean['gtm_container_id']         = sanitize_text_field( $input['gtm_container_id'] ?? '' );
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

function cw_render_site_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$settings = cw_get_site_settings();
	$og_url   = $settings['default_og_image_id'] ? wp_get_attachment_image_url( $settings['default_og_image_id'], 'medium' ) : '';
	$mail_test = isset( $_GET['cw_mail_test'] ) ? sanitize_key( wp_unslash( $_GET['cw_mail_test'] ) ) : '';
	?>
	<div class="wrap">
		<h1>SEO &amp; Analytics</h1>
		<p>Global defaults for search and social previews, plus tracking snippets injected on every public page.</p>
		<?php if ( $mail_test === 'sent' ) : ?>
			<div class="notice notice-success is-dismissible"><p>Test email sent successfully.</p></div>
		<?php elseif ( $mail_test === 'failed' ) : ?>
			<div class="notice notice-error is-dismissible"><p>Test email could not be sent. Check your SMTP settings below and your server error log.</p></div>
		<?php endif; ?>
		<form method="post" action="options.php">
			<?php settings_fields( 'cw_site_settings_group' ); ?>
			<table class="form-table" role="presentation">
				<tr><th colspan="2"><h2 class="title" style="margin:0;">Analytics</h2></th></tr>
				<tr>
					<th scope="row"><label for="cw_ga4">GA4 Measurement ID</label></th>
					<td><input type="text" class="regular-text" id="cw_ga4" name="cw_site_settings[ga4_measurement_id]" value="<?php echo esc_attr( $settings['ga4_measurement_id'] ); ?>" placeholder="G-XXXXXXXXXX">
					<p class="description">Google Analytics 4 — loads gtag.js automatically when set.</p></td>
				</tr>
				<tr>
					<th scope="row"><label for="cw_gtm">GTM Container ID</label></th>
					<td><input type="text" class="regular-text" id="cw_gtm" name="cw_site_settings[gtm_container_id]" value="<?php echo esc_attr( $settings['gtm_container_id'] ); ?>" placeholder="GTM-XXXXXXX">
					<p class="description">Google Tag Manager — head + body snippets added when set.</p></td>
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
						<?php submit_button( 'Send test email', 'secondary', 'submit', false ); ?>
					</td>
				</tr>
			</table>
		</form>

		<p><strong>Enquiry form:</strong> Submissions send an email to the enquiry recipient <em>and</em> a confirmation to the person who filled in the form.</p>
		<p><strong>Per-page SEO:</strong> Edit any Page, Product, or Installation — use the <strong>SEO</strong> box in the sidebar.</p>
	</div>
	<?php
}

add_action( 'wp_head', 'cw_output_analytics_head', 1 );
add_action( 'wp_body_open', 'cw_output_analytics_body', 1 );

function cw_output_analytics_head() {
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

	if ( ! empty( $settings['gtm_container_id'] ) ) {
		$gtm = esc_js( $settings['gtm_container_id'] );
		echo "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{$gtm}');</script>\n";
	}

	if ( ! empty( $settings['ga4_measurement_id'] ) ) {
		$ga = esc_attr( $settings['ga4_measurement_id'] );
		echo '<script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga . '"></script>' . "\n";
		echo "<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','" . esc_js( $settings['ga4_measurement_id'] ) . "');</script>\n";
	}

	if ( ! empty( $settings['analytics_head'] ) ) {
		echo $settings['analytics_head'] . "\n";
	}
}

function cw_output_analytics_body() {
	$settings = cw_get_site_settings();

	if ( ! empty( $settings['gtm_container_id'] ) ) {
		$gtm = esc_attr( $settings['gtm_container_id'] );
		echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $gtm . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>' . "\n";
	}

	if ( ! empty( $settings['analytics_body'] ) ) {
		echo $settings['analytics_body'] . "\n";
	}
}
