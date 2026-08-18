<?php
/**
 * Shared product specification table.
 *
 * @var array $args { specs: array<array{label:string,value:string}> }
 */
$specs = $args['specs'] ?? [];
if ( empty( $specs ) ) {
	return;
}
?>
<table class="cw-ledger">
  <tbody>
    <?php foreach ( $specs as $row ) : ?>
      <tr>
        <th><?php echo esc_html( $row['label'] ); ?></th>
        <td><?php echo esc_html( $row['value'] ); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
