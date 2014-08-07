<?php namespace cahnrswp\cahnrs\core;

class feed_control {

	public function init_feed_control(){
		if( isset( $_GET['cahnrs-feed'] ) ){ 
			add_filter( 'template_include', array( $this , 'render_as_feed' ), 99 );
		}
	}
	
	public function render_as_feed( $template ){
		$feed = $_GET['cahnrs-feed'];
		switch ( $feed ){
			case 'select-list':
				$feed_path = DIR.'templates/select-list-options.php';
			case 'content-html':
				$feed_path = DIR.'templates/content-html-feed.php';
				break;
			default:
				$feed_path = DIR.'templates/json-feed.php';
				break;
		}
		return $feed_path;
	}
}
?>