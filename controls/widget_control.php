<?php namespace cahnrswp\cahnrs\core;

class widget_control {

	public function __construct() {
		$this->add_custom_filter();
		include DIR.'widgets/cahnrs-slideshow/widget.php';
		include DIR . '/widgets/directory-search/widget.php';
		include DIR . '/widgets/action-item/widget.php';
		include DIR . '/widgets/cahnrs-feed/widget.php';
		include DIR . '/widgets/custom-gallery/widget.php';
		include DIR . '/widgets/cahnrs-insert-item/widget.php';
		include DIR . '/widgets/cahnrs-azindex/widget.php';
	}
	
	private function add_custom_filter(){
		\add_filter('cc_the_content', 'do_shortcode');
		\add_filter( 'cc_the_content', 'wptexturize'        );
		\add_filter( 'cc_the_content', 'convert_smilies'    );
		\add_filter( 'cc_the_content', 'convert_chars'      );
		\add_filter( 'cc_the_content', 'wpautop'            );
		\add_filter( 'cc_the_content', 'shortcode_unautop'  );
		\add_filter( 'cc_the_content', 'prepend_attachment' );
	}
	
}

?>