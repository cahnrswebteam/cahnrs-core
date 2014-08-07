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
			'CAHNRS Action Item', // Name
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

		$name = esc_attr( $instance['name'] );
		$url = esc_attr( $instance['url'] );

		if ( ! empty( $name ) && ! empty( $url ) ){
			echo $args['before_widget'];
			echo '<a href="' . $url . '">' . $name . '</a>';
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

		if ( isset( $instance[ 'name' ] ) )
			$name = $instance[ 'name' ];
		else
			$name = '';
			
		if ( isset( $instance[ 'url' ] ) )
			$url = $instance[ 'url' ];
		else
			$url = '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'name' ); ?>">Name</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'name' ); ?>" name="<?php echo $this->get_field_name( 'name' ); ?>" type="text" value="<?php echo esc_attr( $name ); ?>">
		</p>
    <p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>">URL</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>">
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