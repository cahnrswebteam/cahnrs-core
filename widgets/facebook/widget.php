<?php 
/**
 * Action item widget.
 */

class cahnrs_facebook extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_facebook', // Base ID
			'Facebook', // Name
			array( 'description' => 'Add Facebook Feed', ) // Args
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
		
		$hgt = ( isset( $in['hgt'] ) )? $in['hgt'] : 558 ;
		if( isset( $in['feed_link'] ) ){
			$in['feed_link'] = urlencode( $in['feed_link'] );
			echo '<iframe src="//www.facebook.com/plugins/likebox.php?href='.$in['feed_link'].'&amp;width&amp;height='.$hgt.'&amp;colorscheme=light&amp;show_faces=true&amp;header=false&amp;stream=true&amp;show_border=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:'.$hgt.'px; background: #fff;" allowTransparency="true"></iframe>';
		}
		/*echo '<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, \'script\', \'facebook-jssdk\'));</script>';
echo '<div class="fb-post" data-href="https://www.facebook.com/FacebookDevelopers/posts/10151471074398553" data-width="500"></div>';*/
		
	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $in ) {
		$in['feed_link'] = ( isset( $in['feed_link'] ) )? $in['feed_link'] : '';
		$in['hgt'] = ( isset( $in['hgt'] ) )? $in['hgt'] : 500;?>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'feed_link' ); ?>">Facebook Link</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'feed_link' ); ?>" name="<?php echo $this->get_field_name( 'feed_link' ); ?>" type="text" value="<?php echo esc_attr( $in['feed_link'] ); ?>">
		</p>
        <p>
			<label for="<?php echo $this->get_field_id( 'hgt' ); ?>">Feed Height</label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'hgt' ); ?>" name="<?php echo $this->get_field_name( 'hgt' ); ?>" type="text" value="<?php echo esc_attr( $in['hgt'] ); ?>">
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

		$instance['hgt'] = ( ! empty( $new_instance['hgt'] ) ) ? strip_tags( $new_instance['hgt'] ) : '';
		$instance['feed_link'] = ( ! empty( $new_instance['feed_link'] ) ) ? strip_tags( $new_instance['feed_link'] ) : '';

		return $instance;

	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){
	register_widget( 'cahnrs_facebook' );
});

?>