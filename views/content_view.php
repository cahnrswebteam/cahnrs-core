<?php namespace cahnrswp\cahnrs\core;

class content_view {
	
	public function get_content_view( $args, $instance , $query = false ){
		$i = 0;
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$wrap_display = array('faq');
		if( 'list' == $view['type'] ) {
			echo '<ul>';
		} 
		else if( in_array( $instance['display'], $wrap_display ) ){
			echo '<div class="cahnrs-core-'.$instance['display'].'" >'; 
		}
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
		if( 'list' == $view['type'] ) {
			echo '</ul>';
		} 
		else if( in_array( $instance['display'], $wrap_display ) ){
			echo '</div>'; 
		}
	}
	
	public function get_updated_content_view( $args, $in , $query_obj = array() ){
		$view = $this->get_sub_view( $in ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		foreach( $query_obj as $post ){
			$display_obj = $this->get_display_obj( $args, $in, $post, $view['fields'] );
			$in['i'] = $post->i;
			$this->$view['method']( $in, $display_obj );
			
		}
	}
	
	public function get_updated_azindex_view( $args, $in , $query_obj = array() ){
		$view = $this->get_sub_view( $in ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$alpha_list = explode(',','a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z');
		$number_list = array( 
			'0' => 'z', 
			'1' => 'o',
			'2' => 't',
			'3' => 't',
			'4' => 'f',
			'5' => 'f',
			'6' => 's',
			'7' => 's',
			'8' => 'e',
			'9' => 'n',
			);
		$index_list = array();
		$index_list[ 'count' ] = 0;
		foreach( $query_obj as $post ){
			$display_obj = $this->get_display_obj( $args, $in, $post, $view['fields'] );
			if( $display_obj->title ){
				$frst = substr( $display_obj->title ,0,1);
				if( is_numeric( $frst ) ){
					$index_list[ $number_list[$frst] ][] = $display_obj;
				} else {
					$index_list[ strtolower( $frst ) ][] = $display_obj;
				}
				$index_list[ 'count' ] = $index_list[ 'count' ] + 1;
			}
		}
		$aztype = ( $in['display_azgroups'] )? ' dynamic-az':'';
		echo '<nav class="cahnrs-azindex-nav'.$aztype.'">';
		$stat = ' selected';
		foreach( $alpha_list as $alpha ):
			$active = '';
			
			if( array_key_exists( $alpha , $index_list ) ){ $active = 'active';}
			$cls = ( $active )? $stat : '';
			?><a class="<?php echo $active.$cls;?>" href="#azindex-<?php echo $alpha;?>"><?php echo $alpha;?></a><?php
			if( $active ) $stat = '';
		endforeach;
		echo '</nav>';
		switch ( $in['display_azgroups'] ){
			case 1:
				$this->get_azindex_view_collapsed( $in, $index_list );
				break;
			default:
				$this->get_azindex_view_full( $in, $index_list );
				break;
		}
		
	}
	
	public function get_slideshow_view( $args, $in , $query_obj = array() ){
		$core_settings = array(
			'slideshow-basic' => array(
				'auto' => 1,
				'spd' => 8000,
				'transpd' => 700,
				'pager' => '1',
				'up' => '1',
				'fx' => 'slideHorz',
				),
			'slideshow-three' => array(
				'auto' => 0,
				'spd' => 8000,
				'transpd' => 500,
				'pager' => '0',
				'up' => '3',
				'fx' => 'threeup',
				'adv' => '1',
				),
		);
		$has_wrapper = ( 'slideshow-three' == $in['display'] )? true : false;
		$i = 0;
		$view = $this->get_sub_view( $in ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$data = array();
		foreach( $core_settings[$in['display']] as $k => $v ){
			$data[] = 'data-'.$k.'="'.$v.'"';
		}
		//if( isset( $in['auto'] ) && $in['auto'] ) $data[] = 'data-auto="1"';
		//$data[] = 'data-auto="1"';
		//$data[] = 'data-spd="8000"';
		//$data[] = 'data-transpd="700"';
		//$data[] = 'data-pager="1"';
		//$data[] = ( 'slideshow-three' == $in['display'] )? 'data-up="3"' : 'data-up="1"';
		//if( 'slideshow-three' == $in['display'] ) $data[] =  'data-fx="threeup"';
		if( 'slideshow-three' == $in['display'] ) $in['slides-up'] = 2;
		echo '<div class="cahnrs-slideshow-wrapper '.$in['display'].'">';
		echo '<div class="cahnrs-slideshow '.$in['display'].'" '.implode(' ', $data).' >';
		/**********************************************
		** Start Render Feed **
		***********************************************/
		//var_dump( $query_obj );
		foreach( $query_obj as $post ){
			$display_obj = $this->get_display_obj( $args, $in, $post, $view['fields'] );
			$in['i'] = $post->i;
			$this->$view['method']( $in, $display_obj );
			
		}
		/**********************************************
		** End Render Feed **
		***********************************************/
		echo '</div>';
		echo '</div>';
	}
	
	/*public function get_index_view( $args, $instance , $query ){
		$alpha_list = explode(',','a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z');
		$number_list = array( 
			'0' => 'z', 
			'1' => 'o',
			'2' => 't',
			'3' => 't',
			'4' => 'f',
			'5' => 'f',
			'6' => 's',
			'7' => 's',
			'8' => 'e',
			'9' => 'n',
			);
		$index_list = array();
		$index_list[ 'count' ] = 0;
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				global $post; 
				$display_obj = $this->get_display_obj( $args, $instance, $post, $view['fields'] );
				if( $display_obj->title ){
					$first_letter = substr( $display_obj->title ,0,1);
					if( is_numeric( $first_letter ) ){
						$index_list[ $number_list[$first_letter] ][] = $display_obj;
					} else {
						$alpha = strtolower( substr( $display_obj->title ,0,1) );
						$index_list[ $alpha ][] = $display_obj;
					}
					$index_list[ 'count' ] = $index_list[ 'count' ]+1;
				}
			} // END WHILE
		} // END IF
		$aztype = ( $instance['display_azgroups'] )? ' dynamic-az':'';
		echo '<nav class="cahnrs-azindex-nav'.$aztype.'">';
		$stat = ' selected';
		foreach( $alpha_list as $alpha ):
			$active = '';
			
			if( array_key_exists( $alpha , $index_list ) ){ $active = 'active';}
			$cls = ( $active )? $stat : '';
			?><a class="<?php echo $active.$cls;?>" href="#azindex-<?php echo $alpha;?>"><?php echo $alpha;?></a><?php
			if( $active ) $stat = '';
		endforeach;
		echo '</nav>';
		switch ( $instance['display_azgroups'] ){
			case 1:
				$this->get_azindex_view_collapsed( $instance, $index_list );
				break;
			default:
				$this->get_azindex_view_full( $instance, $index_list );
				break;
		}
	}*/
	
	
	
	public function get_sub_view( $instance ){
		$view = array();
		switch ( $instance['display'] ){ // GET DISPLAY TYPE
			case 'faq':
				$view['method'] = 'get_faq_view';
				$view['type'] = 'faq';
				$view['fields'] = array('title','content', 'link');
				break;
			case 'slideshow-three':
				//$view['method'] = 'get_subslide_view';
				//$view['type'] = 'slideshow';
				//$view['fields'] = array('title','link','excerpt','image');
				//break;
			case 'slideshow-basic':
				$view['method'] = 'get_slide_view';
				$view['type'] = 'slideshow';
				$view['fields'] = array('title','link','excerpt','image');
				break;
			case 'basic_gallery':
				$view['method'] = 'get_gallery_view';
				$view['type'] = 'promo';
				$view['fields'] = array('title','link','excerpt','image','meta');
				break;
			case 'promo':
			case 'column_promo': // IF COLUMN PROMO DO THIS
				$view['method'] = 'get_promo_view';
				$view['type'] = 'promo';
				$view['fields'] = array('title','link','excerpt','image');
				break;
			case 'full': // IF COLUMN PROMO DO THIS
				$view['method'] = 'get_full_view';
				$view['type'] = 'full';
				$view['fields'] = array('title','content','image');
				break;
			case 'list':
			default: // DEFAULT LIST VIEW
				$view['method'] = 'get_basic_list_view';
				$view['type'] = 'list';
				$view['fields'] = array('title','link','excerpt');
				break;
		};
		return $view;
	}
	
	public function get_display_obj( $args, $in, $post, $fields ){
		
		include DIR.'inc/item_form_legacy_handler.php';
		/*********************************************
		** Clean From Pagebuilder **
		**********************************************/
		$post->post_content = str_replace('<!-- PRIMARY CONTENT -->', '',$post->post_content ); 
		
		$display_obj = new \stdClass();
		/***********************************************
		** POST **
		************************************************/
		$display_obj->post = $post;
		/***********************************************
		** TITLE **
		************************************************/
		$display_obj->title = ( $this->check_get( 'title' , $fields , $in , 'remove_title' ) )? 
			\apply_filters( 'the_title', $post->post_title ) : false;
		/***********************************************
		** EXCERPT **
		************************************************/
		if( $this->check_get( 'excerpt' , $fields , $in , 'hide_text' ) ) {
			if( ( isset( $in['force_content'] ) && $in['force_content'] ) || ( isset( $in['display_content'] ) && $in['display_content'] ) ){ 
				$display_obj->excerpt = \apply_filters( 'the_content', $post->post_content );
			}
			else if( $post->post_excerpt ){
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
		/***********************************************
		** CONTENT **
		************************************************/	
		if( $this->check_get( 'content' , $fields , $in , 'hide_text' ) ){
			if( ( isset( $in['force_excerpt'] ) && $in['force_excerpt'] ) || ( isset( $in['display_excerpt'] ) && $in['display_excerpt'] ) ){
				if( $post->post_excerpt ){
					$display_obj->content = $post->post_excerpt;
				} else {
					$excerpt = strip_shortcodes( $post->post_content );
					$excerpt = strip_tags( $excerpt );
					$excerpt = wp_trim_words( $excerpt, 35, ' ...' );
					$display_obj->content = $excerpt;
				}
			} else {
				$display_obj->content = \apply_filters( 'the_content', $post->post_content );
			}
		} else {
			$display_obj->content = false;
		}
		/***********************************************
		** LINK **
		************************************************/
		if( $this->check_get( 'link' , $fields , $in , 'remove_link' ) ){
			$display_obj->link = ( isset( $post->post_link ) )? $post->post_link : \get_permalink( $post->ID );
		} else {
			$display_obj->link = false;
		}
		/***********************************************
		** IMAGE **
		************************************************/
		if( $this->check_get( 'image' , $fields , $in , 'remove_image' )){
			if( $post->img ){ $display_obj->image = $post->img;}
			else {
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
			}
		} else {
			$display_obj->image = false;
		}
		/***********************************************
		** LINK **
		************************************************/
		$display_obj->link_start = '';
		$display_obj->link_end = '';
		if( $display_obj->link ){
			$site = ( isset( $post->src )  && $post->src != get_home_url() )? '&src='.urlencode( $post->src ): '' ;
			$ld = array();
			$ld[] = ( isset( $post->post_type ) )? 'data-type="'.$post->post_type.'"' : '';
			$ld[] = ( isset( $post->post_type ) )? 'data-serviceurl="'.get_home_url().'"' : '';
			if( isset( $post->post_type ) && 'video' == $post->post_type ) $ld[] = 'data-vid="'. implode( $post->meta['_video_id'] ).'"';
			$lcls = $this->check( 'display_lightbox', $in , '', ' cahnrs-lightbox-item' );
			$display_obj->link_start = ( $display_obj->link )? '<a class="'.$lcls.'" href="'.$display_obj->link.'" '.implode(' ',$ld ).'>' : '';
			$display_obj->link_end = ( $display_obj->link )? '</a>' : '';	
		}
		/***********************************************
		** META **
		************************************************/
		$display_obj->meta = ( in_array( 'meta' , $fields ) )? \get_the_date() : '';
		/***********************************************
		** RETURN OBJ **
		************************************************/
		return $display_obj;
	}
	
	
	public function get_editor_ops( $display_obj = false ){
		if( current_user_can( 'edit_posts') || current_user_can( 'edit_pages') ){
			if( !$display_obj ){
    			edit_post_link(' - Edit Item', '<span class="cc-edit-link">', '</span>');
			}
			else if( isset( $display_obj->post ) ){
				echo ' <a href="'.get_edit_post_link( $display_obj->post->ID, '' ).'" > - Edit Item</a>';
			} 
		}
	}
	
	
	public function get_faq_view( $instance , $display_obj ){
		$is_odd = ( isset($instance['i'] ) && !( $instance['i'] % 2 == 0 ) )? 'is-odd' : '';
		?>
        	<a href="<?php echo $display_obj->link;?>" class="cc-title"><?php echo $display_obj->title;?></a>
            <div class="cc-content"><?php echo $display_obj->content;?><?php $this->get_editor_ops();?></div>
	<?php }
	
	
	public function get_basic_list_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$is_odd = ( isset($instance['i'] ) && !( $instance['i'] % 2 == 0 ) )? 'is-odd' : '';
		?>
    	<li class="cahnrs-list-view cahnrs-core-<?php echo $instance['display'];?> <?php echo $is_odd;?>">
        	<span class="cc-title"><?php echo $ls.$display_obj->title.$le;?></span>
            <span class="cc-excerpt"><?php echo $ls.$display_obj->excerpt.$le;?></span>
            <span class="cc-content"><?php echo $ls.$display_obj->content.$le;?></span>
            <?php $this->get_editor_ops( $display_obj ); ?>
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
                <span class="cc-excerpt"><?php echo $display_obj->excerpt;?></span>
                <?php endif;?>
                <?php /* if( $display_obj->content ):?>
                <span class="cc-content"><?php echo $display_obj->content;?></span>
                <?php endif; */?>
                <?php $this->get_editor_ops( $display_obj ); ?>
            </div>
            <div style="clear:both"></div>
        </div>
	<?php }
	
	public function get_gallery_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		?><div class="gallery-item-wrapper <?php echo $instance['column_class']; ?>-columns">
			<div class="cc-inner-wrapper"> 
			<?php if( $display_obj->image ): ?>
				<div class="cc-image-wrapper">
					<?php echo $ls.$display_obj->image.$le; ?>
				</div>
			<?php endif; ?>
			<?php if( $display_obj->title ): ?>
				<h4 class="cc-title"><?php echo $ls.$display_obj->title.$le;?></h4>
			<?php endif; ?>
      <?php if( $display_obj->meta ): ?>
      	<time class="article-date" datetime=""><?php echo $display_obj->meta; ?></time>
      <?php endif; ?>
      <?php if( $display_obj->excerpt ): ?>
      	<span class="cc-excerpt"><?php echo $display_obj->excerpt; ?></span>
      <?php endif; ?>
      <?php if( $display_obj->content ): ?>
      	<span class="cc-content"><?php echo $display_obj->content; ?></span>
      <?php endif; ?>
      <?php $this->get_editor_ops( $display_obj ); ?>
			</div>       
		</div><?php }
		
	public function get_slide_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		$slides_up = ( isset( $instance['slides-up'] ) )? $instance['slides-up'] : 0;
		$is_active = ( $slides_up >= $instance[ 'i'] )? 'currentslide' : ''; 
		?><div class="cahnrs-slide <?php echo $is_active;?>" >
			<?php echo $ls.$display_obj->image.$le;?>
            <div class="caption">
                    <?php if( $display_obj->title ):?>
                    <div class="title"><?php echo $ls.$display_obj->title.$le;?></div>
                    <?php endif;?>
                    <?php if( $display_obj->excerpt ):?>
                    <div class="excerpt"><?php echo $display_obj->excerpt;?></div>
                    <?php endif;?>
            </div>
        </div><?php 
	}
	
	/*public function get_subslide_view( $instance , $display_obj ){
		$ls = $display_obj->link_start;
		$le = $display_obj->link_end;
		?><div class="cahnrs-subslide" >
			<?php echo $ls.$display_obj->image.$le;?>
            <div class="caption">
                    <?php if( $display_obj->title ):?>
                    <div class="title"><?php echo $ls.$display_obj->title.$le;?></div>
                    <?php endif;?>
                    <?php if( $display_obj->excerpt ):?>
                    <div class="excerpt"><?php echo $display_obj->excerpt;?></div>
                    <?php endif;?>
            </div>
        </div><?php 
	}*/
	
	public function get_full_view( $instance , $display_obj ){
		echo $display_obj->content;
	}
	
	public function get_azindex_view_full( $instance , $items ){
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$instance['columns'] = ( isset( $instance['columns'] ))? $instance['columns'] : 1;
		$items_per_column = $items['count'] / $instance['columns'];
		$items_all = array();
		ksort( $items );
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
	public function get_azindex_view_collapsed( $instance , $item_index ){
		$view = $this->get_sub_view( $instance ); // GET VIEW LAYOUT TYPE AND USED FIELDS
		$instance['columns'] = ( isset( $instance['columns'] ))? $instance['columns'] : 1;
		$c = 0;
		ksort( $item_index );
		foreach( $item_index as $label => $items ){
			
			if( is_array( $items ) ){
				$stat = ( 0 == $c )? ' selected':'';
				echo '<div class="cahnrs-az-column-wrapper cahnrs-az-collapsed az-columns-'.$instance['columns'].' '.$stat.' column-group-'.$label.'" >';
				$per_column = ceil( count( $items ) / $instance['columns'] );//ceil( count( $items ) / $instance['columns'] );
				//var_dump( count( $items ) );
				//var_dump( $per_column );
				for( $i = 1; $i <= $instance['columns']; $i++ ){ 
					echo '<div class="cahnrs-az-column azcolumn-'.$i.'" ><div class="inner-column">';
					if( 'list' == $view['type'] ) echo '<ul>';
					for( $x = $per_column * ( $i - 1 ); $x < $per_column * $i; $x++ ){
						if( $x >= count( $items ) ) break;  
						$instance['i'] = $x;
						$this->$view['method']( $instance, $items[$x] );
						//var_dump( $items[$x]->title );
					}
					if( 'list' == $view['type'] ) echo '</ul>';
					echo '</div></div>';
				}
				echo '</div>';
				$c++;
			}
			
			
		}
		
		
		/*$items_per_column = $items['count'] / $instance['columns'];
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
	*/
	}
	public function check( $name , $in , $default = 'na' , $alt = 'na' ){
		if( 'na' == $default ){
			if( isset( $in[$name] ) && $in[$name] ) return true;
			return false;
		} 
		else if( 'na' != $alt ){
			if( isset( $in[$name] ) && $in[$name] ) return $alt;
			return $default;
		} else {
			if( isset( $in[$name] ) && $in[$name] ) return $in[$name];
			return $default;
		}
	}
	
	public function check_get( $fname , $fields , $in , $ovr = false ){
		//if( isset( $in[$name] ) && 0 === $in[$name] ) return false;
		if( !in_array( $fname , $fields ) ) return false; // Check if in fields
		if( $ovr && isset( $in[$ovr] ) && $in[$ovr] ) return false; // Check if has override
		//if( isset( $in[$name] ) && !$in[$name] ) return false;
		return true;
	}
	
	public function handle_content(){
	}
}
?>