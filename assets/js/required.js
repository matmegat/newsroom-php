(function() {
	
	var callbacks = {};
	window.required_js = {};
	window.required_js.add_callback = function(name, callback) {
		callbacks[name] = callback;
	};
	
	$(function() {
		
		var form = $(".required-form");
		var is_draft_save = false;
		var last_submit_button = null;
		var is_loader_test = false;
		
		var check_button_class = function() {
			
			var _this = $(this);
			last_submit_button = _this;
			var name = _this.attr("name");
			is_draft_save = name == "is_draft" || 
				name == "is_preview";
			
			if (name == "is_preview") 
				  var frame = "_blank";
			else var frame = "_self";			
			var _form = _this.parents("form");
			_form.attr("target", frame);
			
		};
		
		var allow_send = function() {
			// prevent the file upload again
			form.find(".real-file").val("");
		};
		
		// if we click save draft then allow save anyway
		form.find("button[type=submit]").on("click", check_button_class);
		form.find("input[type=submit]").on("click", check_button_class);
		
		form.on("submit", function(ev) {
			
			var _this = $(this);
			
			if (!is_loader_test)
			     _this.find(".required-error").remove();
			else _this.find(".required-loader-error").remove();
			
			var failed_loader_count = 0;
			var failed = false;
			
			if (window.CKEDITOR !== undefined && window.CKEDITOR.env.isCompatible)
				for (var idx in window.CKEDITOR.instances)
					window.CKEDITOR.instances[idx].updateElement();
			
			if (is_draft_save) {
				allow_send();
				return;
			}
				
			var required_fields = _this.find(".required");
			var callback_fields = _this.find(".required-callback");
			
			if (is_loader_test) {
				required_fields = required_fields.filter(".had-loader");
				required_fields.removeClass("had-loader")
			}
			
			for (var i = 0; i < required_fields.size(); i++) {
				
				var required_eq = required_fields.eq(i);
				var has_loader = required_eq.hasClass("loader");
				if (required_eq.val() && !has_loader) 
					continue;
				
				if (has_loader) {
					
					var required_error = $.create("div");
					required_error.addClass("alert alert-warning");
					required_error.addClass("required-error required-loader-error");
					error_html = "<strong>Patience!<\/strong> You" 
						+ " must wait until this task and/or validation"
						+ " is finished.";
					required_error.html(error_html);
					required_eq.before(required_error);
					required_eq.addClass("had-loader");
					failed_loader_count++;
					
				} else {
					
					var required_error = $.create("div");
					required_error.addClass("alert alert-error");
					required_error.addClass("required-error");
					error_html = "<strong>Required!<\/strong> The " 
						+ "<strong>" + required_eq.data("required-name") 
						+ "<\/strong> field must have a value.";
					required_error.html(error_html);
					required_eq.before(required_error);
					
				}
				
				failed = true;
				required_eq.on("change.required", function() {
					var _this = $(this);
					if (_this.val()) {
						_this.prev(".required-error").remove();
						required_eq.off("change.required");
					}
				});
				
			}
			
			for (var i = 0; i < callback_fields.size(); i++) {
				
				var callback_eq = callback_fields.eq(i);
				var value = callback_eq.val();
				if (callback_eq.hasClass("required") && !value)
					continue;
				
				var callback_data = callback_eq.data("required-callback");
				var callback_names = callback_data.split(/\s+/);
				
				for (var j = 0; j < callback_names.length; j++) {
					
					var callback_name = callback_names[j];
					var callback = callbacks[callback_name];
					if (callback === undefined) continue;
					var response = callback.call(null, value);
					if (response.valid) continue;
					
					var callback_error = $.create("div");
					callback_error.addClass("alert alert-error");
					callback_error.addClass("required-error");
					error_html = "<strong>Error!<\/strong> The " 
						+ "<strong>" + callback_eq.data("required-name") 
						+ "<\/strong> field " + response.text + ".";
					callback_error.html(error_html);
					callback_eq.before(callback_error);				
					failed = true;
					
				}
				
			}
			
			// bad performance to render again but shouldn't
			// happen often enough for it to matter
			if (!is_loader_test && failed_loader_count > 0) {
				setTimeout(function() {
					is_loader_test = true;
					last_submit_button.trigger("click");
					is_loader_test = false;
				}, 500);
			}			
			
			if (failed)
			{
				var first = $(".required-error").eq(0);
				var offset = first.offset();
				if (!offset) return false;
				var offset_top = offset.top - 50;
				$(window).scrollTop(offset_top);
				ev.preventDefault();
				return false;
			}
			
			allow_send();
			
		});
		
	});

})();