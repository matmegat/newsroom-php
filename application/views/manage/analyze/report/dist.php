<?= @$vd->dist_pre_header ?>
<?php if (!@$vd->dist_skip_header): ?>
<?= $ci->load->view('manage/analyze/report/partials/header.php'); ?>
<?php endif ?>
<?= @$vd->dist_post_header ?>

<style>
	
.main .report-h2 strong {
	color: #333;
	display: inline-block;
	margin-right: 10px;
}

.main h2.report-h2 {
	font-size: 18px;
	line-height: normal;
	padding: 0;
}

table.fin-services {
	margin-bottom: 20px;
}

.docsites {
	margin: 50px 20px 20px 20px;
}
	
</style>

<div id="report-container">
	<h2 class="report-h2 marbot-20">
		<strong>Distribution Report</strong> 
		<a href="<?= $ci->website_url($vd->m_content->url()) ?>"><?= $vd->esc($vd->m_content->title) ?></a>
	</h2>
	<div class="header-text">
		<?= Model_Setting::value('dist_header_text') ?>
	</div>
	<table class="fin-services">
		<tr>
		<?php foreach ($vd->services as $k => $service): ?>
		<?php if ($k % 2 === 0 && $k > 0): ?>
		</tr><tr class="<?= value_if_test($k % 34 === 0, 'break-after') ?>">
		<?php endif ?>
			<td>
				<?php if ($service->logo_image_id): ?>
				<?php $lo_im = Model_Image::find($service->logo_image_id); ?>
				<?php $lo_variant = $lo_im->variant('dist-finger'); ?>
				<?php $lo_url = Stored_File::url_from_filename($lo_variant->filename); ?>
				<a href="<?= $vd->esc($service->url) ?>" target="_blank">
					<img src="<?= $lo_url ?>" />
				</a>
				<?php else: ?>
				<div class="fin-service-blank"></div>
				<?php endif ?>
			</td>
			<td>
				<div class="fin-service-link">
					<a href="<?= $vd->esc($service->url) ?>" target="_blank">
						<?= $vd->esc($service->name) ?>
					</a>
				</div>
				<a href="<?= $vd->esc($service->content_url) ?>" target="_blank">
					View Press Release
				</a>
			</td>		
		<?php endforeach ?>
		</tr>
	</table>
	<div class="docsites">
		<div class="row-fluid">
			<div class="span4 ta-center">
				<a href="<?= @$vd->docs->docsite_issuu ?>">
					<img src="<?= $vd->assets_base ?>im/docsite_issuu.png" />
				</a>
			</div>
			<div class="span4 ta-center">
				<a href="<?= @$vd->docs->docsite_scribd ?>">
					<img src="<?= $vd->assets_base ?>im/docsite_scribd.png" />
				</a>
			</div>
		</div>
	</div>
</div>

<?= @$vd->dist_pre_footer ?>
<?= @$vd->dist_post_footer ?>