<?= $ci->load->view('manage/analyze/report/partials/header.php'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content content-no-tabs">

			<h2><?= $vd->esc($ci->newsroom->company_name) ?> Newsroom</h2>
			
			<div class="row-fluid">
				
				<div class="span4" id="stats-summary">
					<strong><?= $vd->summary->hits ?> Views</strong>
					<span>over</span>
					<strong><?= $vd->summary->visits ?> Visits</strong>
				</div>
				<div class="span8">
					<div class="pull-right">
						<form action="manage/analyze/overall" method="post">
							<div class="pull-right" id="analyze-date-range">
								<div class="input-append">
									<input type="text" name="date_start" class="span10" 
										data-date-format="yyyy-mm-dd" id="date-start"
										value="<?= $vd->dt_start->format('Y-m-d') ?>" />
									<span class="add-on"><i class="icon-calendar"></i></span>
								</div>
								<div class="input-append">
									<input type="text" name="date_end" class="span10" 
										data-date-format="yyyy-mm-dd" id="date-end"
										value="<?= $vd->dt_end->format('Y-m-d') ?>" />
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
			</div>
						
			<div class="row-fluid marbot-20">
				<div class="span8">
					<div class="total-views-panel stats-loader" style="width: 880px; height: 350px">
						<img src="manage/analyze/overall/report_chart?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" />
					</div>
				</div>
			</div>

			<div class="row-fluid marbot-20">
				<div class="span12">
					<h2>Recent Content</h2>
					<table class="grid listing">
						<tbody>
						<?php foreach ($vd->recent_content as $content): ?>
						<tr>
							<td class="left"><?= $vd->esc($content->title) ?></td>		
							<td><?= Model_Content::short_type($content->type) ?></td>
							<td>
								<?php $publish = Date::out($content->date_publish); ?>
								<?= $publish->format('M j') ?>
							</td>
							<td><?= $vd->esc($content->hits) ?> Views</td>
						</tr>
						<?php endforeach ?>
						</tbody>
					</table>
				</div>				
			</div>
			
			<div class="row-fluid report">
				<div class="span12">
					<h2>Top Locations</h2>
					<div class="locations row-fluid">
						<?= $ci->report_geolocation() ?>
					</div>
				</div>				
			</div>

		</div>
	</div>
</div>

<img class="sleep" src="sleep/5" />