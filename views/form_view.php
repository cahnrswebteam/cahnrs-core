<?php namespace cahnrswp\cahnrs\core;
/*************************************************
** Instead of creating individual form parts this **
** is meant to have all fields and allow for **
** selection/display of relevant ones with relvant settings **
**************************************************/
class form_view{
	public $post_types = array();
	public $sites = array();
	public $sel = false;
	
	public function __construct(){
		$post_types = \get_post_types( array( 'public' => true ) , 'objects' );
		foreach( $post_types as $post_type ){
			if( 'attachment' == $post_type->name ){ // Is an attachment
				$this->post_types[$post_type->name] = 'Media Library - All';
				$this->post_types[$post_type->name.'_image'] = 'Images';
				$this->post_types[$post_type->name.'_file'] = 'Documents/Pdfs';
			} else {
				$this->post_types[$post_type->name] = $post_type->labels->name;
			}
		}
		if( is_multisite() ){
			$sites = wp_get_sites();
			if( $sites ){
				$this->sites[0] = 'Current Site';
				foreach( $sites as $site ){
					$details = get_blog_details( $site['blog_id'] );
					//$this->sites[ $site['blog_id'] ] = $details->blogname;
					$this->sites[ $details->siteurl ] = $details->blogname;
				}
			}
		}
	}
	
	public function get_form( $in, $caps, $wid_obj ){
		$this->post = $post;
		$this->in = $in;
		$this->wid_obj = $wid_obj;
		$this->sel = false;
		$this->caps = $caps;
		$i = 0;
		foreach( $caps as $cap_key => $cap ){
			$this->sel = ( 0 == $i )? true : false;
			switch ( $cap_key ){
				case 'show_feed':
					$this->show_feed( $cap );
					break;
				case 'show_adv_feed':
					$this->show_adv_feed( $cap );
					break;
				case 'show_display':
					$this->show_display( $cap );
					break;
			}
			$i++;
		}
	}
	
	public function show_adv_feed( $cap ){
		$this->section_wrap( true , 'cc-form-adv-feed' );
			$this->header_wrap( 'Advanced Feed Settings' , '' );
			$this->part_wrap( true , $class = 'advanced-feed-section' );
			$this->sub_section_wrap( true , 'adv-feed' );
				/** Site Source **/
				if( $this->sites ){
					$this->input_wrap( true );
						echo '<label>Source: </label>';
						$this->input_select( 'current_site' , array( 'class' => 'cc-adv-feed-src','value' => $this->sites, 'style' => 'max-width: 60%' ) );
					$this->input_wrap();
				}
				/** Query Order **/
				$this->input_wrap( true );
					$query_order = array(
						"date" => 'Published Date',
                        "title" =>'Title',
                        "name" =>'URL/Slug',
                        "modified" =>'Published Date',
                        "parent" =>'Parent ID',
                        "rand" =>'Random',
                        "comment_count" =>'Comment Count',
                        "menu_order" =>'Menu Order',
                        "meta_value" =>'Meta Value*',
                        "meta_value_nu" =>'Meta Value (Number)*',
                        "post__in" =>'Query Order',
					);
					echo '<label>Order By: </label>';
					$this->input_select( 'order_by' , array( 'value' => $query_order ) );
				$this->input_wrap();
				/** Order **/
				$this->input_wrap( true );
					echo '<label>Order: </label>';
					$order_type = array(
							"ASC" => 'Ascending',
							"DESC" =>'Descending',
						);
					$this->input_select( 'order' , array( 'value' => $order_type ) );
				$this->input_wrap();
			$this->sub_section_wrap();
			$this->part_wrap();
		$this->section_wrap();
	}
	public function show_display( $cap ){
		$this->section_wrap( true , 'cc-form-display-settings' );
			$this->header_wrap( 'Display Settings' , '' );
			$this->part_wrap( true , $class = 'display-settings-section' );
			$this->sub_section_wrap( true , 'feed-display' );
				if( is_array( $cap ) ){
					foreach( $cap as $hook ){
						$method = 'show_display_'.$hook;
						if( method_exists( $this , $method ) ){
							$this->$method();
						}
					}
				} else {
				}
			$this->sub_section_wrap();
			$this->part_wrap();
		$this->section_wrap();
	}
	
	public function show_display_title(){
		$this->input_wrap( true , '' , 'display: inline-block; width: 70%;' ); 
			echo '<label>Item Title:</label><br>'; 
    		$this->input_text( 'title', array( 'value' => $this->in['title'] , 'style' => 'width: 90% !important;' ) );
		$this->input_wrap();
		$this->input_wrap( true , '' , 'display: inline-block; width: 25%;' ); 
			echo '<label>Display As:</label><br>';
			$vals = array( 0 => 'N/A' , 'h2' => 'H2' , 'h3' => 'H3','h4' => 'H4' ); 
			$this->input_select( 'title_tag' , array( 'value' => $vals ) );
		$this->input_wrap();
	}
	
	public function show_display_slideshowstyle(){
		$this->input_wrap( true ); 
		echo '<label>Slideshow Style: </label>';
		$vals = array( 'slideshow-basic' => 'Standard Slideshow' , 'slideshow-three' => 'CAHNRS 3 UP' );
		$this->input_select( 'display' , array( 'value' => $vals ) );
		$this->input_wrap();
	}
	
	public function show_display_imagesize(){
		$vals = array();
		$image_sizes = \get_intermediate_image_sizes();
		foreach ( $image_sizes as $size_name ){
			$vals[ $size_name ] = $size_name;
		}
		$this->input_wrap( true ); 
		echo '<label>Image Size: </label>';
		$this->input_select( 'image_size' , array( 'value' => $vals ) );
		$this->input_wrap();
	}
	
	public function show_display_style(){
		$has_columns = ( in_array('columns', $this->caps['show_display']) )? true : false;
		$vals = array(
			"list" => 'List',
			"promo" => 'Promo',
			"column_promo" => 'Column Promo',
			'faq' => 'FAQs',
			'basic_gallery' => 'Gallery',
			'full' => 'Full Content',
		);
		if( $has_columns ){ 
			$this->input_wrap( true , '', 'display: inline-block; width: 70%;' );
			echo '<label>Display Style:</label><br />'; 
		} else {
			$this->input_wrap( true );
			echo '<label>Display Style: </label>';
		}
		$this->input_select( 'display' , array( 'value' => $vals , 'style' => 'width: 90% !important;' ) );
		$this->input_wrap();
		if( $has_columns ){
			$col_vals = array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
			);
			$this->input_wrap( true , '', 'display: inline-block; width: 25%;' ); 
			echo '<label>Columns:</label><br />';
			$this->input_select( 'columns' , array( 'value' => $col_vals ) );
			$this->input_wrap();
		}
	}
	public function show_display_az_index(){
		$this->input_wrap( true ); 
				$this->input_checkbox( 'display_azgroups', array( 'value' => 1 ) );
				echo ' <label>Collapse/Hide AZindex Groups</label>';
		$this->input_wrap();
	}
	
	public function show_display_details(){
		$this->input_wrap( true ); 
		$wrap = '<div class="cc-inline-input">';
			echo $wrap;
				$this->input_checkbox( 'display_title', array( 'value' => 1 ) );
				echo ' <label>Display Post Title</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_excerpt', array( 'value' => 1 ) );
				echo ' <label>Display Summary</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_content', array( 'value' => 1 ) );
				echo ' <label>Display Full Text</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_image', array( 'value' => 1 ) );
				echo ' <label>Display Image</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_link', array( 'value' => 1 ) );
				echo ' <label>Link to Content</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_meta', array( 'value' => 1 ) );
				echo ' <label>Display Meta</label>';
			echo '</div>';
		$this->input_wrap();
	}
	
	public function show_display_override(){
		$this->input_wrap( true ); 
		$wrap = '<div class="cc-inline-input">';
			echo $wrap;
				$this->input_checkbox( 'remove_title', array( 'value' => 1 ) );
				echo ' <label>Hide Post Title</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'hide_text', array( 'value' => 1 ) );
				echo ' <label>Hide Text</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'force_excerpt', array( 'value' => 1 ) );
				echo ' <label>Use Summary Text</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'force_content', array( 'value' => 1 ) );
				echo ' <label>Use Full Text</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'remove_image', array( 'value' => 1 ) );
				echo ' <label>Hide Image</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'remove_link', array( 'value' => 1 ) );
				echo ' <label>Remove Link</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_meta', array( 'value' => 1 ) );
				echo ' <label>Display Meta</label>';
			echo '</div>';
			echo $wrap;
				$this->input_checkbox( 'display_lightbox', array( 'value' => 1 ) );
				echo ' <label>Display Lightbox</label>';
			echo '</div>';
		$this->input_wrap();
	}
	
	public function show_feed( $cap ){
		/**********************************
		** Get post type list - used in a few options **
		**********************************/
		$type_array = array();
		$post_types = \get_post_types( array( 'public' => true ) , 'objects' );
		foreach( $post_types as $post_type ){
			$title = ( 'attachment' != $post_type->name )? $post_type->labels->name : 'Media Libary';
			$type_array[$post_type->name] = $title;
		}
		/**********************************
		** Start building the form **
		**********************************/
		$this->section_wrap( true , 'feed-type-wrapper' );
			$this->header_wrap( 'Feed Settings' , 'active' );
			$this->part_wrap( true , $class = 'feed-type-section active' );
				$cap = ( is_array( $cap ))? $cap : array( 'select', 'basic','meta' );
				$this->sub_section_wrap( true , 'feed-type' );
					foreach( $cap as $feed ){
						$active = ( $feed == $this->in['feed_type'] )? 'active' : '';
						$feed_name = ( 'basic' == $feed )? 'feed': $feed ;
						echo '<label for="feed-'.$feed.'" class="action-select-'.$feed.' '.
							$active.'" data-set="'.$feed.'-feed-type">'.ucfirst( $feed_name ).'</label>';
							$this->input_radio('feed_type' , array(
								'id' => 'feed-'.$feed,
								'value' => $feed,
								) );
					
					}
				$this->sub_section_wrap();
				
					foreach( $cap as $feed ){
						$selected = ( $feed == $this->in['feed_type'] )? 'selected' : '';
						$this->sub_section_wrap( true , 'select-feed cc-form-feed-options '.$feed.'-feed-type cc-dynamic-section '.$selected );
						switch( $feed ){
							case 'select':
								$this->select_post_type();
								$this->show_feed_select();
								$this->show_feed_selected();
								break;
							case 'basic':
								$this->show_basic_feed();
					  			break;
							case 'meta':
								$this->show_meta_feed();
					  			break;
						}
						$this->sub_section_wrap();
					}
				
			$this->part_wrap();
		$this->section_wrap();
	}
	
	public function show_meta_feed(){
		/** Select Post Type **/
		$this->input_wrap( true );
			echo '<label>Select Type: </label>';
			$this->input_select( 'post_type_meta' , array( 'value' => $this->post_types ) );
		$this->input_wrap();
		/** Meta Key **/
		$this->input_wrap( true );
			echo '<label>Meta Key: </label>';
			$this->input_text( 'meta_key' , array( 'value' => $this->in['meta_key'] ) );
		$this->input_wrap();
		/** Meta Value **/
		$this->input_wrap( true );
			echo '<label>Meta Value: </label>';
			$this->input_text( 'meta_value' , array( 'value' => $this->in['meta_value'] ) );
			echo '<br />';
			$this->input_checkbox( 'meta_value_time', array( 'value' => 1 ) );
			echo '<label>Use Current Date </label>';
		$this->input_wrap();
		/** Compare **/
		$this->input_wrap( true );
			echo '<label>Compare: </label>';
			$this->input_text( 'compare', array( 'value' => $this->in['compare'] ) );
		$this->input_wrap();
	}
	
	public function show_basic_feed(){
		/** Select Post Type **/
		$this->input_wrap( true );
			echo '<label>Select Type: </label>';
			$this->input_select( 'post_type' , array( 'value' => $this->post_types ) );
		$this->input_wrap();
		/** Select Taxonomy **/
		$this->input_wrap( true );
			$tax_vals = array( 'all' => 'All', 'category' => 'Categories', 'tag' => 'Tags' );
			echo '<label>Feed By: </label>';
			$this->input_select( 'taxonomy' , array( 'value' => $tax_vals ) );
		$this->input_wrap();
		/** Taxonomy Names **/
		$this->input_wrap( true );
			echo '<label>Cateogries/Tags: </label>';
			$this->input_text( 'terms', array( 'value' => $this->in['terms'] ) );
		$this->input_wrap();
		/** Count **/
		$this->input_wrap( true );
			echo '<label>Count: </label>';
			$this->input_text( 'count', array( 'value' => $this->in['count'] , 'style' => 'width: 30px;' ) );
			echo '&nbsp;&nbsp; <label>Skip: </label>';
			$this->input_text( 'skip', array( 'value' => $this->in['skip'] , 'style' => 'width: 30px;' ) );
		$this->input_wrap();
	}
	
	public function select_post_type( ){
		if( isset( $this->in['is_simple'] ) ) return false;
		$this->input_wrap( true );
			echo '<label>Select Type:</label>';
			echo '<select class="dynamic-load-select" id="" name="" data-source="?service=select-list">';
			foreach( $this->post_types as $type_key => $type_title ){
				echo '<option value="'.$type_key.'" '.selected( $type_key, $this->in['post_type'], false).'>'.$type_title.'</option>';
			}
			echo '</select>';
		$this->input_wrap();
	}
	
	public function show_feed_select(){ 
		$pt = ( isset( $this->in['post_type'] ) ) ? $this->in['post_type'] : 'post';
		echo '<script type="text/javascript">var widget_home_url = "'.\get_home_url().'"</script>';
		$this->input_wrap( true );
			echo '<select class="cc-select-content-drpdwn" style="width: 70%; max-width: 80%; max-height: 150px;" id="" name="" data-type="0">';
				$post_query = new \WP_Query( array('post_type' => $pt, 'posts_per_page' => -1 ) );
				if ( $post_query->have_posts() ) {
					while ( $post_query->have_posts() ) {
						$post_query->the_post();
						echo '<option value="'.$post_query->post->ID.'">'.$post_query->post->post_title.'</option>';
					}
				} 
				wp_reset_postdata();
			echo '</select>';
			echo '<a href="#" class="cc-button-primary action-add-selected">+ ADD</a>';
		$this->input_wrap();
	}
	
	public function show_feed_selected(){
		$this->input_wrap( true, 'cc-inserted-items-wrap' );
			if( isset( $this->in['selected_item'] ) && $this->in['selected_item'] ){
				$selected_items = explode(',',$this->in['selected_item'] );
				foreach( $selected_items as $item ){
					$c_post = \get_post( $item );
					if( $c_post ){
						echo '<a href="#" data-id="'.$item.'"><span>X</span>'.$c_post->post_title.'</a>';
					}
				}
			}
			echo '<label>Selected IDs: </label>';
			$this->input_text('selected_item', array( 'value' => $this->in['selected_item'] , 'hidden' => false ));
		$this->input_wrap();
	}
	
	public function section_wrap( $start = false, $class = '' ){
		$class = ( $class )? 'cc-form-'.$class : '';
		$wrap = '</section>';
		$wrap = ( $start )? '<section class="cc-form-section '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function header_wrap( $title, $class = '' ){
		$class = ( $this->sel )? $class.' active' : $class;
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
		$class = ( $this->sel )? $class.' active' : $class;
		$wrap = '</div>';
		$wrap = ( $start )? '<div  class="section-wrapper '.$class.'">' : $wrap;
		echo $wrap;
	}
	
	public function input_wrap( $start = false , $class = '' , $style='' ){
		$wrap = '</p>';
		$wrap = ( $start )? '<p class="input-wrap '.$class.'" style="'.$style.'">' : $wrap;
		echo $wrap;
	}
	
	public function input_text( $name, $args = array() ){
		$args = $this->input_defaults( $args );
		$full_name = $this->wid_obj->get_field_name( $name );
		$args['class'] = ( $args['hidden'] )? $args['class'].' hidden-input': $args['class'];
		echo '<input class="'.$args['class'].'" type="text" value="'.$args['value'].'" name="'.$full_name.'" style="'.$args['style'].'"/>';
	}
	
	public function input_radio( $name, $args = array() ){
		$args = $this->input_defaults( $args );
		$full_name = $this->wid_obj->get_field_name( $name );
		$args['class'] = ( $args['hidden'] )? $args['class'].' hidden-input': $args['class']; 
		echo '<input id="'.$args['id'].'" class="'.$args['class']
			.'" type="radio" value="'.$args['value'].'" name="'.$full_name.'" '.checked( $args['value'], $this->in[$name], false ).' />';
	}
	
	public function input_checkbox( $name, $args = array() ){
		$args = $this->input_defaults( $args );
		$full_name = $this->wid_obj->get_field_name( $name );
		$args['class'] = ( $args['hidden'] )? $args['class'].' hidden-input': $args['class']; 
		echo '<input id="" class="hidden-input" type="checkbox" value="0" name="'.$full_name.'" checked="checked" />';
		echo '<input id="'.$args['id'].'" class="'.$args['class']
			.'" type="checkbox" value="'.$args['value'].'" name="'.$full_name.'" '.checked( $args['value'], $this->in[$name], false ).' />';
	}
	
	public function input_select( $name, $args = array() ){
		$args = $this->input_defaults( $args );
		$full_name = $this->wid_obj->get_field_name( $name );
		if( is_array( $args['value'] ) ){
			echo '<select id="'.$args['id'].'" class="'.$args['class'].'" name="'.$full_name.'" style="'.$args['style'].'">';
				foreach( $args['value'] as $value => $title ){
					echo '<option value="'.$value.'" '.selected( $value, $this->in[$name], false).' >'.$title.'</option>';
				}
			echo '</select>'; 
		}
	}
	
	public function input_defaults( $args = array() ){
		$defaults = array(
			'class' => '',
			'value' => '',
			'hidden' => false,
			'style' =>'',
			'id' =>'',
			'extra' => '',
			);
		foreach( $defaults as $arg_k => $arg ){
			if( !array_key_exists( $arg_k , $args ) ){
				$args[ $arg_k ] = $arg;
			} 
		}
		return $args;
	}
}