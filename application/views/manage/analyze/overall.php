<link rel="stylesheet" href="<?= $vd->assets_base ?>css/manage-print.css?<?= $vd->version ?>" media="print" />

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Newsroom Stats</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/analyze/overall/report?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" 
							class="bt-publish bt-orange btn-with-icon">
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

			<h2><span><?= $vd->esc($ci->newsroom->company_name) ?></span></h2>
			
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
				<div class="span12">
					<div class="total-views-panel stats-loader" style="width: 880px; height: 280px">
						<img src="manage/analyze/overall/chart?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&amp;date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>" />
					</div>
				</div>
			</div>

			<div class="row-fluid">
				
				<div class="span4 with-border" id="geolocation">
					<!-- <h2>Top Locations</h2> -->
					<div class="locations stats-loader"></div>
					<script>
					
					$(function() {
						
						var url = "manage/analyze/overall/geolocation?date_start=<?= 
							$vd->dt_start->format('Y-m-d') ?>&date_end=<?= 
							$vd->dt_end->format('Y-m-d') ?>";
						
						var locations = $("#geolocation .locations");
						locations.load(url, function() {
							locations.removeClass("stats-loader");
						});
						
					});
					
					</script>
				</div>
				
				<div class="span8">
					<!-- <h2>World Map</h2> -->
					<?= $vd->world_map ?>
				</div>
				
			</div>

		</div>
	</div>
</div>