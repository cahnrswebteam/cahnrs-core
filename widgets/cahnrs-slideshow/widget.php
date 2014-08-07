<?php 

class CAHNRS_Slideshow_widget extends \WP_Widget {
	public $content_feed_control;

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();
		
		parent::__construct(
			'cahnrs_slideshow', // Base ID
			'Slideshow', // Name
			array( 'description' => 'Customizable Slideshow', ) // Args
		);
	}
	
	public function get_defaults(){
		return array(
			'image_size' => '16x9-medium',
			'post_type' => 'post',
			'taxonomy' => 'all',
			'terms' => '',
			'display' => 'slideshow-basic',
			'count' => 3,
			'skip' => 0,
			'display_title' => 1,
			'display_excerpt' => 1,
			'display_content' => 0,
			'display_image' => 1,
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

	public function widget( $args, $instance = array() ) {
		/** DEFAULT HANDLER ****************/
		$instance = $this->set_defaults( $instance );
		/** END DEFAULT HANDLER ****************/
		/** QUERY FEED *************************/
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		$q_args = $this->content_feed_control->get_query_args( $instance ); // BUILD THE QUERY ARGS
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		
		\query_posts($q_args); // DO WE HAVE A QUERY?????
		
		$temp_query_2 = clone $wp_query; // JUST IN CASE LETS WRITE A SEDON QUERY
		
		$this->render_slideshow_wrapper_start( $instance );
		
		$this->view->get_content_view( $args, $instance , $query );
		
		$this->render_slideshow_wrapper_end( $instance , $temp_query_2 );
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE
		/** END QUERY FEED *************************/
		
		/*$feed_control = $this->content_feed_control;
		$q_args = $feed_control->get_query_args( $instance );
		$query = new WP_Query( $q_args );
		echo $args['before_widget'];
		//$show_json = file_get_contents( 'http://api.wpdev.cahnrs.wsu.edu/?service=standard-feed&id=featured&encode=json' );
		//$show_obj = json_decode( $show_json ,true);
		switch ( $instance['display'] ){
			case '3-up':
				include 'slideshows/cahnrs-3-up.phtml';
				break;
			default:
				include 'slideshows/basic.phtml';
				break;
		}
		echo $args['after_widget'];*/
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $in) {
		/** DEFAULT HANDLER ****************/
		$in = $this->set_defaults( $in );
		/** END DEFAULT HANDLER ****************/
		include cahnrswp\cahnrs\core\DIR.'forms/feed.phtml';
		include cahnrswp\cahnrs\core\DIR.'forms/slideshow_display.phtml';
		//$val = $this->get_defaults();
		//foreach( $val as $v_k => $v_d ){
			//$val[ $v_k ] = ( isset( $instance[ $v_k ] ) )? $instance[ $v_k ] : $val[ $v_k ];
		//}
		//$this->content_feed_control->get_form( 'basic_feed', $this , $val );
		//$this->content_feed_control->get_form( 'cahnrs_api_feed', $this , $val );
		//$this->content_feed_control->get_form( 'slideshow_display', $this , $val );
		//$this->content_feed_control->get_form( 'form_part_count', $this , $val );
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
	
	/*private function get_view_3_up( $show_obj , $args , $instance ){?>
    	<div class="cahnrs-slideshow-wrapper">
        	<div class="cahnrs-slideshow slideshow-<?php echo $instance['display'];?>">
            	<div class="slide">
                <?php $s = 0; foreach( $show_obj['features'] as $slide ): 
					if( $s < 3 ):
					?><div class="sub-slide">
                		<img src="<?php echo $slide['image'];?>" class="slide-image" />
                        <div class="slide-details">
                        	<div class="slide-title"><?php echo $slide['title'];?></div>
                            <div class="slide-summary"><?php echo $slide['summary'];?></div>
                        </div>
                        <a class="slide-link" href="<?php echo $slide['url'];?>" ></a>
                	</div><?php 
					endif;
				$s++; 
				endforeach;?>
                </div>
        	</div>
        </div>
	<?php }*/
	
	public function render_slideshow_wrapper_start( $instance ){?>
			<div class="cahnrs-slideshow <?php echo $instance['display'];?>" data-speed="5000" data-action="fade" data-auto="1" data-transition="fade">
				<div class="slideshow-inner">
					<div class="slideshow-wrapper">
	<?php }
	
	public function render_slideshow_wrapper_end( $instance , $query = false ){?>
			</div></div>
            <?php if( 'slideshow-basic' == $instance['display'] && $query ){
				$i = 0;
				if ( $query->have_posts() && $query->post_count > 1 ) {
					echo '<div class="cahnrs-slide-nav">';
					while ( $query->have_posts() ) {
						$query->the_post();
						$is_active = ( 0 == $i )? 'current-slide' : '';
						echo '<a href="#" class="'.$is_active.'" data-slide="'.$i.'" ></a>';
						$i++;
					}
					echo '</div>';
				}
			}?>
            </div>
	<?php }
};


add_action('widgets_init', create_function('', 'return register_widget("CAHNRS_Slideshow_widget");'));
?>