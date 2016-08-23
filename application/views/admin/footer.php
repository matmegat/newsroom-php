	</div> <!-- wrapper -->
	
	<?php if ($ci->eob): ?>
	<div id="eob">
		<?php foreach ($ci->eob as $eob) 
			echo $eob; ?>
	</div>
	<?php endif ?>
	
	<script src="<?= $vd->assets_base ?>lib/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?= $vd->assets_base ?>lib/jquery.browser.mobile.js"></script>
	<script src="<?= $vd->assets_base ?>lib/bootstrap-select.min.js"></script>
	<script src="<?= $vd->assets_base ?>lib/jquery.lockfixed.js"></script>
	<script src="<?= $vd->assets_base ?>js/nav.js?<?= $vd->version ?>"></script>
	
	<script>
	
	$(function() { 
		
		$(".selectpicker").on_load_select();
		
	});
	
	</script>
	
</body>
</html>