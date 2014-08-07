jQuery(document).ready(function(){
	jQuery('.cahnrs-slideshow').each( 
		function( index ) { window['c_s_'+index ] = new cahnrs_slideshow( jQuery( this ) );
		} );
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