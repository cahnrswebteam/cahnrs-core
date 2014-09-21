<?php namespace cahnrswp\cahnrs\core;

class post_type_control {
	public $post_types = array(
		'faq' => array(
			  'public' => true,
			  'label'  => 'FAQs',
			  'taxonomies' => array('category','post_tag'), 
			  ),
		'video' => array(
				'public' => true,
				'label'  => 'Videos',
				'taxonomies' => array('category','post_tag'),
				'supports' => array('title','excerpt','thumbnail'), 
				),
	);
	public $metaboxes = array(
		'video' => array(
			'title' => 'Video Settings',
			'callback' =>'get_video',
			'context' => 'normal',
			'post_types' => array('video'),
			'priority' => 'high',
			'fields' => array(
				'_video_id' => 'text',
				)
			),
		);
	
	public function init(){
		\add_action( 'init', array( $this , 'create_post_types' ) );
		\add_action( 'add_meta_boxes', array( $this , 'add_metabox' ) );
		\add_action( 'save_post', array( $this , 'save') );
		\add_filter( 'the_content', array( $this , 'content_filter' ) );
	}
	
	public function create_post_types(){
		foreach( $this->post_types as $type_id => $type_args ){
			\register_post_type( $type_id, $type_args );
		}
	}
	
	public function add_metabox(){
		foreach ( $this->metaboxes as $m_id => $m_args ){
			foreach( $m_args['post_types'] as $screen ){
				add_meta_box(
					$m_id,
					$m_args['title'],
					array( $this , $m_args['callback'] ),
					$screen,
					$m_args['context'],
					$m_args['priority']
				);
			}
		}
		
	}
	
	public function get_video( $post ){
		$id_meta = get_post_meta( $post->ID , '_video_id', true );
		 echo '<label>Youtube Video ID</label><br />';
         echo '<input type="text" name="_video_id" value="'.$id_meta.'"/>';
		 echo '<div class="helper-text">Example: http://www.youtube.com/watch?v=<span style="color: red">I1qHVVbYG8Y</span></div>';
		 echo '<hr />';
		 wp_editor( $post->post_content, 'content' );
	}
	
	public function content_filter( $content ){
		global $post;
		if( 'video' == $post->post_type ){
			$video = get_post_meta( $post->ID , '_video_id', true );
			$video = '<iframe width="100%" height="400" src="//www.youtube.com/embed/'.$video.'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
			return $video.$content;
		}
		else return $content;
	}
	
	public function save( $post_id ){
		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */
	
		// Check if our nonce is set.
		/*if ( ! isset( $_POST['myplugin_meta_box_nonce'] ) ) {
			return;
		}
	
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['myplugin_meta_box_nonce'], 'myplugin_meta_box' ) ) {
			return;
		}*/
	
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		/*if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}*/
	
		// Check the user's permissions.
		/*if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
	
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return;
			}
	
		} else {
	
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}*/
	
		/* OK, it's safe for us to save the data now. */
		foreach( $this->metaboxes as $args ){
			foreach( $args['fields'] as $f_key => $f_type ){
				if( isset( $_POST[$f_key] ) ){
					if( 'text' == $f_type ) $data = sanitize_text_field( $_POST[$f_key] );
					update_post_meta( $post_id , $f_key , $data );
				}
			}
		}
	}
	
}

?>