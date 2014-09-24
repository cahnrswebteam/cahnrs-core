<?php 
/**
 * Action item widget.
 */

class cahnrs_iframe extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_iframe', // Base ID
			'IFRAME', // Name
			array( 'description' => 'Embed Iframe in page', ) // Args
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
		echo '<iframe src="'.$in['src'].'" frameborder="0" '.$style.' >';
		echo '</iframe>';
		echo $args['after_widget']; // ECHO AFTER WRAPPER
	}
	
	public function check_defaults( $in ){
		$in['height'] = $this->check( 'height' , $in , '300px' );
		$in['width'] = $this->check( 'width' , $in , '100%' );
		$in['src'] = $this->check( 'src' , $in , 'about:blank' );
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
			<label for="<?php echo $this->get_field_id( 'src' ); ?>">Iframe Link</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'src' ); ?>" name="<?php echo $this->get_field_name( 'src' ); ?>" type="text" value="<?php echo esc_attr( $in['src'] ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'height' ); ?>">Feed Height</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo esc_attr( $in['height'] ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'width' ); ?>">Feed Height</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo esc_attr( $in['width'] ); ?>">
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
		$in = array();
		
		$in['height'] = $this->check( 'height' , $in , strip_tags( $new_in['height'] ) );
		$in['width'] = $this->check( 'width' , $in , strip_tags( $new_in['width'] ) );
		$in['src'] = $this->check( 'src' , $in , strip_tags( $new_in['src'] ) );
		$in['scroll'] = $this->check( 'scroll' , $in , strip_tags( $new_in['scroll'] ) );

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
	register_widget( 'cahnrs_iframe' );
});

?>