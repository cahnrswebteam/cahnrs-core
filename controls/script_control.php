<?php namespace cahnrswp\cahnrs\core;

class script_control {

	public function __construct() {
		\add_action( 'wp_enqueue_scripts', array( $this ,'wp_scripts' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this , 'wp_admin_scripts' ) );
	}
	
	public function wp_scripts(){
		wp_enqueue_style( 'cahnrs_core_css', URL.'css/cahnrs-core-css.css' );
		wp_enqueue_script( 'cahnrs_core_js', URL . '/js/cahnrs-core-js.js' );
	}
	
	public function wp_admin_scripts(){
		wp_register_style( 'cahnrs_core_admin_css', URL . '/css/cahnrs-core-admin-css.css', false, '1.0.0' );
		wp_enqueue_style( 'cahnrs_core_admin_css' );
		wp_enqueue_script( 'cahnrs_core_admin_js', URL . '/js/cahnrs-core-admin-js.js' );
	}
}
?>