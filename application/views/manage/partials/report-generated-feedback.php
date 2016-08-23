<div class="alert alert-success">
	<strong>Generated!</strong> The report will now be <a href="<?= $vd->download_url ?>">downloaded</a>.
</div>

<script>

$(function() {
	
	setTimeout(function() {
		window.location = <?= json_encode($vd->download_url) ?>;
	}, 1000);
	
});
	
</script>