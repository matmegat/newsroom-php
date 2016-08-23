<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if ($ci->input->get('terms')): ?>
					<h1>Search Results</h1>
					<?php else: ?>
					<h1>Email Stats</h1>
					<?php endif ?>
				</div>
				<div class="span6">
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
				<table class="grid" id="analyze-results">
					<thead>
						
						<tr>
							<th class="left">Name</th>
							<th>Content</th>
							<th>Send Date<sup>&dagger;</sup></th>
							<th>Sent</th>
							<th>Opened*</th>
						</tr>
						
					</thead>
					<tbody>
						
						<?php foreach ($vd->results as $k => $result): ?>
						<tr>
							<td class="left">
								<a href="manage/analyze/email/view/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->name, 40)) ?>
								</a>
							</td>
							<td>
								<?php if ($result->content_type): ?>
								<span><?= Model_Content::full_type($result->content_type) ?></span>
								<?php else: ?>
								<span>-</span>
								<?php endif ?>
							</td>
							<td>
								<?php $deliver = Date::out($result->date_send); ?>
								<?= $deliver->format('M j, Y') ?>
							</td>
							<td>
								<span><?= (int) $result->contact_count ?></span>
							</td>
							<td>
								<span><?= (int) @$vd->opens[$k] ?>+</span>
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
				
				<div class="clearfix">
					<div class="pull-left grid-report ta-left">
						* Some email clients do not allow views to be tracked.
						<br />&dagger; Assumes that the content is published.
					</div>
					<div class="pull-right grid-report">Displaying <?= count($vd->results) ?> 
						of <?= $vd->chunkination->total() ?> Campaigns</div>
					</div>
				</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>