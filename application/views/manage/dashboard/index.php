<div class="row-fluid" id="manage-dashboard">

	<aside class="span3 aside aside-left">

		<?= $ci->load->view('manage/overview/dashboard/partials/views') ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/video') ?>
		
	</aside>
	
	<div class="span6 main">
		<div class="content">
			
			<?= $ci->load->view('manage/overview/dashboard/partials/credits') ?>
			<div class="marbot-20"><?= $ci->load->view('manage/overview/dashboard/partials/chart') ?></div>
			<?= $ci->load->view('manage/overview/dashboard/partials/submissions') ?>	
			
		</div>
	</div>

	<aside class="span3 aside aside-right">

		<?php if (!$vd->bar->is_done()): ?>
		<section class="aside-block aside-inewsroom-progress">
			<h3>Tasks to do</h3>
			<div class="aside-content aside-content-border">
				<?= $vd->bar->render(); ?>
			</div>
		</section>
		<?php endif ?>

		<?= $ci->load->view('manage/overview/dashboard/partials/news') ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/help') ?>
		
	</aside>
</div>