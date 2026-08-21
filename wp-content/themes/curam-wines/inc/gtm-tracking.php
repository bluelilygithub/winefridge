<?php
/**
 * Google Tag Manager — data attributes and dataLayer events for conversion tracking.
 *
 * GTM setup guide: see GTM-TRACKING.txt in the theme root.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build HTML attributes for click tracking (safe to repeat on many links).
 *
 * @param string               $event   Event name pushed by GTM (e.g. quote_click).
 * @param array<string,string> $args    Optional id, location, label.
 */
function cw_gtm_tracking_attrs( $event, $args = [] ) {
	$attrs = [
		'data-gtm-event' => $event,
	];

	if ( ! empty( $args['location'] ) ) {
		$attrs['data-gtm-location'] = $args['location'];
	}

	if ( ! empty( $args['label'] ) ) {
		$attrs['data-gtm-label'] = $args['label'];
	}

	if ( ! empty( $args['id'] ) ) {
		$attrs['id'] = $args['id'];
	}

	$html = '';
	foreach ( $attrs as $key => $value ) {
		if ( $value === '' ) {
			continue;
		}
		$html .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
	}

	return $html;
}

function cw_gtm_quote_attrs( $location, $unique_id = '' ) {
	return cw_gtm_tracking_attrs( 'quote_click', [
		'location' => $location,
		'id'         => $unique_id,
	] );
}

function cw_gtm_phone_attrs( $location, $unique_id = '' ) {
	return cw_gtm_tracking_attrs( 'phone_click', [
		'location' => $location,
		'id'         => $unique_id,
	] );
}

function cw_gtm_email_attrs( $location, $unique_id = '' ) {
	return cw_gtm_tracking_attrs( 'email_click', [
		'location' => $location,
		'id'         => $unique_id,
	] );
}

function cw_gtm_is_enquire_page() {
	return is_page( 'enquire' );
}

function cw_gtm_is_contact_page() {
	return is_page( 'contact' );
}

function cw_gtm_is_thank_you_page() {
	return is_page( 'thank-you' );
}

add_filter( 'body_class', function ( $classes ) {
	if ( cw_gtm_is_enquire_page() ) {
		$classes[] = 'cw-gtm-page-enquire';
	}
	if ( cw_gtm_is_contact_page() ) {
		$classes[] = 'cw-gtm-page-contact';
	}
	if ( cw_gtm_is_thank_you_page() ) {
		$classes[] = 'cw-gtm-page-thank-you';
	}

	return $classes;
} );

add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}

	$payload = null;

	if ( cw_gtm_is_enquire_page() ) {
		$payload = [
			'event'     => 'cw_form_view',
			'page_type' => 'enquiry_form',
		];
	} elseif ( cw_gtm_is_contact_page() ) {
		$payload = [
			'event'     => 'cw_form_view',
			'page_type' => 'contact_form',
		];
	} elseif ( cw_gtm_is_thank_you_page() ) {
		$payload = [
			'event'     => 'cw_form_submit',
			'page_type' => 'thank_you',
		];
	}

	if ( ! $payload ) {
		return;
	}

	echo '<script>window.dataLayer=window.dataLayer||[];dataLayer.push(' . wp_json_encode( $payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . ');</script>' . "\n";
}, 5 );
