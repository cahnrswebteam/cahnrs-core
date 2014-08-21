<?php namespace cahnrswp\cahnrs\core;

class content_feed_control {

	public function __construct() {
	}
	
	public function get_query_args( $in ){ // Legacy function but could be user to route url and api requests
		return $this->get_basic_query_args( $in );
	}
		
	
	public function get_basic_query_args( $in ){
		$query = array();
		
		$in['feed_type'] = ( isset( $in['feed_type'] ) )? $in['feed_type'] : 'basic'; // SET DEFAULT FEED TYPE IF EMPTY
		/************************************
		** HANDLE POST TYPE ARG **
		*************************************/
		if( isset( $in['post_type'] ) || isset( $in['post_type_meta'] ) ){ // IS TYPE SET??
			if( 'meta' == $in['feed_type'] && isset( $in['post_type_meta'] ) ) $in['post_type'] = $in['post_type_meta'];
			if( isset( $in['feed_type'] ) && 'select' == $in['feed_type'] ){ // If is select feed type
				$query['post_type'] = 'any'; // ASSIGN TO QUERY ARG
			} else {
				if (strpos($in['post_type'],'attachment') !== false) {
					$p_t = explode('_', $in['post_type'] ); // Split mime type attachment_[mime type]
					if( isset( $p_t[1] )){ // Has mime type
						switch( $p_t[1] ){ // Switch based on mime type indicator
							case 'image': // Is image
								$query['post_mime_type'] = 'image'; // Use image mime type
								break;
							case 'file':
								$query['post_mime_type'] = array( 'text','application' ); // Is document 
								break;
						}// End switch
					}; // end if
					$query['post_type'] = $p_t[0]; // ASSIGN TO QUERY ARG
				} else { // else: not an attachment
					$query['post_type'] = $in['post_type']; // ASSIGN TO QUERY ARG
				} // end if
			} // end if
			/** Attachments aren't published so set "post_status" to any **/
			if( strpos( $in['post_type']  ,'attachment' ) !== false ) $query['post_status'] = 'any'; 
		} else { // IF NOT SET: SET TO ANY
			$query['post_type'] = 'any'; // SET POST TYPE TO ANY IF NOT SET
		} // END IF
		
		/************************************
		** Handle meta feed type **
		*************************************/
		if( 'meta' == $in['feed_type'] && isset( $in['meta_key'] ) ) {
			$meta_query = array(); // Populate this later
			$meta_query['key'] = $in['meta_key'];
			if( isset( $in['meta_value_time'] ) && 1 == $in['meta_value_time'] ){ // Override with time
				$meta_query['value'] = time(); // Set to current time
			} else { // Do not override
				$meta_query['value'] = ( isset( $in['meta_value'] ) ) ? $in['meta_value'] : ''; // set value to value
			}
			if( isset( $in['compare'] ) ) $meta_query['compare'] = $in['compare'];
			$query['meta_query'] = array( $meta_query );
		}
		
		/************************************
		** HANDLE ORDER BY ARG **
		*************************************/
		if( isset( $in['order_by'] ) ) { // CAN WE HAVE ORDER PLEASE????
			if( 'post__in' == $in['order_by'] ){ // IF SET TO POST__IN AKA QUERY ORDER
				/** CHECK IF THE FEED TYPE IS SELECT. IF NOT SELECT, SWAP POST__IN FOR DATE **/ 
				$query['orderby'] = ( isset( $in['feed_type'] ) && 'select' == $in['feed_type'] )?  $in['order_by'] : 'date';
			} else { // NOT POST__IN ORDER
				$query['orderby'] = $in['order_by']; // ASSIGN TO QUERY
			} // END IF
			if( 'meta_value' == $query['orderby'] && isset( $in['meta_key'] ) ) $query['meta_key'] = $in['meta_key'];
			/*if( 'meta_value' == $in['order_by'] && isset( $in['meta_value'] ) ){ // Order by meta value
				$meta_query = array();
				if( isset( $in['meta_value_time'] ) && 1 == $in['meta_value_time'] ){
					$meta_query['value'] = time();
				} else {
					$meta_query['value'] = $in['meta_value'];
				}
				$meta_query['key'] = $in['meta_value'];
				if( isset( $in['compare'] ) ) $meta_query['compare'] = $in['compare'];
				$query['meta_query'] = array( $meta_query );*/
		} // END IF
		/************************************
		** HANDLE ORDER ARG **
		*************************************/
		if( isset( $in['order'] ) ) $query['order'] = $in['order']; // SEND IN THE MICRO MANAGER
		/************************************
		** HANDLE SELECTED ITEM ARG **
		*************************************/
		if( isset( $in['selected_item'] ) && 0 != $in['selected_item'] && 'select' == $in['feed_type'] ){ // POST I CHOOSE YOU
			$in['selected_item'] = explode(',' , $in['selected_item'] ); // SPLIT BY ,
			$query['post__in'] = $in['selected_item']; // ASSIGN TO QUERY
		}// END IF
		/************************************
		** HANDLE TAXONOMY ARG **
		*************************************/
		if( isset( $in['taxonomy'] ) && 'all' != $in['taxonomy'] && 'select' != $in['feed_type'] ){
			if( isset( $in['terms'] ) ){
				$terms = array();
				$term_names = explode(',', $in['terms'] );
				foreach( $term_names as $term ){
					$term_obj = \get_term_by( 'name', $term ,$in['taxonomy'] );
					$terms[] = $term_obj->slug;
				}
				$query['tax_query'][] = array(
					'taxonomy' => $in['taxonomy'],
					'field' => 'slug',
					'terms' => $terms,
				);	
			}
		}
		/************************************
		** HANDLE COUNT ARG **
		*************************************/
		if( isset( $in['count'] ) ) { // CAN WE COUNT
			if( 'select' != $in['feed_type'] ){ // CHECK IF ITEMS ARE SELECTED
				$in['count'] = ( 'all' == $in['count'] )? -1 : $in['count']; // HOW MANY POSTS MUST THE LOOP GO THOUGH, BEFORE YOU CAN CALL IT A LOOP?
				$query['posts_per_page'] = $in['count'];
			} else { // HAS SELECTED ITEMS
				$query['posts_per_page'] = -1; // SET TO ALL POSTS SO WE DON'T LIMIT OUR QUERY
			}// END IF
		}// END IF
		/************************************
		** HANDLE SKIP ARG **
		*************************************/
		if( isset( $in['skip'] ) && 'select' != $in['feed_type'] ) $query['offset'] = $in['skip']; // NOTHING TO SEE HERE, MOVE ALONG
		/************************************
		** DONE, RETURN QUERY ARGS **
		*************************************/
		return $query;
	}
	
	/*public function get_display_obj( $args, $in, $post, $fields ){
		$display_obj = new \stdClass();
		$display_obj->title = ( in_array( 'title' , $fields ) && $in['display_title'] )? get_the_title() : false;
		$display_obj->excerpt = ( in_array( 'excerpt' , $fields ) && 'excerpt' == $in['display_content'] )? get_the_excerpt() : false;
		$display_obj->content = ( in_array( 'content' , $fields ) && 'full' == $in['display_content'] )? get_the_content() : false;
		$display_obj->link = ( in_array( 'link' , $fields ) && $in['display_link'] )? get_permalink( $post->ID ) : false;
		if( in_array( 'image' , $fields ) && $in['display_image'] ){
			$post_type = ( $post->post_type )? $post->post_type : get_post_type( $post->ID );
			$size = ( $in['image_size'] )? $in['image_size'] : 'thumbnail';
			if( 'attachment' == $post_type ){
			}
			else if( 'video' == $post_type ){
				$size = ( $in['image_size'] )? $in['image_size'] : 'medium';
				$image = get_the_post_thumbnail( $post->ID, $size, array( 'style' => 'max-width: 100%' ));
				$display_obj->image = '<div class="video-image-wrapper" style="position: relative">'.$image.'<span class="video-play"></span></div>';
			}
			else if( has_post_thumbnail( $post->ID ) ){
				$display_obj->image = get_the_post_thumbnail( $post->ID, $size, array( 'style' => 'max-width: 100%' ));
			} else {
				$display_obj->image = false;
			}
		} else {
			$display_obj->image = false;
		}
		$display_obj->link_start = ( $display_obj->link )? '<a href="'.$display_obj->link.'">' : '';
		$display_obj->link_end = ( $display_obj->link )? '</a>' : '';
		return $display_obj;
	}*/
	
	//public function feed_template( $template ){
		//$new_template = DIR.'/views/template_feed_json.php';
		//return $new_template;
	//}
}
?>