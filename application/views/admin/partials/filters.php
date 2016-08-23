<?php if ($vd->filters): ?>
<div class="list-filters">
	<div class="list-filter-header">filters active</div>
	<?php foreach ($vd->filters as $filter): ?>
	<div class="list-filter" data-gstring="<?= $filter->gstring ?>">
		<div class="name"><?= $vd->esc($filter->name) ?></div>
		<div class="value">
			<?= $vd->esc($filter->value) ?>
			<a href="#" class="remove"></a>
		</div>
	</div>
	<?php endforeach ?>
</div>
<?php endif ?>