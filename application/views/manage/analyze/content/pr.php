<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Content Stats</h1>
				</div>
				<div class="span6">
				</div>
			</div>
		</header>
	</div>
</div>
			
<?= $this->load->view('manage/analyze/content/tabs') ?>

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
						<th>Distribution PDF</th>
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
						<td>
							<?php $utc_publish = new DateTime($result->date_publish); ?>
							<?php if ($result->is_published && $result->is_premium 
								&& $utc_publish < Date::hours(-3)): ?>
							<a href="manage/analyze/content/dist/<?= $result->id ?>">
								<img class="icon" src="<?= $vd->assets_base ?>im/fugue-icons/blue-document-pdf-text.png" />
								Download
							</a>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
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