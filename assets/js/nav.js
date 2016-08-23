$(function() {
	
	var base_url = $("base").attr("href");
	var absolute_url = window.location.href;
	var local_url = absolute_url.substr(base_url.length);
	
	var find_nav_selected = function() {
		var $this = $(this);
		var pattern_str = $this.data("on");
		if (!pattern_str) return;
		var pattern = new RegExp(pattern_str);
		if (pattern.test(local_url)) {
			$this.parent().addClass("active");
			return false;
		}
	};
	
	$(".nav-activate").each(function() {
		$(this).find("a").each(find_nav_selected);
	});
	
});