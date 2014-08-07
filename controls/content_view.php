<?php namespace cahnrswp\cahnrs\core;

class content_view {
	
	public function get_content_view( $args, $instance , $query ){
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		if( 'promo' == $view['type'] && !isset( $instance['display_image'] ) ) $instance['display_image'] = 1;
		if( 'list' == $view['type'] ) echo '<ul>';
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post; 
				$display_obj = $this->get_display_obj( $args, $instance, $post, $view['fields'] );
				$this->$view['method']( $instance, $display_obj );
			} // END WHILE
		} // END IF
		if( 'list' == $view['type'] ) echo '</ul>';
	}
	
	public function get_sub_view( $instance ){
		$view = array();
		switch ( $instance['display'] ){ // GET DISPLAY TYPE
			case 'gallery':
				$view['method'] = 'get_gallery_view';
				$view['type'] = 'promo';
				$view['fields'] = array('title','link','excerpt','content','image');
				break;
			case 'promo':
			case 'column_promo': // IF COLUMN PROMO DO THIS
				$view['method'] = 'get_promo_view';
				$view['type'] = 'promo';
				$view['fields'] = array('title','link','excerpt','content','image');
				break;
			case 'list':
			default: // DEFAULT LIST VIEW
				$view['method'] = 'get_basic_list_view';
				$view['type'] = 'list';
				$view['fields'] = array('title','link','excerpt','content');
				break;
		};
		return $view;
	}
	
	public function get_display_obj( $args, $vals, $post, $fields ){
		$display_obj = new \stdClass();
		$display_obj->post = $post;
		$display_obj->title = ( in_array( 'title' , $fields ) && $vals['display_title'] )? get_the_title() : false;
		$display_obj->excerpt = ( in_array( 'excerpt' , $fields ) && 'excerpt' == $vals['display_content'] )? get_the_excerpt() : false;
		$display_obj->content = ( in_array( 'content' , $fields ) && 'full' == $vals['display_content'] )? get_the_content() : false;
		$display_obj->link = ( in_array( 'link' , $fields ) && $vals['display_link'] )? get_permalink( $post->ID ) : false;
		if( in_array( 'image' , $fields ) && $vals['display_image'] ){
			$post_type = ( $post->post_type )? $post->post_type : get_post_type( $post->ID );
			$size = ( $vals['image_size'] )? $vals['image_size'] : 'thumbnail';
			if( 'attachment' == $post_type ){
			}
			else if( 'video' == $post_type ){
				$size = ( $vals['image_size'] )? $vals['image_size'] : 'medium';
				$image = get_the_post_thumbnail( $post->ID, $size, array( 'style' => 'max-width: 100%' ));
				$display_obj->image = '<div class="video-image-wrapper" style="position: relative">'.$image.'<span class="video-play"></span></div>';
			}
			else if( has_post_thumbnail( $post->ID ) ){
				$display_obj->image = get_the_post_thumbnail( $post->ID, $size, array( 'style' => 'max-width: 100%' ));
			} else {
				$display_obj->image = false;
			}
		} else {
			$display_obj->image = false;
		}
		$display_obj->link_start = ( $display_obj->link )? '<a href="'.$display_obj->link.'">' : '';
		$display_obj->link_end = ( $display_obj->link )? '</a>' : '';
		return $display_obj;
	}
	
	public function get_editor_ops(){?>
    <?php edit_post_link('Edit Item', '<span class="cc-edit-link">', '</span>'); ?>
    <?php
	}
	
	public function get_basic_list_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		?>
    	<li class="cahnrs-list-view cahnrs-core-<?php echo $instance['display'];?>">
        	<span class="cc-title"><?php echo $ls.$display_obj->title.$le;?></span>
            <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
            <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
            <?php if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ) $this->get_editor_ops(); ?>
        </li>
	<?php }
	
	
	
	public function get_promo_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$has_image = ( $display_obj->image )? ' has_image': '';
		?>
    	<div class="cahnrs-promo-view cahnrs-core-<?php echo $instance['display'].' '.$has_image;?>">
        	<div class="cc-image-wrapper">
            	<?php echo $ls.$display_obj->image.$le;?>
            </div>
            <div class="cc-content-wrapper">
                <h3 class="cc-title"><?php echo $ls.$display_obj->title.$le;?></h3>
                <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
                <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
                <?php if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ) $this->get_editor_ops(); ?>
            </div>
            <div style="clear:both"></div>
        </div>
	<?php }
	
	public function get_gallery_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$has_image = ( $display_obj->image )? ' has_image': '';
		?>
    	<div class="cahnrs-promo-view cahnrs-core-<?php echo $instance['display'].' '.$has_image;?>">
        	<div class="cc-image-wrapper">
            	<?php echo $ls.$display_obj->image.$le;?>
            </div>
            <div class="cc-content-wrapper">
                <h3 class="cc-title"><?php echo $ls.$display_obj->title.$le;?></h3>
                <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
                <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
                <?php if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ) $this->get_editor_ops(); ?>
            </div>
            <div style="clear:both"></div>
        </div>
	<?php }
	
}
?>