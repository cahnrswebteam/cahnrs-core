<?php 
/**
 * Action item widget.
 */

class cahnrs_scroll_pageload extends \WP_Widget {


	/**
	 * Sets up the widgets name etc.
	 */
	public function __construct() {
		$this->content_feed_control = new cahnrswp\cahnrs\core\content_feed_control();
		$this->view = new cahnrswp\cahnrs\core\content_view();

		parent::__construct(
			'cahnrs_scroll_pageload', // Base ID
			'Scroll Pageload', // Name
			array( 'description' => 'Activate scrolling pageload for section', ) // Args
		);

	}

	public function widget( $args, $in ) {
		$top_nav = array();
		$menu_loc = 'site'; // TO DO: MAKE THIS A DROPDOWN IN THEME OPTIONS
		$menu_name = $menu_loc; // LOCATION TO LOOK FOR
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) { // CHECK IF 
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] ); // GET THE MENU OBJECT FROM LOCATION
			$menu_items = wp_get_nav_menu_items( $menu->term_id ); // GET MENU ITEMS FROM OBJECT ID
			foreach( $menu_items as $key => $menu ){ // LOOP THROUGH MENU ITEMS - DB
				if( !$menu->menu_item_parent ){ // IF TOP LEVEL NAV
					$top_nav[] = $menu; // ADD TO TOP LE
				} // End if
			} // END FOREACHLOCATION IS SET
		};
		if( $top_nav ){ // Top nav exists
			foreach( $top_nav as $nav_item ){
				if( 'post_type' == $nav_item->type && $nav_item->object_id != $c_id ){ // Is page/post/custom type 
					
					global $wp_query; // GET GLOBAL QUERY
					$temp_query = clone $wp_query; // WRITE MAIN QUERY TO TEMP SO WE DON'T LOSE IT
					
					\query_posts(array('p' => $nav_item->object_id , 'post_type' => 'any' ) ); // DO YOU HAVE A QUERY?????
					if ( have_posts() ) {
						while ( have_posts() ) {
							global $post;
							the_post();
							the_title();
							the_content();
						}
					}
					
					$wp_query = clone $temp_query; // RESET ORIGINAL QUERY - IT NEVER HAPPEND, YOU DIDN'T SEE ANYTHING
				} 
				else if('custom' == $nav_item->type ){ // Is link
				}
				else if('taxonomy' == $nav_item->type ){ // Is taxonomy
				}
			}
		} // End if
	}


	
	
	public function form( $in ) {
		
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


	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', function(){
	register_widget( 'cahnrs_scroll_pageload' );
});

?>