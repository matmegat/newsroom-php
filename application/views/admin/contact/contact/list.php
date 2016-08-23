<div class="row-fluid marbot-20">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Contacts Manager</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<?= $this->load->view('admin/partials/filters'); ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Contact Email</th>	
						<th>Information</th>
						<th>Owner</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a class="view" href="admin/contact/contact/edit/<?= $result->id ?>" target="_blank">
									<?= $vd->esc($vd->cut($result->email, 45)) ?>
								</a>
							</h3>	
							<ul>
								<li><a href="admin/contact/contact/edit/<?= $result->id ?>" target="_blank">Edit</a></li>
								<li><a href="admin/contact/contact/delete/<?= $result->id ?>" target="_blank">Delete</a></li>
								<?php if ($result->is_unsubscribed): ?>
								<li><a class="muted">Unsubscribed</a></li>
								<?php else: ?>
								<li><a href="admin/contact/contact/unsubscribe/<?= $result->id ?>">
									Unsubscribe</a></li>		
								<?php endif ?>
							</ul>
						</td>
						<td>
							<?php if (!$result->first_name &&
							          !$result->last_name &&
							          !$result->contact_company_name): ?>
							<span>-</span>
							<?php else: ?>							          
							<?= $vd->esc($result->first_name) ?>
							<?= $vd->esc($result->last_name) ?>
							<div class="muted">
								<?= $vd->esc($result->contact_company_name) ?>
							</div>
							<?php endif ?>
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
					Results
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>