<?php
/**
 * Enquiry form submissions — stored as an admin-only log in the sidebar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'cw_register_enquiry_log_cpt' );

function cw_register_enquiry_log_cpt() {
	register_post_type( 'cw_enquiry', [
		'labels'              => [
			'name'               => 'Enquiry log',
			'singular_name'      => 'Enquiry',
			'menu_name'          => 'Enquiry log',
			'all_items'          => 'All enquiries',
			'view_item'          => 'View enquiry',
			'search_items'       => 'Search enquiries',
			'not_found'          => 'No enquiries yet.',
			'not_found_in_trash' => 'No enquiries in Trash.',
		],
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 26,
		'menu_icon'           => 'dashicons-email-alt',
		'capability_type'     => 'post',
		'map_meta_cap'        => true,
		'capabilities'        => [
			'create_posts' => 'do_not_allow',
		],
		'supports'            => [ 'title' ],
		'has_archive'         => false,
		'rewrite'             => false,
		'show_in_rest'        => false,
	] );
}

/**
 * Persist a form submission. Returns the log post ID or 0 on failure.
 *
 * @param array<string,string> $data
 * @param array{admin?:bool,customer?:bool} $mail_status
 */
function cw_log_enquiry( array $data, array $mail_status = [] ) {
	$form_type = $data['form_type'] ?? 'quote';
	if ( $form_type !== 'contact' ) {
		$form_type = 'quote';
	}

	$name  = $data['name'] ?? ( $form_type === 'contact' ? 'Contact' : 'Enquiry' );
	$prefix = $form_type === 'contact' ? 'Contact' : 'Quote';
	$title  = sprintf(
		'%s — %s — %s',
		$prefix,
		$name,
		wp_date( 'j M Y, g:ia' )
	);

	$post_id = wp_insert_post(
		[
			'post_type'   => 'cw_enquiry',
			'post_status' => 'publish',
			'post_title'  => $title,
			'post_content'=> '',
		],
		true
	);

	if ( is_wp_error( $post_id ) || ! $post_id ) {
		return 0;
	}

	$fields = [
		'form_type',
		'mode',
		'name',
		'email',
		'phone',
		'topic',
		'city',
		'bottle_capacity',
		'series',
		'installation_type',
		'width',
		'height',
		'depth',
		'finish',
		'property_type',
		'deadline',
		'message',
	];

	$data['form_type'] = $form_type;

	foreach ( $fields as $key ) {
		$value = $data[ $key ] ?? '';
		update_post_meta( $post_id, '_cw_enquiry_' . $key, is_string( $value ) ? $value : '' );
	}

	update_post_meta( $post_id, '_cw_enquiry_mail_admin', ! empty( $mail_status['admin'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_cw_enquiry_mail_customer', ! empty( $mail_status['customer'] ) ? '1' : '0' );
	update_post_meta( $post_id, '_cw_enquiry_ip', cw_get_enquiry_client_ip() );
	update_post_meta( $post_id, '_cw_enquiry_user_agent', isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '' );
	update_post_meta( $post_id, '_cw_enquiry_referer', wp_get_referer() ? esc_url_raw( wp_get_referer() ) : '' );

	return (int) $post_id;
}

function cw_get_enquiry_client_ip() {
	$keys = [ 'HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' ];

	foreach ( $keys as $key ) {
		if ( empty( $_SERVER[ $key ] ) ) {
			continue;
		}
		$raw = sanitize_text_field( wp_unslash( $_SERVER[ $key ] ) );
		$ip  = trim( explode( ',', $raw )[0] );
		if ( filter_var( $ip, FILTER_VALIDATE_IP ) ) {
			return $ip;
		}
	}

	return '';
}

/**
 * @return array<string,string>
 */
function cw_get_enquiry_log_field_labels() {
	return [
		'form_type'         => 'Form',
		'name'              => 'Name',
		'email'             => 'Email',
		'phone'             => 'Phone',
		'topic'             => 'Topic',
		'city'              => 'City / State',
		'bottle_capacity'   => 'Bottle capacity',
		'property_type'     => 'Property / situation',
		'series'            => 'Series interest',
		'installation_type' => 'Installation type',
		'width'             => 'Available width',
		'height'            => 'Available height',
		'depth'             => 'Available depth',
		'finish'            => 'Finish / enclosure',
		'deadline'          => 'Target install date',
		'mode'              => 'Form mode',
		'message'           => 'Message',
	];
}

function cw_get_enquiry_meta( $post_id, $key, $default = '' ) {
	$value = get_post_meta( $post_id, '_cw_enquiry_' . $key, true );

	return is_string( $value ) && $value !== '' ? $value : $default;
}

/* -------------------------------------------------------------------------
 * Admin list columns
 * ---------------------------------------------------------------------- */
add_filter( 'manage_cw_enquiry_posts_columns', function ( $columns ) {
	$date = $columns['date'] ?? null;
	unset( $columns['date'], $columns['title'] );

	$columns['cw_enquiry_when']    = 'Received';
	$columns['cw_enquiry_type']    = 'Type';
	$columns['title']              = 'Enquiry';
	$columns['cw_enquiry_contact'] = 'Contact';
	$columns['cw_enquiry_city']    = 'City';
	$columns['cw_enquiry_bottles'] = 'Bottles';
	$columns['cw_enquiry_mail']    = 'Email status';

	if ( $date ) {
		$columns['date'] = $date;
	}

	return $columns;
} );

add_action( 'manage_cw_enquiry_posts_custom_column', function ( $column, $post_id ) {
	switch ( $column ) {
		case 'cw_enquiry_when':
			echo esc_html( get_the_date( 'j M Y g:ia', $post_id ) );
			break;

		case 'cw_enquiry_type':
			$type = cw_get_enquiry_meta( $post_id, 'form_type', 'quote' );
			echo esc_html( $type === 'contact' ? 'Contact' : 'Quote' );
			break;

		case 'cw_enquiry_contact':
			$email = cw_get_enquiry_meta( $post_id, 'email' );
			$phone = cw_get_enquiry_meta( $post_id, 'phone' );
			if ( $email ) {
				printf( '<a href="mailto:%1$s">%2$s</a>', esc_attr( $email ), esc_html( $email ) );
			}
			if ( $email && $phone ) {
				echo '<br>';
			}
			if ( $phone ) {
				$tel = preg_replace( '/[^\d+]/', '', $phone );
				printf( '<a href="tel:%1$s">%2$s</a>', esc_attr( $tel ), esc_html( $phone ) );
			}
			break;

		case 'cw_enquiry_city':
			echo esc_html( cw_get_enquiry_meta( $post_id, 'city', '—' ) );
			break;

		case 'cw_enquiry_bottles':
			echo esc_html( cw_get_enquiry_meta( $post_id, 'bottle_capacity', '—' ) );
			break;

		case 'cw_enquiry_mail':
			$admin_ok    = cw_get_enquiry_meta( $post_id, 'mail_admin' ) === '1';
			$customer_ok = cw_get_enquiry_meta( $post_id, 'mail_customer' ) === '1';
			if ( $admin_ok && $customer_ok ) {
				echo '<span style="color:#1a7f37;">Sent</span>';
			} elseif ( $admin_ok || $customer_ok ) {
				echo '<span style="color:#996800;">Partial</span>';
				echo '<br><span class="description">' . esc_html(
					sprintf(
						'Admin: %s · Customer: %s',
						$admin_ok ? 'ok' : 'fail',
						$customer_ok ? 'ok' : 'fail'
					)
				) . '</span>';
			} else {
				echo '<span style="color:#b32d2e;">Not sent</span>';
			}
			break;
	}
}, 10, 2 );

add_filter( 'manage_edit-cw_enquiry_sortable_columns', function ( $columns ) {
	$columns['cw_enquiry_when'] = 'date';

	return $columns;
} );

/* -------------------------------------------------------------------------
 * Detail meta box on single enquiry
 * ---------------------------------------------------------------------- */
add_action( 'add_meta_boxes', function () {
	add_meta_box(
		'cw_enquiry_details',
		'Enquiry details',
		'cw_render_enquiry_details_meta_box',
		'cw_enquiry',
		'normal',
		'high'
	);
} );

function cw_render_enquiry_details_meta_box( $post ) {
	$labels = cw_get_enquiry_log_field_labels();

	echo '<table class="widefat striped" style="margin-top:0.5rem;"><tbody>';
	foreach ( $labels as $key => $label ) {
		$value = cw_get_enquiry_meta( $post->ID, $key );
		if ( $key === 'form_type' ) {
			$value = $value === 'contact' ? 'Contact' : 'Quote';
		} elseif ( $value === '' ) {
			$value = '—';
		}

		echo '<tr>';
		printf( '<th scope="row" style="width:12rem;vertical-align:top;">%s</th>', esc_html( $label ) );
		if ( $key === 'email' && is_email( $value ) ) {
			printf( '<td><a href="mailto:%1$s">%2$s</a></td>', esc_attr( $value ), esc_html( $value ) );
		} elseif ( $key === 'phone' && $value !== '—' ) {
			$tel = preg_replace( '/[^\d+]/', '', $value );
			printf( '<td><a href="tel:%1$s">%2$s</a></td>', esc_attr( $tel ), esc_html( $value ) );
		} elseif ( $key === 'message' ) {
			printf( '<td style="white-space:pre-wrap;">%s</td>', esc_html( $value ) );
		} else {
			printf( '<td>%s</td>', esc_html( $value ) );
		}
		echo '</tr>';
	}

	$admin_ok    = cw_get_enquiry_meta( $post->ID, 'mail_admin' ) === '1';
	$customer_ok = cw_get_enquiry_meta( $post->ID, 'mail_customer' ) === '1';
	$ip          = cw_get_enquiry_meta( $post->ID, 'ip' );
	$referer     = cw_get_enquiry_meta( $post->ID, 'referer' );

	echo '<tr><th scope="row">Admin email</th><td>' . esc_html( $admin_ok ? 'Sent' : 'Failed / not sent' ) . '</td></tr>';
	echo '<tr><th scope="row">Customer email</th><td>' . esc_html( $customer_ok ? 'Sent' : 'Failed / not sent' ) . '</td></tr>';
	if ( $ip !== '' ) {
		echo '<tr><th scope="row">IP address</th><td>' . esc_html( $ip ) . '</td></tr>';
	}
	if ( $referer !== '' ) {
		printf( '<tr><th scope="row">Submitted from</th><td><a href="%1$s">%2$s</a></td></tr>', esc_url( $referer ), esc_html( $referer ) );
	}
	echo '</tbody></table>';
}

/* Hide “Add New” / editor chrome that does not apply. */
add_action( 'admin_head', function () {
	$screen = get_current_screen();
	if ( ! $screen || $screen->post_type !== 'cw_enquiry' ) {
		return;
	}
	echo '<style>
		.wrap .page-title-action,
		#edit-slug-box,
		#minor-publishing,
		#misc-publishing-actions .misc-pub-section:not(.misc-pub-post-status),
		.block-editor-writing-flow { display: none !important; }
		#post-body-content { margin-bottom: 0; }
	</style>';
} );

add_filter( 'post_row_actions', function ( $actions, $post ) {
	if ( $post->post_type !== 'cw_enquiry' ) {
		return $actions;
	}

	unset( $actions['inline hide-if-no-js'], $actions['view'] );

	return $actions;
}, 10, 2 );
