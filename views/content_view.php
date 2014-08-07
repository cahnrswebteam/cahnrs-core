<?php namespace cahnrswp\cahnrs\core;

class content_view {
	
	public function get_content_view( $args, $instance , $query ){
		$i = 0;
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		if( 'list' == $view['type'] ) echo '<ul>';
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				$instance['i'] = $i;
				global $post; 
				$display_obj = $this->get_display_obj( $args, $instance, $post, $view['fields'] );
				$this->$view['method']( $instance, $display_obj );
				$i++;
			} // END WHILE
		} // END IF
		if( 'list' == $view['type'] ) echo '</ul>';
	}
	
	public function get_index_view( $args, $instance , $query ){
		$alpha_list = explode(',','a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z');
		$index_list = array();
		$index_list[ 'count' ] = 0;
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post; 
				$display_obj = $this->get_display_obj( $args, $instance, $post, $view['fields'] );
				if( $display_obj->title ){
					$alpha = strtolower( substr( $display_obj->title ,0,1) );
					$index_list[ $alpha ][] = $display_obj;
					$index_list[ 'count' ] = $index_list[ 'count' ]+1;
				}
			} // END WHILE
		} // END IF
		echo '<nav class="cahnrs-azindex-nav">';
		foreach( $alpha_list as $alpha ):
			$active = ( array_key_exists( $alpha , $index_list ) )? 'active' : '';
			?><a class="<?php echo $active;?>" href="#azindex-<?php echo $alpha;?>"><?php echo $alpha;?></a><?php
		endforeach;
		echo '</nav>';
		switch ( $instance['display_full'] ){
			default:
				$this->get_azindex_view_full( $instance, $index_list );
				break;
		}
	}
	
	
	
	public function get_sub_view( $instance ){
		$view = array();
		switch ( $instance['display'] ){ // GET DISPLAY TYPE
			case 'slideshow-basic':
			case 'slideshow-3-up':
				$view['method'] = 'get_slide_view';
				$view['type'] = 'slideshow';
				$view['fields'] = array('title','link','excerpt','content','image');
				break;
			case 'basic_gallery':
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
			case 'full': // IF COLUMN PROMO DO THIS
				$view['method'] = 'get_full_view';
				$view['type'] = 'full';
				$view['fields'] = array('content');
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
	
	public function get_display_obj( $args, $in, $post, $fields ){
		
		include DIR.'inc/item_form_legacy_handler.php';
		
		$display_obj = new \stdClass();
		$display_obj->post = $post;
		$display_obj->title = ( in_array( 'title' , $fields ) && isset( $in['display_title'] ) && 1 == $in['display_title'] )? 
			$post->post_title : false;
			
		if( in_array( 'excerpt' , $fields ) && isset( $in['display_excerpt'] ) && 1 == $in['display_excerpt']  ) {
			if( $post->post_excerpt ){
				$display_obj->excerpt = $post->post_excerpt;
			} else {
				$excerpt = strip_shortcodes( $post->post_content );
				$excerpt = strip_tags( $excerpt );
				$excerpt = wp_trim_words( $excerpt, 35, ' ...' );
				$display_obj->excerpt = $excerpt;
			}
		} else {
			$display_obj->excerpt = false;
		}
			
			
		$display_obj->content = ( in_array( 'content' , $fields ) && isset( $in['display_content'] ) && 1 == $in['display_content'] )? 
			\apply_filters( 'cc_the_content', $post->post_content ) : false;
		$display_obj->link = ( in_array( 'link' , $fields ) && isset( $in['display_link'] ) && 1 == $in['display_link'] )? 
			\get_permalink( $post->ID ) : false;
		if( in_array( 'image' , $fields ) && isset( $in['display_link'] ) && 1 == $in['display_image'] ){
			$post_type = ( $post->post_type )? $post->post_type : get_post_type( $post->ID );
			$size = ( isset( $in['image_size'] ) )? $in['image_size'] : 'large';
			if( 'attachment' == $post_type ){
			}
			else if( 'video' == $post_type ){
				$size = ( $in['image_size'] )? $in['image_size'] : 'medium';
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
		$display_obj->meta = ( in_array( 'meta' , $fields ) )? \get_the_date() : '';
		return $display_obj;
	}
	
	
	public function get_editor_ops(){?>
    <?php edit_post_link('Edit Item', '<span class="cc-edit-link">', '</span>'); ?>
    <?php
	}
	
	public function get_basic_list_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$is_odd = ( isset($instance['i'] ) && !( $instance['i'] % 2 == 0 ) )? 'is-odd' : '';
		?>
    	<li class="cahnrs-list-view cahnrs-core-<?php echo $instance['display'];?> <?php echo $is_odd;?>">
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
        	<?php if( $display_obj->image ):?>
        	<div class="cc-image-wrapper">
            	<?php echo $ls.$display_obj->image.$le;?>
            </div>
            <?php endif;?>
            <div class="cc-content-wrapper">
            	<?php if( $display_obj->title ):?>
                <h3 class="cc-title"><?php echo $ls.$display_obj->title.$le;?></h3>
                <?php endif;?>
                <?php if( $display_obj->excerpt ):?>
                <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
                <?php endif;?>
                <?php if( $display_obj->content ):?>
                <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
                <?php endif;?>
                <?php if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ) $this->get_editor_ops(); ?>
            </div>
            <div style="clear:both"></div>
        </div>
	<?php }
	
	public function get_gallery_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		?><div class="gallery-item-wrapper <?php echo $instance['column_class']?>-columns">
        	<div class="cc-inner-wrapper"> 
				<?php if( $display_obj->image ):?>
                <div class="cc-image-wrapper">
                    <?php echo $ls.$display_obj->image.$le;?>
                </div>
            	<?php endif;?>
            	<?php if( $display_obj->title ):?>
                <h4 class="cc-title"><?php echo $ls.$display_obj->title.$le;?></h4>
                <?php endif;?>
                <?php if( $display_obj->pubdate ):?>
                <time class="article-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_date() . '</time>
                <?php endif;?>
                <?php if( $display_obj->excerpt ):?>
                <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
                <?php endif;?>
                <?php if( $display_obj->content ):?>
                <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
                <?php endif;?>
                <?php if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ) $this->get_editor_ops(); ?>
					<!--if( $display_obj->title )
						echo '<h4>' . $link_start . $display_obj->title . $link_end . '</h4>';
					//if( $display_obj->pubdate )
					//	echo '<time class="article-date" datetime="' . get_the_date( 'c' ) . '">' . get_the_date() . '</time>';
					if( $display_obj->excerpt )
						echo '<p>' . $display_obj->excerpt . '</p>';
					if( $display_obj->content )
						echo '<p>' . $display_obj->content . '</p>'; -->
        	</div>       
		</div><?php }
		
	public function get_slide_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$is_active = ( 0 == $instance[ 'i'] )? 'current-slide' : ''; 
		?><div class="cahnrs-slide <?php echo $is_active;?>" >
        	<?php echo $ls; echo $le;?>
        	<div class="image-wrapper">
				<?php echo $display_obj->image;?>
            </div>
            <div class="caption">
                <div class="caption-inner">
                    <?php if( $display_obj->title ):?>
                    <div class="title"><?php echo $display_obj->title;?></div>
                    <?php endif;?>
                    <?php if( $display_obj->excerpt ):?>
                    <div class="excerpt"><?php echo $display_obj->excerpt;?></div>
                    <?php endif;?>
                    <?php if( $display_obj->link ):?>
                    <div class="link"><?php echo $ls;?>Learn More ><?php echo $le;?></div>
                    <?php endif;?>
                </div>
            </div>
        </div><?php 
	}
	
	public function get_full_view( $instance , $display_obj ){
		echo $display_obj->content;
	}
	
	public function get_azindex_view_full( $instance , $items ){
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$instance['columns'] = ( isset( $instance['columns'] ))? $instance['columns'] : 1;
		$items_per_column = $items['count'] / $instance['columns'];
		$items_all = array();
		foreach( $items as $i_k => $i_v ){
			if( is_array( $i_v ) ){
				foreach( $i_v as $i_d ){
					$items_all[] = array( 'display_obj' => $i_d , 'label' => $i_k );
				}
			}
		}
		
		$items_total = count($items_all);
		$c_total = 0;
		$header_label = false;
		$header_tag = ( 'list' == $view['type'] )? 'li' : 'div';
		echo '<div class="cahnrs-az-column-wrapper az-columns-'.$instance['columns'].'" >';
		for( $c = 1; $c <= $instance['columns']; $c++ ){
			echo '<div class="cahnrs-az-column azcolumn-'.$c.'" ><div class="inner-column">'; 
			if( 'list' == $view['type'] ) echo '<ul>';
			$column_total = $items_total - ( $c * $items_per_column );
			while( count( $items_all ) > $column_total ){
				if( $header_label != $items_all[ $c_total ]['label'] ){
					echo '<'.$header_tag.' id="azindex-'.$items_all[ $c_total ]['label'].'" class="cahnrs-azindex-header">'.$items_all[ $c_total ]['label'].'</'.$header_tag.'>';
					$header_label = $items_all[ $c_total ]['label'];
				}
				$instance['i'] = $c_total+1;
				$this->$view['method']( $instance, $items_all[ $c_total ]['display_obj'] );
				unset( $items_all[ $c_total] );
				$c_total++;
			};
			if( 'list' == $view['type'] ) echo '</ul>';
			echo '</div></div>';
		}
		echo '</div>';
		//echo count($items_all);
	}
}
?>