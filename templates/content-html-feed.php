<?php
$content = array(); 
if( isset( $_GET['post_type'] ) ){
	$args = array(
		'post_type' => $_GET['post_type'],
		'posts_per_page' => -1,
	);
	$the_query = new WP_Query( $args );
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$content[] = '<option value="'.$the_query->post->ID.'">'.$the_query->post->post_title.'</option>';
			//the_content();
		} // end while
	} // end if
}
if( isset( $_GET['json'] ) ) {
	echo json_encode( $content );
} else {
	echo implode('', $content );
}
?>