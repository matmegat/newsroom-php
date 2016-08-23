<div class="row-fluid" id="manage-dashboard">

	<aside class="span3 aside aside-left">

		<?= $ci->load->view('manage/overview/dashboard/partials/views') ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/video') ?>
		
		<?php if (count($vd->bars) > 3): ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/news') ?>
		<?php endif ?>
		<?php if (count($vd->bars) > 1): ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/help') ?>
		<?php endif ?>
		
	</aside>
	
	<div class="span6 main">
		<div class="content">
			
			<?= $ci->load->view('manage/overview/dashboard/partials/credits') ?>
			<?= $ci->load->view('manage/overview/dashboard/partials/chart') ?>			
			
			<?php if (Auth::user()->has_platinum_access()): ?>
			<section class="home-companies">
				<header class="ao-header row-fluid">
					<div class="span6">
						<h3>Companies Managed</h3>
					</div>
					<div class="span6">
						<ul class="sub-header-menu">
							<li><a href="manage/companies">View All &raquo;</a></li>
						</ul>							
					</div>
				</header>
				<div class="clearfix home-companies-list">
					<?php foreach ($vd->companies as $newsroom): ?>
					<div class="cm-item current clearfix" data-url="<?= $newsroom->url('manage/dashboard') ?>">
						<?php if ($lo_im = Model_Image::find($newsroom->logo_image_id)): ?>
						<?php $lo_variant = $lo_im->variant('header-finger'); ?>
						<?php $url = Stored_File::url_from_filename($lo_variant->filename); ?>
						<div class="image-container"><img src="<?= $url ?>" /></div>
						<?php else: ?>
						<div class="image-container">
							<img src="<?= $vd->assets_base ?>im/trans.gif" class="blank" />
						</div>
						<?php endif ?>
						<div class="cm-item-content">
							<div class="cm-item-middle">
								<div class="cm-item-title"><?= $vd->esc($newsroom->company_name) ?></div>
								<div><a href="<?= $newsroom->url() ?>">View Newsroom</a></div>							
							</div>
						</div>
					</div>
					<?php endforeach ?>
					<div class="cm-item new">
						<a class="cm-item-new-link" href="#">
							&nbsp;<i class="icon-plus"></i>&nbsp;
						</a>
						<div class="cm-item-content">
							<div class="cm-item-middle">
								<div class="cm-item-title">New Company</div>
								<div><a href="#">Add Newsroom</a></div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<script>
			
			$(function() {
				
				$(".home-companies .cm-item.current").on("click", function(ev) {
					if ($(ev.target).is("a")) return;
					window.location = $(this).data("url");
					return false;
				});
				
				$(".home-companies .cm-item.new").on("click", function() {
					$("#<?= $vd->new_company_modal_id ?>").modal("show");
					return false;
				});
				
			});
			
			</script>
			<?php endif ?>

			<?= $ci->load->view('manage/overview/dashboard/partials/submissions') ?>	
			
		</div>
	</div>

	<aside class="span3 aside aside-right">

		<?php if (count($vd->bars)): ?>
		<section class="aside-block aside-inewsroom-progress">
			<h3>Tasks to do</h3>
			<?php foreach ($vd->bars as $bar): ?>
			<div class="aside-content aside-content-border">
				<h3 class="marbot-10"><?= $vd->esc($bar->company_name) ?></h3>
				<?= $bar->render(); ?>
			</div>
			<?php endforeach ?>
		</section>
		<?php endif ?>

		<?php if (count($vd->bars) <= 3): ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/news') ?>
		<?php endif ?>
		<?php if (count($vd->bars) <= 1): ?>
		<?= $ci->load->view('manage/overview/dashboard/partials/help') ?>
		<?php endif ?>
		
	</aside>
</div>