<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<section class="article-details">
		<div class="row-fluid">
			<div class="span12">
				<?= $ci->load->view('browse/view/partials/article_info') ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				
				<?php if ($vd->m_content->external_video_id): ?>
					<?php $provider = Video::get_instance(
						$vd->m_content->external_provider, 
						$vd->m_content->external_video_id); ?>
					<span class="media-block">
						<?php echo $provider->render(540, 304); ?>
					</span>
				<?php endif; ?>
				
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