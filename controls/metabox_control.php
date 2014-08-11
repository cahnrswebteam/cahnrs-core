<?php namespace cahnrswp\cahnrs\core;

class metabox_control{

	public function init(){
		//\add_action( 'add_meta_boxes', array( $this , 'init_metaboxes' ) );
		
	}
	
	public function init_metaboxes(){
		/** Select what posts this should display on - DB **/
		
		$screens = array( 'post', 'page' );
		/** Add custom metabox for each type **/
		foreach ( $screens as $screen ) {
	
			add_meta_box(
				'post_settings',
				__( 'Post Settings' ),
				array( $this , 'render_post_settings' ),
				$screen
			);
		}
	}
	
	public function render_post_settings( $post ){
		;?>
        <div class="ps-input-wrap">
        	<label>Content Type Category:</label><br />
            <select name="">
            	<option value="0">Select</option>
            	<option value="0">Standard Content</option>
                <option value="news">News</option>
                <option value="news">Event</option>
                <option value="featured-story">Featured Story</option>
                <option value="announcement">Announcement</option>
                <option value="form-online">Form: Online</option>
                <option value="form-online">Form: Document</option>
                <option value="form-online">Report/Publication</option>
                <option value="form-online">Presentation (ppt)</option>
                <option value="form-online">Video</option>
                <option value="form-online">Other</option>
            </select>
        </div>
        <div class="ps-input-wrap">
        	<label>Subject Category:</label><br />
            <select name="">
            	<option value="0"><em>Select</em></option>
            	<option value="0">Research</option>
                <option value="0">Academics</option>
                <option value="0">Extension</option>
                <option value="0">Students - All</option>
                <option value="0">Students - Undergraduate</option>
                <option value="0">Students - Graduate</option>
                <option value="0">Faculty/Staff</option>
                <option value="0">Education & Training</option>
                <option value="0">FAQ</option>
                <option value="0">Other</option>
            </select>
        </div>
        <div class="ps-input-wrap">
        	<label>Subject Sub Category:</label><br />
            <select name="">
            	<option value="0"><em>Select</em></option>
            	<option value="0">Research</option>
                <option value="0">Academics</option>
                <option value="0">Extension</option>
                <option value="0">Education & Training</option>
                <option value="0">FAQ</option>
                <option value="0">Other</option>
                <option value="0">Students - All</option>
                <option value="0">Students - Undergraduate</option>
                <option value="0">Students - Graduate</option>
                <option value="0">Faculty/Staff</option>
                <option value="0">Alumni & Friends</option>
                <option value="0">External - Individaul</option>
                <option value="0">External - Industry</option>
            </select>
        </div>
        <div class="ps-input-wrap">
        	<label>Primary Audience:</label><br />
            <select name="">
            	<option value="0"><em>Select</em></option>
                <option value="0">External - Individaul</option>
                <option value="0">External - Industry</option>
                <option value="0">Students - All</option>
                <option value="0">Students - Undergraduate</option>
                <option value="0">Students - Graduate</option>
                <option value="0">Faculty</option>
                <option value="0">Staff</option>
                <option value="0">Alumni & Friends</option>
            </select>
        </div>
        <?php
		echo 'hello world';
	}
}
?>