<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Companies</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^admin/companies/all" 
				href="admin/companies/all<?= $vd->esc(gstring()) ?>">All</a></li>			
			<li><a data-on="^admin/companies/basic" 
				href="admin/companies/basic<?= $vd->esc(gstring()) ?>">Basic</a></li>
			<li><a data-on="^admin/companies/newsroom" 
				href="admin/companies/newsroom<?= $vd->esc(gstring()) ?>">Newsroom</a></li>
			<li><a data-on="^admin/companies/archived" 
				href="admin/companies/archived<?= $vd->esc(gstring()) ?>">Archived</a></li>
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
						<th class="left">Company</th>
						<th>Status</th>
						<th>Owner</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a href="admin/publish?filter_company=<?= $result->id ?>" 
									class="add-filter-icon"></a>
								<a class="view" href="<?= $result->url('manage') ?>" target="_blank">
									<?= $vd->esc($vd->cut($result->company_name, 45)) ?>
								</a>
							</h3>	
							<ul>
								<li><a href="<?= $result->url('manage/newsroom/company') ?>" target="_blank">Profile</a></li>
								<li><a href="<?= $result->url('manage/newsroom/customize') ?>" target="_blank">Customize</a></li>
								<li><a href="<?= $result->url('manage/analyze') ?>" target="_blank">Stats</a></li>
							</ul>
						</td>
						<td>
							<?php if ($result->is_active): ?>
							<span><a href="<?= $result->url() ?>">Newsroom</a></span>
							<?php elseif ($result->is_archived): ?>
							<span class="muted">Archived</span>
							<?php elseif ($result->is_legacy): ?>
							<span>Basic</span>
							<?php else: ?>
							<span>Basic</span>
							<?php endif ?>
						</td>
						<td>
							<?php if ($result->o_user_id): ?>
							<div>								
								<a data-gstring="&amp;filter_user=<?= $result->o_user_id ?>"
									href="#" class="add-filter-icon"></a>							
								<a href="admin/users/view/<?= $result->o_user_id ?>" class="black">
									<?php if ($result->o_user_first_name && $result->o_user_last_name): ?>
									<?= $vd->esc($result->o_user_first_name) ?>
									<?= $vd->esc($result->o_user_last_name) ?>
									<?php else: ?>
									<?= $vd->esc($vd->cut($result->o_user_email, 30)) ?>									
									<?php endif ?>
								</a>
							</div>
							<?php endif ?>
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
					Companies
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>