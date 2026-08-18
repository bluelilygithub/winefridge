<?php
require '/Users/michaelbarrrett/Local Sites/winefridge/app/public/wp-load.php';

$page = get_page_by_path( 'racking' );
if ( $page instanceof WP_Post && $page->post_type === 'page' ) {
    wp_update_post([
        'ID' => $page->ID,
        'post_name' => 'racking-intro',
        'post_content' => trim(str_replace('[cw_racking_styles]', '', (string) $page->post_content)),
    ]);
    echo "Renamed page {$page->ID} to racking-intro\n";
} else {
    $page = get_page_by_path( 'racking-intro' );
    if ($page) {
        echo "Intro page already at racking-intro ({$page->ID})\n";
    }
}

$racks = [
    [
        'slug' => 'high-density-storage',
        'title' => 'High-density storage',
        'style' => 'Capacity first',
        'image' => 'high-density',
        'excerpt' => 'Maximise bottle count with tight, efficient rows when capacity is the priority.',
        'content' => "<p>High-density storage is the practical answer when the collection is growing fast and every bay needs to work harder. The emphasis is on compact bottle spacing, simple retrieval, and the highest possible count within the room.</p><p>We usually pair this direction with dedicated large-format handling elsewhere in the cellar, so the main field can stay dense and orderly.</p>",
    ],
    [
        'slug' => 'display-showcase',
        'title' => 'Display & showcase',
        'style' => 'Feature display',
        'image' => 'display',
        'excerpt' => 'Label-forward racking that turns the collection into a visible architectural feature.',
        'content' => "<p>Display-led racking gives the collection visual presence. Bottles are presented label-forward or in hero rows, often with lighting, framed zones, or glazing so the cellar reads as part of the room design.</p><p>This is the right fit when the bottles need to perform as storage and as a focal point.</p>",
    ],
    [
        'slug' => 'mixed-capacity-display',
        'title' => 'Mixed capacity & display',
        'style' => 'Balanced layout',
        'image' => 'mixed',
        'excerpt' => 'A blend of dense storage and feature shelving for collectors who want both function and presence.',
        'content' => "<p>Most projects land here: enough high-density storage for the working collection, with selected zones set aside for display, entertaining, and easier browsing.</p><p>It is the most flexible brief because we can bias the final layout toward storage, display, or service accessories as the concept develops.</p>",
    ],
    [
        'slug' => 'diamond-x-bins',
        'title' => 'Diamond / X bins',
        'style' => 'Bulk storage',
        'image' => 'diamond',
        'excerpt' => 'Traditional bin storage for mixed cases, regions, and easy bulk access.',
        'content' => "<p>Diamond bins are useful when you want quick bulk storage without committing every bottle to its own fixed slot. They suit collectors who buy by case, group wines by region, or want a classic cellar language.</p><p>We often combine bins with individual bottle rows so the cellar stays practical as the collection evolves.</p>",
    ],
    [
        'slug' => 'magnum-large-format',
        'title' => 'Magnum & large format',
        'style' => 'Oversized bottles',
        'image' => 'magnum',
        'excerpt' => 'Dedicated bays sized around magnums and larger bottles so they are integrated rather than improvised.',
        'content' => "<p>Large formats quickly expose weak planning if they are left until the end. Dedicated magnum storage builds those bottles into the fit-out from the start, preserving clean lines and usable spacing elsewhere.</p><p>These bays can sit quietly inside a broader storage wall or become part of the display moment.</p>",
    ],
    [
        'slug' => 'custom-configurations',
        'title' => 'Custom configurations',
        'style' => 'Tailored fit-out',
        'image' => 'custom',
        'excerpt' => 'Materials, bottle orientation, lighting, and special zones tuned to the room and collection.',
        'content' => "<p>Custom configurations let the rack design respond to the architecture rather than forcing the room into a standard pattern. Timber, metal, stain, bottle orientation, accessory shelves, and lighting can all be tuned to suit.</p><p>This is where the fit-out becomes specific to your collection and the space it lives in.</p>",
    ],
];

foreach ( $racks as $i => $rack ) {
    $existing = get_page_by_path( $rack['slug'], OBJECT, 'rack' );
    $postarr = [
        'post_type' => 'rack',
        'post_status' => 'publish',
        'post_name' => $rack['slug'],
        'post_title' => $rack['title'],
        'post_excerpt' => $rack['excerpt'],
        'post_content' => $rack['content'],
        'menu_order' => $i,
    ];
    if ( $existing ) {
        $postarr['ID'] = $existing->ID;
        $rack_id = wp_update_post( $postarr, true );
        echo "Updated rack {$rack['slug']} (#{$rack_id})\n";
    } else {
        $rack_id = wp_insert_post( $postarr, true );
        echo "Created rack {$rack['slug']} (#{$rack_id})\n";
    }
    if ( ! is_wp_error( $rack_id ) ) {
        update_post_meta( $rack_id, '_rack_style', $rack['style'] );
        update_post_meta( $rack_id, '_rack_theme_image', $rack['image'] );
        update_post_meta( $rack_id, '_cw_show_in_gallery', '0' );
    }
}

flush_rewrite_rules();
echo "Flushed rewrite rules\n";
