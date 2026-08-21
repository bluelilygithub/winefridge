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

	$org = cw_get_org_email();
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

	$org = cw_get_org_email();
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

function cw_get_smtp_config() {
	$port       = (int) cw_get_site_setting( 'smtp_port', 587 );
	$encryption = cw_get_site_setting( 'smtp_encryption', 'tls' );

	if ( $port === 465 && $encryption === 'tls' ) {
		$encryption = 'ssl';
	} elseif ( $port === 587 && $encryption === 'ssl' ) {
		$encryption = 'tls';
	}

	return [
		'enabled'     => cw_is_smtp_enabled(),
		'host'        => cw_get_site_setting( 'smtp_host' ),
		'port'        => $port > 0 ? $port : 587,
		'encryption'  => $encryption,
		'user'        => cw_get_site_setting( 'smtp_user' ),
		'pass'        => cw_get_site_setting( 'smtp_pass' ),
	];
}

function cw_smtp_is_ready() {
	$config = cw_get_smtp_config();

	return $config['enabled'] && $config['host'] !== '';
}

add_filter( 'wp_mail_from', function ( $email ) {
	$from = cw_get_mail_from_email();

	return is_email( $from ) ? $from : $email;
} );

add_filter( 'wp_mail_from_name', function ( $name ) {
	return cw_get_mail_from_name();
} );

add_action( 'phpmailer_init', 'cw_configure_phpmailer' );

function cw_configure_phpmailer( $phpmailer ) {
	if ( ! cw_smtp_is_ready() ) {
		return;
	}

	$config = cw_get_smtp_config();

	$phpmailer->isSMTP();
	$phpmailer->Host          = $config['host'];
	$phpmailer->Port          = $config['port'];
	$phpmailer->SMTPAuth      = $config['user'] !== '';
	$phpmailer->Username      = $config['user'];
	$phpmailer->Password      = $config['pass'];
	$phpmailer->SMTPSecure    = $config['encryption'] === 'none' ? '' : $config['encryption'];
	$phpmailer->SMTPAutoTLS   = $config['encryption'] !== 'none';
	$phpmailer->Timeout       = 12;
	$phpmailer->SMTPKeepAlive = false;
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
	$body        = cw_build_enquiry_customer_body( $data );
	$headers     = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', cw_get_mail_from_name(), cw_get_mail_from_email() ),
	];
	$attachments = [];
	$brochure    = cw_get_enquiry_brochure_path();

	if ( $brochure !== '' ) {
		$attachments[] = $brochure;
	}

	return wp_mail( $data['email'], $subject, $body, $headers, $attachments );
}

/**
 * @param array<string,string> $data
 */
function cw_send_contact_admin_email( array $data ) {
	$to      = cw_get_enquiry_recipient();
	$topic   = ! empty( $data['topic'] ) ? $data['topic'] : 'General';
	$subject = sprintf( '[Contact] %s — %s', $data['name'], $topic );
	$body    = cw_build_contact_admin_body( $data );
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', $data['name'], $data['email'] ),
	];

	return wp_mail( $to, $subject, $body, $headers );
}

/**
 * @param array<string,string> $data
 */
function cw_send_contact_customer_email( array $data ) {
	$subject = sprintf(
		'We received your message — %s',
		cw_get_mail_from_name()
	);
	$body    = cw_build_contact_customer_body( $data );
	$headers = [
		'Content-Type: text/plain; charset=UTF-8',
		sprintf( 'Reply-To: %s <%s>', cw_get_mail_from_name(), cw_get_mail_from_email() ),
	];

	return wp_mail( $data['email'], $subject, $body, $headers );
}

/**
 * @param array<string,string> $data
 */
function cw_build_contact_admin_body( array $data ) {
	$lines = [
		'A new contact message was submitted on the website.',
		'',
		'Form: Contact (general)',
		"Name: {$data['name']}",
		"Email: {$data['email']}",
		'Phone: ' . ( $data['phone'] !== '' ? $data['phone'] : '(not provided)' ),
		'Topic: ' . ( $data['topic'] !== '' ? $data['topic'] : '(not specified)' ),
		'',
		'Message:',
		$data['message'],
		'',
		'---',
		'Reply directly to this email to respond.',
	];

	return implode( "\n", $lines );
}

/**
 * @param array<string,string> $data
 */
function cw_build_contact_customer_body( array $data ) {
	$phone = cw_get_org_phone();
	$lines = [
		"Hi {$data['name']},",
		'',
		'Thanks for getting in touch with ' . cw_get_mail_from_name() . '.',
		'We have received your message and will reply within one business day.',
		'',
	];

	if ( $phone !== '' ) {
		$lines[] = 'If you prefer to talk sooner, call ' . $phone . '.';
		$lines[] = '';
	}

	if ( ! empty( $data['topic'] ) ) {
		$lines[] = 'Topic: ' . $data['topic'];
		$lines[] = '';
	}

	$lines[] = 'Your message:';
	$lines[] = $data['message'];
	$lines[] = '';
	$lines[] = '---';
	$lines[] = cw_get_mail_from_name();

	return implode( "\n", $lines );
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
		'We have received your details and will be in touch within one business day with your quote.',
		'',
	];

	$brochure_label = cw_get_enquiry_brochure_label();
	if ( $brochure_label !== '' ) {
		$lines[] = 'We have attached our product brochure for your reference.';
		$lines[] = '';
	}

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
add_action( 'admin_post_cw_test_mail', 'cw_handle_test_mail' );

function cw_handle_test_mail() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'Unauthorized', 'curam-wines' ) );
	}

	check_admin_referer( 'cw_test_mail' );
	@set_time_limit( 25 );

	$to = isset( $_POST['test_recipient'] ) ? sanitize_email( wp_unslash( $_POST['test_recipient'] ) ) : '';
	if ( ! $to || ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$result = cw_send_test_mail( $to );

	$redirect = add_query_arg(
		[
			'page'         => 'cw-site-settings',
			'cw_mail_test' => $result ? 'sent' : 'failed',
		],
		admin_url( 'options-general.php' )
	);

	wp_safe_redirect( $redirect );
	exit;
}

/**
 * @return bool
 */
function cw_send_test_mail( $to ) {
	if ( ! cw_smtp_is_ready() ) {
		return false;
	}

	$config  = cw_get_smtp_config();
	$connect = cw_probe_smtp_host( $config['host'], $config['port'] );
	if ( ! $connect['ok'] ) {
		return false;
	}

	$smtp_on = cw_is_smtp_enabled() ? 'yes' : 'no';

	return (bool) wp_mail(
		$to,
		'Test email — ' . get_bloginfo( 'name' ),
		"This is a test email from your WordPress site.\n\nSMTP enabled: {$smtp_on}\nFrom: " . cw_get_mail_from_name() . ' <' . cw_get_mail_from_email() . ">\nTime: " . wp_date( 'Y-m-d H:i:s T' ),
		[ 'Content-Type: text/plain; charset=UTF-8' ]
	);
}

/**
 * Fail fast instead of waiting for PHPMailer's 5-minute default timeout.
 *
 * @return array{ok:bool,message:string}
 */
function cw_probe_smtp_host( $host, $port ) {
	if ( ! function_exists( 'fsockopen' ) ) {
		return [ 'ok' => true, 'message' => '' ];
	}

	$errno  = 0;
	$errstr = '';
	$socket = @fsockopen( $host, (int) $port, $errno, $errstr, 8 );

	if ( is_resource( $socket ) ) {
		fclose( $socket );
		return [ 'ok' => true, 'message' => '' ];
	}

	$detail = trim( $errstr !== '' ? "{$errstr} (error {$errno})" : "error {$errno}" );

	return [
		'ok'      => false,
		'message' => sprintf(
			'Cannot reach SMTP host %s on port %d (%s). Check the hostname and port. If you are testing from Local (winefridge.local), SiteGround SMTP may be blocked — test this on the live SiteGround site. If you are already on SiteGround, use the Outgoing server from Site Tools → Email → Accounts → Mail Configuration.',
			$host,
			(int) $port,
			$detail
		),
	];
}
