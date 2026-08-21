<?php
/**
 * One-click page layout fixes (Contact page, homepage order, Enquire form order).
 * Page copy lives in the database — git push alone does not update it.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', function () {
	add_management_page(
		'Page layouts',
		'Page layouts',
		'manage_options',
		'cw-page-layouts',
		'cw_render_page_layouts_tool'
	);
} );

add_action( 'admin_post_cw_apply_page_layouts', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized', 'curam-wines' ) );
	}
	check_admin_referer( 'cw_apply_page_layouts' );

	$result = cw_apply_page_layout_fixes();
	set_transient( 'cw_page_layouts_result', $result, 5 * MINUTE_IN_SECONDS );

	wp_safe_redirect( add_query_arg( [ 'page' => 'cw-page-layouts', 'applied' => '1' ], admin_url( 'tools.php' ) ) );
	exit;
} );

function cw_render_page_layouts_tool() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$result = null;
	if ( isset( $_GET['applied'] ) ) {
		$result = get_transient( 'cw_page_layouts_result' );
		delete_transient( 'cw_page_layouts_result' );
	}
	?>
	<div class="wrap">
		<h1>Page layouts</h1>
		<p>Theme files come from git. <strong>Homepage, Enquire, and Contact page content live in the database</strong> — so deploying the theme does not move blocks or create the Contact page by itself.</p>
		<p>This is not a browser cache issue. Use the button below on the site you are editing (Local or SiteGround) to apply the current layout fixes.</p>

		<?php if ( is_array( $result ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p><strong>Layouts applied.</strong></p>
				<ul>
					<?php foreach ( $result as $line ) : ?>
						<li><?php echo esc_html( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" onsubmit="return confirm('Update Contact, Homepage, and Enquire page content now?');">
			<?php wp_nonce_field( 'cw_apply_page_layouts' ); ?>
			<input type="hidden" name="action" value="cw_apply_page_layouts">
			<?php submit_button( 'Apply layout fixes now', 'primary' ); ?>
		</form>

		<h2>What this changes</h2>
		<ol>
			<li><strong>Contact</strong> — creates/publishes <code>/contact/</code> so the Contact menu item has a real page (form is built into the theme template).</li>
			<li><strong>Homepage</strong> — puts the “How It Works” block above the video.</li>
			<li><strong>Enquire</strong> — puts the quote form above “What happens after you send this”.</li>
		</ol>
	</div>
	<?php
}

/**
 * @return string[] Status lines for the admin notice.
 */
function cw_apply_page_layout_fixes() {
	$lines   = [];
	$lines[] = cw_ensure_contact_page();
	$lines[] = cw_fix_homepage_how_it_works_order();
	$lines[] = cw_fix_enquire_form_order();
	flush_rewrite_rules( false );

	return array_values( array_filter( $lines ) );
}

function cw_ensure_contact_page() {
	$page = get_page_by_path( 'contact' );
	$data = [
		'post_title'   => 'Contact',
		'post_name'    => 'contact',
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_content' => '',
		'post_excerpt' => 'Call, email, or send a quick message — no quote details required.',
	];

	if ( $page ) {
		$data['ID'] = $page->ID;
		$id         = wp_update_post( $data, true );
		$action     = 'updated';
	} else {
		$id     = wp_insert_post( $data, true );
		$action = 'created';
	}

	if ( is_wp_error( $id ) ) {
		return 'Contact: failed — ' . $id->get_error_message();
	}

	delete_post_meta( (int) $id, '_wp_page_template' );
	if ( ! get_post_meta( (int) $id, '_cw_meta_description', true ) ) {
		update_post_meta(
			(int) $id,
			'_cw_meta_description',
			'Contact Walk-In Wine Cabinets Australia. Call, email, or send a message for general questions and support.'
		);
	}

	return sprintf( 'Contact: %s (#%d) → %s', $action, (int) $id, get_permalink( (int) $id ) );
}

function cw_fix_homepage_how_it_works_order() {
	$front_id = (int) get_option( 'page_on_front' );
	$front    = $front_id ? get_post( $front_id ) : null;
	if ( ! $front ) {
		return 'Homepage: no front page set.';
	}

	$content = $front->post_content;

	// Capture How It Works intro section.
	$how = '';
	if ( preg_match( '/<section class="cw-sec">\s*<div class="cw-wrap">\s*<div class="cw-intro">[\s\S]*?<\/div>\s*<\/div>\s*<\/section>/i', $content, $m ) ) {
		$how     = $m[0];
		$content = str_replace( $how, "\n", $content );
	} else {
		return 'Homepage: could not find the “How It Works” block — edit the front page manually or restore expected markup.';
	}

	// Capture video shortcode (and optional wrapping section).
	$video = '';
	if ( preg_match( '/<section class="cw-sec">\s*<div class="cw-wrap">\s*(\[cw_video_block[^\]]*\])\s*<\/div>\s*<\/section>/i', $content, $m ) ) {
		$video   = $m[0];
		$content = str_replace( $video, "\n", $content );
	} elseif ( preg_match( '/\[cw_video_block[^\]]*\]/', $content, $m ) ) {
		$video   = '<section class="cw-sec"><div class="cw-wrap">' . $m[0] . '</div></section>';
		$content = str_replace( $m[0], "\n", $content );
	} else {
		return 'Homepage: How It Works found, but no video shortcode — nothing moved.';
	}

	// Also pull process out of a shared wrapper with video leftovers.
	$content = preg_replace(
		'/<section class="cw-sec">\s*<div class="cw-wrap">\s*\[cw_process_steps\]\s*<\/div>\s*<\/section>/i',
		"[cw_process_steps]\n",
		$content
	);
	$content = preg_replace( '/<section class="cw-sec">\s*<div class="cw-wrap">\s*<\/div>\s*<\/section>/i', "\n", $content );

	$block = $how . "\n\n" . $video . "\n";

	if ( preg_match( '/(<section class="cw-sec">\s*<div class="cw-wrap">\s*\[cw_trust_strip[^\]]*\]\s*<\/div>\s*<\/section>)/i', $content ) ) {
		$content = preg_replace(
			'/(<section class="cw-sec">\s*<div class="cw-wrap">\s*\[cw_trust_strip[^\]]*\]\s*<\/div>\s*<\/section>)/i',
			"$1\n\n" . $block,
			$content,
			1
		);
	} elseif ( preg_match( '/\[cw_home_hero[^\]]*\]/', $content ) ) {
		$content = preg_replace( '/(\[cw_home_hero[^\]]*\])/', "$1\n\n" . $block, $content, 1 );
	} else {
		$content = $block . "\n" . $content;
	}

	$content = preg_replace( "/\n{3,}/", "\n\n", $content );
	$content = str_replace( 'Get a fixed quote', 'Get a quote', $content );

	$r = wp_update_post( [ 'ID' => $front_id, 'post_content' => $content ], true );
	if ( is_wp_error( $r ) ) {
		return 'Homepage: failed — ' . $r->get_error_message();
	}

	return 'Homepage: “How It Works” moved above the video.';
}

function cw_fix_enquire_form_order() {
	$page = get_page_by_path( 'enquire' );
	if ( ! $page ) {
		return 'Enquire: page not found.';
	}

	$new = <<<'HTML'
<section class="cw-sec" id="enquire">
  <div class="cw-wrap">
    <div class="cw-enquiry">
      <div>
        [cw_enquiry_form]
      </div>
      <div class="cw-enquiry-intro">
        <h2>What happens after you send this</h2>
        <p>We read it the same day and confirm the right series, the right size, and a quote — usually within one business day. There's no site visit required to get a quote, and no cost attached to asking.</p>
        <p>If you're not sure what you need yet, just describe the space. We'll work it out from there.</p>
        [cw_enquiry_contact]
      </div>
    </div>
  </div>
</section>
HTML;

	$r = wp_update_post(
		[
			'ID'           => $page->ID,
			'post_content' => $new,
			'post_excerpt' => "Bottle count, room, and access — that's enough for us to confirm a series and a quote.",
		],
		true
	);

	if ( is_wp_error( $r ) ) {
		return 'Enquire: failed — ' . $r->get_error_message();
	}

	return 'Enquire: form placed above “What happens after you send this”.';
}
