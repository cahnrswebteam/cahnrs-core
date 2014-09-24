<?php 
/**
 * Action item widget.
 */

class cahnrs_surveygizmo_embed extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_surveygizmo_embed', // Base ID
			'Surveygizmo', // Name
			array( 'description' => 'Embed Surveygizmo form in page/post', ) // Args
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
	public function widget( $args, $in ) {
		$in = $this->check_defaults( $in );
		$style = 'style="width:'.$in['width'].';height:'.$in['height'].';'.$in['scroll'].'"';
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER
		switch( $in['type'] ){
			case 'iframe':
				echo '<iframe src="'.$in['src'].'" frameborder="0" '.$style.'></iframe>';
				break;
			default:
				$src_b = explode( '//', $in['src'] );
				echo '<script type="text/javascript" >document.write(\'<script src="http\' + ( ("https:" == document.location.protocol) ? "s" : "") + \'://'.$src_b[1].'?__output=embedjs&__ref=\' + escape(document.location) + \'" type="text/javascript" ></scr\'  + \'ipt>\');</script><noscript>This survey is powered by SurveyGizmo\'s <a href="http://www.surveygizmo.com">online survey software</a>. <a href="'.$in['src'].'?jsfallback=true">Please take my survey now</a></noscript>';
				break;
		}
		echo $args['after_widget']; // ECHO AFTER WRAPPER
	}
	
	public function check_defaults( $in ){
		$in['type'] = $this->check( 'type' , $in , 'js' );
		$in['height'] = $this->check( 'height' , $in , '800px' );
		$in['width'] = $this->check( 'width' , $in , '100%' );
		$in['src'] = $this->check( 'src' , $in , '' );
		$in['scroll'] = $this->check( 'scroll' , $in , 'overflow:auto' );
		return $in;
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $in ) {
		$in = $this->check_defaults( $in );?>
        <p>
			<label for="<?php echo $this->get_field_id( 'type' ); ?>">Embed Type</label> 
			<select class="widefat" id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo $this->get_field_name( 'type' ); ?>" >
            	<option value="js" <?php selected( $in['type'], 'js' );?>>Javascript</option>
                <option value="iframe" <?php selected( $in['type'], 'iframe' );?>>Iframe</option>
            </select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'src' ); ?>">Iframe Link</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'src' ); ?>" name="<?php echo $this->get_field_name( 'src' ); ?>" type="text" value="<?php echo esc_attr( $in['src'] ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">Height</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $in['height'] ); ?>"><br />
            <span style="font-size: 0.9em;">***Applies to Iframe only</span>
            
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">Width</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $in['width'] ); ?>"><br />
            <span style="font-size: 0.9em;">***Applies to Iframe only</span>
		</p>
		<?php 

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
	public function update( $new_in, $old_in ) {
		$in = $this->check_defaults( $new_in );
		$in['type'] = strip_tags( $in['type'] );
		$in['height'] = strip_tags( $in['height'] );
		$in['width'] = strip_tags( $in['width'] );
		$in['src'] = strip_tags( $in['src'] );
		$in['scroll'] = strip_tags( $in['scroll'] );

		return $in;

	}
	
	private function check( $value , $in , $default = 'na' ){
		if( 'na' == $default ){
			if( isset( $in[$value] ) && $in[$value] ) return true;
			return false; 
		} else {
			if( isset( $in[$value] ) && $in[$value] ) return $in[$value];
			return $default;
		}
	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){ 
	register_widget( 'cahnrs_surveygizmo_embed' );
});

?>