<link rel="stylesheet" href="<?= $vd->assets_base ?>css/manage-print.css?<?= $vd->version ?>" media="print" />

<ul class="breadcrumb no-print">
	<li><a href="manage/analyze">iAnalyze</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/analyze/email">Email Stats</a> <span class="divider">&raquo;</span></li>
	<li class="active"><?= $vd->esc($vd->campaign->name) ?></li>
</ul>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Email Stats</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/analyze/email/report/<?= $vd->campaign->id ?>" 
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
		<div class="content listing content-no-tabs">
			
			<div class="row-fluid">				
				<div class="span12" id="double-stats-summary">
					<h2><span><?= $vd->esc($vd->campaign->name) ?></span></h2>
					<div class="stats-summary">
						<strong><?= $vd->views ?>+ Views</strong>* and 
						<strong><?= $vd->clicks ?> Clicks</strong>
					</div>
				</div>
			</div>
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Contact</th>
						<th>Company</th>
						<th>Viewed</th>
						<th>Clicked</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<?php if ($result->first_name || $result->last_name): ?>
							<div class="marbot-5">
								<?= $vd->esc($result->first_name) ?>
								<?= $vd->esc($result->last_name) ?>
							</div>
							<div class="muted"><?= $vd->esc($result->email) ?></div>
							<?php else: ?>
							<div><?= $vd->esc($result->email) ?></div>
							<?php endif ?>
						</td>
						<td>
							<?php if ($result->company_name): ?>
							<?= $vd->esc($result->company_name) ?>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
						</td>
						<td>
							<span><?= $result->viewed ? 'Yes' : 'No*' ?></span>
						</td>
						<td>
							<span><?= $result->clicked ? 'Yes' : 'No' ?></span>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
					
			<div class="clearfix">
				<div class="pull-left grid-report">
					* Some email clients do not allow views to be tracked.
				</div>
				<div class="pull-right grid-report">Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> Contacts</div>
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>