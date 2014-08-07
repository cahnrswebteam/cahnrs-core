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


	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		$q_args = $this->content_feed_control->get_query_args( $instance ); // BUILD THE QUERY ARGS
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		\query_posts($q_args); // DO YOU HAVE A QUERY?????
		/**********************************************************
		** LET'S GET READY TO RENDER **
		***********************************************************/
		$this->view->get_content_view( $args, $instance , $query ); // RENDER THE VIEW
		//$this->widget_basic_gallery_view( $args, $instance , $wp_query ); // SWAP PHIL'S VIEW
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
		/*echo $args['before_widget'];
		// The Loop
		//$args = $this->content_feed_control->get_query_args( $instance );
		$the_query = new WP_Query( array( 'p' => $instance['selected_item'], 'post_type' => 'any' ) );
		switch ( $instance['display'] ){
			default:
				$this->post_content_view->render_list_view( $args, $instance, $the_query );
				//$this->widget_list_view( $args, $instance , $the_query );
				break;
		};
		echo $args['after_widget'];
		/* Restore original Post Data */
		//wp_reset_postdata();*/
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $in ) {
		
		include cahnrswp\cahnrs\core\DIR.'inc/item_form_legacy_handler.php';
		
		$val = array(
			'selected_item' => 0,
			'display' => 'basic_content',
			'image_size' => 'large',
			'display' => 'basic_gallery',
			'columns' => 1,
			'display_title' => 1,
			'display_excerpt' => 1,
			'display_content' => 0,
			'display_link' => 1,
			'display_image' => 1,
			'display_meta' => 0
		);
		foreach( $val as $v_k => $v_d ){
			$in[ $v_k ] = ( isset( $in[ $v_k ] ) )? $in[ $v_k ] : $val[ $v_k ];
		}
		include cahnrswp\cahnrs\core\DIR.'forms/select_post.phtml';
		include cahnrswp\cahnrs\core\DIR.'forms/insert_item_display.phtml';
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