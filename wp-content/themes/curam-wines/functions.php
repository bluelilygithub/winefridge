<?php
define( 'CW_VERSION', '1.1.0' );

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
} );

/* -------------------------------------------------------------------------
 * Enqueue styles and scripts
 * ---------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'curam-wines-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&display=swap',
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

	$name     = sanitize_text_field( wp_unslash( $_POST['name']     ?? '' ) );
	$email    = sanitize_email(      wp_unslash( $_POST['email']    ?? '' ) );
	$phone    = sanitize_text_field( wp_unslash( $_POST['phone']    ?? '' ) );
	$city     = sanitize_text_field( wp_unslash( $_POST['city']     ?? '' ) );
	$bottles  = sanitize_text_field( wp_unslash( $_POST['bottles']  ?? '' ) );
	$series   = sanitize_text_field( wp_unslash( $_POST['series']   ?? '' ) );
	$message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	$to      = get_option( 'admin_email' );
	$subject = "[Specs Request] {$name}";
	$body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nCity: {$city}\nBottle count: {$bottles}\nSeries interest: {$series}\n\nMessage:\n{$message}";
	$headers = [ 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>" ];

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) . '#enquire' );
	exit;
}

add_filter( 'show_admin_bar', '__return_false' );
