<?php
/**
 * Installations archive grid — data from Installations CPT.
 */
?>
<div class="cw-cs-filter-wrap">
  <div class="cw-filter cw-filter--situation" role="group" aria-label="Filter installations by property type">
    <button type="button" class="cw-chip is-active" data-filter="*" aria-pressed="true">All</button>
    <?php foreach ( cw_get_situation_filters() as $slug => $label ) : ?>
      <button type="button" class="cw-chip" data-filter="<?php echo esc_attr( $slug ); ?>" aria-pressed="false"><?php echo esc_html( $label ); ?></button>
    <?php endforeach; ?>
  </div>
  <div class="cw-cs-grid">
  <?php
  $query = isset( $args['query'] ) && $args['query'] instanceof WP_Query ? $args['query'] : null;

  if ( $query && $query->have_posts() ) :
    while ( $query->have_posts() ) : $query->the_post();
      $location = get_post_meta( get_the_ID(), '_cs_location', true );
      $type     = get_post_meta( get_the_ID(), '_cs_type', true );
      $bottles  = get_post_meta( get_the_ID(), '_cs_bottles', true );
      $filter   = cw_get_installation_situation_slug( get_the_ID() );
  ?>
    <a class="cw-cs-card" href="<?php the_permalink(); ?>" data-cats="<?php echo esc_attr( $filter ); ?>">
      <div class="cw-cs-card-img">
        <?php if ( has_post_thumbnail() ) {
          the_post_thumbnail( 'large', [ 'alt' => get_the_title() ] );
        } else {
          echo '<img src="' . esc_url( get_theme_file_uri( 'assets/images/product-panel-walkin.jpg' ) ) . '" alt="' . esc_attr( get_the_title() ) . '">';
        } ?>
      </div>
      <div class="cw-cs-card-body">
        <?php if ( $location ) : ?><span class="cw-cs-card-loc"><?php echo esc_html( $location ); ?></span><?php endif; ?>
        <h3><?php the_title(); ?></h3>
        <?php if ( $type ) : ?><span class="cw-cs-card-type"><?php echo esc_html( $type ); ?></span><?php endif; ?>
        <?php if ( $bottles ) : ?><span class="cw-cs-card-bottles"><?php echo esc_html( $bottles ); ?> bottles</span><?php endif; ?>
        <span class="cw-link">View installation <span>&rarr;</span></span>
      </div>
    </a>
  <?php
    endwhile;
    wp_reset_postdata();
  elseif ( have_posts() ) :
    while ( have_posts() ) : the_post();
      $location = get_post_meta( get_the_ID(), '_cs_location', true );
      $type     = get_post_meta( get_the_ID(), '_cs_type', true );
      $bottles  = get_post_meta( get_the_ID(), '_cs_bottles', true );
      $filter   = cw_get_installation_situation_slug( get_the_ID() );
  ?>
    <a class="cw-cs-card" href="<?php the_permalink(); ?>" data-cats="<?php echo esc_attr( $filter ); ?>">
      <div class="cw-cs-card-img">
        <?php if ( has_post_thumbnail() ) {
          the_post_thumbnail( 'large', [ 'alt' => get_the_title() ] );
        } else {
          echo '<img src="' . esc_url( get_theme_file_uri( 'assets/images/product-panel-walkin.jpg' ) ) . '" alt="' . esc_attr( get_the_title() ) . '">';
        } ?>
      </div>
      <div class="cw-cs-card-body">
        <?php if ( $location ) : ?><span class="cw-cs-card-loc"><?php echo esc_html( $location ); ?></span><?php endif; ?>
        <h3><?php the_title(); ?></h3>
        <?php if ( $type ) : ?><span class="cw-cs-card-type"><?php echo esc_html( $type ); ?></span><?php endif; ?>
        <?php if ( $bottles ) : ?><span class="cw-cs-card-bottles"><?php echo esc_html( $bottles ); ?> bottles</span><?php endif; ?>
        <span class="cw-link">View installation <span>&rarr;</span></span>
      </div>
    </a>
  <?php
    endwhile;
  else :
    echo '<p class="cw-empty">No installations published yet.</p>';
  endif;
  ?>
  </div>
</div>
