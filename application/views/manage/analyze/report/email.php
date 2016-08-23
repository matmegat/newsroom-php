<?= $ci->load->view('manage/analyze/report/partials/header.php'); ?>

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
				<div class="pull-right grid-report">Displaying 
					<?= count($vd->results) ?> Contacts</div>
				</div>
			</div>
		
		</div>
	</div>
</div>

<img class="sleep" src="sleep/5" />