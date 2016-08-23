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
						<th>Publish Date</th>					
						<th>Company</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<h3>
								<span class="muted"><?= Model_Content::short_type($result->type) ?></span>
								<a href="<?= $result->mock_nr->url() ?>manage/publish/<?= $result->type ?>/edit/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->title, 45)) ?>
								</a>
							</h3>
							<ul>
								<li><a href="<?= $ci->common()->url($result->url()) ?>" target="_blank">View</a></li>
								<li><a href="<?= $result->mock_nr->url() ?>manage/publish/<?= $result->type ?>/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="<?= $result->mock_nr->url() ?>manage/publish/<?= $result->type ?>/delete/<?= $result->id ?>">Delete</a></li>
								<?php if (!Auth::user()->is_free_user() || ($result->is_premium && $result->is_published)): ?>
								<li><a href="<?= $result->mock_nr->url() ?>manage/contact/campaign/edit/from/<?= $result->id ?>">Email</a></li>
								<?php endif ?>
								<?php if ($result->is_published): ?>
								<li><a href="<?= $result->mock_nr->url() ?>manage/analyze/content/view/<?= $result->id ?>">Statistics</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php if ($result->is_published): ?>
							<span class="status"><img class="tl" title="Published"
								src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>
							<?php elseif ($result->is_under_review): ?>
							<span class="status"><img class="tl" title="Under Review"
								src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
							<?php elseif ($result->is_draft): ?>
							<span class="status"><img class="tl" title="Draft"
								src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
							<?php else: ?>
							<span class="status"><img class="tl" title="Scheduled"
								src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
							<?php endif ?>
							<?php $dt_publish = Date::out($result->date_publish, $result->mock_nr->timezone); ?>
							<?= $dt_publish->format('M j, Y') ?>
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
				</div>
				<div class="pull-right grid-report">			
					Displaying <?= count($vd->results) ?> 
						of <?= $vd->chunkination->total() ?> Results</div>
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>