<li>
	<a class="relative images-list-item
		<?= isset($image) ? 's-existing' : 's-select' ?>
		<?= value_if_test($featured, 'featured') ?>">		
		<div class="requires-premium web-image-overlay"></div>
		<input type="hidden" name="image_ids[]" 
			class="image_id" value="<?= @$image->id ?>" />
		<?php if ($featured): ?>
			<input type="hidden" class="cover_image_id image_id" 
				name="cover_image_id" value="<?= @$image->id ?>" />
			<span class="featured-img-label"></span>
		<?php endif ?>
		<span class="select-image s-select">
			<span class="select-image-content">
				<i class="icon-upload"></i>
				Select Image
			</span>
			<input class="real-file" type="file" name="image" />
		</span>
		<span class="select-image s-progress">
			<span class="img-progress-panel">
				<span class="progress-block">
					<span class="progress-value"></span>
				</span>
				<span class="progress-label">Uploading</span>
			</span>
		</span>
		<span class="s-existing">
			<?php if (isset($image)): ?>
			<?php $web_filename = $image->variant('web')->filename; ?>
			<img src="<?= Stored_Image::url_from_filename($web_filename) ?>" />
			<?php else: ?>
			<img />
			<?php endif ?>
			<span class="images-list-item-remove">
				<button type="button" class="btn btn-mini">Remove</button>
			</span>	
		</span>
	</a>
</li>