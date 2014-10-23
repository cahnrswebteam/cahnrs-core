<?php namespace cahnrswp\cahnrs\core;

class items_model {
	private $query_model;
	public $in;
	public $items = array();
	
	public function __construct( $in , $query_model ){
		$this->in = $in;
		$this->query_model = $query_model;
		if( is_array( $this->query_model ) ){
			foreach ( $this->query_model as $post ){
				$item = new \stdClass;
				$this->set_link( $item , $post );
				$this->set_image( $item , $post );
				$this->set_title( $item , $post );
				$this->set_excerpt( $item , $post );
				$this->set_content( $item , $post );
				$this->set_text( $item , $post );
				$this->items[] = $item;
			}
		}
	}
	
	public function set_link( &$item , $post ){
		if( isset( $post->post_link ) ) {
			$item->link = $post->post_link;
			$item->link_start = '<a href="'.$item->link.'">';
			$item->link_end = '</a>';
		}
	}
	
	public function set_image( &$item , $post ){
		if( isset( $post->images ) ) {
			$size = ( isset( $this->in['image_size'] ) )? $this->in['image_size'] : 'thumbnail';
			$item->image = $post->images->$size;
		} else {
			$item->image = $post->img;
		}
		
	}
	
	public function set_title( &$item , $post ){
		$item->title = \apply_filters( 'the_title', $post->post_title );
	}
	
	public function set_excerpt( &$item , $post ){
		if( $post->post_excerpt ){
			$item->excerpt = $post->post_excerpt;
		} else {
			$excerpt = strip_shortcodes( $post->post_content );
			$excerpt = strip_tags( $excerpt );
			$excerpt = wp_trim_words( $excerpt, 55, ' ...' );
			$item->excerpt = $excerpt;
		}
	}
	
	public function set_text( &$item , $post ){
		$item->text = $item->excerpt;
	}
	
	public function set_content( &$item , $post ){
		$item->content = \apply_filters( 'the_content', $post->post_content );
	}
}
?>