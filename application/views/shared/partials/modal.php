<div id="<?= $id ?>" class="eob-modal modal hide fade" tabindex="-1" role="dialog" aria-hidden="true"
	style="width: <?= ($width + 40) ?>px; height: <?= ($height + 81) ?>px; margin-left: -<?= (($width + 40) / 2) ?>px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
			<i class="icon-remove"></i>
		</button>
		<!-- the nbsp is required to show this -->
		<h3><?= $vd->esc($title) ?> &nbsp;</h3>
	</div>
	<div class="modal-body">
		<div class="modal-content" style="width: <?= $width ?>px; height: <?= $height ?>px">
			<?= $content ?>
		</div>
	</div>
</div>

<script>

$(function() {

	var modal = $("#<?= $id ?>");
	modal.modal({ show: <?= json_encode($as) ?> });

});

</script>