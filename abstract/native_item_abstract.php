<?php namespace cahnrswp\pagebuilder;

class native_item_abstract {
	public function init_widget_clone( $input_base ){
		$widget = new \stdClass();
		$widget->get_field_name = $this->get_field_name;
		$widget->get_field_id = $this->get_field_id;
		return $widget;
	}
	
	public function get_field_name( $input_base ){
		return 'name';
		
	}
	public function get_field_id( $input_base ){
		return 'id';
	}
	
	 
	
};?>