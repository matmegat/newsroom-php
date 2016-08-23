$(function() {

	(function() {
		var items_container = $("#ln-container");
		if (!items_container.hasClass("masonry")) return;
		items_container.imagesLoaded(function() {
			items_container.find("img").addClass("loaded");
			items_container.masonry({
				itemSelector: ".ln-block",
				gutter: 20
			});
		});
	})();
	
	$("a.email-obfuscated").on("click", function() {
		var _this = $(this);
		if (!_this.hasClass("email-obfuscated")) return;
		_this.removeClass("email-obfuscated");
		var value = _this.attr("href");
		value = value.replace(/^mailto:/, "");
		var chars = value.split("");
		chars.reverse();
		value = chars.join("");
		value = "mailto:" + value;
		_this.attr("href", value);
	});
	
	(function() {
		
		var _window = $(window);
		var _document = $(document);
		var _window_height = _window.height();
		var _limit_reached = false;
		var _request_active = false;
		var _list_container = $("#ln-container");
		var _loader = $("#ln-container-loader");
		if (!_list_container.size())
			return;
		
		var render_content = function(res) {
			
			_loader.hide();
			_request_active = false;			
			if (!res) return _limit_reached = true;
			var elements = $(res.data);
			_list_container.append(elements);
			if (_list_container.hasClass("masonry")) {
				elements.addClass("hidden")
				_list_container.imagesLoaded(function() {
					elements.removeClass("hidden");
					_list_container.find("img").addClass("loaded");
					_list_container.masonry("appended", elements);
				});
			}
			
		};
		
		var request_content = function() {
			
			if (_request_active) return;
			if (_limit_reached) return;
			_request_active = true;
			
			var offset = _list_container.children().size();
			if (offset === 0) return;
			
			_loader.show();
			var data = { partial: true, offset: offset };			
			$.get(null, data, render_content);
			
		};
		
		var perform_check = function() {
			
			var scrollTop = _window.scrollTop();
			if (_document.height() - scrollTop < 
			    _window_height * 2) {
				request_content();
			}
			
		};
		
		_window.on("scroll", perform_check);
		if (_document.height() < _window_height * 2) 
			request_content();
		
	})();
	
	$.fn.lightbox = function() {
		
		var create_box = function() {
			
			// hide elements that contain flash content
			$(".has-flash-content").addClass("lightbox-hidden");
			
			$("#lightbox").remove();
			var href = $(this).attr("href");
			var container = $.create("div");
			container.attr("id", "lightbox");
			var back = $.create("div");
			back.addClass("back");
			container.append(back);
			var boxz = $.create("div");
			boxz.addClass("boxz");
			var box = $.create("div");
			box.addClass("box");
			boxz.append(box);
			container.append(boxz);
			
			var cached = $.create("img");
			$(cached).on("load", function() {
				box.append(cached);
				box.addClass("loaded");
				cached.css("max-height", (($(window).height() - 20) * 0.8));
				cached.css("max-width", (($(window).width() - 20) * 0.8));
				box.css("height", cached.height());
				box.css("width", cached.width());
			});
			
			$(document.body).append(container);
			boxz.on("click", function() {
				$(".has-flash-content").removeClass("lightbox-hidden");
				container.remove();
			});
			
			setTimeout(function() {
				cached.attr("src", href);
				back.addClass("on");
				box.addClass("on");
			}, 0);
			
		};
		
		$(this).on("click", function() {
			create_box.call(this);
			return false;
		});
		
	};
	
	$(".use-lightbox").lightbox();
	
	(function() {
		
		var width = 640;
		var height = 480;
		
		var features = {
			directories: "no",
			location: "no",
			resizable: "yes",
			scrollbars: "no",
			status: "no",
			toolbar: "no"
		};
		
		$(".share-window").on("click", function(ev) {
			
			var _this = $(this);
			
			if (_this.data("width")) width = parseInt(_this.data("width"));
			if (_this.data("height")) height = parseInt(_this.data("height"));
						
			features.screenX = (window.screen.width / 2) - ((width / 2) + 10);
			features.screenY = (window.screen.height / 2) - ((height / 2) + 50);
			features.left = features.screenX;
			features.top = features.screenY;
			features.width = width;
			features.height = height;
			
			var features_arr = [];
			for (var idx in features)
				features_arr.push([idx, features[idx]].join("="))
			var features_str = features_arr.join(",");
			
			window.open(_this.attr("href"), "aa", features_str);
			ev.preventDefault();
			return false;
			
		});
		
	})();

});