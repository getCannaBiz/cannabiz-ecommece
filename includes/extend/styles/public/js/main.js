(function($) { 
	'use strict';

	/**
	 * Product Quick View
	 */
	
	$('.wpdshortcode').magnificPopup({
		type:'inline',
		midClick: true,
		gallery:{
			enabled:true
		},
		delegate: 'a.wpb_wl_preview',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		},
	  	closeOnContentClick: false,
	});

	/**
	 * product image lightbox
	 */

	$("[data-fancybox]").fancybox();

})(jQuery);

(function($) { 
	'use strict';

	/**
	 * Product Quick View
	 */
	
	$('.carousel-item').magnificPopup({
		type:'inline',
		midClick: true,
		gallery:{
			enabled:true
		},
		delegate: 'a.wpb_wl_preview',
		removalDelay: 500, //delay removal by X to allow out-animation
		callbacks: {
		    beforeOpen: function() {
		       this.st.mainClass = this.st.el.attr('data-effect');
		    }
		},
	  	closeOnContentClick: false,
	});

	/**
	 * product image lightbox
	 */

	$("[data-fancybox]").fancybox();

})(jQuery);

