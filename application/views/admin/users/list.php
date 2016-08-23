<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Users</h1>
				</div>
				<div class="span6">
					<a href="admin/users/view" class="btn bt-silver pull-right">New User</a>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^admin/users/all" 
				href="admin/users/all<?= $vd->esc(gstring()) ?>">All</a></li>
			<li><a data-on="^admin/users/reseller" 
				href="admin/users/reseller<?= $vd->esc(gstring()) ?>">Resellers</a></li>
			<li><a data-on="^admin/users/admin" 
				href="admin/users/admin<?= $vd->esc(gstring()) ?>">Admins</a></li>
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
						<th class="left">User</th>
						<th>Details</th>
						<th>Created</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a href="admin/publish?filter_user=<?= $result->id ?>" 
									class="add-filter-icon"></a>
								<a class="view" href="admin/users/view/<?= $result->id ?>">
									<?= $vd->esc($result->email) ?>
									<?php if (!$result->email): ?>
									<span class="status-muted">
										<?= $result->id ?>
									</span>
									<?php endif ?>
								</a>
							</h3>	
							<ul>
								<li><a href="admin/users/view/<?= $result->id ?>">Edit</a></li>
								<li><a href="<?= Admo::url('manage', $result->id) ?>" target="_blank" 
									class="status-false">Admin Session</a></li>
							</ul>
						</td>
						<td>
							<div>
								<?= $vd->esc($result->first_name) ?>
								<?= $vd->esc($result->last_name) ?>
							</div>
							<div class="muted">
								<?php if ($result->is_active && $result->is_verified): ?>
									<?= value_if_test($result->is_admin, 'Admin'); ?>
									<?= value_if_test($result->is_reseller, 'Reseller'); ?>
									<?php if (!$result->is_admin && !$result->is_reseller): ?>
										Normal User
									<?php endif ?>
								<?php else: ?>
								<?php if ($result->is_verified): ?>
									Verified, Disabled
								<?php else: ?>
									Not Verified
								<?php endif ?>
								<?php endif ?>
							</div>
						</td>
						<td>
							<?php $created = Date::out($result->date_created); ?>
							<?= $created->format('M j, Y') ?>&nbsp;
							<span class="muted"><?= $created->format('H:i') ?></span>
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
					Users
				</div>
			</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>