<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<section class="article-details">
		<div class="row-fluid">
			<?php $cover_image = Model_Image::find($vd->m_content->cover_image_id); ?>
			<?php if($cover_image) : ?>
				<div class="span4">
					<?php $orig_variant = $cover_image->variant('original'); ?>
					<?php $ci_variant = $cover_image->variant('view-cover'); ?>
					<?php $ci_filename = $ci_variant->filename; ?>
					<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
					<img src="<?= Stored_File::url_from_filename($ci_filename) ?>" 
					alt="<?= $vd->esc($vd->m_content->title) ?>" class="add-border" /></a>
				</div>
				<div class="span8">
			<?php else: ?>
				<div class="span12">
			<?php endif; ?>
				<?= $ci->load->view('browse/view/partials/article_info') ?>
				<p><?php echo $vd->esc($vd->m_content->summary) ?></p>
			</div>
		</div>
	</section>

	<section class="article-content clearfix">
		<?php echo $ci->load->view('browse/view/partials/supporting_quote') ?>
		<div class="marbot-15 html-content"><?php echo $vd->m_content->content ?></div>
		<?= $ci->load->view('browse/view/partials/share-bottom') ?>
	</section>

	<?php echo $ci->load->view('browse/view/partials/additional_images') ?>
	<?php echo $ci->load->view('browse/view/partials/additional_links') ?>
	<?php echo $ci->load->view('browse/view/partials/tags_categories') ?>

</article>