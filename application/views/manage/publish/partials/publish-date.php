<li>
	<input class="span12 in-text datepicker" id="publish-date" type="text" 
		data-date-format="yyyy-mm-dd hh:ii" name="date_publish" 
		value="<?= @$vd->m_content->date_publish_str ?>"
		placeholder="Publish Date" />
	<script>
	
	$(function() {
		
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 
			nowTemp.getDate(), 0, 0, 0, 0);
		
		var publish_date = $("#publish-date")
		
		publish_date.datetimepicker({
			startDate: now,
			autoclose: true,
			todayBtn: true,
			minView: 1,
		});
		
		publish_date.on("changeDate", function(ev) {
			ev.date.setMinutes(0);
		});
		
	});
	
	</script>
</li>

<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-datetimepicker.css" />	
<script src="<?= $vd->assets_base ?>lib/bootstrap-datetimepicker.js"></script>