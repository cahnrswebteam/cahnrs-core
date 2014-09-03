jQuery(document).ready(function(){
	jQuery('.cahnrs-slideshow').each( 
		function( index ) { window['c_s_'+index ] = new cahnrs_slideshow( jQuery( this ) );
		} );
	var cahnrs_core = new cahnrs_core_init();
	});

var cahnrs_slideshow = function( sh ){
	this.sh = sh;
	this.t = 'f';
	this.nv = this.sh.find('.cahnrs-slide-nav');
	this.sp = 6000;
	this.tm;
	var s = this;
	
	s.init_basic = function(){
		s.basic_e();
		s.auto_rot();
		//alert('fire');
	}
	
	s.auto_rot = function(){
		s.tm = setTimeout(function(){ s.h_aut_fd(); s.auto_rot();}, this.sp );
	}
	s.h_aut_fd = function(){
		var c_sld = s.sh.find('.cahnrs-slide.current-slide');
		var n_sld = s.n_sld( c_sld );
		s.fd_sld( c_sld, n_sld );
		if( s.nv.length > 0 ) s.h_chg_nav( n_sld );
	}
	s.h_chg_nav = function( n_sld ){
		var id = n_sld.index();
		s.nv.find('a').eq( id ).addClass('current-slide').siblings().removeClass('current-slide');
	}
	
	s.fd_sld = function( c_sld , n_sld ){
		n_sld.addClass('fade-next');
		n_sld.fadeIn(2000, function(){
			c_sld.removeClass('current-slide').hide();
			n_sld.addClass('current-slide').removeClass('fade-next');
			});
	}
	
	s.n_sld = function( c_sld ){
		var ns = c_sld.next('.cahnrs-slide');
		ns = ( ns.length > 0 )? ns : s.sh.find('.cahnrs-slide' ).first();
		return ns;
	}
	
	s.basic_e = function(){
		s.nv.on('click', 'a', function( event ){ event.preventDefault();});
	}
	
	if( sh.hasClass('slideshow-basic') && sh.find('.cahnrs-slide').length > 1 ) s.init_basic();
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