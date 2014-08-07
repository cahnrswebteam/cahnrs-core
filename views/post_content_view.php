<?php namespace cahnrswp\cahnrs\core;

class post_content_view {
	
	//public $content_feed_control;
	
	public function __construct(){
		$this->content_feed_control = new content_feed_control();
	}
	
	public function get_view( $display_obj , $view = 'list' ){
		switch( $view ){
			case 'promo':
				$this->get_promo_view( $display_obj );
				break;
			case 'column_promo':
				$this->get_column_promo_view( $display_obj );
				break;
			default:
				$this->get_list_view( $display_obj );
				break;
		}
	}
	
	public function render_list_view( $args, $vals, $query ){
		$fields = array('title','link','excerpt','content');
		if ( $query->have_posts() ) {
			echo '<ul>';
			while ( $query->have_posts() ) {
				$query->the_post();
				$display_obj = $this->content_feed_control->get_display_obj( $args, $vals, $query->post , $fields );?>
                <li>
					<?php if( $display_obj->title ):?>
                        <?php echo $display_obj->link_start;?>
                            <span class="list-title" style="font-weight: bold;">
                                <?php echo $display_obj->title;?>
                            </span>
                        <?php echo $display_obj->link_end;?><br />
                    <?php endif;?>
                    <?php if( $display_obj->excerpt ):?>
                        <?php echo $display_obj->link_start;?>
                            <span class="list-excerpt" style="font-size: 0.9em;">
                                <?php echo $display_obj->excerpt;?>
                            </span>
                        <?php echo $display_obj->link_end;?><br />
                    <?php endif;?>
                    <?php if( $display_obj->content ):?>
                        <?php echo $display_obj->link_start;?>
                            <span class="list-content" style="font-size: 0.9em;">
                                <?php echo $display_obj->content;?>
                            </span>
                        <?php echo $display_obj->link_end;?><br />
                    <?php endif;?>
                </li>
			<?php }
			echo '</ul>';
		} else {
			// no posts found
		}
	}
	
	public function get_list_view( $display_obj ){?>
        <li>
            <?php if( $display_obj->title ):?>
            	<?php echo $display_obj->link_start;?>
                	<span class="list-title" style="font-weight: bold;">
                		<?php echo $display_obj->title;?>
                	</span>
                <?php echo $display_obj->link_end;?><br />
            <?php endif;?>
            <?php if( $display_obj->excerpt ):?>
            	<?php echo $display_obj->link_start;?>
                	<span class="list-excerpt" style="font-size: 0.9em;">
                		<?php echo $display_obj->excerpt;?>
                	</span>
                <?php echo $display_obj->link_end;?><br />
            <?php endif;?>
            <?php if( $display_obj->content ):?>
            	<?php echo $display_obj->link_start;?>
                	<span class="list-content" style="font-size: 0.9em;">
                		<?php echo $display_obj->content;?>
                	</span>
                <?php echo $display_obj->link_end;?><br />
            <?php endif;?>
        </li>
	<?php }
	
	public function get_promo_view( $display_obj ){?>
		<div class="promo-wrapper $image_class.'" style="padding: 8px 0; border-bottom: 1px solid #999;">
					<?php if( $display_obj->image ):?>
						<div class="promo-image-wrapper" style="width: 125px; float: left;">
							 <?php echo $display_obj->link_start;?>
                             	<?php echo $display_obj->image;?>
							<?php echo $display_obj->link_end;?>
						</div>
						<?php $image_margin = 135;?>
					<?php endif;?>
					<div class="promo-content-wrapper" style="margin-left: <?php echo $image_margin;?>px;">
					<?php if( $display_obj->title ):?> 
						<?php echo $display_obj->link_start;?>
							<span class="promo-title" style="font-weight: bold;">
								<?php echo $display_obj->title;?>
							</span>
						<?php echo $display_obj->link_end;?><br />
					<?php endif;?>
					<?php if( $display_obj->excerpt ):?>
                    	<?php echo $display_obj->link_start;?>
							<span class="promo-excerpt" style="font-size: 0.9em;">
								<?php echo $display_obj->excerpt;?>
							</span>
						<?php echo $display_obj->link_end;?>
					<?php endif;?>
					<?php if( $display_obj->content ):?>
						<?php echo $display_obj->link_start;?>
							<span class="promo-content" style="">
								<?php echo $display_obj->content;?>
							</span>
						<?php echo $display_obj->link_end;?>
					<?php endif;?>
					</div>
				<div style="clear: both;"></div>
				</div>
	<?php }
	
	public function get_column_promo_view( $display_obj ){?>
		<div class="promo-wrapper $image_class.'" style="padding: 8px 0; border-bottom: 1px solid #999;">
					<?php if( $display_obj->image ):?>
						<div class="promo-image-wrapper" style="width: 40px; float: left;">
							 <?php echo $display_obj->link_start;?>
                             	<?php echo $display_obj->image;?>
							<?php echo $display_obj->link_end;?>
						</div>
						<?php $image_margin = 50;?>
					<?php endif;?>
					<div class="promo-content-wrapper" style="margin-left: <?php echo $image_margin;?>px;">
					<?php if( $display_obj->title ):?> 
						<?php echo $display_obj->link_start;?>
							<span class="promo-title" style="font-weight: bold;">
								<?php echo $display_obj->title;?>
							</span>
						<?php echo $display_obj->link_end;?><br />
					<?php endif;?>
					<?php if( $display_obj->excerpt ):?>
                    	<?php echo $display_obj->link_start;?>
							<span class="promo-excerpt" style="font-size: 0.9em;">
								<?php echo $display_obj->excerpt;?>
							</span>
						<?php echo $display_obj->link_end;?>
					<?php endif;?>
					<?php if( $display_obj->content ):?>
						<?php echo $display_obj->link_start;?>
							<span class="promo-content" style="">
								<?php echo $display_obj->content;?>
							</span>
						<?php echo $display_obj->link_end;?>
					<?php endif;?>
					</div>
				<div style="clear: both;"></div>
				</div>
	<?php }
}
?>