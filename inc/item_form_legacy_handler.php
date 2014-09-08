<?php
/**********************************************
** LEGACY PROCESSING **
***********************************************/
if( 'excerpt' == $in['display_content'] ){ $in['display_excerpt'] = 1; $in['display_content'] = 0; }
if( 'full' == $in['display_content'] ){ $in['display_excerpt'] = 0; $in['display_content'] = 1; }
/** END LEGACY *****************/
/**************************************************************
/**************************************************************
** Legacy Widget Handler **
**************************************************************/
if( isset( $in['is_legacy'] ) && $in['is_legacy'] ){
	if( !isset( $in['display_title'] ) || !$in['display_title'] ) $in['remove_title'] = 1;
	if( !isset( $in['display_image'] ) || !$in['display_image'] ) $in['remove_image'] = 1;
	if( !isset( $in['display_link'] ) || !$in['display_link'] ) $in['remove_link'] = 1;
	if( !isset( $in['display_meta'] ) || !$in['display_meta'] ) $in['remove_meta'] = 1;
	$excerpt = ( !isset( $in['display_excerpt'] ) || !$in['display_excerpt'] )? false : true;
	$content = ( !isset( $in['display_content'] ) || !$in['display_content'] )? false : true;
	if( !$excerpt && !$content ) $in['hide_text'] = 1;
	if( isset( $in['display_excerpt'] ) && $in['display_excerpt'] ) $in['force_excerpt'] = 1;
	if( isset( $in['display_content'] ) && $in['display_content'] ) $in['force_content'] = 1;
}
/*************************************************************/
;?>