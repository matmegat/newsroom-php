<?php for ($i = 1; $i <= 3; $i++): ?>
<?php $cat_var = "cat_{$i}_id"; ?>
<?php $selected_cat_id = (int) @$vd->m_content->$cat_var; ?>
<li class="select-category select-right">
	<select class="show-menu-arrow span12 category" 
		name="category[]" data-required-name="Category">
		<option class="selectpicker-default" title="Select Category" value=""
			<?= value_if_test(!$selected_cat_id, 'selected') ?>>None</option>
		<?php foreach ($vd->cats as $group): ?>
		<?php if (!$group->is_listed) continue; ?>
		<optgroup label="<?= $vd->esc($group->name) ?>">
			<?php foreach ($group->cats as $cat): ?>
			<?php if (!$cat->is_listed) continue; ?>
			<option value="<?= $cat->id ?>"
				<?= value_if_test(($selected_cat_id === (int) $cat->id), 'selected') ?>>
				<?= $vd->esc($cat->name) ?>
			</option>
			<?php endforeach ?>
		</optgroup>
		<?php endforeach ?>
	</select>
</li>
<?php endfor ?>