(function($){  
	$(function(){

		// Masonry
		if (($("#msnry_block").length > 0)) {
			var container = document.querySelector('#msnry_block');
			var msnry = new Masonry( container, {
				itemSelector: '.news-item'
			});
		}

		// Fixed Submenu
		var page = $('.fixed-submenu');
		if (page.length > 0) {
			enquire.register('screen and (min-width:768px)', {
				match : function() {
					var	nav = page.find('.nav > .active'),
						submenu = nav.find('.navbar-submenu'),
						nav_height = nav.height(),
						doc_top = $(document).scrollTop();

					var fixed_submenu_method = function() {
						if (doc_top > nav_height ) {
							submenu.addClass('fixed');
						} else {
							submenu.removeClass('fixed');
						}
					}

					fixed_submenu_method();
					$(window).scroll(function() {
						doc_top = $(document).scrollTop()
						fixed_submenu_method();
					});
				}
			});
		}
		
	});
})(jQuery);
