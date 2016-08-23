<div id="report-generate">	
	
	<div class="creating">
		<h2>Creating Report.</h2>
		<h3>This process can take several minutes.</h3>
		<img src="<?= $vd->assets_base ?>im/loader-line-large.gif" />
	</div>
	
</div>

<script>

setTimeout(function() {

	var return_url = <?= json_encode($vd->return_url) ?>;
	var generate_url = <?= json_encode($vd->generate_url) ?>;
	$.post(generate_url, { indirect: 1 }, function() {
		
		window.location = return_url;
		
	});
	
}, 2000);

</script>