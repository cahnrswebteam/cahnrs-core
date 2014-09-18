<?php namespace cahnrswp\cahnrs\core;
$qc = new query_control();
$query = $_GET;
unset( $query['service'] );
$query_obj = $qc->get_query_obj( $query );
$query_obj = json_encode( $query_obj );
echo $query_obj;
?>