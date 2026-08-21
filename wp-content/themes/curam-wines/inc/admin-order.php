<?php
/**
 * Drag-and-drop post order in WP Admin list tables.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cw_orderable_post_types() {
	return [ 'post', 'page', 'product', 'case_study', 'rack', 'cw_faq', 'cw_gallery', 'cw_process' ];
}

add_action( 'init', function () {
	foreach ( [ 'product', 'case_study', 'post' ] as $type ) {
		add_post_type_support( $type, 'page-attributes' );
	}
}, 20 );

function cw_is_orderable_admin_screen() {
	if ( ! is_admin() ) {
		return false;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || $screen->base !== 'edit' ) {
		return false;
	}
	return in_array( $screen->post_type, cw_orderable_post_types(), true );
}

add_action( 'pre_get_posts', function ( $query ) {
	if ( ! $query instanceof WP_Query || ! $query->is_main_query() ) {
		return;
	}

	$type = $query->get( 'post_type' );
	if ( is_array( $type ) ) {
		return;
	}
	if ( ! in_array( $type, cw_orderable_post_types(), true ) ) {
		return;
	}

	if ( is_admin() ) {
		if ( ! empty( $_GET['orderby'] ) || ! empty( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		$query->set( 'orderby', [ 'menu_order' => 'ASC', 'title' => 'ASC' ] );
		$query->set( 'order', 'ASC' );
		return;
	}

	if ( $query->get( 'orderby' ) ) {
		return;
	}

	$query->set( 'orderby', [ 'menu_order' => 'ASC', 'title' => 'ASC' ] );
	$query->set( 'order', 'ASC' );
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
	if ( $hook !== 'edit.php' || ! cw_is_orderable_admin_screen() ) {
		return;
	}

	if ( ! empty( $_GET['orderby'] ) || ! empty( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}

	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_style(
		'cw-admin-order',
		get_template_directory_uri() . '/assets/css/admin-order.css',
		[],
		CW_VERSION
	);
	wp_enqueue_script(
		'cw-admin-order',
		get_template_directory_uri() . '/assets/js/admin-order.js',
		[ 'jquery', 'jquery-ui-sortable' ],
		CW_VERSION,
		true
	);

	$per_page = (int) get_user_option( 'edit_' . get_current_screen()->post_type . '_per_page' );
	if ( $per_page < 1 ) {
		$per_page = 20;
	}
	$paged = max( 1, (int) ( $_GET['paged'] ?? 1 ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

	wp_localize_script( 'cw-admin-order', 'cwAdminOrder', [
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'cw_save_post_order' ),
		'start'   => ( $paged - 1 ) * $per_page,
		'i18n'    => [
			'saved' => 'Order saved.',
			'error' => 'Could not save order. Try again.',
		],
	] );
} );

add_filter( 'manage_posts_columns', 'cw_add_order_column', 5 );
add_filter( 'manage_pages_columns', 'cw_add_order_column', 5 );

function cw_add_order_column( $columns ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->post_type, cw_orderable_post_types(), true ) ) {
		return $columns;
	}

	return [ 'cw_order' => '' ] + $columns;
}

add_action( 'manage_posts_custom_column', 'cw_render_order_column', 10, 2 );
add_action( 'manage_pages_custom_column', 'cw_render_order_column', 10, 2 );

function cw_render_order_column( $column, $post_id ) {
	if ( $column !== 'cw_order' ) {
		return;
	}
	echo '<span class="cw-order-handle dashicons dashicons-menu" title="Drag to reorder" aria-hidden="true"></span>';
	echo '<span class="screen-reader-text">Drag to reorder</span>';
}

add_action( 'admin_notices', function () {
	if ( ! cw_is_orderable_admin_screen() ) {
		return;
	}
	if ( ! empty( $_GET['orderby'] ) || ! empty( $_GET['s'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	echo '<div class="notice notice-info is-dismissible"><p><strong>Reorder:</strong> Drag the <span class="dashicons dashicons-menu" style="font-size:16px;width:16px;height:16px;vertical-align:text-bottom;"></span> handle to change the order these appear on the site. The new order saves automatically.</p></div>';
} );

add_action( 'wp_ajax_cw_save_post_order', function () {
	check_ajax_referer( 'cw_save_post_order', 'nonce' );

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => 'You cannot edit these items.' ], 403 );
	}

	$ids   = array_values( array_filter( array_map( 'absint', (array) ( $_POST['ids'] ?? [] ) ) ) );
	$start = absint( $_POST['start'] ?? 0 );

	if ( empty( $ids ) ) {
		wp_send_json_error( [ 'message' => 'Nothing to save.' ], 400 );
	}

	global $wpdb;
	foreach ( $ids as $index => $id ) {
		if ( ! current_user_can( 'edit_post', $id ) ) {
			continue;
		}
		$wpdb->update(
			$wpdb->posts,
			[ 'menu_order' => $start + $index + 1 ],
			[ 'ID' => $id ],
			[ '%d' ],
			[ '%d' ]
		);
		clean_post_cache( $id );
	}

	wp_send_json_success( [ 'saved' => count( $ids ) ] );
} );
