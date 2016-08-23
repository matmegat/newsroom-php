<a id="view-log"><i class="icon-tasks"></i></a>
	
<p class="pre-statuses" id="in-progress">
	Your account is now being migrated. This can take several minutes.
	Please wait until it is complete.
</p>

<p class="pre-statuses clearfix hidden" id="complete">
	The migration process is now complete. <br />
	<a href="manage" class="btn">Continue to Dashboard</a>
</p>

<div id="advanced" class="hidden">
	<hr />
	<pre id="statuses"></pre>
</div>

<script>

$(function() {
	
	var has_view_log = false;
	var statuses = $("#statuses");
	var update_status = function() {
		$.get("common/migration/status_poll", function(res) {
			statuses.prepend(res.statuses.join(""));
			if (res.finished) {
				$("#in-progress").remove();
				$("#complete").removeClass("hidden");
				if (!has_view_log) {
					var location = $("#complete a").attr("href");
					window.location = location;
				}
			} else {
				setTimeout(update_status, 5000);
			}
		});
	};
	
	var view_log = $("#view-log");
	view_log.on("click", function() {
		$("#advanced").show();
		view_log.hide();		
		has_view_log = true;
	});
	
	setTimeout(update_status, 3000);
	
});
	
</script>
