<?php
/**
 * Accessibility helpers — landmarks, skip link, reduced-motion-friendly markup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style(
		'curam-wines-a11y',
		get_template_directory_uri() . '/assets/css/a11y.css',
		[ 'curam-wines-theme' ],
		CW_VERSION
	);
}, 20 );

function cw_skip_link() {
	echo '<a class="cw-skip-link" href="#main-content">Skip to main content</a>' . "\n";
}

function cw_nav_is_active( $slug ) {
	if ( $slug === 'installations' ) {
		return is_post_type_archive( 'case_study' ) || is_singular( 'case_study' );
	}
	if ( $slug === 'racking' ) {
		return is_post_type_archive( 'rack' ) || is_singular( 'rack' );
	}
	return is_page( $slug );
}

function cw_nav_link_attrs( $slug ) {
	$attrs = '';
	if ( cw_nav_is_active( $slug ) ) {
		$attrs .= ' class="is-active" aria-current="page"';
	}
	return $attrs;
}

function cw_open_main() {
	echo '<main id="main-content" tabindex="-1">' . "\n";
}

function cw_close_main() {
	echo '</main>' . "\n";
}

add_action( 'wp_body_open', 'cw_skip_link', 5 );
