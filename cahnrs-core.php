<?php namespace cahnrswp\cahnrs\core;
/**
 * Plugin Name: CAHNRS Core
 * Plugin URI:  http://cahnrs.wsu.edu/communications/
 * Description: Core Features and Widgets for CAHNRS
 * Version:     1.1
 * Author:      CAHNRS Communications, Danial Bleile
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
		$scripts = new script_control();
		$feeds = new feed_control();
		$feeds->init_feed_control();
	}

}

$cahnrs_core = new cahnrs_core();
$cahnrs_core->init_plugin();
?>