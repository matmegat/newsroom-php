// ckeditor => init_editor
(function(undefined) {
	
	var __elements = [];
	var __options = [];
	var __callbacks = [];
	var __loaded = false;
	var __load_started = false;
	
	window.init_editor = function(elements, options, callback) {
	
		var editor_loaded = function() {
			for (var idx in __elements)
				init_editor_elements(__elements[idx], __options[idx], __callbacks[idx]);
		};
		
		var init_editor_elements = function(elements, options, callback) {
			elements.each(function() {
				CKEDITOR.replace(this, options); 
				if (callback === undefined) return;
				callback.call(CKEDITOR.instances[this.id]);
			});
		};
		
		if (!__load_started) {
			__load_started = true;
			$(function() {
				cke_url = CKEDITOR_BASEPATH + "/ckeditor.js";
				var ajax_opt = { dataType: "script", cache: true, url: cke_url };
				$.ajax(ajax_opt).done(editor_loaded);
			});
		}
		
		if (!__loaded) {	
			__elements.push(elements);
			__options.push(options);
			__callbacks.push(callback);
			return;
		}
		
		init_editor_elements(elements, options, callback);
		return;
		
	};
	
})();

// ajax file upload
(function(undefined) {
	
	var xhr_create = function() {
		
		if (window.XMLHttpRequest !== undefined)
			return new window.XMLHttpRequest();
		if (!window.ActiveXObject) return null;
		try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (err) {}
		try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (err) {}
		return null;
		
	};
	
	$.fn.ajax_upload = function(ex_options) {
		
		if (window.FormData === undefined)
			return false;
		
		var options = {
			url: null,
			callback: null,
			fd: new window.FormData(),
			progress: null,
			data: {}
		};
		
		$.extend(options, ex_options);
		
		for (var i = 0; i < this[0].files.length; i++)
			options.fd.append(this.attr("name"), this[0].files[i]);
		
		for (var idx in options.data) {
			if ($.isArray(options.data[idx])) {
				for (var i = 0; i < options.data[idx].length; i++) 
					options.fd.append(idx + "[]", options.data[idx][i]);
			} else {
				options.fd.append(idx, options.data[idx]);
			}
		}
		
		$.ajax({
			url: options.url,
			data: options.fd,
			processData: false,
			contentType: false,
			type: "POST",
			success: options.callback,
			xhr: function() {
				var xhr = xhr_create();
				xhr.upload.addEventListener("progress", function(ev) {
 					if (options.progress && ev.lengthComputable) 
 						options.progress(ev);
				}, false);
				return xhr;
			}
		});
			
		return true;
		
	};
	
})();
	
$.fn.limit_length = function(length, status, status_number) {
	
	var _this = this;
	var is_real_keyboard = false;
	
	var _remain = function(value) {
		return length - value.length;
	};
	
	var _color = function(remain) {
		status.toggleClass("low-remain", remain < 0.025 * length)
	};
	
	_this.attr("maxlength", length);		
	_this.on("change", function() {
		var value = _this.val();
		if (_remain(value) < 0) {
			status_number.text(0);
			value = value.substr(0, length);
			_this.val(value);
			_color(0);
		} else {
			var remain = _remain(value);
			status_number.text(remain);
			_color(remain);
		}
	});
	
	_this.on("keypress", function(ev) {
		is_real_keyboard = true;
		var value = _this.val();
		if (ev.ctrlKey) return;
		if (ev.which === 0 || ev.which === 8) return;
		if (_remain(value) <= 0) return false;
	});
	
	_this.on("keyup", function() {
		var value = _this.val();
		var remain = _remain(value);
		status_number.text(remain);
		_color(remain);
	});
	
	_this.trigger("change");
	
};
	
// other funcs
$(function() {
	
	$("#nav-main .overview-link").on("click", function() {
		if ($(document.body.parentNode).hasClass("shift-down")) {
			window.location = $(this).attr("href");
			return false;
		}
	});
		
	var update_placeholder = function() {
		var _this = $(this);
		var has_value = !!($.trim(_this.val()));
		var placeholder = _this.next(".placeholder");
		placeholder.toggleClass("active", has_value);
	};
	
	var placeholders = $(".has-placeholder");
	placeholders.on("change", update_placeholder);
	placeholders.each(update_placeholder);
	
});