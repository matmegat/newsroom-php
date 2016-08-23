<header class="page-header">
	<div class="row-fluid">
		<div class="span6">
			<h1>iContact Overview</h1>
		</div>
	</div>
</header>

<div class="content overview-combined-list overview-contact">
	<div class="grid-content">

		<div class="tab-pane active" id="all">
			<table class="grid">
				<thead>
					<tr>
						<th class="left">Company Name</th>
						<th class="left">Latest Email Campaigns</th>
						<th>Send Date <sup>&dagger;</sup></th>
						<th>Contacts</th>
						<th>Viewed <sup>*</sup></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($vd->results as $newsroom): ?>
					<?php
					
					$create_list_modal = new Modal();
					$create_list_modal->set_title('List Name');
					$modal_view = 'manage/overview/contact/partials/list_name';
					$modal_content = $ci->load->view($modal_view, 
						array('newsroom' => $newsroom), true);
					$create_list_modal->set_content($modal_content);
					$ci->add_eob($create_list_modal->render(400, 44));
					
					?>
					<tr>
						<td class="left vtm">
							<table class="sub-table-title">
								<tr>
									<td>
										<?php if ($lo_im = Model_Image::find($newsroom->logo_image_id)): ?>
										<?php $lo_variant = $lo_im->variant('header-finger'); ?>
										<?php $url = Stored_File::url_from_filename($lo_variant->filename); ?>
										<a href="<?= $newsroom->url('manage/contact') ?>">
											<div class="image-container flex"><img src="<?= $url ?>" /></div>
										</a>
										<?php else: ?>
										<a href="<?= $newsroom->url('manage/contact') ?>">
											<div class="image-container flex">
												<img src="<?= $vd->assets_base ?>im/trans.gif" class="blank" />
											</div>
										</a>
										<?php endif ?>
									</td>
									<td class="left">
										<div class="company-name"><strong><?= $vd->esc($newsroom->company_name) ?></strong></div>
										<div class="btn-group">
											<a class="dropdown-toggle" data-toggle="dropdown" href="#">
												Manage<span class="caret"></span>
											</a>
											<ul class="dropdown-menu">
												<li><a href="<?= $newsroom->url('manage/contact/campaign/edit') ?>">New Campaign</a></li>
												<li><a href="<?= $newsroom->url('manage/contact/contact/edit') ?>">New Contact</a></li>
												<li><a data-toggle="modal" href="#<?= $create_list_modal->id ?>">New Contact List</a></li>
												<li><a href="<?= $newsroom->url('manage/contact/import') ?>">Import Contacts</a></li>												
											</ul>
										</div>
									</td>
								</tr>
							</table>
						</td>
						<td class="content-list">
							<ul class="td-list">
								<?php if (count($newsroom->content)): ?>
								<?php foreach ($newsroom->content as $content): ?>
								<li>
									<a href="<?= $newsroom->url("manage/contact/campaign/edit/{$content->id}") ?>">Edit</a>
									<a href="<?= $newsroom->url("manage/contact/campaign/edit/{$content->id}") ?>">
										<?= $vd->esc($vd->cut($content->name, 38)) ?></a>
								</li>
								<?php endforeach ?>								
								<?php else: ?>
								<li>No Campaigns</li>
								<?php endif ?>
							</ul>
						</td>
						<td class="content-date">
							<ul class="td-list td-list-center">
								<?php foreach ((array) $newsroom->content as $content): ?>
								<li>
									<?php if ($content->is_sent): ?>
									<span class="status"><img class="tl" title="Sent"
										src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>									
									<?php elseif ($content->is_draft): ?>
									<span class="status"><img class="tl" title="Draft"
										src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
									<?php else: ?>
									<span class="status"><img class="tl" title="Scheduled"
										src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
									<?php endif ?>
									<?php $dt_send = Date::out($content->date_send, $newsroom->timezone); ?>
									<?= $dt_send->format('d M Y') ?>
								</li>
								<?php endforeach ?>								
							</ul>
						</td>
						<td class="td-list-last">
							<ul class="td-list td-list-center">
								<?php foreach ((array) $newsroom->content as $content): ?>
								<li>
									<?php if ($content->is_sent || $content->is_send_active): ?>
									<span><?= $content->contact_count ?></span>
									<?php else: ?>
									<span><?= $content->credits_required() ?></span>
									<?php endif ?>
								</li>
								<?php endforeach ?>
							</ul>
						</td>
						<td class="td-list-last">
							<ul class="td-list td-list-center">
								<?php foreach ((array) $newsroom->content as $content): ?>
								<?php if ($content->is_sent): ?>
								<li><a href="<?= $newsroom->url("manage/analyze/email/view/{$content->id}") ?>">
									<span><?= (int) $content->view_rate ?>%</span></a></li>
								<?php else: ?>
								<li><span>N/A</span></li>
								<?php endif ?>
								<?php endforeach ?>
							</ul>
						</td>
					</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		</div>
		
		<div class="clearfix">
			<div class="pull-left grid-report ta-left">
				Dates are shown using company specific timezones.
				<br />* Some email clients do not allow views to be tracked.
				<br />&dagger; Assumes that the content is published.
			</div>
			<div class="pull-right grid-report">			
				Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> Companies</div>
			</div>
		</div>
			
		<?= $vd->chunkination->render() ?>
		
	</div>
</div>