<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if ($ci->input->get('terms')): ?>
					<h1>Search Results</h1>
					<?php else: ?>
					<h1>Email Campaigns</h1>
					<?php endif ?>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/contact/campaign/edit" class="bt-publish bt-orange">New Campaign</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/contact/campaign/all" 
				href="<?= gstring('manage/contact/campaign/all') ?>">All</a></li>
			<li><a data-on="^manage/contact/campaign/sent" 
				href="<?= gstring('manage/contact/campaign/sent') ?>">Sent</a></li>
			<li><a data-on="^manage/contact/campaign/scheduled" 
				href="<?= gstring('manage/contact/campaign/scheduled') ?>">Scheduled</a></li>
			<li><a data-on="^manage/contact/campaign/draft" 
				href="<?= gstring('manage/contact/campaign/draft') ?>">Draft</a></li>
		</ul>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content listing">
			
				<table class="grid" id="selectable-results">
					<thead>
						
						<tr>
							<th class="left">Name</th>
							<th>Content</th>
							<th>Send Date*</th>
							<th>Status</th>
							<th>Sent</th>
						</tr>
						
					</thead>
					<tbody>
						
						<?php foreach ($vd->results as $result): ?>
						<tr>
							<td class="left">
								<h3 class="contact-name">
									<a href="manage/contact/campaign/edit/<?= $result->id ?>">
										<?= $vd->esc($vd->cut($result->name, 45)) ?>
									</a>
								</h3>
								<ul>
									<li><a href="manage/contact/campaign/edit/<?= $result->id ?>">Edit</a></li>
									<li><a href="manage/contact/campaign/delete/<?= $result->id ?>">Delete</a></li>
									<?php if ($result->is_sent): ?>
									<li><a href="manage/analyze/email/view/<?= $result->id ?>">Statistics</a></li>
									<?php endif ?>
								</ul>
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
								<?php if ($result->is_sent): ?>
								<span>Sent</span>
								<?php elseif ($result->is_draft): ?>
								<span>Draft</span>
								<?php else: ?>
								<span>Scheduled</span>
								<?php endif ?>
							</td>
							<td>
								<?php if ($result->is_sent || $result->is_send_active): ?>
								<span><?= (int) $result->contact_count ?></span>
								<?php else: ?>
								<span>-</span>
								<?php endif ?>
							</td>
						</tr>
						<?php endforeach ?>

					</tbody>
				</table>
				
				<div class="clearfix">
					<div class="pull-left grid-report">
						* Assumes that the content is published.
					</div>
					<div class="pull-right grid-report">Displaying <?= count($vd->results) ?> 
						of <?= $vd->chunkination->total() ?> Campaigns</div>
					</div>
				</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>