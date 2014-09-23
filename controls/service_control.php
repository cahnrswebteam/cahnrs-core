<?php namespace cahnrswp\cahnrs\core;

class service_control {

	public function init(){
		if( isset( $_GET['service'] ) || isset( $_GET['cahnrs-feed'] ) ){ 
			if( isset( $_GET['cahnrs-feed'] ) ) {
				add_filter( 'template_include', array( $this , 'render_as_feed' ), 99 );
			}
			else if( isset( $_GET['service'] ) ) {
				add_filter( 'template_include', array( $this , 'get_service' ), 99 );
			}
		}
	}
	
	public function render_as_feed( $template ){
		$feed = $_GET['cahnrs-feed'];
		switch ( $feed ){
			case 'content-html':
				$feed_path = DIR.'templates/content-html-feed.php';
				break;
			default:
				$feed_path = DIR.'templates/json-feed.php';
				break;
		}
		return $feed_path;
	}
	
	public function get_service( $template ){
		$service = $_GET['service'];
		switch( $service ){
			case 'select-list':
				$template = DIR.'templates/select-list.php';
				break;
			case 'query':
				$template = DIR.'/templates/query.php';
				break;
			case 'iframe':
				$template = DIR.'/templates/html-embed.php';
				break;
		}
		return $template;
	}
}
?>