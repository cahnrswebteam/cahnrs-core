<?php namespace cahnrswp\cahnrs\core;

class widget_control {

	public function __construct() {
		include DIR.'widgets/cahnrs-slideshow/widget.php';
		include DIR . '/widgets/directory-search/widget.php';
		include DIR . '/widgets/action-item/widget.php';
		include DIR . '/widgets/cahnrs-feed/widget.php';
		include DIR . '/widgets/custom-gallery/widget.php';
		include DIR . '/widgets/cahnrs-insert-item/widget.php';
		include DIR . '/widgets/cahnrs-azindex/widget.php';
	}

}

?>