<?php foreach ($vd->locations as $location): ?>
<div class="location">
	<div class="country">
		<img src="<?= $location->country->flag ?>" />
		<span class="name">
			<?= $vd->esc($location->country->label) ?>
		</span>
		<span class="visits">
			(<?= $vd->esc($location->country->visits) ?>)
		</span>
	</div>
	<div class="regions">
		<?php foreach ($location->regions as $region): ?>
		<div class="region">
			<span class="name">
				<?= $vd->esc($region->label) ?>
			</span>
			<span class="visits">
				(<?= $region->visits ?>)
			</span>
		</div>
		<?php endforeach ?>
	</div>
</div>
<?php endforeach ?>