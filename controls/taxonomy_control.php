<?php namespace cahnrswp\cahnrs\core;

class taxonomy_control {


	public function __construct() {

		add_action( 'init', array( $this, 'add_tags_to_attachments' ) );

	}


	public function add_tags_to_attachments() {

		register_taxonomy_for_object_type( 'post_tag', 'attachment' );

	}


}

?>