<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Contacts Manager</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<form action="manage/contact/list/edit/save" method="post">
							<input type="text" placeholder="List Name" name="name" />
							<button type="submit" class="bt-publish bt-orange">
								Create List
							</button>
						</form>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/contact/list" href="manage/contact/list">Lists</a></li>
			<li><a data-on="^manage/contact/contact" href="manage/contact/contact">Contacts</a></li>
		</ul>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">List Name</th>
						<th>Date Created</th>
						<th>Latest Campaign</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left">
							<h3>
								<a href="manage/contact/list/edit/<?= $result->id ?>">
									<?= $vd->esc($result->name) ?> 
									<span class="muted">(<?= (int) $result->count_contacts ?>)</span>
								</a>
							</h3>
							<ul>
								<li><a href="manage/contact/list/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="manage/contact/list/delete/<?= $result->id ?>">Delete</a></li>
								<li><a href="manage/contact/list/download/<?= $result->id ?>">Export</a></li>
								<li><a href="manage/contact/contact/edit/from/<?= $result->id ?>">Add Contact</a></li>
							</ul>
						</td>
						<td>
							<?php $created = Date::out($result->date_created); ?>
							<?= $created->format('M j, Y') ?>
						</td>
						<td>
							<?php if ($result->last_campaign_id): ?>
							<a href="manage/analyze/email/view/<?= $result->last_campaign_id ?>">
								<?= $vd->esc($result->last_campaign_name) ?>
							</a>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="grid-report">Displaying <?= count($vd->results) ?> 
				of <?= $vd->chunkination->total() ?> Lists</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>