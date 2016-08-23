<?php 

$images = $vd->m_content->get_images();
if (count($images) == 1 && $images[0]->id == $vd->m_content->cover_image_id)
	return;
	
?>

<?php if ($images): ?>
<section class="resources-block view-web-images-section">
	<h3>
		Additional <strong>Images</strong>
	</h3>
	<div class="view-web-images clearfix">
		<?php foreach($images as $image) : ?>
			<?php $tmp_image = Model_Image::find($image->id); ?>
			<?php $tmp_variant = $tmp_image->variant('view-web'); ?>
			<?php $tmp_original_variant = $tmp_image->variant('original'); ?>
			<a href="<?= Stored_File::url_from_filename($tmp_original_variant->filename) ?>" 
				target="_blank" class="use-lightbox">
				<img src="<?= Stored_File::url_from_filename($tmp_variant->filename) ?>" />
			</a>
		<?php endforeach; ?>
	</div>
</section>
<?php endif; ?>