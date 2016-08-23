<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Search Results</h1>
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
							</h3>
							<ul>
								<li><a href="contact/<?= $result->id ?>">View</a></li>	
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