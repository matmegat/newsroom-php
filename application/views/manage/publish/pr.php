<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Press Release Manager</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/publish/pr/edit" class="bt-publish bt-orange">Submit Press Release</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>
			
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/publish/pr/all" href="manage/publish/pr/all">All</a></li>
			<li><a data-on="^manage/publish/pr/published" href="manage/publish/pr/published">Published</a></li>
			<li><a data-on="^manage/publish/pr/scheduled" href="manage/publish/pr/scheduled">Scheduled</a></li>
			<li><a data-on="^manage/publish/pr/under_review" href="manage/publish/pr/under_review">Under Review</a></li>
			<li><a data-on="^manage/publish/pr/draft" href="manage/publish/pr/draft">Draft</a></li>
		</ul>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Press Release Title</th>
						<th>Publish Date</th>
						<th>Type</th>
						<th>Status</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<h3>
								<a href="manage/publish/pr/edit/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->title, 45)) ?>
								</a>
							</h3>
							<ul>
								<li><a href="<?= $ci->common()->url($result->url()) ?>" target="_blank">View</a></li>
								<li><a href="manage/publish/pr/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="manage/publish/pr/delete/<?= $result->id ?>">Delete</a></li>
								<?php if (!Auth::user()->is_free_user() || ($result->is_premium && $result->is_published)): ?>
								<li><a href="manage/contact/campaign/edit/from/<?= $result->id ?>">Email</a></li>
								<?php endif ?>
								<?php if ($result->is_published): ?>
								<li><a href="manage/analyze/content/view/<?= $result->id ?>">Statistics</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php if (!$result->is_draft): ?>							
							<?php $publish = Date::out($result->date_publish); ?>							
							<?= $publish->format('M j, Y') ?>&nbsp;
							<span class="muted"><?= $publish->format('H:i') ?></span>
							<?php if ($result->is_scheduled() && ((!$result->is_premium && !$vd->pr_credits_basic) ||
								($result->is_premium && !$vd->pr_credits_premium))): ?>
							<div class="needs-credit smaller">Credit Needed</div>
							<?php endif ?>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
						</td>
						<td>
							<?php if ($result->is_premium): ?>
							<span>Premium</span>
							<?php else: ?>
							<span>Basic</span>
							<?php endif ?>
						</td>
						<td>
							<?php if ($result->is_published): ?>
							<span>Published</span>
							<?php elseif ($result->is_under_review): ?>
							<span>Under Review</span>
							<?php elseif ($result->is_draft): ?>
							<span>Draft</span>
							<?php else: ?>
								<span>Scheduled</span>
								<?php if ((!$result->is_premium && !$vd->pr_credits_basic) ||
								          ($result->is_premium && !$vd->pr_credits_premium)): ?>
								<div class="needs-credit smaller">Credit Needed</div>
								<?php endif ?>
							<?php endif ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="grid-report">Displaying <?= count($vd->results) ?> 
				of <?= $vd->chunkination->total() ?> Press Releases</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>