<div class="ln-block">
		
	<?php $cover_image = Model_Image::find($content->image_id); ?>
	
	<?php if ($cover_image): ?>
	<?php $ci_variant = $cover_image->variant('cover'); ?>
	<?php $ci_filename = $ci_variant->filename; ?>
	<a class="ln-cover" href="view/<?= $content->slug ?>">
		<img src="<?= Stored_File::url_from_filename($ci_filename) ?>" 
			alt="<?= $vd->esc($content->title) ?>" 
			width="<?= $ci_variant->width ?>" 
			height="<?= $ci_variant->height ?>" />
	</a>
	<?php endif ?>
	
	<?= $ci->load->view('browse/listing/partials/details') ?>

	<a href="view/<?= $content->slug ?>" class="content-link">
		<span class="ln-title">
			<?= $vd->esc($content->title) ?>
		</span>
		<?php if (@$content->summary): ?>
		<span class="ln-content">
			<p><?= nl2p($vd->esc($content->summary)) ?></p>
		</span>
		<?php endif ?>
	</a>
	
</div>