<?php 
/**
 * Action item widget.
 */

class cahnrs_action_item extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_action_item', // Base ID
			'Action Button', // Name
			array( 'description' => 'Action items for CAHNRS website landing pages', ) // Args
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

		$name_1 = esc_attr( $instance['name_1'] );
		$url_1 = esc_attr( $instance['url_1'] );
		
		$name_2 = esc_attr( $instance['name_2'] );
		$url_2 = esc_attr( $instance['url_2'] );
		
		$name_3 = esc_attr( $instance['name_3'] );
		$url_3 = esc_attr( $instance['url_3'] );

		if ( ! empty( $name_1 ) && ! empty( $url_1 ) ) {
			echo $args['before_widget'];
			echo '<a href="' . $url_1 . '">' . $name_1 . '</a>';
			if ( ! empty( $name_2 ) && ! empty( $url_2 ) ) echo '<a href="' . $url_2 . '">' . $name_2 . '</a>';
			if ( ! empty( $name_3 ) && ! empty( $url_3 ) ) echo '<a href="' . $url_3 . '">' . $name_3 . '</a>';
			echo $args['after_widget'];
		}
		
		

	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'name_1' ] ) )
			$name_1 = $instance[ 'name_1' ];
		else
			$name_1 = '';

		if ( isset( $instance[ 'url_1' ] ) )
			$url_1 = $instance[ 'url_1' ];
		else
			$url_1 = '';

		if ( isset( $instance[ 'name_2' ] ) )
			$name_2 = $instance[ 'name_2' ];
		else
			$name_2 = '';

		if ( isset( $instance[ 'url_2' ] ) )
			$url_2 = $instance[ 'url_2' ];
		else
			$url_2 = '';

		if ( isset( $instance[ 'name_3' ] ) )
			$name_3 = $instance[ 'name_3' ];
		else
			$name_3 = '';

		if ( isset( $instance[ 'url_3' ] ) )
			$url_3 = $instance[ 'url_3' ];
		else
			$url_3 = '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'name_1' ); ?>">First Action Item Name</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'name_1' ); ?>" name="<?php echo $this->get_field_name( 'name_1' ); ?>" type="text" value="<?php echo esc_attr( $name_1 ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'url_1' ); ?>">First Action Item URL</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'url_1' ); ?>" name="<?php echo $this->get_field_name( 'url_1' ); ?>" type="text" value="<?php echo esc_attr( $url_1 ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'name_2' ); ?>">Second Action Item Name</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'name_2' ); ?>" name="<?php echo $this->get_field_name( 'name_2' ); ?>" type="text" value="<?php echo esc_attr( $name_2 ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'url_2' ); ?>">Second Action Item URL</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'url_2' ); ?>" name="<?php echo $this->get_field_name( 'url_2' ); ?>" type="text" value="<?php echo esc_attr( $url_2 ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'name_3' ); ?>">Third Action Item Name</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'name_3' ); ?>" name="<?php echo $this->get_field_name( 'name_3' ); ?>" type="text" value="<?php echo esc_attr( $name_3 ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'url_3' ); ?>">Third Action Item URL</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'url_3' ); ?>" name="<?php echo $this->get_field_name( 'url_3' ); ?>" type="text" value="<?php echo esc_attr( $url_3 ); ?>">
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
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance['name'] = ( ! empty( $new_instance['name'] ) ) ? strip_tags( $new_instance['name'] ) : '';
		$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';

		return $instance;

	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){
	register_widget( 'cahnrs_action_item' );
});

?>