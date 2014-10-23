<?php 
/**
 * Campaign Progress widget
 */

class cahnrs_campaign_progress extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_campaign_progress', // Base ID
			'Campaign Progress', // Name
			array( 'description' => 'Animated campaign progress meter', ) // Args
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

		$progress = esc_attr( $instance['progress'] );

		if ( ! empty( $progress ) ) :
			echo $args['before_widget'];
			?>
			<div class="meter-padding">
				<h2>Campaign Progress</h2>
			</div>
			<div class="meter-container">
				<img src="<?php echo plugins_url( 'cahnrs-core' ); ?>/images/spirit-mark.png" width="700" height="553" />
				<div class="meter-bar"><p>$250 Million</p></div>
				<div class="meter-progress background"></div>
				<div class="meter-progress">
					<p class="progress-amount-wrapper">$<span class="progress-amount" data-progress="<?php echo $progress; ?>">0</span> Million</p>
				</div>
			</div>
			<div class="meter-padding"></div>
      <?php
			echo $args['after_widget'];
		endif;

	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		if ( isset( $instance[ 'progress' ] ) )
			$progress = $instance[ 'progress' ];
		else
			$progress = '';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'progress' ); ?>">Dollars raised so far</label><br />
			$<input id="<?php echo $this->get_field_id( 'progress' ); ?>" name="<?php echo $this->get_field_name( 'progress' ); ?>" type="text" value="<?php echo esc_attr( $progress ); ?>"> Million
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

		$instance['progress'] = ( ! empty( $new_instance['progress'] ) ) ? strip_tags( $new_instance['progress'] ) : '';

		return $instance;

	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function() {
	register_widget( 'cahnrs_campaign_progress' );
});

?>