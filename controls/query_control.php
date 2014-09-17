<?php namespace cahnrswp\cahnrs\core;

class query_control {

	public function get_query( $in ){
		// Just in case let's set a default for feed type
		$in['feed_type'] = ( isset( $in['feed_type'] ) && $in['feed_type'] )? $in['feed_type'] : 'basic'; 
		// Based on type call feed method
		switch( $in['feed_type'] ){
			case 'basic':
			case 'default':
				$query = $this->get_basic( $in );
				break;
		}
		$query_obj = $this->get_query_obj( $query );
		return $query_obj;
		//return $query;
	}
	
	// Get basic feed ie: feed by category, tag
	public function get_basic( $in ){
		$query = array();
		/**********************************************
		** Set Post Type  **
		**********************************************/
		$this->check_post_type( $in, $query );
		/**********************************************
		** Set Taxonomy **
		**********************************************/
		$this->check_taxonomy( $in, $query );
		/**********************************************
		** Set Count **
		**********************************************/
		$query['posts_per_page'] = $this->check( $in , 'count' , 10 );
		/**********************************************
		** Set Skip **
		**********************************************/
		$query['offset'] = $this->check( $in , 'skip' , 0 );
		/**********************************************
		** Return Results **
		**********************************************/
		return $query; 
	}
	
	public function get_query_obj( $query ){
		$query_obj = array();
		global $wp_query; // GET GLOBAL QUERY
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		$the_query = new \WP_Query( $query ); // DO YOU HAVE A QUERY?????
		if ( $the_query->have_posts() ) {
			$i = 0;
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$the_query->post->i = $i;
				$query_obj[] = $the_query->post;
				$i++;
			} // END WHILE
		} // END IF
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE
		\wp_reset_postdata();
		return $query_obj;
	}
	/************************************************
	** Services **
	************************************************/
	public function check( $in , $value , $default = 'na' ){
		// If no default value set 
		if( 'na' == $default ){
			$bool = ( isset( $in[$value] ) && $in[$value] )? true : false;
			return $bool;
		} else {
			// Has default value
			$value = ( isset( $in[$value] ) && $in[$value] )? $in[$value] : $default;
			return $value;
		}
		
	}
	
	public function check_post_type( $in, &$query ){
		$query['post_type'] = $this->check( $in , 'post_type' , 'any' );
		// Check for mime type
		if( strpos( $query['post_type'] , 'attachment' ) !== false ){
			$pt = explode( '_' , $query['post_type'] );
			$query['post_type'] = 'attachment';
			switch( $pt[1] ){ // Switch based on mime type indicator
				case 'image': // Is image
					$query['post_mime_type'] = 'image'; // Use image mime type
					break;
				case 'file':
					$query['post_mime_type'] = array( 'text','application' ); // Is document 
					break;
			}// End switch
		}
	}
	
	public function check_taxonomy( $in , &$query ){
		if( $this->check( $in , 'taxonomy' ) && $this->check( $in , 'terms' ) && 'all' != $in['taxonomy'] ){
			$terms = array();
			// Split the terms separated by ','
			$in['terms'] = explode( ',' , $in['terms'] );
			// Get term slug for query
			foreach( $in['terms'] as $term ){
				$term_obj = \get_term_by( 'name', $term ,$in['taxonomy'] );
				// Write term slug to terms array
				$terms[] = $term_obj->slug;
			}
			// Add the tax query
			$query['tax_query'][] = array(
					'taxonomy' => $in['taxonomy'],
					'field' => 'slug',
					'terms' => $terms,
				);	
		};
	}
	
}
?>