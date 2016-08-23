<li>
	<?php $file = Stored_file::load_data_from_db($stored_file_id); ?>
	<?php $file_name = preg_replace('#\.[a-z0-9]+$#i', '', basename($stored_file_name)); ?>
	<?php $file_ext = Stored_file::parse_extension($stored_file_name); ?>
	<?php if ($file_ext === "pdf"): ?>
	<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-pdf-text.png" />
	<?php elseif ($file_ext === "ppt"): ?>
	<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-powerpoint.png" />
	<?php elseif ($file_ext === "doc"): ?>
	<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-word-text.png" />
	<?php elseif ($file_ext === "zip"): ?>
	<img src="<?= $vd->assets_base ?>im/fugue-icons/folder-zipper.png" />
	<?php else: ?>
	<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document.png" />
	<?php endif; ?>
	<a href="<?php echo Stored_file::url_from_filename($file->filename) ?>"><?= 
		$vd->esc($file_name) ?></a>	
</li>