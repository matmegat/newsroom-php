$(function() {
	
	var html = $(document.body.parentNode);
	
	$(window).on("blur", function() {
		html.removeClass("alt-down");
		html.removeClass("ctrl-down");
		html.removeClass("shift-down");
	});
	
	$(document).on("keydown", function(ev) {
		if (ev.which == 16) html.addClass("shift-down");
		if (ev.which == 17) html.addClass("ctrl-down");
		if (ev.which == 18) html.addClass("alt-down");
	});
	
	$(document).on("keyup", function(ev) {
		if (ev.which == 16) html.removeClass("shift-down");
		if (ev.which == 17) html.removeClass("ctrl-down");
		if (ev.which == 18) html.removeClass("alt-down");
	});
	
	// view tooltips on hover
	if ($.fn.tooltip !== undefined)
	$(".tl").tooltip().on("click", function() {
		if ($(this).attr("href") === "#")
			return false;
	});
	
	// enable click of radio container box 
	$(document).on("click", ".radio-container-box", function() {
		var input = $(this).find("input[type=radio]");
		if (input.is(":disabled")) return;
		input.prop("checked", true).trigger("change");
	});
	
	// enable click of checkbox container box 
	$(document).on("click", ".checkbox-container-box", function() {
		var input = $(this).find("input[type=checkbox]");
		if (input.is(":disabled")) return;
		input.prop("checked", !input.is(":checked"))
			.trigger("change");
	});
	
});

// parse comma delim values like tags
$.parse_comma_delim = function(str) {
	var exploded = str.split(",");
	var listed = [];		
	for (var i = 0; i < exploded.length; i++) 
		if ((exploded[i] = $.trim(exploded[i]))) 
			if (listed.indexOf(exploded[i]) < 0)
				listed.push(exploded[i]);				
	return listed;
};

(function() {
	
	var rate_limit_call = function(func, context) {
		return function() {
			func.__rate_limit = false;
			func.call(context);
		};		
	};
	
	var rate_limit = function(func, time, context) {
		if (func.__rate_limit === true) return;
		func.__rate_limit = true;
		var timed = rate_limit_call(func, context);
		window.setTimeout(timed, time);
	};
	
	window.rate_limit = rate_limit;
	
})();