<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" sizes="64x64" href="<?php echo get_theme_file_uri('assets/images/favicon-64.png'); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_theme_file_uri('assets/images/favicon-32.png'); ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_theme_file_uri('assets/images/favicon-180.png'); ?>">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="cw-live-region" id="cw-live-region" aria-live="polite" aria-atomic="true"></div>

<header class="cw-header<?php echo is_front_page() ? ' is-overlay' : ' is-solid'; ?>" role="banner">
  <div class="cw-header-inner">
    <a class="cw-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <img class="cw-brand-crest" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/crest-white.png' ) ); ?>" alt="" width="44" height="44" decoding="async">
      <span class="cw-brand-text">
        <span class="b1">Walk-In Wine Cabinets</span>
        <span class="b2">Australia</span>
      </span>
    </a>
    <nav class="cw-nav" id="cw-primary-nav" aria-label="Primary">
      <ul>
        <li><a href="<?php echo esc_url( home_url( '/products/' ) ); ?>"<?php echo cw_nav_link_attrs( 'products' ); ?>>The Range</a></li>
        <li><a href="<?php echo esc_url( home_url( '/engineering/' ) ); ?>"<?php echo cw_nav_link_attrs( 'engineering' ); ?>>How It Works</a></li>
        <li><a href="<?php echo esc_url( home_url( '/installations/' ) ); ?>"<?php echo cw_nav_link_attrs( 'installations' ); ?>>Installations</a></li>
        <li><a href="<?php echo esc_url( get_post_type_archive_link( 'rack' ) ?: home_url( '/racking/' ) ); ?>"<?php echo cw_nav_link_attrs( 'racking' ); ?>>Racking</a></li>
        <li><a href="<?php echo esc_url( home_url( '/gallery/' ) ); ?>"<?php echo cw_nav_link_attrs( 'gallery' ); ?>>Gallery</a></li>
        <li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"<?php echo cw_nav_link_attrs( 'faq' ); ?>>FAQ</a></li>
      </ul>
    </nav>
    <div class="cw-header-cta">
      <a class="cw-header-phone" href="tel:<?php echo esc_attr( cw_get_org_phone_tel() ); ?>" aria-label="<?php echo esc_attr( 'Call ' . cw_get_org_phone() ); ?>"><?php echo esc_html( cw_get_org_phone() ); ?></a>
      <a class="cw-btn cw-btn--ghost" href="<?php echo esc_url( home_url( '/enquire/' ) ); ?>">Get a fixed quote</a>
      <button type="button" class="cw-burger" aria-label="Open menu" aria-expanded="false" aria-controls="cw-primary-nav">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true" focusable="false"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
</header>
<?php cw_open_main(); ?>
