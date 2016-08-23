<div class="main-content <?= value_if_test($vd->wide_view, 'span12', 'span8') ?>">
	
	<?php if ($vd->wide_view): ?>
	<?= $ci->load->view('browse/view/partials/share-side') ?>
	<?php endif ?>
	
	<section class="content-view">

		<div id="cv-container" class="content-type-<?= $vd->m_content->type ?>">
			<?= $ci->load->view("browse/view/{$vd->m_content->type}"); ?>
		</div>
		
	</section>	
	
</div>