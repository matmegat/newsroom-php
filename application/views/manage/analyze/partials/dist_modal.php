<table class="fin-services marbot-20">
	<?php foreach ($vd->services as $service): ?>
	<tr>
		<td>
			<?php if ($service->logo_image_id): ?>
			<?php $lo_im = Model_Image::find($service->logo_image_id); ?>
			<?php $lo_variant = $lo_im->variant('dist-finger'); ?>
			<?php $lo_url = Stored_File::url_from_filename($lo_variant->filename); ?>
			<a href="<?= $vd->esc($service->url) ?>" target="_blank">
				<img src="<?= $lo_url ?>" />
			</a>
			<?php else: ?>
			<div class="fin-service-blank"></div>
			<?php endif ?>
		</td>
		<td>
			<div class="fin-service-link">
				<a href="<?= $vd->esc($service->url) ?>" target="_blank">
					<?= $vd->esc($service->name) ?>
				</a>
			</div>
			<a href="<?= $vd->esc($service->content_url) ?>" target="_blank">
				View Press Release
			</a>
		</td>
	</tr>
	<?php endforeach ?>
</table>