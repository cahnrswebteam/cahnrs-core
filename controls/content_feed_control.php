<?php namespace cahnrswp\cahnrs\core;

class content_feed_control {

	public function __construct() {
		//if( isset( $_GET['cahnrs-feed'] ) ){
			//\add_action( 'template_include', array( $this , 'feed_template' ) );
		//}
	}
	
	/*public function get_form( $form = '', $widget , $val ){
		switch( $form ){
			case 'form_part_count':
				include DIR.'inc/form_part_feed_count_inc.phtml';
				break;
			case 'slideshow_display':
				include DIR.'inc/form_part_slideshow_display.phtml';
				break;
			case 'display_view_all':
				include DIR.'inc/form_part_display_views_all.phtml';
				break;
			case 'content_display':
				include DIR.'inc/form_part_content_display.phtml';
				break;
			case 'select_item':
				include DIR.'inc/select_post_type_item_form_inc.phtml';
				break;
			case 'cahnrs_api_feed' :
				include DIR.'inc/api_feed_form_inc.phtml';
				break;
			case 'feed_display':
				include DIR.'inc/feed_display_form_inc.phtml';
				break;
			case 'display':
				include DIR.'inc/display_form_inc.phtml';
				break;
			case 'gallery_display':
				include DIR.'inc/gallery_display_form_inc.phtml';
				break;
			default:
				include DIR.'inc/basic_feed_form_inc.phtml';
				break;
		}
	}*/
	
	public function get_query_args( $in ){
		return $this->get_basic_query_args( $in );
	}
		
	
	public function get_basic_query_args( $vals ){
		$query = array();
		
		$query['post_type'] = ( isset( $vals['post_type'] ) )? $vals['post_type'] : 'any';
		
		if( $vals['post_type'] == 'attachment' ){
			$query['post_status'] = 'any';
		}
		
		if( isset( $vals['order_by'] ) ) $query['orderby'] = $vals['order_by'];
		
		
		if( isset( $vals['order'] ) ) $query['order'] = $vals['order'];
		
		if( isset( $vals['selected_item'] ) && 0 != $vals['selected_item'] ) $query['p'] = $vals['selected_item'];
		
		if( isset( $vals['taxonomy'] ) && 'all' != $vals['taxonomy'] ){
			if( isset( $vals['terms'] ) ){
				$terms = array();
				$term_names = explode(',', $vals['terms'] );
				foreach( $term_names as $term ){
					$term_obj = \get_term_by( 'name', $term ,$vals['taxonomy'] );
					$terms[] = $term_obj->slug;
				}
				$query['tax_query'][] = array(
					'taxonomy' => $vals['taxonomy'],
					'field' => 'slug',
					'terms' => $terms,
				);	
			}
		}
		
		if( isset( $vals['count'] ) ) {
			$vals['count'] = ( 'all' == $vals['count'] )? -1 : $vals['count'];
			$query['posts_per_page'] = $vals['count'];
		}
		
		if( isset( $vals['skip'] ) ) $query['offset'] = $vals['skip'];
		return $query;
	}
	
	public function get_display_obj( $args, $vals, $post, $fields ){
		$display_obj = new \stdClass();
		$display_obj->title = ( in_array( 'title' , $fields ) && $vals['display_title'] )? get_the_title() : false;
		$display_obj->excerpt = ( in_array( 'excerpt' , $fields ) && 'excerpt' == $vals['display_content'] )? get_the_excerpt() : false;
		$display_obj->content = ( in_array( 'content' , $fields ) && 'full' == $vals['display_content'] )? get_the_content() : false;
		$display_obj->link = ( in_array( 'link' , $fields ) && $vals['display_link'] )? get_permalink( $post->ID ) : false;
		if( in_array( 'image' , $fields ) && $vals['display_image'] ){
			$post_type = ( $post->post_type )? $post->post_type : get_post_type( $post->ID );
			$size = ( $vals['image_size'] )? $vals['image_size'] : 'thumbnail';
			if( 'attachment' == $post_type ){
			}
			else if( 'video' == $post_type ){
				$size = ( $vals['image_size'] )? $vals['image_size'] : 'medium';
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
	}
	
	//public function feed_template( $template ){
		//$new_template = DIR.'/views/template_feed_json.php';
		//return $new_template;
	//}
}
?>