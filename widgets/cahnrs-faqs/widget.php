<?php 
/**
 * Action item widget.
 */

class cahnrs_faqs extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_faqs', // Base ID
			'Add FAQs', // Name
			array( 'description' => 'Add FAQ items by category/tag or individaul item', ) // Args
		);

	}

	public function widget( $args, $instance ) {
	}


	public function get_defaults(){
		return array(
			'feed_type' => 'select',
			'image_size' => 0,
			'post_type' => 'faq',
			'taxonomy' => 'all',
			'terms' => '',
			'display' => 'faq',
			'count' => 6,
			'skip' => 0,
			'display_title' => 1,
			'display_excerpt' => 0,
			'display_content' => 1,
			'display_image' => 0,
			'display_link' => 0,
		);
	}
	
	public function set_defaults( $instance ){
		$defaults = $this->get_defaults(); // GET THE DEFAULTS - DB
		foreach( $defaults as $d_k => $d_v ){ // FOR EACH DEFAULT SETTING - DB
			if( !isset($instance[ $d_k ] ) ){ // IF IS NOT SET - DB
				$instance[ $d_k ] = $d_v; // ADD DEFAULT VALUE - DB
			} // END IF - DB
		} // END FOREACH - DB
		return $instance;
	}
	
	public function form( $in ) {
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		include cahnrswp\cahnrs\core\DIR.'forms/feed-w-static.phtml';
		//include cahnrswp\cahnrs\core\DIR.'forms/slideshow_display.phtml';
	}


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {


	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){
	register_widget( 'cahnrs_faqs' );
});

?>