<?php namespace cahnrswp\cahnrs\core;

class item_email_view {
	private $item_model;
	private $in;
	
	public function __construct( $in , $item_model ){
		$this->in = $in;
		$this->item_model = $item_model;
	}
	
	public function get_view(){
		$layout = '';
		$this->in['display'] = ( isset( $this->in['display'] ) )? $this->in['display']: 'list';
		switch( $this->in['display'] ){
			case 'full':
				$this->email_full_view( $layout );
				break;
			case 'promo':
				$this->email_promo_view( $layout );
				break;
			case 'list':
			case 'default':
				$this->email_list_view( $layout );
				break;
		}
		return $layout;
	}
	
	private function email_list_view( &$layout ){
		$layout .= '<tr><td  valign="top"><ul>';
		foreach( $this->item_model->items as $item ){
			$ls = $item->link_start;
			$le = $item->link_end;
			$layout .= '<li>';
				if( isset( $item->title ) ) $layout .= '<h5>'.$ls . $item->title . $le.'</h5>';
				if( isset( $item->text ) ) {
					$layout .= $ls . $item->text . $le;
				}
			$layout .= '</li>';
		}
		$layout .= '</ul></td></tr><tr><td>&nbsp;</td></tr>';
	}
	
	private function email_promo_view( &$layout ){
		foreach( $this->item_model->items as $item ){
			$layout .= '<tr>';
			$ls = $item->link_start;
			$le = $item->link_end;
			$has_image = false;
			if( isset( $item->image ) ){
				$has_image = true;
				$layout .= '<td width="160" style="width: 160px; height: auto;" valign="top">';
					$layout .= '<img src="'.$item->image.'" width="150" />';
				$layout .= '</td>';
			}
			$colspan = ( $has_image )? '' : ' colspan="2"';
			$layout .= '<td  valign="top"'.$colspan.'>';
			if( isset( $item->title ) ) $layout .= '<h4>'.$ls . $item->title . $le .'</h4>';	
			if( isset( $item->text ) ) $layout .= $ls . $item->text . $le;
			$layout .= '</td>';
			$layout .= '</tr><tr><td colspan="2">&nbsp;</td></tr>';
		}
	}
	
	private function email_full_view( &$layout ){
		foreach( $this->item_model->items as $item ){
			$layout .= '<tr>';
			$ls = $item->link_start;
			$le = $item->link_end;
			$layout .= '<td  valign="top">';
			if( isset( $item->title ) ) $layout .= '<h3>'.$ls . $item->title . $le .'</h3>';	
			if( isset( $item->content ) ) $layout .= $ls . $item->content . $le;
			$layout .= '</td>';
			$layout .= '</tr><tr><td>&nbsp;</td></tr>';
		}
	}
}
?>