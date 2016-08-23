		<?= Video_Guide::render() ?>
	
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
						<li><a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/publish">iPublish</a></li>
						<li><a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/contact">iContacts</a></li>
						<li><a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/analyze">iAnalyze</a></li>
						<li><a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/newsroom">iNewsroom</a></li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
	
	<?php if ($ci->eob): ?>
	<div id="eob">
		<?php foreach ($ci->eob as $eob) 
			echo $eob; ?>
	</div>
	<?php endif ?>
	
	<script src="<?= $vd->assets_base ?>lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?= $vd->assets_base ?>lib/jquery.browser.mobile.js"></script>
	<script src="<?= $vd->assets_base ?>lib/bootstrap-select.min.js"></script>
	<script src="<?= $vd->assets_base ?>lib/bootstrap-datepicker.js"></script>
	<script src="<?= $vd->assets_base ?>lib/jquery.lockfixed.js"></script>
	<script src="<?= $vd->assets_base ?>js/nav.js?<?= $vd->version ?>"></script>
	
	<?= $ci->load->view('partials/clickdesk') ?>
	
	<script>
	
	$(window).load(function() { 
		
		$(".selectpicker").on_load_select();
		
	});
	
	</script>
	
</body>
</html>