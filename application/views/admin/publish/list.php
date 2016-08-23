<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1><?= Model_Content::full_type($vd->type) ?> Manager</h1>
				</div>
			</div>
		</header>
	</div>
</div>
			
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^admin/publish/<?= $vd->type ?>/all" 
				href="admin/publish/<?= $vd->type ?>/all<?= $vd->esc(gstring()) ?>">All</a></li>
			<?php if ($vd->type === Model_Content::TYPE_PR): ?>
			<li><a data-on="^admin/publish/<?= $vd->type ?>/under_review" 
				href="admin/publish/<?= $vd->type ?>/under_review<?= $vd->esc(gstring()) ?>">Under Review</a></li>
			<?php endif ?>
			<li><a data-on="^admin/publish/<?= $vd->type ?>/published" 
				href="admin/publish/<?= $vd->type ?>/published<?= $vd->esc(gstring()) ?>">Published</a></li>
			<li><a data-on="^admin/publish/<?= $vd->type ?>/scheduled" 
				href="admin/publish/<?= $vd->type ?>/scheduled<?= $vd->esc(gstring()) ?>">Scheduled</a></li>
			<li><a data-on="^admin/publish/<?= $vd->type ?>/draft" 
				href="admin/publish/<?= $vd->type ?>/draft<?= $vd->esc(gstring()) ?>">Draft</a></li>
		</ul>
	</div>
</div>

<?= $this->load->view('admin/partials/filters'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Content Title</th>	
						<th>Details</th>
						<th>Owner</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a class="view" href="<?= $ci->common()->url($result->url()) ?>" target="_blank">
									<?= $vd->esc($vd->cut($result->title, 45)) ?>
								</a>
							</h3>	
							<ul>
								<?php if ($result->is_under_review): ?>
								<li><a class="admin-approve" href="admin/publish/approve/<?= $result->id ?>">Approve</a></li>
								<li><a class="admin-reject" href="admin/publish/reject/<?= $result->id ?>">Reject</a></li>
								<?php endif ?>
								<li><a href="admin/publish/edit/<?= $result->id ?>" target="_blank">Edit</a></li>
								<li><a href="admin/publish/delete/<?= $result->id ?>" target="_blank">Delete</a></li>
								<?php if ($result->is_published): ?>
								<li><a href="admin/publish/stats/<?= $result->id ?>" target="_blank">Stats</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php $publish = Date::out($result->date_publish); ?>
							<?= $publish->format('M j, Y') ?>&nbsp;
							<span class="muted"><?= $publish->format('H:i') ?></span>
							<div class="muted">
								<?php if ($result->is_published): ?>
								<span>Published</span>
								<?php elseif ($result->is_under_review): ?>
								<span>Under Review</span>
								<?php elseif ($result->is_draft): ?>
									<?php if ($result->is_rejected): ?>
									<span>Rejected</span>
									<?php else: ?>
									<span>Draft</span>
									<?php endif ?>
								<?php else: ?>
								<span>Scheduled</span>
								<?php endif ?>
								<?php if ($vd->type === Model_Content::TYPE_PR): ?>
								<?php if ($result->is_premium): ?>
								<span>(Premium)</span>
								<?php else: ?>
								<span>(Basic)</span>
								<?php endif ?>
								<?php endif ?>
							</div>
						</td>
						<?= $ci->load->view('admin/partials/owner-column', 
							array('result' => $result)); ?>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="clearfix">
				<div class="pull-left grid-report ta-left">
					All times are in UTC.
				</div>
				<div class="pull-right grid-report">
					Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> 
					<?= Model_Content::full_type_plural($vd->type) ?>
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>