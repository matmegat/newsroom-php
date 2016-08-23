<li id="select-license">
	<select class="show-menu-arrow span12" name="license">
		<option class="selectpicker-default" title="Select License" value=""
			<?= value_if_test(!@$vd->m_content->license, 'selected') ?>>None</option>
		<?php foreach ($licenses as $license): ?>
		<option value="<?= $vd->esc($license) ?>"
			<?= value_if_test((@$vd->m_content->license === $license), 'selected') ?>>
			<?= $vd->esc($license) ?>
		</option>
		<?php endforeach ?>
	</select>
	<script>

	$(function() {
		
		$("#select-license select").on_load_select();
		
	});
	
	</script>
</li>