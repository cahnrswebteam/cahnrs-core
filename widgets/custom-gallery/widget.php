<?php 

class cahnrs_custom_gallery_widget extends \WP_Widget {
	public $content_feed_control;
	public $is_content = true;
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();
		parent::__construct(
			'cahnrs_gallery', // Base ID
			'Custom Gallery', // Name
			array( 'description' => 'Creates a dynamic custom gallery of Pages, Posts & other content items', ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance = array() ) {
		global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		$q_args = $this->content_feed_control->get_query_args( $instance ); // BUILD THE QUERY ARGS
		$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
		$instance['is_legacy'] = true;
		
		\query_posts($q_args); // DO YOU HAVE A QUERY?????
		/**********************************************************
		** LET'S GET READY TO RENDER **
		***********************************************************/
		// Only because I don't want numbers in my class names - PHL COLUMN CORRECTOR
		// LET'S WRITE THIS BACK TO THE INSTANCE ARRAY SO IT'S MORE PORTABLE - DB
		switch ($instance['columns']) {
			case 1: $instance['column_class'] = 'one'; break; // WE'LL JUST HARD CODE THE " " AND "-COLUMNS" 
			case 2: $instance['column_class'] = 'two'; break;
			case 3: $instance['column_class'] = 'three'; break;
			case 4: $instance['column_class'] = 'four'; break;
		}
		
		$this->view->get_content_view( $args, $instance , $query ); // RENDER THE VIEW
		//$this->widget_basic_gallery_view( $args, $instance , $wp_query ); // SWAP PHIL'S VIEW
		
		echo $args['after_widget']; // ECHO AFTER WRAPPER
		
		$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
		
		\wp_reset_postdata();
		// The Loop
		/*$q_args = $this->content_feed_control->get_query_args( $instance );
		$the_query = new WP_Query( $q_args );
		echo $args['before_widget'];
		switch ( $instance['display'] ){
			default:
				$this->widget_basic_gallery_view( $args, $instance , $the_query );
				break;
		};
		echo $args['after_widget'];
		wp_reset_postdata();*/
	}
	
	public function widget_basic_gallery_view( $args, $vals, $query ){
		$fields = array('title','link','excerpt','content','image');
		$vals['display_image'] = true;
		$vals['columns'] = ( isset( $vals['columns'] ) )? $vals['columns'] : 3;
		$width = round( ( 100/$vals['columns'] ) , 1 ) - 2;

		// Only because I don't want numbers in my class names
		switch ($vals['columns']) {
			case 1: $column_class = ''; break;
			case 2: $column_class = ' two-columns'; break;
			case 3: $column_class = ' three-columns'; break;
			case 4: $column_class = ' four-columns'; break;
		}
		
		if ( $query->have_posts() ) {
			
			while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );
				$link_start = ( $display_obj->link )? '<a href="'.$display_obj->link.'">' : '';
				$link_end = ( $display_obj->link )? '</a>' : '';
				echo '<div class="gallery-item-wrapper ' . $column_class . '">';
					if( $display_obj->image )
						echo $link_start . $display_obj->image . $link_end;
					if( $display_obj->title )
						echo '<h4>' . $link_start . $display_obj->title . $link_end . '</h4>';
					//if( $display_obj->pubdate )
					//	echo '<time class="article-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_date() . '</time>';
					if( $display_obj->excerpt )
						echo '<p>' . $display_obj->excerpt . '</p>';
					if( $display_obj->content )
						echo '<p>' . $display_obj->content . '</p>';
				echo '</div>';
			}
			echo '<br style="clear:both;" />';
			
			/*while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );
				$link_start = ( $display_obj->link )? '<a href="'.$display_obj->link.'">' : '';
				$link_end = ( $display_obj->link )? '</a>' : '';
				$image_class = ( $display_obj->image )? 'has_image' : 'no_image';
				echo '<div class="gallery-item-wrapper '.$image_class.'" style="padding: 8px 0; display: inline-block; vertical-align: top; width: '.$width.'%; margin: 0 1%;">';
					if( $display_obj->image ){
						echo '<div class="promo-image-wrapper">';
							 echo $link_start.$display_obj->image.$link_end;
						echo '</div>';
					}
					echo '<div class="promo-content-wrapper">';
					if( $display_obj->title ) echo $link_start.
						'<span class="promo-title" style="font-weight: bold;">'.
						$display_obj->title.
						'</span>'.
						$link_end.'<br />';
					if( $display_obj->excerpt ) echo $link_start.
						'<span class="promo-excerpt" style="font-size: 0.9em;">'.
						$display_obj->excerpt.
						'</span>'.
						$link_end;
					if( $display_obj->content ) echo $link_start.
						'<span class="promo-content" style="">'.
						$display_obj->content.
						'</span>'.
						$link_end;
					echo '</div>';
				echo '<div style="clear: both;"></div>';
				echo '</div>';
			}*/
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
			'display' => 'basic_gallery',
			'columns' => 3,
			'image_size' => 'medium',
			'count' => 6,
			'skip' => 0,
			'display_image' => 1,
			'display_title' => 1,
			'display_excerpt' => 1,
			//'display_content' => 'excerpt',
			'display_content' => 0,
			'display_link' => 1,
			//'display_date' => 1,
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
			'show_display' => array( 'title', 'style','columns','imagesize', 'details' ),
			);
		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this );
		//$this->content_feed_control->get_form( 'basic_feed', $this , $val );
		//$this->content_feed_control->get_form( 'cahnrs_api_feed', $this , $val );
		//$this->content_feed_control->get_form( 'gallery_display', $this , $val );
		//$this->content_feed_control->get_form( 'display', $this , $val );
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


add_action('widgets_init', create_function('', 'return register_widget("cahnrs_custom_gallery_widget");'));
?>