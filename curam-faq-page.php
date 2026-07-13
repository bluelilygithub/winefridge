<?php
/**
 * One-time script: creates the FAQ WordPress page.
 * Visit in browser, then delete this file.
 */
require_once __DIR__ . '/wp-load.php';

$slug = 'faq';

if ( get_page_by_path( $slug ) ) {
    echo '<pre>SKIPPED — FAQ page already exists at /' . $slug . '/. Delete this file.</pre>';
    exit;
}

$id = wp_insert_post( [
    'post_title'  => 'Frequently Asked Questions',
    'post_name'   => $slug,
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_content'=> '',
] );

echo '<pre style="font-family:monospace;padding:2rem;">';
echo is_wp_error( $id )
    ? 'ERROR: ' . $id->get_error_message()
    : "CREATED page ID {$id} at /" . $slug . "/\n\nDone. Delete this file now.";
echo '</pre>';
