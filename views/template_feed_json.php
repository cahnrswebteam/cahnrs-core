<?php 
	if ( have_posts() ) {
		while ( have_posts() ) {
			$doc = new DOMDocument();
			the_post(); 
			ob_start();
			the_content();
			//$doc->loadHTML( ob_get_clean() );
			//$imageTags = $doc->getElementsByTagName('img');
			//foreach( $imageTags  as $tag ){
				//$tag->setAttribute('src','#');
			//}
			$content = ob_get_clean();
			echo '<script type="text/javascript"> var page = '.json_encode ( $content ).'</script>';
			echo '<script type="text/javascript"> document.write( page )</script>';
			//
			// Post Content here
			//
		} // end while
	} // end if
?>