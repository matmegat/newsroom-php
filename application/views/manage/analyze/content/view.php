<link rel="stylesheet" href="<?= $vd->assets_base ?>css/manage-print.css?<?= $vd->version ?>" media="print" />

<ul class="breadcrumb no-print">
	<li><a href="manage/analyze">iAnalyze</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/analyze/content/<?= $vd->m_content->type ?>"><?= 
		Model_Content::full_type_plural($vd->m_content->type) ?></a> 
		<span class="divider">&raquo;</span></li>
	<li class="active"><?= $vd->esc($vd->m_content->title) ?></li>
</ul>

<div class="row-fluid no-print">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span4">
					<h1>Content Stats</h1>
				</div>
				<div class="span8">
					<div class="pull-right">
						<?php if ($vd->m_content->is_premium && $vd->m_content->is_published): ?>
						<a href="manage/analyze/content/dist/<?= $vd->m_content->id ?>" 
							class="bt-orange btn-with-icon">
							<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-pdf-text.png" />
							Distribution Report
						</a>
						<?php endif ?>
						<a href="manage/analyze/content/report/<?= $vd->m_content->id ?>?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" 
							class="bt-orange btn-with-icon">
							<img src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-pdf-text.png" />
							Export as PDF
						</a>
						<a href="javascript:void(0)" class="bt-publish bt-silver" id="print">Print</a>
						<script> 
						
						$(function() {
							
							$("#print").on("click", function() {
								window.print();
							});
							
						});
						
						</script>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content content-no-tabs">

			<div class="row-fluid relative">				
				<div class="span6">
					<h2 id="stats-summary">
						<?= $vd->esc($vd->m_content->title) ?>
					</h2>
				</div>
				<div class="span6 push-down push-right">
					<form action="manage/analyze/content/view/<?= $vd->m_content->id ?>" method="post">
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
			
			<div id="analyze-sources" class="marbot-5 clearfix">		
				<span class="source">
					<strong><?= $vd->summary->hits ?></strong> <em>Views</em>
				</span>
				<?php if ($vd->m_content->is_premium && $vd->m_content->is_published): ?>
				<span class="source" id="dist-sites">
					<strong><?= (int) $vd->dist_count ?></strong>
					<?php if ($vd->dist_count): ?>
					<a href="#"><em>Distribution Sites</em></a> 
					<?php else: ?>
					<em>Distribution Sites</em>
					<?php endif ?>
				</span>
				<script>
				
				$(function() {
					
					var content_loaded = false;
					var content_url = "manage/analyze/content/dist_modal/<?= $vd->m_content->id ?>";
					$("#dist-sites a").on("click", function(ev) {
						ev.preventDefault();
						var modal = $("#<?= $vd->dist_modal_id ?>").modal("show");
						if (!content_loaded) {
							content_loaded = true;
							var modal_content = modal.find(".modal-content");
							modal_content.addClass("stats-loader");
							modal_content.load(content_url, function() {
								modal_content.removeClass("stats-loader");
							});
						}
					});
					
				});
				
				
				</script>
				<span class="source" id="google-results">
					<img src="<?= $vd->assets_base ?>im/loader-line.gif" />
					<script>
					
					$(function() {

						var container = $("#google-results");
						var url = "manage/analyze/content/google_results/<?= $vd->m_content->id ?>";
						container.load(url, window.update_sources_bar);

					});
					
					</script>
				</span>
				<?php endif ?>
				<span class="source" id="twitter-shares">
					<img src="<?= $vd->assets_base ?>im/loader-line.gif" />
					<script>
					
					$(function() {

						var container = $("#twitter-shares");
						var url = "manage/analyze/content/twitter_shares/<?= $vd->m_content->id ?>";
						container.load(url, window.update_sources_bar);

					});
					
					</script>
				</span>
				<span class="source" id="facebook-shares">
					<img src="<?= $vd->assets_base ?>im/loader-line.gif" />
					<script>
					
					$(function() {

						var container = $("#facebook-shares");
						var url = "manage/analyze/content/facebook_shares/<?= $vd->m_content->id ?>";
						container.load(url, window.update_sources_bar);

					});
					
					</script>
				</span>
				<script>
				
				$(window.update_sources_bar = function() {
					
					var sources = $("#analyze-sources");
					var sources_width = sources.width();
					var sources_spans = sources.children("span.source");
					var sources_count = sources_spans.size();
					
					console.log(sources_width);
					
					sources_width -= 15 * (sources_count - 1);
					sources_width  = sources_width / sources_count;
					sources_width -= 2;
					sources_width  = Math.floor(sources_width);					
					sources_spans.width(sources_width);
					
				});
				
				</script>
			</div>
						
			<div class="row-fluid marbot-20">
				<div class="span12">
					<div class="total-views-panel stats-loader" style="width: 880px; height: 280px">
						<img src="manage/analyze/content/chart/<?= $vd->m_content->id ?>?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" />
					</div>
				</div>
			</div>

			<div class="row-fluid">
				
				<div class="span4">
					<div id="geolocation" class="with-border">
						<!-- <h2>Top Locations</h2> -->
						<div class="locations stats-loader"></div>
						<script>
						
						$(function() {
							
							var url = "manage/analyze/content/geolocation/<?= 
								$vd->m_content->id ?>?date_start=<?= 
								$vd->dt_start->format('Y-m-d') ?>&date_end=<?= 
								$vd->dt_end->format('Y-m-d') ?>";
							
							var locations = $("#geolocation .locations");
							locations.load(url, function() {
								locations.removeClass("stats-loader");
							});
							
						});
						
						</script>
					</div>
				</div>
				
				<div class="span8">
					<div id="world-map">
						<?= $vd->world_map ?>
					</div>
				</div>
				
			</div>
			
		</div>
	</div>
</div>