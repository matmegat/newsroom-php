<section class="form-section configuration-setting">
	<div class="name"><strong><?= $vd->esc($setting->name) ?></strong></div>
	<div class="description muted"><?= $vd->esc($setting->description) ?></div>
	<?php if ($setting->type === Model_Setting::TYPE_INTEGER): ?>
	<div class="input">
		<input type="number" class="span12"
			name="<?= $vd->esc($setting->name) ?>" 
			value="<?= $vd->esc($setting->value) ?>" />
	</div>
	<?php elseif ($setting->type === Model_Setting::TYPE_BOOLEAN): ?>
	<div class="input">
		<label class="checkbox-container">
			<input type="checkbox" name="<?= $vd->esc($setting->name) ?>"
				<?= value_if_test($setting->value, 'checked') ?> value="1" />
			<span class="checkbox"></span>
			Enable
		</label>
	</div>
	<?php elseif ($setting->type === Model_Setting::TYPE_TEXT): ?>
	<div class="input">
		<input class="source-ta span12" type="text"
			value="<?= $vd->esc($vd->cut($setting->value, 0, 50)) ?>" />
		<input class="source-ta-data" type="hidden"
			name="<?= $vd->esc($setting->name) ?>" 
			value="<?= $vd->esc($setting->value) ?>" />
	</div>
	<?php else: ?>
	<div class="input">
		<input type="text" class="span12" 
			name="<?= $vd->esc($setting->name) ?>" 
			value="<?= $vd->esc($setting->value) ?>" />
	</div>
	<?php endif ?>
</section>