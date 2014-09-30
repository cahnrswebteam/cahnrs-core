<?php 
/**
 * Action item widget.
 */

class cahnrs_faqs extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();

		parent::__construct(
			'cahnrs_faqs', // Base ID
			'Add FAQs', // Name
			array( 'description' => 'Add FAQ items by category/tag or individual item', ) // Args
		);

	}

	public function widget( $args, $in ) {
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		$q_args = $this->content_feed_control->get_basic_query_args( $in ); // BUILD THE QUERY ARGS
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		$in['is_legacy'] = true;
		
		\query_posts($q_args); // DO YOU HAVE A QUERY?????
		
		$this->view->get_content_view( $args, $in );
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
		
		\wp_reset_postdata();
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
			'display_link' => 1,
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
		$caps = array(
			'show_feed' => true,
			'show_adv_feed' => true,
			'show_display' => array( 'title' ),
		);

		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this ); 
		/** DEFAULT HANDLER ****************/
		//$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		//include cahnrswp\cahnrs\core\DIR.'forms/feed-w-static.phtml';
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