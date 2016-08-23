<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Audio Manager</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/publish/audio/edit" class="bt-publish bt-orange">Submit Audio</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>
			
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/publish/audio/all" href="manage/publish/audio/all">All</a></li>
			<li><a data-on="^manage/publish/audio/published" href="manage/publish/audio/published">Published</a></li>
			<li><a data-on="^manage/publish/audio/scheduled" href="manage/publish/audio/scheduled">Scheduled</a></li>
			<li><a data-on="^manage/publish/audio/draft" href="manage/publish/audio/draft">Draft</a></li>
		</ul>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Audio Title</th>
						<th>Publish Date</th>
						<th>Status</th>
						<th>Size</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<h3>
								<a href="manage/publish/audio/edit/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->title, 45)) ?>
								</a>
							</h3>
							<ul>
								<li><a href="view/<?= $result->slug ?>" target="_blank">View</a></li>	
								<li><a href="manage/publish/audio/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="manage/publish/audio/delete/<?= $result->id ?>">Delete</a></li>
								<?php if ($this->newsroom->is_active): ?>
								<li><a href="manage/contact/campaign/edit/from/<?= $result->id ?>">Email</a></li>
								<?php endif ?>
								<?php if ($result->is_published): ?>
								<li><a href="manage/analyze/content/view/<?= $result->id ?>">Statistics</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php if ($result->is_draft): ?>
							<span>-</span>
							<?php else: ?>
							<?php $publish = Date::out($result->date_publish); ?>
							<?= $publish->format('M j, Y') ?>&nbsp;
							<span class="muted"><?= $publish->format('H:i') ?></span>
							<?php endif ?>
						</td>
						<td>
							<?php if ($result->is_published): ?>
							<?php if ($ci->newsroom->is_active): ?>
							<span>Published</span>
							<?php else: ?>
							<span>Published*</span>
							<?php endif ?>
							<?php elseif ($result->is_under_review): ?>
							<span>Under Review</span>
							<?php elseif ($result->is_draft): ?>
							<span>Draft</span>
							<?php else: ?>
							<span>Scheduled</span>
							<?php endif ?>
						</td>
						<td>
							<?= $result->file_size ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="clearfix">
				<?php if (!$ci->newsroom->is_active): ?>
				<div class="pull-left grid-report ta-left">
					* Private, newsroom is not active.
				</div>
				<?php endif ?>
				<div class="pull-right grid-report">
					Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> Results
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>