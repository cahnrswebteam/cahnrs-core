<?php namespace cahnrswp\cahnrs\core;

class post_type_control {
	
	public function register_post_types(){
		/************************************************
		** ADD FAQ POST TYPE **
		************************************************/
		$faq_args = array(
			'public' => true,
			'label'  => 'FAQs',
			'taxonomies' => array('category','post_tag'), 
			);
		\register_post_type( 'faq', $faq_args );
	}

}

?>