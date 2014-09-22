<?php
	if( isset( $_GET['source'] ) ){
		$page = \file_get_contents( $_GET['source'].'?cahnrs-feed=true' );
		var_dump($page);
	}
?>