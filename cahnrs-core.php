<?php namespace cahnrswp\cahnrs\core;
/**
 * Plugin Name: CAHNRS Core
 * Plugin URI:  http://cahnrs.wsu.edu/communications/
 * Description: Core Features and Widgets for CAHNRS
 * Version:     1.3
 * Author:      CAHNRS Communications, Danial Bleile, Phil Cabel
 * Author URI:  http://cahnrs.wsu.edu/communications/
 * License:     Copyright Washington State University
 * License URI: http://copyright.wsu.edu
 */

class cahnrs_core {


	public function __construct() {
		$this->define_constants(); // Define constants
		$this->init_autoload(); // Activate custom autoloader for classes
	}

	private function define_constants() {
		define( __NAMESPACE__ . '\URL', plugin_dir_url( __FILE__ )  ); // Plugin base URL
		define( __NAMESPACE__ . '\DIR', plugin_dir_path( __FILE__ ) ); // Directory path
	}

	private function init_autoload() {
		require_once 'controls/autoload_control.php'; // Require autoloader control
		$autoload = new autoload_control(); // Init autoloader to eliminate further dependency on require
	}

	public function init_plugin() {
		$widgets = new widget_control();
		$widgets->register_widgets();
		$scripts = new script_control();
		$taxonomy = new taxonomy_control();
		$services = new service_control();
		$services->init();
		$metabox = new metabox_control();
		$metabox->init();
		/*********************************************
		** ADD CUSTOM POST TYPES **
		*********************************************/
		$post_types = new post_type_control();
		$post_types->init();
	}

}

$cahnrs_core = new cahnrs_core();
$cahnrs_core->init_plugin();
?>