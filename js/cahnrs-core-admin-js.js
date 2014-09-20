jQuery(document).ready(function(){ var cahnrs_core_widget_sett = new cahnrs_core_widget_settings();});

var cahnrs_core_widget_settings = function(){
	//this.edtr = jQuery( '#layout-editor');
	var s = this;
	
	s.be = function(){ // BIND EVENTS
		//s.edtr.on('change','.activate-next',function(){ s.act_n( jQuery( this ) ) });
		jQuery('body').on('change','.dynamic-load-select',function(){ s.dy_l_s( jQuery( this ) ) });
		jQuery('body').on('click','.cc-form-section > header,.cc-form-section-advanced > header ', function(){ s.chg_frm_sec( jQuery( this ) ) });
		jQuery('body').on('click','.settings-wrapper .cc-feed-settings label, .settings-wrapper .cc-form-feed-type label', function(){ 
			s.chg_set_ops( jQuery( this ) ) });
		jQuery('body').on('click','.settings-wrapper .action-add-selected', function( event ){ 
			event.preventDefault(); s.add_sel_item( jQuery( this ) ) });
		jQuery('body').on('click','.settings-wrapper .cc-inserted-items-wrap a', function( event ){ 
			event.preventDefault(); s.rmv_sel_itm( jQuery( this ) ) });
		//s.edtr.on('focus','.dynamic-load-select-content',function(){ s.dy_l_s( jQuery( this ) ) });
		
	}
	
	s.add_sel_item = function( i_c ){
		var par = i_c.parents('.settings-wrapper');
		var drp = par.find('.cc-select-content-drpdwn');
		var loc = par.find('.cc-inserted-items-wrap');
		if( drp.val() != 0 ){
			loc.prepend('<a href="#" data-id="'+drp.val()+'"><span>X</span>'+drp.find('option:selected').text()+'</a>' );
		}
		s.updt_sel_item( loc );
	}
	
	s.rmv_sel_itm = function( i_c ){
		var loc = i_c.parents('.cc-inserted-items-wrap');
		i_c.slideUp('fast', function(){
			i_c.remove();
			s.updt_sel_item( loc );
		});
	}
	
	s.updt_sel_item = function( par ){
		var items = new Array();
		par.find('a').each(function(){
			items.push( jQuery(this).data('id') );
		});
		par.find('input').val( items.join(',') )
	}
	
	s.chg_set_ops = function( i_c ){
		var sel_set = i_c.data('set');
		var inpt = i_c.next('input');
		inpt.prop('checked', true);
		i_c.addClass('active').siblings('label').removeClass('active');
		if( sel_set ){
			var par = i_c.parents('.settings-wrapper');
			var sec = par.find('.'+sel_set );
			if( sec.length > 0 ){
				sec.addClass('selected').siblings().removeClass( 'selected' );
			}
		}
	}
	
	s.chg_frm_sec = function( i_c ){
		if( !i_c.hasClass('active') ){
			par = i_c.parents('.settings-wrapper');
			par.find('.cc-form-section > header, .cc-form-section-advanced > header ').removeClass('active');
			i_c.addClass('active');
			par.find('.section-wrapper.active').slideUp('medium' ,function(){
				jQuery( this ).removeClass('active');
				});
			i_c.next('.section-wrapper').slideDown('medium', function(){ jQuery(this).addClass('active');});
		}
	}
	
	s.dy_l_s = function( ci ){ // DYNAMIC LOAD SELECT
		var site = ci.parents('.form-content').find('.cc-adv-feed-src');
		site = ( site.length > 0 && site.val() )? '&site='+encodeURIComponent( site.val() ):'';
		var p = ci.parents('.cc-form-section');
		var l = p.find('.cc-select-content-drpdwn');
		var v = ci.val();
		
		//l.addClass('inactive');
		l.prop('disabled', true);

		var src = widget_home_url+ci.data('source');
		l.find('option').not(':selected').remove();
		jQuery.get( src+'&post_type='+v+site , function( data ) {
			l.append( data );
			l.prop('disabled', false);
		});
		
	}
	
	//s.act_n = function( ic ){ // ACTIVATE NEXT
		//var p = ic.parents('.activate-group');
		//p.find('select.inactive, input.inactive').removeClass('inactive');
		//ic.removeClass( 'activate-next');
	//}
	
	s.be();
}