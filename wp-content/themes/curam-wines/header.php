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

<header class="cw-header">
  <div class="cw-header-inner">
    <a class="cw-brand" href="<?php echo home_url('/'); ?>">
      <img class="cw-brand-crest" src="<?php echo get_theme_file_uri('assets/images/crest-white.png'); ?>" alt="Walk-In Wine Cabinets Australia">
      <span class="cw-brand-text">
        <span class="b1">Walk-In Wine Cabinets</span>
        <span class="b2">Australia</span>
      </span>
    </a>
    <ul class="cw-nav">
      <li><a href="<?php echo home_url('/products/'); ?>"<?php if(is_page('products')) echo ' class="is-active"'; ?>>The Range</a></li>
      <li><a href="<?php echo home_url('/'); ?>#craft">Engineering</a></li>
      <li><a href="<?php echo home_url('/installations/'); ?>"<?php if(is_post_type_archive('case_study')||is_singular('case_study')) echo ' class="is-active"'; ?>>Installations</a></li>
      <li><a href="<?php echo home_url('/blog/'); ?>"<?php if(is_home()||is_category()||is_tag()||is_singular('post')) echo ' class="is-active"'; ?>>Journal</a></li>
      <li><a href="<?php echo home_url('/about/'); ?>"<?php if(is_page('about')) echo ' class="is-active"'; ?>>About</a></li>
      <li><a href="<?php echo home_url('/faq/'); ?>"<?php if(is_page('faq')) echo ' class="is-active"'; ?>>FAQ</a></li>
    </ul>
    <div class="cw-header-cta">
      <a class="cw-header-phone" href="tel:1300924671">1300 924 671</a>
      <a class="cw-btn cw-btn--ghost" href="<?php echo home_url('/enquire/'); ?>">Get Specs</a>
      <button class="cw-burger" aria-label="Menu" aria-expanded="false">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
      </button>
    </div>
  </div>
</header>
