<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if ($vd->is_search): ?>
					<h1>Search Results</h1>
					<?php else: ?>
					<h1>Content Stats</h1>
					<?php endif ?>
				</div>
				<div class="span6">
				</div>
			</div>
		</header>
	</div>
</div>
			
<?php if ($vd->is_search): ?>
<div class="marbot-20"></div>
<?php else: ?>
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li>
				<a data-on="^manage/analyze/content/pr/published" 
					href="<?= gstring('manage/analyze/content/pr/published') ?>">
					Press Releases
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/news/published" 
					href="<?= gstring('manage/analyze/content/news/published') ?>">
					News
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/event/published" 
					href="<?= gstring('manage/analyze/content/event/published') ?>">
					Events
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/image/published" 
					href="<?= gstring('manage/analyze/content/image/published') ?>">
					Images
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/audio/published" 
					href="<?= gstring('manage/analyze/content/audio/published') ?>">
					Audio
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/video/published" 
					href="<?= gstring('manage/analyze/content/video/published') ?>">
					Video
				</a>
			</li>
		</ul>
	</div>
</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid" id="analyze-results">
				<thead>
					
					<tr>
						<th class="left">Title</th>
						<?php if ($vd->is_search): ?>
						<th>Type</th>
						<?php endif ?>
						<th>Publish Date</th>
						<th>Views</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<a href="manage/analyze/content/view/<?= $result->id ?>">
								<?= $vd->esc($vd->cut($result->title, 45)) ?>
							</a>
						</td>
						<?php if ($vd->is_search): ?>						
						<td>
							<?= Model_Content::short_type($result->type) ?>
						</td>
						<?php endif ?>
						<td>
							<?php $publish = Date::out($result->date_publish); ?>
							<?= $publish->format('M j, Y') ?>
						</td>
						<td>
							<?= $result->hits ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<script>
			
			$(function() {
				$("#analyze-results td").on("click", function(ev) {
					if ($(ev.target).is("a")) return;
					$(this).parents("tr").find("a")[0].click();
					return false;
				});
			});
			
			</script>
			
			<div class="grid-report">Displaying <?= count($vd->results) ?> 
				of <?= $vd->chunkination->total() ?> Items</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>