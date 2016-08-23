<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/mediaelement/mediaelementplayer.css" />
	<script src="<?= $vd->assets_base ?>lib/mediaelement/mediaelement-and-player.min.js"></script>

	<section class="article-details">
		<div class="row-fluid">
			<div class="span12">
				<?= $ci->load->view('browse/view/partials/article_info') ?>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<?php if ($vd->m_content->stored_file_id): ?>
				<div id="audio-player">
					<?php $audio = Stored_file::load_data_from_db(
					$vd->m_content->stored_file_id); ?>
					<audio src="<?php echo Stored_file::url_from_filename($audio->filename) ?>" />
				</div>
				<script>					
					$(function() {

						var audio = $("#audio-player audio");
						audio.mediaelementplayer({
							audioWidth: 540
						});
						
					});				
				</script>
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