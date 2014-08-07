<?php 
/**
 * Directory search widget.
 */

class cahnrs_directory_search extends \WP_Widget {


	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {

		parent::__construct(
			'cahnrs_directory_search', // Base ID
			'CAHNRS Directory Search', // Name
			array( 'description' => 'A search field for the CAHNRS and Extension online directory', ) // Args
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
	public function widget( $args, $instance = array() ) {

		// Old one for now
		echo $args['before_widget'];
		echo '<form name="aspnetForm" method="post" action="http://cahnrsdb.wsu.edu/newdirectory/findName.aspx" id="aspnetForm">';
		echo '<input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="/wEPDwUJOTUxNjgxMzEwZGRVN5ypXlKlBNv5M5Ctw1wFux74yQ==">';
		echo '<input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="/wEWBALxh+y6BQLR48eXDQLR49OXDQKNoLCgBA1OZmR7QF0x+plD4t62UXxKoCdF">';
		echo '<input name="ctl00$MainContent$TextBox2" type="text" id="ctl00_MainContent_TextBox2" style="visibility: hidden; display: none;">';
		echo '<input name="ctl00$MainContent$TextBox1" type="text" value="" placeholder="Search People" id="ctl00_MainContent_TextBox1"><input type="submit" name="ctl00$MainContent$Find" value="$" id="ctl00_MainContent_Find">';
		echo '</form>';
		echo $args['after_widget'];

	}


	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Probably don't need options, but could allow placeholder text customization

	}


	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {

		// processes widget options to be saved

	}


}


/**
 * Register widget with WordPress.
 */
add_action( 'widgets_init', create_function( '', 'return register_widget("cahnrs_directory_search");' ) );
?>