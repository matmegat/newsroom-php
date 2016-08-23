$(function() {
	
	// list filters remove
	$(".list-filters a.remove").click(function(ev) {
		var list_filter = $(this).parents(".list-filter");
		var pattern = new RegExp(list_filter.data("gstring"), "g");
		window.location = window.location.href.replace(pattern, "");
		ev.preventDefault();
		return false;
	});
	
	// add-filter-icon handler
	$(".add-filter-icon").click(function(ev) {
		var _this = $(this);
		var gstring = _this.data("gstring");
		if (!gstring) return;		    
		var url = window.location.href;
		if (!window.location.search) url = url + "?";
		url = url + gstring;
		window.location = url;
		ev.preventDefault();
		return false;
	});

});