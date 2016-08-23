<?= @$vd->content_pre_header ?>
<?php if (!@$vd->content_skip_header): ?>
<?= $ci->load->view('manage/analyze/report/partials/header.php'); ?>
<?php endif ?>
<?= @$vd->content_post_header ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content content-no-tabs">

			<div class="row-fluid">				
				<div class="span6">
					<h2 id="stats-summary">
						<?= $vd->esc($vd->m_content->title) ?>
					</h2>
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
		
			<div class="row-fluid">
				<div class="span6 offset6">
					<div class="pull-right">
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
			</div>
						
			<div class="row-fluid marbot-20">
				<div class="span8">
					<div class="total-views-panel stats-loader" style="width: 880px; height: 350px">
						<img src="manage/analyze/content/report_chart/<?= $vd->m_content->id ?>?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" />
					</div>
				</div>
			</div>
			
			<div class="row-fluid report">
				<div class="span12">
					<h2>Top Locations</h2>
					<div class="locations row-fluid">
						<?= $ci->report_geolocation($vd->m_content->id) ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<img class="sleep" src="sleep/5" />

<?= @$vd->content_pre_footer ?>
<?= @$vd->content_post_footer ?>