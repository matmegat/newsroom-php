<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<section class="article-details">
		<div class="row-fluid">
			<?php if ($vd->m_content->is_premium): ?>
				<?php $cover_image = Model_Image::find($vd->m_content->cover_image_id); ?>
				<?php if ($cover_image): ?>
					<div class="span3">
						<?php $orig_variant = $cover_image->variant('original'); ?>
						<?php $ci_variant = $cover_image->variant('view-cover'); ?>
						<?php $ci_filename = $ci_variant->filename; ?>
						<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
						<img src="<?= Stored_File::url_from_filename($ci_filename) ?>" 
						alt="<?= $vd->esc($vd->m_content->title) ?>" class="add-border" /></a>
					</div>
					<div class="span9">
				<?php else: ?>
					<div class="span12">
				<?php endif; ?>
			<?php else: ?>
				<div class="span12">
			<?php endif ?>
				<?= $ci->load->view('browse/view/partials/article_info') ?>
				<p><?php echo $vd->esc($vd->m_content->summary) ?></p>
			</div>
		</div>
	</section>

	<section class="article-content">
		<?php echo $ci->load->view('browse/view/partials/supporting_quote') ?>
		<div class="marbot-15 html-content"><?php echo $vd->m_content->content ?></div>
		<?= $ci->load->view('browse/view/partials/share-bottom') ?>
		<?php if ($vd->m_content->is_premium && $vd->m_content->web_video_id) : ?>
			<?php $provider = Video::get_instance($vd->m_content->web_video_provider, $vd->m_content->web_video_id); ?>
			<span class="media-block pull-left clearfix">
				<?php echo $provider->render(700,394); ?>
			</span>
		<?php endif; ?>
	</section>

	<?php if ($vd->m_content->is_premium): ?>
	<?= $ci->load->view('browse/view/partials/related_resources'); ?>
	<?= $ci->load->view('browse/view/partials/additional_images'); ?>
	<?php endif ?>
	
	<?= $ci->load->view('browse/view/partials/tags_categories') ?>

</article>
