<?php 

class CAHNRS_Slideshow_widget extends \WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		parent::__construct(
			'cahnrs_slideshow', // Base ID
			'CAHNRS Slideshow', // Name
			array( 'description' => 'Customizable Slideshow for CAHNRS', ) // Args
		);
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance = array() ) {
		$show_json = file_get_contents( 'http://api.wpdev.cahnrs.wsu.edu/?service=standard-feed&id=featured&encode=json' );
		$show_obj = json_decode( $show_json ,true);
		switch ( $instance['display'] ){
			case '3-up':
				$this->get_view_3_up( $show_obj , $args , $instance );
				break;
		}
		// outputs the content of the widget
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {?>
    	<select id="<?php echo $this->get_field_id( 'display' ); ?>"name="<?php echo $this->get_field_name( 'display' ); ?>">
        	<option value="3-up" >CAHNRS 3-Up</option>
            
        </select>
	<?php }

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
	
	private function get_view_3_up( $show_obj , $args , $instance ){?>
    	<div class="cahnrs-slideshow-wrapper">
        	<div class="cahnrs-slideshow slideshow-<?php echo $instance['display'];?>">
            	<div class="slide">
                <?php $s = 0; foreach( $show_obj['features'] as $slide ): 
					if( $s < 3 ):
					?><div class="sub-slide">
                		<img src="<?php echo $slide['../CAHNRS_Slideshow/image'];?>" class="slide-image" />
                        <div class="slide-details">
                        	<div class="slide-title"><?php echo $slide['title'];?></div>
                            <div class="slide-summary"><?php echo $slide['summary'];?></div>
                        </div>
                        <a class="slide-link" href="<?php echo $slide['../CAHNRS_Slideshow/url'];?>" ></a>
                	</div><?php 
					endif;
				$s++; 
				endforeach;?>
                </div>
        	</div>
        </div>
	<?php }
};


add_action('widgets_init', create_function('', 'return register_widget("CAHNRS_Slideshow_widget");'));
?>