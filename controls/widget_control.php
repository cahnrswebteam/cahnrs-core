<?php namespace cahnrswp\cahnrs\core;

class widget_control {

	public function register_widgets() {
		include DIR . 'widgets/cahnrs-slideshow/widget.php';
		include DIR . '/widgets/directory-search/widget.php';
		include DIR . '/widgets/action-item/widget.php';
		include DIR . '/widgets/cahnrs-feed/widget.php';
		include DIR . '/widgets/custom-gallery/widget.php';
		include DIR . '/widgets/cahnrs-insert-item/widget.php';
		include DIR . '/widgets/cahnrs-azindex/widget.php';
		include DIR . '/widgets/cahnrs-faqs/widget.php';
		include DIR . '/widgets/cahnrs-scroll-pageload/widget.php';
		include DIR . '/widgets/cahnrs-insert-existing/widget.php';
		include DIR . '/widgets/cahnrs-insert-video/widget.php';
		include DIR . '/widgets/facebook/widget.php';
		include DIR . '/widgets/cahnrs-iframe/widget.php';
		include DIR . '/widgets/cahnrs-surveygizmo/widget.php';
		include DIR . '/widgets/campaign-progress/widget.php';
	}

}

?>