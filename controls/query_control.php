<?php namespace cahnrswp\cahnrs\core;

class query_control {

	public function get_query( $in ){
		$query_obj = array();
		// Just in case let's set a default for feed type
		$in['feed_type'] = ( isset( $in['feed_type'] ) && $in['feed_type'] )? $in['feed_type'] : 'basic'; 
		// Based on type call feed method
		switch( $in['feed_type'] ){
			case 'meta':
				$query = $this->get_meta_query( $in );
				break;
			case 'select':
				$query = $this->get_select( $in );
				break;
			case 'basic':
			case 'default':
				$query = $this->get_basic( $in );
				break;
		}
		
		$query['image-size'] = $this->check( $in , 'image_size', 'thumbnail' );
		
		if( $this->check( $in , 'current_site' ) ){
			$query = $this->convert_query( $in ,$query ); 
			try {
				$query_obj = \file_get_contents( $query );
				$query_obj = json_decode( $query_obj );
			} catch ( Exception $e ){
				$query_obj = array();
			}
			return $query_obj;
		} else {
			$query_obj = $this->get_query_obj( $query, $in );
		}
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
		if( $this->check( $in , 'count' ) && 'all' == $in['count'] ) $in['count'] = -1 ; 
		$query['posts_per_page'] = $this->check( $in , 'count' , 10 );
		/**********************************************
		** Set Skip **
		**********************************************/
		$query['offset'] = $this->check( $in , 'skip' , 0 );
		/**********************************************
		** Return Results **
		**********************************************/
		if( $this->check( $in , 'order' ) ) $query['order'] = $in['order']; // SEND IN THE MICRO MANAGER
		if( $this->check( $in , 'order_by' ) ) $query['orderby'] = $in['order_by'];
		
		return $query; 
	}
	
	public function get_select( $in ){
		$query = array();
		if( $this->check( $in , 'selected_item') ){
			$query['post_type'] = 'any'; // ASSIGN TO QUERY ARG
			$in['selected_item'] = explode(',' , $in['selected_item'] ); // SPLIT BY ,
			$query['post__in'] = $in['selected_item']; // ASSIGN TO QUERY
			$query['orderby'] = $this->check( $in , 'order_by', 'post__in' );
			if( $this->check( $in , 'order' ) ) $query['order'] = $in['order']; // SEND IN THE MICRO MANAGER
		}
		return $query;
	}
	
	public function get_meta_query( $in ){
		$query = array();
		/**********************************************
		** Set Post Type  **
		**********************************************/
		if( isset( $in['post_type_meta'] ) ) $query['post_type'] = $in['post_type_meta'];
		/**********************************************
		** Set Count **
		**********************************************/
		if( $this->check( $in , 'count' ) && 'all' == $in['count'] ) $in['count'] = -1 ; 
		$query['posts_per_page'] = $this->check( $in , 'count' , 10 );
		/**********************************************
		** Set Skip **
		**********************************************/
		$query['offset'] = $this->check( $in , 'skip' , 0 );
		/**********************************************
		** Set-Up Meta Query **
		**********************************************/
		$meta_query = array(); // Populate this later
		/**********************************************
		** Set Key **
		**********************************************/
		$meta_query['key'] = $in['meta_key'];
		/**********************************************
		** Check Current Time **
		**********************************************/
		if( isset( $in['meta_value_time'] ) && 1 == $in['meta_value_time'] ){ // Override with time
			$meta_query['value'] = time(); // Set to current time
		} else { // Do not override
			$meta_query['value'] = ( isset( $in['meta_value'] ) ) ? $in['meta_value'] : ''; // set value to value
		}
		/**********************************************
		** Set Compare **
		**********************************************/
		if( isset( $in['compare'] ) ) $meta_query['compare'] = $in['compare'];
		/**********************************************
		** Return Query **
		**********************************************/
		$query['meta_query'] = array( $meta_query );
		
		return $query;
	}
	
	public function get_query_obj( $query , $in = array() ){
		$image_size = 'thumbnail';
		if( isset( $query['image-size'] ) ){
				$image_size = $query['image-size'];
				unset( $query['image-size'] );
			};
		if(!$query) return array();
		$query_obj = array();
		global $wp_query; // GET GLOBAL QUERY
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		$the_query = new \WP_Query( $query ); // DO YOU HAVE A QUERY?????
		if ( $the_query->have_posts() ) {
			$i = 0;
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$the_query->post->post_link = \get_permalink( $the_query->post->ID );
				$the_query->post->i = $i;
				$the_query->post->meta = \get_post_meta( $the_query->post->ID );
				// Handle images 
				$image = get_the_post_thumbnail( $the_query->post->ID , $image_size );
				if( 'attachment' == $the_query->post->post_type ){
				}
				else if( 'video' == $the_query->post->post_type ){
					$vid = '';
					$has_link = ( isset( $in['remove_link'] ) && $in['remove_link'] )? false : true;
					$has_lightbox = ( isset( $in['display_lightbox'] ) && $in['display_lightbox'] )? true : false;
					$vid_wp_cls = ( !$has_link && !$has_lightbox )? ' has_video': '';
					$data = 'data-vid="'.implode( $the_query->post->meta['_video_id'] ).'"';
					$image = '<div class="video-image-wrapper '.$vid_wp_cls.'" '.$data.'style="position: relative">'
						.$image.'<span class="video-play"></span></div>';
				}
				$the_query->post->img = $image;
				$query_obj[] = $the_query->post;
				$i++;
			} // END WHILE
		} // END IF
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE
		\wp_reset_postdata();
		return $query_obj;
	}
	
	public function convert_query( $in , $query ){
		$site = $this->check( $in , 'current_site' , '' );
		$query = $site.'?service=query&'.http_build_query( $query );
		return $query;
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
			$query['post_status'] = 'any'; 
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