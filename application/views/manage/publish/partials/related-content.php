<?php if (isset($vd->m_content)): ?>
<?php $related = $vd->m_content->get_related(); ?>
<?php else: ?>
<?php $related = array(); ?>
<?php endif ?>
<section class="form-section related-content related-materials">
	<h2>Related Content</h2>
	<ul>
		<li>
			<div class="row-fluid">
				<input type="text" class="in-text span12" 
					id="related-search"
					placeholder="Search for Content" />
			</div>
		</li>
		<li>
			<div class="rm-tabs-block">
				<ul class="nav nav-tabs" id="related-tabs-bar">					
					<li class="active">
					   <a data-toggle="tab" href="#rm-all" id="#rm-all-button">All</a></li>
					<li><a data-toggle="tab" data-type="pr" href="#rm-press-releases">Press Releases</a></li>
					<li><a data-toggle="tab" data-type="news" href="#rm-news">News</a></li>
					<li><a data-toggle="tab" data-type="event" href="#rm-events">Events</a></li>
					<li><a data-toggle="tab" data-type="image" href="#rm-images">Images</a></li>
					<li><a data-toggle="tab" data-type="audio" href="#rm-audio">Audio</a></li>
					<li><a data-toggle="tab" data-type="video" href="#rm-video">Video</a></li>
					<li class="pull-right">
						<a data-toggle="tab" data-selected="1" href="#rm-selected" 
							id="#rm-selected-button">Selected</a></li>
				</ul>
				<div class="tab-content" id="related-tabs">					
					<div id="rm-all" class="tab-pane active">
						<ul class="rm-list"></ul>
					</div>
					<div id="rm-selected" class="tab-pane">
						<ul class="rm-list">
							<?php foreach ($related as $content): ?>
							<li class="selected" data-selected="1" data-related-id="<?= $content->id ?>">
								<span class="rm-title"><?= $vd->esc($content->title) ?> 
									(<?= $vd->esc(Model_Content::short_type($content->type)) ?>)</span>
								<span class="rm-link"><a><i class="icon-remove"></i></a></span>
								<input type="hidden" name="related[]" value="<?= $content->id ?>" />
							</li>
							<?php endforeach ?>
						</ul>
					</div>
					<div id="rm-press-releases" class="tab-pane"><ul class="rm-list"></ul></div>
					<div id="rm-news" class="tab-pane"><ul class="rm-list"></ul></div>
					<div id="rm-events" class="tab-pane"><ul class="rm-list"></ul></div>
					<div id="rm-images" class="tab-pane"><ul class="rm-list"></ul></div>
					<div id="rm-audio" class="tab-pane"><ul class="rm-list"></ul></div>
					<div id="rm-video" class="tab-pane"><ul class="rm-list"></ul></div>
				</div>
			</div>
		</li>
	</ul>
</section>

<script>
	
$(function() {
	
	var related_tabs = $("#related-tabs");
	var related_search = $("#related-search");
	var related_results = $("#rm-all ul");	
	var selected_results = $("#rm-selected ul");
	var all_button = $("#rm-all-button");
	var current_opt_type = null;
	var current_search = null;	
	
	var opt_selected = false;
	var opt_type = null;
	
	var perform_search_render = function(results) {
		
		if (opt_selected) return;
		if (current_search != results.search ||
		    current_opt_type != results.type)
			return;
		
		related_search.removeClass("active");
		related_results.empty();
		
		if (!results.data.length) {
			
			var row = $.create("li");
			row.addClass("no-results");
			row.text("No Results Found");
			related_results.append(row);
			return;
			
		}
		
		for (var idx in results.data) {
			
			var result = results.data[idx];
			var row = $.create("li");
			var span_title = $.create("span");
			var span_link = $.create("span");
			var a_link = $.create("a");
			var i_link = $.create("i");
			
			var is_selected = !!(selected_results.find("li")
				.filter(function() {
					return $(this).data("related-id") == result.id;
				}).size());
				
			row.toggleClass("selected", is_selected);
			row.data("selected", is_selected);
			row.data("related-id", result.id);
			
			var title = result.title;
			if (!current_opt_type) title += " (" + result.type + ")";			
			var i_class = is_selected ? "icon-remove" : "icon-plus";
			
			span_title.text(title);
			span_title.addClass("rm-title");
			span_link.addClass("rm-link");
			i_link.addClass(i_class);
			span_link.append(a_link);
			a_link.append(i_link);
			row.append(span_title);
			row.append(span_link);	
					
			related_results.append(row);
			
		}
		
	};
	
	var perform_search = function(search, type) {
		
		var post_data = {};
		post_data.empty_limit = 5;
		post_data.limit = 10;
		post_data.allow_empty = true;
		post_data.terms = search;
		post_data.type = type;
		
		related_search.addClass("active");
		$.post("manage/publish/search/related", 
			post_data, perform_search_render);
		
	};
	
	var schedule_search_check = function() {
		
		if (opt_selected) {
			current_opt_type = null;
			current_search = null;
			return;
		}
		
		var search = related_search.val();
		if (current_search == search && 
		    opt_type == current_opt_type) return;
		perform_search(search, opt_type);
		current_opt_type = opt_type;
		current_search = search;
		
	};
	
	var schedule_search = function() {		
		
		setTimeout(schedule_search_check, 250);
		
		if (!opt_selected) return;
		all_button.tab("show");
		opt_selected = false;
		
	};
	
	related_tabs.on("click", ".rm-list li", function() {
		
		var _this = $(this);
		if (_this.hasClass("no-results")) return;
		var is_selected = !!(_this.data("selected"));
		var related_id = _this.data("related-id");
		
		if (is_selected) {
			
			selected_results.find("li").filter(function() {
				return $(this).data("related-id") == related_id;
			}).remove();				
			
			_this.data("selected", "");
			_this.find("i").attr("class", "icon-plus");
			_this.removeClass("selected");
					
		} else {
			
			var input = $.create("input");
			input.attr("type", "hidden");
			input.attr("name", "related[]");
			input.val(related_id);
			
			_this.find("i").attr("class", "icon-remove");
			_this.addClass("selected");		
			_this.data("selected", "1");
			
			var clone = _this.clone(true);
			clone.append(input);
			
			selected_results.append(clone);			
					
		}
		
	});
	
	related_search.on("keypress", schedule_search);
	related_search.on("change", schedule_search);
	
	$("#related-tabs-bar a").on("shown", function() {
		
		var _this = $(this);
		related_results = $(_this.attr("href")).children("ul");
		opt_selected = !!(_this.data("selected"));
		opt_type = _this.data("type");
		if (!opt_type) opt_type = null;
		schedule_search_check();
		
	});
	
	schedule_search_check();
	
});
	
</script>