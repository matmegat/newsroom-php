<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Video Guides</h1>
				</div>				
				<div class="span6">
					<a href="admin/settings/guides/edit" 
						class="btn bt-silver pull-right">New Guide</a>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Guide</th>
						<th>Section</th>
						<th>Updated</th>
					</tr>
					
				</thead>
				<tbody class="results">
					
					<?php foreach ($vd->results as $result): ?>
					<tr data-id="<?= $result->id ?>" class="result">
						<td class="left">
							<h3>
								<a class="view" href="admin/settings/guides/edit/<?= $result->id ?>">
									<?= $vd->esc($vd->cut($result->title, 45)) ?>
								</a>
							</h3>
							<ul>
								<li><a href="admin/settings/guides/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="admin/settings/guides/delete/<?= $result->id ?>">Delete</a></li>
							</ul>
						</td>
						<td>
							<?= ucwords($result->section) ?>
						</td>
						<td>
							<?php $dt_updated = Date::out($result->date_updated) ?>
							<?= $dt_updated->format('M j, Y'); ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
		
		</div>
	</div>
</div>