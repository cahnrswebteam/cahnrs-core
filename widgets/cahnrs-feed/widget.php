<?php 

class CAHNRS_feed_widget extends \WP_Widget {
	public $content_feed_control;
	public $view;
	public $is_content = true;
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();
		$this->query = new cahnrswp\cahnrs\core\query_control();
		//$this->post_content_view = new cahnrswp\cahnrs\core\post_content_view(); 
		parent::__construct(
			'cahnrs_feed', // Base ID
			'Content Feed', // Name
			array( 'description' => 'Creates a dynamic feed of Pages, Posts & other content items', ) // Args
		);
	}

	public function widget( $args, $in = array() ) {
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		//$q_args = $this->content_feed_control->get_query_args( $in ); // BUILD THE QUERY ARGS
		//$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		//\query_posts($q_args); // DO YOU HAVE A QUERY?????
		/**********************************************************
		** LET'S GET READY TO RENDER **
		***********************************************************/
		//$this->view->get_content_view( $args, $in , $query ); // RENDER THE VIEW
		//$this->widget_basic_gallery_view( $args, $in , $wp_query ); // SWAP PHIL'S VIEW
		$query_obj = $this->query->get_query( $in );
		$this->view->get_updated_content_view( $args, $in , $query_obj );
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		//global $wp_query; // GET GLOBAL QUERY
		//echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		//$q_args = $this->content_feed_control->get_query_args( $instance ); // BUILD THE QUERY ARGS
		//$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		//$instance['is_legacy'] = true;
		//\query_posts($q_args); // DO YOU HAVE A QUERY?????
		
		//$this->view->get_content_view( $args, $instance , $query );
		
		//echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		//$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
		
		//\wp_reset_postdata();
	}
	
	public function widget_list_view( $args, $vals, $query ){
		$fields = array('title','link','excerpt','content');
		if ( $query->have_posts() ) {
			echo '<ul>';
			while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );
				$this->post_content_view->get_view( $display_obj , 'list' );
			}
			echo '</ul>';
		} else {
			// no posts found
		}
	}
	
	public function widget_promo_view( $args, $vals, $query ){
		$fields = array('title','link','excerpt','content','image');
		$vals['display_image'] = true;
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );
				$this->post_content_view->get_view( $display_obj , 'promo' );
			}
		} else {
			// no posts found
		}
	}
	
	public function widget_column_promo_view( $args, $vals, $query ){
		$fields = array('title','link','excerpt','content','image');
		$vals['display_image'] = true;
		if ( $query->have_posts() ) {
			echo '<ul>';
			while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );
				$this->post_content_view->get_view( $display_obj , 'column_promo' );
			}
			echo '</ul>';
		} else {
			// no posts found
		}
	}
	
	public function get_defaults(){
		return array(
			'feed_type' => 'basic',
			'post_type' => 'post',
			'taxonomy' => 'all',
			'terms' => '',
			'display' => 'list',
			'count' => 5,
			'skip' => 0,
			'display_title' => 1,
			'display_excerpt' => 1,
			'display_content' => 0,
			'display_image' => 1,
			'display_link' => 1,
			'display_meta' => 0,
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
		
		include cahnrswp\cahnrs\core\DIR.'inc/item_form_legacy_handler.php';
		
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		$caps = array(
			'show_feed' => true,
			'show_adv_feed' => true,
			'show_display' => array( 'title', 'style','imagesize', 'override' ),
			);
		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this );
		
		/*$val = array(
			'post_type' => 'post',
			'taxonomy' => 'all',
			'terms' => '',
			'display' => 'list',
			'count' => 5,
			'skip' => 0,
			'display_title' => 1,
			'display_excerpt' => 1,
			'display_content' => 0,
			'display_image' => 1,
			'display_link' => 1,
			'display_meta' => 0,
		);
		foreach( $val as $v_k => $v_d ){
			$in[ $v_k ] = ( isset( $in[ $v_k ] ) )? $in[ $v_k ] : $val[ $v_k ];
		}
		include cahnrswp\cahnrs\core\DIR.'forms/feed.phtml';
		include cahnrswp\cahnrs\core\DIR.'forms/feed_display.phtml';*/
		/*$this->content_feed_control->get_form( 'basic_feed', $this , $val );
        $this->content_feed_control->get_form( 'cahnrs_api_feed', $this , $val );
        $this->content_feed_control->get_form( 'feed_display', $this , $val );
		$this->content_feed_control->get_form( 'display', $this , $val );*/
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		return $new_instance;
	}
};


add_action('widgets_init', create_function('', 'return register_widget("CAHNRS_feed_widget");'));
?>