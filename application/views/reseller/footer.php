	</div> <!-- wrapper -->
	
	<footer class="footer">
		<div class="container">
			<div class="row-fluid">
				<div class="span6">
					<ul>
						<li><a href="<?= $ci->conf('website_url') ?>">iNewsWire Control Panel</a></li>
					</ul>
				</div>
				<div class="span6">
					<ul class="pull-right">
						<li><a href="manage/dashboard">Dashboard</a></li>
						<li><a href="manage/publish">iPublish</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	
	<script src="<?= $vd->assets_base ?>lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?= $vd->assets_base ?>lib/bootstrap-select.min.js"></script>
	<script src="<?= $vd->assets_base ?>js/nav.js?<?= $vd->version ?>"></script>
	
	<script>
	
	$(function() { 
		
		$(".selectpicker").on_load_select();
		
	});
	
	</script>
	
</body>
</html>