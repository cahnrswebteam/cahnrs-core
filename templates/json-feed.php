<?php 
	function json_feed_get_image( &$post ){
		
	}
	
	$cahnrs_posts = array();
	$i = 1;
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); 
			ob_start();
			the_content();
			$post->full_content = ob_get_clean();
			$post->post_link = \get_permalink( $post->ID );
			$post->i = $i;
			$post->meta = \get_post_meta( $post->ID );
			$post->images = array();
			$image_sizes = \get_intermediate_image_sizes();
			foreach( $image_sizes as $size ){
				$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $size );
				$post->images[ $size ] = $image[0]; 
			}
			$cahnrs_posts[] = $post;
			$i++;
		} // end while
	} // end if
	echo json_encode( $cahnrs_posts );
?>