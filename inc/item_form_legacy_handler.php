<?php
/**********************************************
** LEGACY PROCESSING **
***********************************************/
if( 'excerpt' == $in['display_content'] ){ $in['display_excerpt'] = 1; $in['display_content'] = 0; }
if( 'full' == $in['display_content'] ){ $in['display_excerpt'] = 0; $in['display_content'] = 1; }
/** END LEGACY *****************/
;?>