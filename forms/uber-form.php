<?php 
/*************************************************
** Instead of creating individual form parts this **
** is meant to have all fields and allow for **
** selection/display of relevant ones with relvant settings **
**************************************************/
class form_view{
	
	public function get_form( $post, $caps, $in , $wid_obj ){
		$this->post = $post;
		$this->in = $in;
		$this->wid_obj = $wid_obj;
		foreach( $caps as $cap_key => $cap ){
			switch ( $cap_key ){
				case 'show_feed':
					$this->show_feed( $cap );
					break;
			}
		}
	}
	
	public function show_feed( $cap ){
		$this->section_wrap( true , 'feed-type' );
			$this->header_wrap( $title , 'active' );
			$this->part_wrap( true , $class = 'feed-type active' );
				$cap = ( is_array( $cap ))? $cap : array('select', 'feed');
				$this->sub_section_wrap( true , 'feed-type' );
					foreach( $cap as $feed ){
						$active = ( $feed == $in['feed_type'] )? 'active' : '';
						echo '<label for="feed-'.$feed.'" href="#" class="action-select-'.$feed.' '.
							$active.'" data-set="'.$feed.'-feed-type">'.ucfirst($feed).'</label>';
							$this->input_radio('feed-type' , array(
								'id' => 'feed-'.$feed,
								'value' => $feed,
								) );
					
						/*switch( $sec ){
							case 'select':
								$this->show_feed_select();
								break;
						}*/
					}
				$this->sub_section_wrap(); 
			$this->part_wrap();
		$this->section_wrap();
	}
	
	public function show_feed_select(){
	}
	
	public function section_wrap( $start = false, $class = '' ){
		$class = ( $class )? 'cc-form-'.$class : '';
		$wrap = '</section>';
		$wrap = ( $start )? '<section class="cc-form-section '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function header_wrap( $title, $class = '' ){
		echo '<header class="'.$class.'">'.$title.'</header>';
	}
	
	public function sub_section_wrap( $start = false , $class = '' ){
		$class = ( $class )? 'cc-form-'.$class : '';
		$wrap = '</div>';
		$wrap = ( $start )? '<div class="form-sub-section '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function part_wrap( $start = false , $class = '' ){
		$class = ( $class )? 'cc-form-'.$class : '';
		$wrap = '</div>';
		$wrap = ( $start )? '<div  class="section-wrapper '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function input_wrap( $start = false , $class = '' ){
		$wrap = '</p>';
		$wrap = ( $start )? '<p class="input-wrap '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function input_text( $name, $id = '',$class = '' , $hidden = false , $style = false ){
		$name = $this->wid_obj->get_field_name( $name );
		$class = ( $hidden )? $class.' hidden-input': $class;
		$value = ( isset( $this->in[ $name ] ) )? $this->in[ $name ] : '';
		echo '<input class="'.$class.'" type="text" value="'.$value.'" name="'.$name.'" />';
	}
	
	public function input_radio( $name, $args = array() ){
		$args = $this->input_defaults( $args );
		$name = $this->wid_obj->get_field_name( $name );
		$args['class'] = ( $args['hidden'] )? $args['class'].' hidden-input': $args['class'];

		echo '<input id="'.$args['id'].'" class="'.$args['class']
			.'" type="radio" value="'.$args['value'].'" name="'.$name.'" '.checked( $args['value'], $this->in[$name], false ).' />';
	}
	
	public function input_defaults( $args = array() ){
		$defaults = array(
			'class' => '',
			'value' => '',
			'hidden' => false,
			'style' =>'',
			'id' =>'',
			);
		foreach( $defaults as $arg_k => $arg ){
			if( !array_key_exists( $arg_k , $args ) ){
				$args[ $arg_k ] = $arg;
			} 
		}
		return $args;
	}
}


$cc_widget = $this;
$caps = ( isset( $caps ) )? $caps : array(); // Check if capabilities are set
$in = ( isset( $in ) )? $in : array(); // Check if instance settings are set
/*************************************************
** Start feed selection options **
**************************************************/
if( isset( $caps['show_feed'] ) ){ // If show feed exists
	var_dump( is_array( $caps['show_feed'] ) );
	$caps['show_feed'] = ( is_array( $caps['show_feed'] ) )? // Check if it has an array with it
		$caps['show_feed'] : array('select', 'feed'); // If not, add default array
	/** Start Render **/
	echo uber_form_section_wrap( true , 'feed-type' );
	echo uber_form_header( $title , 'active' );
    echo uber_form_sec_wrap( true , $class = 'feed-type active' );
	/** Select a feed type - set **/
	echo uber_form_sub_section_wrap( true , 'feed-type' );
	foreach( $caps['show_feed'] as $feed ){	
		$active = ( $feed == $in['feed_type'] )? 'active' : '';
		echo '<label for="feed-'.$feed.'" href="#" class="action-select-'.$feed.' '.$active.'" data-set="'.$feed.'-feed-type">'.ucfirst($feed).'</label>';
	}
	echo uber_form_sub_section_wrap();
	/** Select feed settings - set **/
	if( in_array( 'select', $caps['show_feed'] ) ){
		$class_feed_type_select = 'feed-type-select cc-form-feed-options select-feed-type cc-dynamic-section';
		$class_feed_type_select = ( 'select' == $in['feed_type'] )? $class_feed_type_select.' selected' : $class_feed_type_select;
		echo uber_form_sub_section_wrap( true , $class_feed_type_select );
			/** Select Post Dropdown **/
			echo uber_form_input_wrap( true );
				echo '<label>Select Item: </label><br />';
				echo '<select class="cc-select-content-drpdwn" style="width: 70%; max-width: 80%; max-height: 150px;" id="" name="" data-type="0">';
				if( isset( $in['post_type'] ) ){
					$post_query = new WP_Query( array('post_type' => $in['post_type'], 'posts_per_page' => -1 ) );
					if ( $post_query->have_posts() ) {
						while ( $post_query->have_posts() ) {
							$post_query->the_post();
							echo '<option value="'.$post_query->post->ID.'">'.$post_query->post->post_title.'</option>';
						}
					} 
					wp_reset_postdata();
				}
				echo '</select>';
				echo '<a href="#" class="cc-button-primary action-add-selected">+ ADD</a>';
			echo uber_form_input_wrap();
			/** Select Post List **/
			echo uber_form_input_wrap( true , 'cc-inserted-items-wrap' );
			echo uber_form_input_wrap();
				echo '<label>Selected Items: </label><br />';
				echo uber_form_text_input( 'selected_item', $in,'', true );
		echo uber_form_sub_section_wrap();
	}// End if
	/** Select a feed type - set **/
	echo uber_form_sec_wrap();
	echo uber_form_section_wrap();
} // End if
/******************************************************
** Start helper functions **
******************************************************/
function uber_form_input_wrap( $start = false , $class = '' ){
	if( $start ){
		echo '<p class="input-wrap '.$class.'">';  
	} else {
		echo '</p>';
	}
}
function uber_form_section_wrap( $start = false , $class = '' ){
	$class = ( $class )? 'cc-form-'.$class : '';
	$wrap = '</section>';
	$wrap = ( $start )? '<section class="cc-form-section '.$class.'">' : $wrap;
	echo $wrap;
}
function uber_form_sub_section_wrap( $start = false , $class = '' ){
	$class = ( $class )? 'cc-form-'.$class : '';
	$wrap = '</div>';
	$wrap = ( $start )? '<div class="form-sub-section '.$class.'">' : $wrap;
	echo $wrap;
}
function uber_form_header( $title , $class = '' ){
	echo '<header class="'.$class.'">Feed Settings</header>';
}
function uber_form_sec_wrap( $start = false , $class = '' ){
	$class = ( $class )? 'cc-form-'.$class : '';
	$wrap = '</div>';
	$wrap = ( $start )? '<div  class="section-wrapper '.$class.'">' : $wrap;
	echo $wrap;
}
function uber_form_text_input( $name, $in, $class = '' , $hidden = false ){
	$name = $widget->get_field_name( $name );
	$class = ( $hidden )? $class.' hidden-input': $class;
	$value = ( isset( $in[ $name ] ) )? $in[ $name ] : '';
	echo '<input class="'.$class.'" type="text" value="'.$value.'" name="'.$name.'" />';
}

