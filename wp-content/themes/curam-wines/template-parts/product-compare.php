<?php
/**
 * Compare cards + table — data from Products CPT.
 */
$compare_rows = cw_get_product_compare_rows();
if ( empty( $compare_rows ) ) {
	return;
}
?>
<div class="cw-compare-cards">
  <?php foreach ( $compare_rows as $row ) : ?>
    <a class="cw-compare-card" href="<?php echo esc_url( $row['url'] ); ?>">
      <h3><?php echo esc_html( $row['title'] ); ?></h3>
      <dl>
        <div><dt>Series</dt><dd><?php echo esc_html( $row['series'] ); ?></dd></div>
        <div><dt>Capacity</dt><dd><?php echo esc_html( $row['capacity'] ?: '—' ); ?></dd></div>
        <div><dt>Dimensions</dt><dd><?php echo esc_html( preg_replace( '/^W\s*/', '', $row['dims'] ) ?: '—' ); ?></dd></div>
        <div><dt>Install</dt><dd><?php echo esc_html( $row['install'] ?: '—' ); ?></dd></div>
        <div><dt>From</dt><dd class="is-price"><?php echo esc_html( $row['price'] ?: '—' ); ?></dd></div>
      </dl>
    </a>
  <?php endforeach; ?>
</div>

<div class="cw-compare-scroll cw-compare-scroll--desktop">
  <table class="cw-compare">
    <thead>
      <tr>
        <th scope="col">Configuration</th>
        <th scope="col">Series</th>
        <th scope="col">Capacity</th>
        <th scope="col">Dimensions</th>
        <th scope="col">Install type</th>
        <th scope="col">From (installed)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ( $compare_rows as $row ) : ?>
        <tr>
          <th scope="row"><a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a></th>
          <td><?php echo esc_html( $row['series'] ); ?></td>
          <td><?php echo esc_html( $row['capacity'] ?: '—' ); ?></td>
          <td><?php echo esc_html( preg_replace( '/^W\s*/', '', $row['dims'] ) ?: '—' ); ?></td>
          <td><?php echo esc_html( $row['install'] ?: '—' ); ?></td>
          <td><?php echo esc_html( $row['price'] ?: '—' ); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
