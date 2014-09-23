<?php namespace cahnrswp\cahnrs\core;

class iframe {
	//public $reg_sites = array(
		//'cahnrs.wsu.edu',
		//'hardwoodbiofuels.org',
		//);
	
	public function get_html( $src ){
		//$is_reg = false;
		//$url = $this->clean_url( $src );
		//foreach( $this->reg_sites as $site ){
		//	if( substr( $url, 0, strlen( $site ) ) === $site ) $is_reg = true;
		//	break;
		//}
		//if( $is_reg ){
		//	$content = 'found';
		//} else {
		//	$content = 'not-found';
		//}
		$content = @json_decode( @file_get_contents( $src.'?cahnrs-feed=true' ) );
		if( !$content ) return false; 	
		foreach( $content as $html ){
			if( isset( $html->full_content ) ) echo $html->full_content;
		}
		
	}
	
	/*public function clean_url( $url ){
		$url = explode( '//', $url );
		$url = str_replace ('www', '', $url[1] );
		return $url;
	}*/
}
$init_iframe = new iframe;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
<?php wp_head();?>
<style>
	html {
		background: none !important;
		height: 100% !important;
	}
	#easter-egg {
		display: none !important;
	}
</style>
</head>
<body>
<?php $init_iframe->get_html( $_GET['src'] );?>
<?php wp_footer();?>
</body>
</html>

