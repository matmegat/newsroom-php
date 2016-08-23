<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Campaign Manager</h1>
				</div>
			</div>
		</header>
	</div>
</div>
			
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^admin/contact/campaign/all" 
				href="admin/contact/campaign/all<?= $vd->esc(gstring()) ?>">All</a></li>
			<li><a data-on="^admin/contact/campaign/sent" 
				href="admin/contact/campaign/sent<?= $vd->esc(gstring()) ?>">Sent</a></li>
			<li><a data-on="^admin/contact/campaign/scheduled" 
				href="admin/contact/campaign/scheduled<?= $vd->esc(gstring()) ?>">Scheduled</a></li>
			<li><a data-on="^admin/contact/campaign/draft" 
				href="admin/contact/campaign/draft<?= $vd->esc(gstring()) ?>">Draft</a></li>
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
						<th class="left">Campaign Name</th>	
						<th>Details</th>
						<th>Owner</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a class="view" href="admin/contact/campaign/edit/<?= $result->id ?>" target="_blank">
									<?= $vd->esc($vd->cut($result->name, 45)) ?>
								</a>
							</h3>	
							<ul>
								<li><a href="admin/contact/campaign/edit/<?= $result->id ?>" target="_blank">Edit</a></li>
								<li><a href="admin/contact/campaign/delete/<?= $result->id ?>" target="_blank">Delete</a></li>
								<?php if ($result->is_sent): ?>
								<li><a href="admin/contact/campaign/stats/<?= $result->id ?>" target="_blank">Stats</a></li>
								<?php endif ?>
								<?php if ($result->content_id): ?>
								<li><a href="<?= Model_Content::permalink_from_id($result->content_id) ?>" 
									target="_blank">Content</a></li>
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php $send = Date::out($result->date_send); ?>
							<?= $send->format('M j, Y') ?>&nbsp;
							<span class="muted"><?= $send->format('H:i') ?></span>
							<div class="muted">
								<?php if ($result->is_sent): ?>
								<span>Sent (<?= (int) $result->contact_count ?> Contacts)</span>								
								<?php elseif ($result->is_draft): ?>
								<span>Draft</span>
								<?php else: ?>
								<span>Scheduled</span>
								<?php endif ?>
							</div>
						</td>
						<td>
							<?php if ($result->user_id): ?>
							<div>								
								<a data-gstring="&amp;filter_user=<?= $result->user_id ?>"
									href="#" class="add-filter-icon"></a>							
								<a href="admin/users/view/<?= $result->user_id ?>" class="black">
									<?php if ($result->user_first_name && $result->user_last_name): ?>
									<?= $vd->esc($result->user_first_name) ?>
									<?= $vd->esc($result->user_last_name) ?>
									<?php else: ?>
									<?= $vd->esc($vd->cut($result->user_email, 30)) ?>									
									<?php endif ?>
								</a>
							</div>
							<?php endif ?>
							<div>
								<a data-gstring="&amp;filter_company=<?= $result->company_id ?>"
									href="#" class="add-filter-icon"></a>
								<a href="admin/companies/view/<?= $result->company_id ?>" class="status-muted smaller">
									<?= $vd->esc($result->company_name) ?>
								</a>
							</div>
						</td>
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
					Results
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>