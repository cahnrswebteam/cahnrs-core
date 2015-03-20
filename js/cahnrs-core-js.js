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
	this.up = ( this.shw.data('up') )? this.shw.data('up') : 1; 
	this.delay = ( this.shw.data('delay') )? this.shw.data('delay') * index : 1000 * index;
	this.pager = ( this.shw.data('pager') )? true : false;
	this.pager_type = ( this.shw.data('pagertype') )? this.shw.data('pagertype') : 'button';
	this.adv = ( this.shw.data('adv') )? true : false;
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
		if( 'next' == sld ){
			var ns = sh.shw.find('.currentslide').last().next('.cahnrs-slide');
			if( ns.length > 0 ){
				sh.nsld = ns;
			} else if( 'threeup' == sh.fx ){
				ns = sh.shw.find('.cahnrs-slide').first();
				sh.shw.find('.currentslide').last().after( ns.clone() );
				sh.nsld = sh.shw.find('.cahnrs-slide').last();
				ns.remove();
			} else {
				sh.nsld = sh.shw.find('.cahnrs-slide').first();
			}
		}
		else if( 'prev' == sld ){
			var ps = sh.csld.prev('.cahnrs-slide');
			if( ps.length > 0 ){
				sh.nsld = ps;
			} else if( 'threeup' == sh.fx ){
				ps = sh.shw.find('.cahnrs-slide').last();
				sh.shw.find('.currentslide').first().before( ps.clone() );
				sh.nsld = sh.shw.find('.cahnrs-slide').first();
				ps.remove();
			} else {
				sh.nsld = sh.shw.find('.cahnrs-slide').last();
			}
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
			sh.st_nsld('next'); sh.st_dir( true );
			sh.chg_sld();
		}
	}
	this.sld_nav = function( dir ){
		if( dir == -1 ) { 
			sh.st_nsld('prev'); 
		} else {
			sh.st_nsld('next');
		}
		sh.st_dir( false );
		sh.chg_sld();
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
				case 'threeup':
					sh.threeup();
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
			if( sh.auto ) sh.tmr = window.setTimeout(function(){ sh.sld_rot(); }, sh.spd );
			sh.shw.removeClass('inslide');
			});
	}
	
	sh.threeup = function(){
		sh.shw.addClass('inslide');
		var chg = sh.nsld.width() / sh.shw.parent().width();
		sh.nsld.addClass('currentslide');
		var lft = ( -100 * chg *  sh.dir) + '%';
		if( -1 == sh.dir ) { 
			sh.shw.css('left', ( -100 * chg ) + '%' );
			lft = 0; 
		}
		//var lft = ( sh.dir > 0 )? '100%': '-33%';
		//sh.nsld.css('left', lft );
		//nslft = ( sh.dir > 0 )? '66.66%' : 0;
		sh.shw.animate( { 'left' : lft }, sh.transpd , function(){
			if( -1 == sh.dir ) {
				sh.shw.find('.currentslide').last().removeClass('currentslide');
			} else {
				sh.shw.find('.currentslide').first().removeClass('currentslide');
			}
			sh.shw.css('left','0');
			sh.csld = sh.shw.find('.currentslide').first();
			if( sh.auto ) sh.tmr = window.setTimeout(function(){ sh.sld_rot(); }, sh.spd );
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
	
	sh.bld_nv = function(){
		sh.shw.after( '<a class="slide-nav next-nav" href="#" ></a>' );
		sh.shw.after( '<a class="slide-nav prev-nav" href="#" ></a>' );
		sh.shw.parent().on('click', '.slide-nav.next-nav', function( event ){ event.preventDefault();sh.sld_nav(1)});
		sh.shw.parent().on('click', '.slide-nav.prev-nav', function( event ){event.preventDefault();sh.sld_nav(-1)});
		sh.shw.parent().hover( 
			function(){ sh.shw.parent().find('.slide-nav').fadeTo( 'medium','0.5' )},
			function(){ sh.shw.parent().find('.slide-nav').fadeTo( 'medium','0' )}
			);
	}
	
	if( sh.auto ) window.setTimeout(function(){ 
		sh.tmr = window.setTimeout(function(){ sh.sld_rot(); }, sh.spd ); 
	}, sh.delay * sh.i );
	
	if( sh.pager ) sh.bld_pager();
	if( sh.adv ) sh.bld_nv();
	
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
	window.addEventListener('message', s.receiveMessage );
	s.receiveMessage = function(e){
		alert('fire');
	}
	/***********************************
	** LIGHTBOX MODULE **
	************************************/
	s.init_lb = function(){
		var lb = this;
		jQuery('body').on('click','.cahnrs-frame-close, #cahnrs-lightbox-bg, #cahnrs-lightbox-frame-wrap' , function( event ){
			event.preventDefault();
			lb.hid_frm( jQuery( this ) );
			});
		jQuery('body').on('click','a.cahnrs-lightbox-item', function( event ){
			event.preventDefault();
			lb.shw_frm( jQuery( this ) );
			});
		jQuery('body').on('click','.video-image-wrapper.has_video', function(){
			lb.shw_inpg( jQuery( this ) );
			//jQuery( this ).find('img').css('z-index': 80);
			//lb.shw_frm( jQuery( this ) );
			});
		lb.add_lb = function(){
			var lb_html = new Array();
			lb_html.push('<div id="cahnrs-lightbox-bg"></div>');
			lb_html.push('<div id="cahnrs-lightbox-frame-wrap">');
				lb_html.push('<div id="cahnrs-lightbox-frame">');
					lb_html.push('<header><a href="#" class="cahnrs-frame-close">Close</a></header>');
					lb_html.push('<div class="cahnrs-frame-content">');
						lb_html.push('<iframe class="cahnrs-iframe" src="about:blank" height="200px" width="100%" frameborder="0" allowfullscreen ></iframe>')
					lb_html.push('</div>');
				lb_html.push('</div>');
			lb_html.push('</div>');
			jQuery('body').append( lb_html.join('') );
			jQuery('#cahnrs-lightbox-frame .cahnrs-iframe').load(function(){});
		}
		lb.hid_frm = function(){
			var frm_w = jQuery('#cahnrs-lightbox-frame-wrap');
			var frm = jQuery('#cahnrs-lightbox-frame');
			var bg = jQuery('#cahnrs-lightbox-bg');
			frm_w.hide();
			bg.fadeOut('fast');
			frm.find('.cahnrs-iframe').attr('src','about:blank');
		}
		lb.shw_frm = function( ic ){
			if( typeof ic.attr('href') === typeof undefined && ic.attr('href') === false ) return false;
			var frm_w = jQuery('#cahnrs-lightbox-frame-wrap');
			var frm = frm_w.find('#cahnrs-lightbox-frame');
			var ifrm = frm.find('.cahnrs-iframe');
			var bg = jQuery('#cahnrs-lightbox-bg');
			var ic_type = ic.data('type');
			frm.addClass( ic_type );
			bg.fadeIn('medium');
			frm_w.fadeIn('medium');
			  lb.set_ht();
			  if( typeof ic_type !== typeof undefined && ic_type !== false ){
				  switch( ic_type ){
					  case 'video':
						  lb.shw_vid( ifrm , ic );
						  break;
					  default:
						  lb.shw_cont( ifrm , ic );
						  break;
				  }
			  } 
		}
		lb.shw_vid = function( ifrm , ic ){
			var vid = ic.data('vid');
			var wdth = ifrm.width(); 
			ifrm.height( ( wdth * 0.5625 ) + 'px' );
			ifrm.attr('src', '//www.youtube.com/embed/'+ vid +'?autoplay=1' );
		}
		lb.shw_inpg = function( ic ){
			var vid = ic.data('vid');
			vid = '<iframe src="//www.youtube.com/embed/'+vid+'?autoplay=1" frameborder="0" allowfullscreen></iframe>';
			ic.append( vid );
		}
		
		lb.shw_cont = function( ifrm , ic ){
			var ic_hrf = ic.attr('href');
			var ic_ser = ic.data('serviceurl');
			ifrm.load(function(){
				var ht = ( this.contentWindow.document.body.offsetHeight + 20 ) + 'px';
				ifrm.height( ht );
				ifrm.hide();
				ifrm.fadeIn('fast');
				ifrm.unbind('load');
				});
			ifrm.attr('src', ic_ser+'?service=iframe&src='+encodeURIComponent( ic_hrf ) );
		}
		lb.set_cont_ht = function(){
			
		}
		lb.set_ht = function(){
			var  wht = jQuery(window).scrollTop();
			jQuery('#cahnrs-lightbox-frame-wrap').css( 'top' , ( wht + 100 ) +'px' );
			}
		if( jQuery('#cahnrs-lightbox-frame').length <= 0 ) lb.add_lb();
	}
	/***********************************
	** CAMPAIGN PROGRESS MODULE **
	************************************/
	s.init_cp = function(){
		var progress = parseInt(jQuery('.progress-amount').attr('data-progress')),
				percent  = ~~( ( progress / 250 ) * 100 ),
				dollars  = ~~( progress + 1 );
		jQuery( '.meter-progress' ).animate({
			height: percent+"%"
			}, 2500
		);
		jQuery({ countNum: jQuery('.progress-amount').text() }).animate({
			countNum: dollars
			},
			{ duration: 2500, easing:'linear', step: function() {
				jQuery('.progress-amount').text( Math.floor( this.countNum ) );
			}
		});
	}
	/***********************************
	** TEST AND ACTIVATE MODULES **
	************************************/
	if( jQuery('.cahnrs-core-faq').length > 0 ) s.init_faq();
	if( jQuery('.cahnrs-azindex-nav.dynamic-az').length > 0 ) s.init_az();
	if( jQuery('a.cahnrs-lightbox-item').length > 0 ) s.init_lb();
	if( jQuery('.meter-container').length > 0 ) s.init_cp();
	
}