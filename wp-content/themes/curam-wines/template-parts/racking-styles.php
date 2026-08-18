<?php
$query = cw_query_racks();
get_template_part( 'template-parts/rack-grid', null, [ 'query' => $query ] );
