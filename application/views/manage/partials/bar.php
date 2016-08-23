<div class="progress-bar">
	<?php if ($bar->is_done()): ?>
	<div class="progress progress-success progress-striped">
		<div class="bar" style="width: 100%"></div>
	</div>
	<?php else: ?>
	<div class="progress progress-info progress-striped">
		<div class="bar" style="width: <?= $bar->percentage() ?>%"></div>
	</div>
	<div class="progress-stages">
		<h3>Still to complete:</h3>
		<ul>
			<?php foreach ($bar->stages() as $stage): ?>
			<?php if ($stage->is_done) continue; ?>
			<li>
				<?php if (empty($stage->info_link)): ?>
				<?= $stage->display_name ?>
				<?php else: ?>
				<a href="<?= $bar->newsroom()->url($stage->info_link) ?>">
					<?= $stage->display_name ?>
				</a>
				<?php endif ?>
			</li>
			<?php endforeach ?>
		</ul>
	</div>
	<?php endif ?>
</div>