jQuery(document).ready(function(){
	jQuery('.cahnrs-slideshow').each( 
		function( index ) { window['c_s_'+index ] = new init_cahnrs_slideshow( jQuery( this ), index );
		} );
	var cahnrs_core = new cahnrs_core_init();
	});

var init_cahnrs_slideshow = function( shw , index ){
	this.shw = shw;
	this.i = index;
	this.auto = ( this.shw.data('auto') )? true : false;
	this.spd =  ( this.shw.data('spd') )? this.shw.data('spd') : 6000;
	console.log( this.spd );
	this.delay = ( this.shw.data('delay') )? this.shw.data('delay') * index : 1000 * index;
	this.pager = ( this.shw.data('pager') )? true : false;
	this.pager_type = ( this.shw.data('pagertype') )? this.shw.data('pagertype') : 'button';
	this.transpd = ( this.shw.data('transpd') )? this.shw.data('transpd') : 1000;
	this.csld = ( this.shw.find('.currentslide').length > 0 )? 
		this.shw.find('.currentslide').first() : this.shw.find('.cahnrs-slide').first();
	this.nsld = false;
	this.dir = false;
	this.timr = false;
	this.fx = ( this.shw.data('fx') )? this.shw.data('fx') : 'slideHorz';
	var sh = this;
	
	this.dyn_ld = function(){
		var nld = sh.shw.find('.incomplete');
		if( nld.length <= 0 ) return false;
		nld.each( function(){
			var c = jQuery( this );
			var src = c.find('img , iframe');
			src.on('load',function(){
				c.removeClass('incomplete');
				sh.shw.parent().find('.pager').append( sh.bld_icn( false ) ); 
			});
			src.attr('src', nld.data('src'));
		});
	}
	
	this.st_nsld = function( sld ){
		if( 'auto' == sld ){
		sh.nsld = ( sh.csld.next('.cahnrs-slide').length > 0 )? 
			sh.csld.next( '.cahnrs-slide' ) : shw.find('.cahnrs-slide').first();
		} else {
			sh.nsld = shw.find('.cahnrs-slide').eq( sld );
		}
	}
	
	this.st_dir = function( is_auto ){
		sh.dir = ( sh.nsld.index() > sh.csld.index() || is_auto  )? 1 : -1;
	}
	
	this.nv_sld = function( index ){
		if( sh.shw.hasClass('inslide') ) return false;
		window.clearTimeout( sh.tmr );
		sh.st_nsld( index );
		sh.st_dir( false );
		sh.chg_sld();
	}
	
	this.sld_rot = function(){
		if( sh.shw.hasClass('paused') ){
			sh.tmr = window.setTimeout(function(){ sh.chg_sld() }, 2000 );
		} else {
			sh.st_nsld('auto'); sh.st_dir( true );
			sh.chg_sld();
		}
	}
	
	
	this.chg_sld = function(){
		
		if( sh.pager ) {
			var pg = sh.shw.find('.pager a').eq( sh.nsld.index() );
			pg.addClass('activeslide').siblings().removeClass('activeslide');
		}
		if( sh.nsld.index() == sh.csld.index() ){
			sh.tmr = window.setTimeout(function(){ sh.chg_sld() }, 2000 );
		} else {
			switch( sh.fx ){
				case 'slideHorz':
					sh.slideHorz();
					break;
			} 
		}
	}
	
	sh.slideHorz = function(){
		sh.shw.addClass('inslide'); 
		var lft = 100 *  sh.dir;
		sh.nsld.css('left', lft+'%');
		sh.nsld.animate( { left : '0px' }, sh.transpd , function(){
			});
		sh.csld.animate( { left : ( lft * -1 ) + '%' }, sh.transpd , function(){
			sh.csld.removeClass('currentslide');
			sh.nsld.addClass('currentslide');
			sh.csld = sh.nsld;
			sh.tmr = window.setTimeout(function(){ sh.sld_rot(); }, sh.spd );
			sh.shw.removeClass('inslide');
			});
	}
	
	sh.bld_icn = function( state ){
		var is_active = ( state )? ' activeslide' : '';
		switch( sh.pager_type ){
			case 'button':
				return '<a href="#" class="'+is_active+'"></a>';
				break;
		}
	}
	
	sh.bld_pager = function(){
		var slds = sh.shw.children(':not(.incomplete,.pager,.show-nav)');
		var icns = new Array();
		slds.each( function( index ){
			var cs = jQuery(this);
			var is_current = ( index == 0 )? true : false;
			icns.push( sh.bld_icn( is_current ) );
		});
		sh.shw.append( '<div class="pager">'+icns.join('')+'</div>' );
		sh.pager = sh.shw.parent().find('.pager');	
		sh.pager.on('click' , 'a', function(){
			sh.nv_sld( jQuery(this).index() );
			});
	}
	
	if( sh.auto ) window.setTimeout(function(){ 
		sh.tmr = window.setTimeout(function(){ sh.sld_rot(); }, sh.spd ); 
	}, sh.delay * sh.i );
	
	if( sh.pager ) sh.bld_pager();
	
	jQuery(window).load( function(){ sh.dyn_ld() });

}

var cahnrs_core_init = function(){
	var s = this;
	/***********************************
	** FAQ MODULE **
	************************************/
	s.init_faq = function(){
		var sf = this;
		jQuery('.cahnrs-core-faq > a').on('click',function( event ){ 
			event.preventDefault(); sf.hdl_faq( jQuery( this ) )});
		
		sf.hdl_faq = function( ic ){
			if( ic.hasClass('active') ){
				ic.next('.cc-content').slideUp('medium');
				ic.removeClass('active');
			} else {
				ic.next('.cc-content').siblings('.cc-content').slideUp('medium');
				ic.addClass('active').siblings('a').removeClass('active');
				ic.next('.cc-content').slideDown('medium');
			}
		}
	}
	/***********************************
	** AZINDEX MODULE **
	************************************/
	s.init_az = function(){
		var sf = this;
		jQuery('.cahnrs-azindex-nav.dynamic-az > a').on('click',function( event ){ 
			event.preventDefault(); sf.hdl_az( jQuery( this ) )});
		
		sf.hdl_az = function( ic ){
			if( ic.hasClass('active') ){
				var cls = '.column-group-'+ic.text();
				var group = ic.parents('.pagebuilder-item').find( cls );
				group.addClass('selected').siblings().removeClass('selected');
				ic.addClass('selected').siblings().removeClass('selected');
			}
			
			//if( ic.hasClass('active') ){
				//ic.next('.cc-content').slideUp('medium');
				//ic.removeClass('active');
			///} else {
				//ic.next('.cc-content').siblings('.cc-content').slideUp('medium');
				//ic.addClass('active').siblings('a').removeClass('active');
				//ic.next('.cc-content').slideDown('medium');
			//}
		}
	}
	/***********************************
	** TEST AND ACTIVATE MODULES **
	************************************/
	if( jQuery('.cahnrs-core-faq').length > 0 ) s.init_faq();
	if( jQuery('.cahnrs-azindex-nav.dynamic-az').length > 0 ) s.init_az();
}