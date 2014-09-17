<?php 

class CAHNRS_Slideshow_widget extends \WP_Widget {
	public $content_feed_control;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();
		$this->query = new cahnrswp\cahnrs\core\query_control();
		
		parent::__construct(
			'cahnrs_slideshow', // Base ID
			'Slideshow', // Name
			array( 'description' => 'Customizable Slideshow', ) // Args
		);
	}
	
	public function get_defaults(){
		return array(
			'feed_type' => 'basic',
			'image_size' => '16x9-medium',
			'post_type' => 'post',
			'taxonomy' => 'all',
			'terms' => '',
			'display' => 'slideshow-basic',
			'count' => 3,
			'skip' => 0,
			'display_title' => 1,
			'display_excerpt' => 1,
			//'display_content' => 0,
			'display_image' => 1,
			'display_link' => 1,
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

	public function widget( $args, $in = array() ) {
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		$in['is_legacy'] = true;
		/** QUERY FEED *************************/
		//global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		//$q_args = $this->content_feed_control->get_query_args( $in ); // BUILD THE QUERY ARGS
		//$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		//\query_posts($q_args); // DO WE HAVE A QUERY?????
		
		//$temp_query_2 = clone $wp_query; // JUST IN CASE LETS WRITE A SEDON QUERY
		 
		$query_obj = $this->query->get_query( $in );
		
		$this->view->get_slideshow_view( $args, $in , $query_obj );
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		//$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE
		
		//\wp_reset_postdata();
		
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $in The widget options
	 */
	public function form( $in) {
		
		include cahnrswp\cahnrs\core\DIR.'inc/item_form_legacy_handler.php';
		
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		$caps = array(
			'show_feed' => array('select','basic'),
			'show_adv_feed' => true,
			'show_display' => array( 'title','slideshowstyle', 'imagesize', 'override' ),
			);
		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this );
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}
	

};


add_action('widgets_init', create_function('', 'return register_widget("CAHNRS_Slideshow_widget");'));
?>