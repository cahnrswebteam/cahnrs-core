<?php

class CAHNRS_dept_action_widget extends \WP_Widget {
	public $content_feed_control;
	public $view;
	public $is_content = true;
	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();

		//$this->post_content_view = new cahnrswp\cahnrs\core\post_content_view();
		parent::__construct(
			'cahnrs_dept_action', // Base ID
			'Department Actions', // Name
			array( 'description' => 'Creates an accordion item with calls to action', ) // Args
		);
	}

	public function widget( $args, $in = array() ) {

		//$this->query = new cahnrswp\cahnrs\core\query_control( $in );

		//global $post;
		//global $wp_query; // GET GLOBAL QUERY
		echo $args['before_widget']; // ECHO BEFORE WIDGET WRAPPER

		//$query_obj = $this->query->get_query( $in );
		//$this->view->get_updated_content_view( $args, $in , $query_obj );

		$loop_args = array(
			'post_type'      => $in['post_type'],
			'category_name'  => $in['terms'],
			'posts_per_page' => $in['count'],

		);

		$the_query = new WP_Query( $loop_args );

		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				?>
				<div class="dept-action"<?php
					if ( has_post_thumbnail() ) {
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
						$url = $thumb['0'];
						echo ' style="background-image:url(' . $url . ');"';
					}
				?>>
					<h2 class="cc-title"><?php the_title(); ?></h2>
					<div class="cc-content"><?php the_content(); ?></div>
				</div>
				<?php
			}
		} else {
			// no posts found
		}

		wp_reset_postdata();

		echo $args['after_widget']; // ECHO AFTER WRAPPER

	}


	public function get_defaults() {
		return array(
			'feed_type' => 'basic',
			'post_type' => 'post',
			'taxonomy' => 'all',
			'terms' => '',
			'count' => 5,
			'skip' => 0,
			'display' => 'list',
		);
	}

	public function set_defaults( $instance ) {
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
			);
		$form = new cahnrswp\cahnrs\core\form_view;
		$form->get_form($in , $caps , $this );

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
}


add_action('widgets_init', create_function('', 'return register_widget("CAHNRS_dept_action_widget");'));
?>