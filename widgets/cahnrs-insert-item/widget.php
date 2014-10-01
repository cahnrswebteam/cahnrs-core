<?php 
/**
 * Action item widget.
 */

class cahnrs_insert_item extends \WP_Widget {
	
	public $is_content = true;

	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();

		parent::__construct(
			'cahnrs_insert_item', // Base ID
			'Insert Item', // Name
			array( 'description' => 'Insert an existing Post, Page, or other Content.', ) // Args
		);

	}

	public function get_defaults(){
		return array(
			'feed_type' => 'select',
			'selected_item' => 0,
			'image_size' => 'large',
			'display' => 'full',
			'columns' => 1,
			'display_title' => 1,
			'display_excerpt' => 1,
			'display_content' => 0,
			'display_link' => 1,
			'display_image' => 1,
			'display_meta' => 0
		);
	}
	
	public function set_defaults( $in ){
		$defaults = $this->get_defaults(); // GET THE DEFAULTS - DB
		foreach( $defaults as $d_k => $d_v ){ // FOR EACH DEFAULT SETTING - DB
			if( !isset($in[ $d_k ] ) ){ // IF IS NOT SET - DB
				$in[ $d_k ] = $d_v; // ADD DEFAULT VALUE - DB
			} // END IF - DB
		} // END FOREACH - DB
		return $in;
	}
	
	public function widget( $args, $in ) {
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		$in['is_legacy'] = true;
		
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		$q_args = $this->content_feed_control->get_query_args( $in ); // BUILD THE QUERY ARGS
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		\query_posts($q_args); // DO YOU HAVE A QUERY?????
		/**********************************************************
		** LET'S GET READY TO RENDER **
		***********************************************************/
		$this->view->get_content_view( $args, $in , $query ); // RENDER THE VIEW
		//$this->widget_basic_gallery_view( $args, $in , $wp_query ); // SWAP PHIL'S VIEW
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
		
		\wp_reset_postdata();
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $in Previously saved values from database.
	 */
	public function form( $in ) {
		
		include cahnrswp\cahnrs\core\DIR.'inc/item_form_legacy_handler.php';
		
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		$caps = array(
			'show_feed' => array('select'),
			'show_adv_feed' => true,
			'show_display' => array( 'title', 'style', 'imagesize', 'details' ),
			);
		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this );
		
		/** DEFAULT HANDLER ****************/
		//$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		
		//include cahnrswp\cahnrs\core\DIR.'forms/select_post.phtml';
		//include cahnrswp\cahnrs\core\DIR.'forms/insert_item_display.phtml';
		//$this->content_feed_control->get_form( 'select_item', $this , $val );
		//$this->content_feed_control->get_form( 'display_view_all', $this , $val );
		//$this->content_feed_control->get_form( 'content_display', $this , $val );

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

		return $new_instance;

	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){
	register_widget( 'cahnrs_insert_item' );
});

?>