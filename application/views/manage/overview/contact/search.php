<header class="page-header">
	<div class="row-fluid">
		<div class="span6">
			<h1>Search Results</h1>
		</div>
	</div>
</header>

<div class="row-fluid overview-search-results">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Campaign Name</th>
						<th>Send Date <sup>&dagger;</sup></th>			
						<th>Contacts</th>		
						<th>Company</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<h3>
								<a href="<?= $result->mock_nr->url() ?>manage/contact/campaign/edit/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->name, 50)) ?>
								</a>
							</h3>
							<ul>
								<li><a href="<?= $result->mock_nr->url() ?>manage/contact/campaign/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="<?= $result->mock_nr->url() ?>manage/contact/campaign/delete/<?= $result->id ?>">Delete</a></li>
								<?php if ($result->is_sent): ?>
								<li><a href="<?= $result->mock_nr->url() ?>manage/analyze/email/view/<?= $result->id ?>">Statistics</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php if ($result->is_sent): ?>
							<span class="status"><img class="tl" title="Sent"
								src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>
							<?php elseif ($result->is_draft): ?>
							<span class="status"><img class="tl" title="Draft"
								src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
							<?php else: ?>
							<span class="status"><img class="tl" title="Scheduled"
								src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
							<?php endif ?>
							<?php $dt_send = Date::out($result->date_send, $result->newsroom_timezone); ?>
							<?= $dt_send->format('M j, Y') ?>
						</td>		
						<td>
							<?php if ($result->is_sent || $result->is_send_active): ?>
							<span><?= $result->contact_count ?></span>
							<?php else: ?>
							<span><?= $result->credits_required() ?></span>
							<?php endif ?>
						</td>				
						<td>
							<span><?= $vd->esc($result->company_name) ?></span>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="clearfix">
				<div class="pull-left grid-report ta-left">
					Dates are shown using company specific timezones.
					<br />&dagger; Assumes that the content is published.
				</div>
				<div class="pull-right grid-report">			
					Displaying <?= count($vd->results) ?> 
						of <?= $vd->chunkination->total() ?> Campaigns</div>
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>