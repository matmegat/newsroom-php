<header class="page-header">
	<div class="row-fluid">
		<div class="span6">
			<h1>iAnalyze Overview</h1>
		</div>
	</div>
</header>

<div class="content overview-combined-list overview-publish">
	<div class="grid-content">
		
		<div class="clearfix marbot-20">
			<div class="pull-right">
				<form action="manage/overview/analyze" method="post">
					<div class="pull-right" id="analyze-date-range">
						<div class="input-append">
							<input type="text" name="date_start" class="span10" 
								data-date-format="yyyy-mm-dd" id="date-start" placeholder="Start Date"
								value="<?php if (isset($vd->dt_start)) echo $vd->dt_start->format('Y-m-d') ?>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>
						<div class="input-append">
							<input type="text" name="date_end" class="span10" 
								data-date-format="yyyy-mm-dd" id="date-end" placeholder="End Date"
								value="<?php if (isset($vd->dt_end)) echo $vd->dt_end->format('Y-m-d') ?>" />
							<span class="add-on"><i class="icon-calendar"></i></span>
						</div>		 					
						<button class="no-print btn btn-normal" type="submit">
							Update
						</button>
						<script>
						
						$(function() {
							
							var date_start = $("#date-start");
							var date_start_i = date_start.next("span");
							var date_end = $("#date-end");	
							var date_end_i = date_end.next("span");
							
							var date_fields = $();
							date_fields = date_fields.add(date_start);
							date_fields = date_fields.add(date_end);
							
							var date_icons = $();
							date_icons = date_icons.add(date_start_i);
							date_icons = date_icons.add(date_end_i);
							
							$(date_fields).datepicker();
							$(date_icons).on("click", function() {
								$(this).prev("input").datepicker("show");
							});
							
						});
						
						</script>
					</div>
				</form>
			</div>
		</div>

		<div class="tab-pane active" id="all">
			<table class="grid">
				<thead>
					<tr>
						<th class="left">Company Name</th>
						<th>Newsroom Views</th>
						<th>PR Views</th>
						<th>Emails Sent</th>
						<th>Distribution</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($vd->results as $newsroom): ?>
					<tr>
						<td class="left vtm">
							<table class="sub-table-title">
								<tr>
									<td>
										<?php if ($lo_im = Model_Image::find($newsroom->logo_image_id)): ?>
										<?php $lo_variant = $lo_im->variant('header-finger'); ?>
										<?php $url = Stored_File::url_from_filename($lo_variant->filename); ?>
										<a href="<?= $newsroom->url('manage/analyze') ?>">
											<div class="image-container flex"><img src="<?= $url ?>" /></div>
										</a>
										<?php else: ?>
										<a href="<?= $newsroom->url('manage/analyze') ?>">
											<div class="image-container flex">
												<img src="<?= $vd->assets_base ?>im/trans.gif" class="blank" />
											</div>
										</a>
										<?php endif ?>
									</td>
									<td class="left">
										<div class="company-name"><strong><?= $vd->esc($newsroom->company_name) ?></strong></div>
										<div class="btn-group">
											<a class="dropdown-toggle" data-toggle="dropdown" href="#">
												Manage<span class="caret"></span>
											</a>
											<ul class="dropdown-menu">
												<li><a href="<?= $newsroom->url('manage/analyze/content') ?>">Content Stats</a></li>
												<li><a href="<?= $newsroom->url('manage/analyze/email') ?>">Email Stats</a></li>
												<li><a href="<?= $newsroom->url('manage/analyze/overall') ?>">Newsroom Stats</a></li>
												<li><a href="<?= $newsroom->url('manage/analyze/settings') ?>">Report Settings</a></li>
											</ul>
										</div>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<?php if ($newsroom->is_active): ?>
							<span class="td-big-link">
								<?= (int) $newsroom->hits ?>
							</span>
							<?php else: ?>
							<span class="td-big-link">-</span>
							<?php endif ?>					
						</td>
						<td>
							<span class="td-big-link">
								<?= (int) $newsroom->pr_hits ?>
							</span>	
						</td>
						<td>
							<span class="td-big-link">
								<?= (int) $newsroom->email_count; ?>
							</span>	
						</td>
						<td>
							<span class="td-big-link">
								<?= (int) $newsroom->dist_count ?>
							</span>	
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
				
		<div class="clearfix">
			<div class="pull-right grid-report">			
				Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> Companies</div>
			</div>
		</div>
			
		<?= $vd->chunkination->render() ?>
		
	</div>
</div>