<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<section class="article-details">		
		<?php $image = Model_Image::find($vd->m_content->image_id); ?>
		<?php if (!$image) $image = new Model_Image(); ?>		
		<?php $orig_variant = $image->variant('original'); ?>
		<?php $full_variant = $image->variant('view-full'); ?>
		<?php if ($full_variant): ?>
		<div class="row-fluid">
			<div class="span12">
		<?php elseif ($wc_variant = $image->variant('view-cover')): ?>
		<div class="row-fluid">
			<div class="span4">		
				<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
					<img src="<?= Stored_File::url_from_filename($wc_variant->filename) ?>" 
				alt="<?= $vd->esc($vd->m_content->title) ?>" class="add-border" /></a>
			</div>
			<div class="span8">
		<?php else: ?>
		<div class="row-fluid">
			<div class="span12">
		<?php endif ?>		
		
				<?= $ci->load->view('browse/view/partials/article_info') ?>
						
				<?php if ($full_variant): ?>
				<div>
					<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
						<img src="<?= Stored_File::url_from_filename($full_variant->filename) ?>" 
					alt="<?= $vd->esc($vd->m_content->title) ?>" /></a>
				</div>
				<?php endif ?>
				
				<?php if ($vd->m_content->license || $vd->m_content->source): ?>
				<p class="article-details-license">
					<?php if($vd->m_content->license) : ?>
						<i>License: <?php echo $vd->esc($vd->m_content->license) ?></i>
					<?php endif; ?>
					<?php if($vd->m_content->source) : ?>
					- <i>Source: <?php echo $vd->esc($vd->m_content->source) ?></i>
					<?php endif; ?>
				</p>
				<?php endif ?>
				
			</div>
		</div>
	</section> 

	<section class="article-content clearfix">
		<?php echo $ci->load->view('browse/view/partials/supporting_quote') ?>
		<?php if ($vd->m_content->summary): ?>
		<div class="article-summary marbot-10">
			<?php echo $vd->esc($vd->m_content->summary) ?>
		</div>
		<?php endif ?>
		<?= $ci->load->view('browse/view/partials/share-bottom') ?>
	</section>

	<?php echo $ci->load->view('browse/view/partials/links_tags') ?>

</article>