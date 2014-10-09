<?php
$content = array(); 
if( isset( $_GET['site'] ) && $_GET['site'] ){
	//$content[] = '<option value="'.$_GET['site'].'">'.$_GET['site'].'</option>';
	try {
		$pt = ( isset( $_GET['post_type'] ) )? $_GET['post_type'] : 'post';
		$content = \file_get_contents( $_GET['site'].'?service=select-list&return-json=true&post_type='.$pt );
		$content = json_decode( $content );
	} catch ( Exception $e ){
				$content[] = '<option value="'.$the_query->post->ID.'">Fail</option>';
	}
} else {
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
}
if( isset( $_GET['return-json'] ) ) {
	echo json_encode( $content );
} else {
	echo implode('', $content );
}
?>