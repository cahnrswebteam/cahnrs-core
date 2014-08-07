jQuery(document).ready(function(){ var cahnrs_core_widget_sett = new cahnrs_core_widget_settings();});

var cahnrs_core_widget_settings = function(){
	//this.edtr = jQuery( '#layout-editor');
	var s = this;
	
	s.be = function(){ // BIND EVENTS
		//s.edtr.on('change','.activate-next',function(){ s.act_n( jQuery( this ) ) });
		jQuery('body').on('change','.dynamic-load-select',function(){ s.dy_l_s( jQuery( this ) ) });
		jQuery('body').on('click','.cc-form-section > header', function(){ s.chg_frm_sec( jQuery( this ) ) });
		//s.edtr.on('focus','.dynamic-load-select-content',function(){ s.dy_l_s( jQuery( this ) ) });
	}
	
	s.chg_frm_sec = function( i_c ){
		if( !i_c.hasClass('active') ){
			par = i_c.parents('.settings-wrapper');
			par.find('.cc-form-section > header').removeClass('active');
			i_c.addClass('active');
			par.find('.section-wrapper.active').slideUp('medium' ,function(){
				jQuery( this ).removeClass('active');
				});
			i_c.next('.section-wrapper').slideDown('medium', function(){ jQuery(this).addClass('active');});
		}
	}
	
	s.dy_l_s = function( ci ){ // DYNAMIC LOAD SELECT
		var p = ci.parents('.dynamic-load-group');
		var l = p.find('.dynamic-load-select-content');
		var v = ci.val();
		if( v != l.data('type') ){
			//l.addClass('inactive');
			l.prop('disabled', true);
			l.attr('data-type', v );
			var src = widget_home_url+ci.data('source');
			l.find('option').not(':selected').remove();
			jQuery.get( src+'&post_type='+v , function( data ) {
				l.append( data );
				l.prop('disabled', false);
			});
		}
	}
	
	//s.act_n = function( ic ){ // ACTIVATE NEXT
		//var p = ic.parents('.activate-group');
		//p.find('select.inactive, input.inactive').removeClass('inactive');
		//ic.removeClass( 'activate-next');
	//}
	
	s.be();
}