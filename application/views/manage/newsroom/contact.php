<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Company Contacts</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/newsroom/contact/edit" class="bt-publish bt-orange">Add Contact</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>
		
<?php if (!$ci->newsroom->is_active): ?>
<div class="below-header-feedback marbot-20">
	<div class="alert alert-info">
		<strong>Attention!</strong> Additional contacts are not visible 
		for companies without a newsroom. Only the main contact is used.
	</div>
</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
			<table class="grid">
				<thead>
					
					<tr>
						<th class="left">Contact Name</th>
						<th>Title</th>
						<th>Email</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $result): ?>
					<tr>
						<td class="left <?= value_if_test(isset($result->image_url), 'finger') ?>">
							<?php if (isset($result->image_url)): ?>
							<a href="manage/newsroom/contact/edit/<?= $result->id ?>">
								<img src="<?= $result->image_url ?>" />
							</a>
							<?php endif ?>
							<h3>
								<a href="manage/newsroom/contact/edit/<?= $result->id ?>">
									<?= $vd->esc($result->name) ?>
								</a>
								<?php if ($result->id == $this->newsroom->company_contact_id): ?>
								(Press Contact)
								<?php endif ?>
							</h3>
							<ul>
								<li><a href="view/contact/<?= $result->id ?>">View</a></li>	
								<li><a href="manage/newsroom/contact/edit/<?= $result->id ?>">Edit</a></li>
								<li><a href="manage/newsroom/contact/delete/<?= $result->id ?>">Delete</a></li>
							</ul>
						</td>
						<td>
							<?= $vd->esc($result->title) ?>
						</td>
						<td>
							<?= $vd->esc($result->email) ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="grid-report">Displaying <?= count($vd->results) ?> 
				of <?= $vd->chunkination->total() ?> Company Contacts</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>