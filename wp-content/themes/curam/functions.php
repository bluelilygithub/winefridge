<?php
/**
 * Curam — Walk-In Wine Cabinets Australia
 * Classic theme functions.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'CURAM_VERSION', '1.0.3' );

// Hide the WordPress admin bar on the front end.
add_filter( 'show_admin_bar', '__return_false' );

/* -------------------------------------------------------------------------
 * Theme setup
 * ---------------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
} );

/* -------------------------------------------------------------------------
 * Enqueue styles and scripts
 * ---------------------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'curam-fonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Work+Sans:wght@300;400;500;600&display=swap',
		array(),
		null
	);
	wp_enqueue_style( 'curam-theme', get_theme_file_uri( 'assets/css/theme.css' ), array( 'curam-fonts' ), CURAM_VERSION );
	wp_enqueue_script( 'curam-theme', get_theme_file_uri( 'assets/js/theme.js' ), array(), CURAM_VERSION, true );
} );

/* -------------------------------------------------------------------------
 * Case Study custom post type
 * ---------------------------------------------------------------------- */
add_action( 'init', function () {
	register_post_type( 'case_study', array(
		'labels'       => array(
			'name'          => 'Case Studies',
			'singular_name' => 'Case Study',
			'menu_name'     => 'Case Studies',
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-portfolio',
		'rewrite'      => array( 'slug' => 'case-studies' ),
		'show_in_rest' => true,
		'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
	) );
} );

/* -------------------------------------------------------------------------
 * Enquiry form handler
 * ---------------------------------------------------------------------- */
add_action( 'admin_post_nopriv_curam_enquiry', 'curam_handle_enquiry' );
add_action( 'admin_post_curam_enquiry',        'curam_handle_enquiry' );

function curam_handle_enquiry() {
	$redirect = wp_get_referer() ?: home_url( '/enquire/' );

	if ( empty( $_POST['curam_enquiry_nonce'] ) || ! wp_verify_nonce( $_POST['curam_enquiry_nonce'], 'curam_enquiry' ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	// Honeypot.
	if ( ! empty( $_POST['curam_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) );
		exit;
	}

	$name     = sanitize_text_field( wp_unslash( $_POST['name']     ?? '' ) );
	$email    = sanitize_email(      wp_unslash( $_POST['email']    ?? '' ) );
	$phone    = sanitize_text_field( wp_unslash( $_POST['phone']    ?? '' ) );
	$city     = sanitize_text_field( wp_unslash( $_POST['city']     ?? '' ) );
	$deadline = sanitize_text_field( wp_unslash( $_POST['deadline'] ?? '' ) );
	$message  = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ) {
		wp_safe_redirect( add_query_arg( 'enquiry', 'error', $redirect ) );
		exit;
	}

	$to      = get_option( 'admin_email' );
	$subject = "[Enquiry] {$name}";
	$body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\nCity: {$city}\nDeadline: {$deadline}\n\nMessage:\n{$message}";
	$headers = array( 'Content-Type: text/plain; charset=UTF-8', "Reply-To: {$name} <{$email}>" );

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'enquiry', 'sent', $redirect ) . '#enquire' );
	exit;
}
