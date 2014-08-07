<?php 
	$cahnrs_posts = array();
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); 
			ob_start();
			the_content();
			$post->full_content = ob_get_clean();
			$cahnrs_posts[] = $post;
		} // end while
	} // end if
	echo json_encode( $cahnrs_posts );
?>