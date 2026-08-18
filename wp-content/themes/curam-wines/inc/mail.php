<?php
/**
 * Outbound mail — SMTP config, From headers, enquiry notifications.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Who receives quote request submissions. */
function cw_get_enquiry_recipient() {
	$recipient = cw_get_site_setting( 'enquiry_recipient' );

	if ( $recipient && is_email( $recipient ) ) {
		return $recipient;
	}

	$org = cw_get_site_setting( 'org_email' );
	if ( $org && is_email( $org ) ) {
		return $org;
	}

	return get_option( 'admin_email' );
}

function cw_get_mail_from_email() {
	$from = cw_get_site_setting( 'mail_from_email' );

	if ( $from && is_email( $from ) ) {
		return $from;
	}

	$org = cw_get_site_setting( 'org_email' );
	if ( $org && is_email( $org ) ) {
		return $org;
	}

	return get_option( 'admin_email' );
}

function cw_get_mail_from_name() {
	$name = cw_get_site_setting( 'mail_from_name' );

	return $name !== '' ? $name : get_bloginfo( 'name' );
}

function cw_is_smtp_enabled() {
	return (bool) cw_get_site_setting( 'smtp_enabled' );
}

add_filter( 'wp_mail_from', function ( $email ) {
	return cw_get_mail_from_email();
} );

add_filter( 'wp_mail_from_name', function ( $name ) {
	return cw_get_mail_from_name();
} );

add_action( 'phpmailer_init', function ( $phpmailer ) {
	if ( ! cw_is_smtp_enabled() ) {
		return;
	}

	$host = cw_get_site_setting( 'smtp_host' );
	if ( $host === '' ) {
		return;
	}

	$port        = (int) cw_get_site_setting( 'smtp_port', 587 );
	$encryption  = cw_get_site_setting( 'smtp_encryption', 'tls' );
	$username    = cw_get_site_setting( 'smtp_user' );
	$password    = cw_get_site_setting( 'smtp_pass' );

	$phpmailer->isSMTP();
	$phpmailer->Host       = $host;
	$phpmailer->Port       = $port > 0 ? $port : 587;
	$phpmailer->SMTPAuth   = $username !== '';
	$phpmailer->Username   = $username;
	$phpmailer->Password   = $password;
	$phpmailer->SMTPSecure = $encryption === 'none' ? '' : $encryption;
} );

/**
 * @param array<string,string> $data Normalised enquiry fields.
 * @return bool Both admin and customer emails sent successfully.
 */
function cw_send_enquiry_notifications( array $data ) {
	$admin_sent    = cw_send_enquiry_admin_email( $data );
	$customer_sent = cw_send_enquiry_customer_email( $data );

	if ( ! $admin_sent || ! $customer_sent ) {
		error_log( sprintf(
			'[curam-wines] Enquiry mail failed — admin: %s, customer: %s, submitter: %s',
			$admin_sent ? 'ok' : 'fail',
			$customer_sent ? 'ok' : 'fail',
			$data['email'] ?? 'unknown'
		) );
	}

	return $admin_sent && $customer_sent;
}

/**
 * @param array<string,string> $data
 */
function cw_send_enquiry_admin_email( array $data ) {
	$to      = cw_get_enquiry_recipient();
	$subject = sprintf( '[Quote Request] %s', $data['name'] );
	$body    = cw_build_enquiry_admin_body( $data );
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $data['name'], $data['email'] ),
	];

	return wp_mail( $to, $subject, $body, $headers );
}

/**
 * @param array<string,string> $data
 */
function cw_send_enquiry_customer_email( array $data ) {
	$subject = sprintf(
		'We received your quote request — %s',
		cw_get_mail_from_name()
	);
	$body    = cw_build_enquiry_customer_body( $data );
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', cw_get_mail_from_name(), cw_get_mail_from_email() ),
	];

	return wp_mail( $data['email'], $subject, $body, $headers );
}

/**
 * @param array<string,string> $data
 */
function cw_build_enquiry_admin_body( array $data ) {
	$lines = [
		'A new quote request was submitted on the website.',
		'',
		"Mode: {$data['mode']}",
		"Name: {$data['name']}",
		"Email: {$data['email']}",
		"Phone: {$data['phone']}",
		"City: {$data['city']}",
		'',
		"Bottle capacity: {$data['bottle_capacity']}",
		"Series interest: {$data['series']}",
		"Installation type: {$data['installation_type']}",
		"Available width: {$data['width']}",
		"Available height: {$data['height']}",
		"Available depth: {$data['depth']}",
		"Finish / enclosure: {$data['finish']}",
		"Property type: {$data['property_type']}",
		"Target install date: {$data['deadline']}",
		'',
		'Access constraints / notes:',
		$data['message'],
		'',
		'---',
		'Reply directly to this email to respond to the customer.',
	];

	return implode( "\n", $lines );
}

/**
 * @param array<string,string> $data
 */
function cw_build_enquiry_customer_body( array $data ) {
	$phone = cw_get_org_phone();
	$lines = [
		"Hi {$data['name']},",
		'',
		'Thank you for your enquiry with ' . cw_get_mail_from_name() . '.',
		'We have received your details and will be in touch within one business day with your fixed quote.',
		'',
	];

	if ( $phone !== '' ) {
		$lines[] = 'If you need to reach us sooner, call ' . $phone . '.';
		$lines[] = '';
	}

	$lines[] = 'Summary of what you sent:';
	$lines[] = '';
	$lines[] = "Phone: {$data['phone']}";
	$lines[] = "City: {$data['city']}";

	if ( $data['bottle_capacity'] !== '' ) {
		$lines[] = "Bottle capacity: {$data['bottle_capacity']}";
	}
	if ( $data['series'] !== '' ) {
		$lines[] = "Series interest: {$data['series']}";
	}
	if ( $data['installation_type'] !== '' ) {
		$lines[] = "Installation type: {$data['installation_type']}";
	}
	if ( $data['property_type'] !== '' ) {
		$lines[] = "Property type: {$data['property_type']}";
	}
	if ( $data['message'] !== '' ) {
		$lines[] = '';
		$lines[] = 'Your notes:';
		$lines[] = $data['message'];
	}

	$lines[] = '';
	$lines[] = '---';
	$lines[] = cw_get_mail_from_name();

	return implode( "\n", $lines );
}

/** Admin: send a test message to verify SMTP / wp_mail. */
add_action( 'admin_post_cw_test_mail', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized', 'curam-wines' ) );
	}

	check_admin_referer( 'cw_test_mail' );

	$to = isset( $_POST['test_recipient'] ) ? sanitize_email( wp_unslash( $_POST['test_recipient'] ) ) : '';
	if ( ! $to || ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$smtp_on = cw_is_smtp_enabled() ? 'yes' : 'no';
	$sent    = wp_mail(
		$to,
		'Test email — ' . get_bloginfo( 'name' ),
		"This is a test email from your WordPress site.\n\nSMTP enabled: {$smtp_on}\nFrom: " . cw_get_mail_from_name() . ' <' . cw_get_mail_from_email() . ">\nTime: " . wp_date( 'Y-m-d H:i:s T' ),
		[ 'Content-Type: text/plain; charset=UTF-8' ]
	);

	$redirect = add_query_arg(
		[
			'page'       => 'cw-site-settings',
			'cw_mail_test' => $sent ? 'sent' : 'failed',
		],
		admin_url( 'options-general.php' )
	);

	wp_safe_redirect( $redirect );
	exit;
} );
