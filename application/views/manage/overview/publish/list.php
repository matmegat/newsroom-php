<header class="page-header">
	<div class="row-fluid">
		<div class="span6">
			<h1>iPublish Overview</h1>
		</div>
		<div class="span6">
			<div class="pull-right">
				<form action="manage/companies/create" method="post">
					<input type="text" placeholder="Company Name" name="company_name" />
					<button type="submit" class="bt-publish bt-orange">
						Create
					</button>
				</form>
			</div>
		</div>
	</div>
</header>

<div class="content overview-combined-list overview-publish">
	<div class="grid-content">

		<div class="tab-pane active" id="all">
			<table class="grid">
				<thead>
					<tr>
						<th class="left">Company Name</th>
						<th class="left">Latest Press Releases</th>
						<th>Date</th>
						<th>Views</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($vd->results as $newsroom): ?>
					<tr>
						<td class="left vtm">
							<table class="sub-table-title">
								<tr>
									<td>
										<?php if ($lo_im = Model_Image::find($newsroom->logo_image_id)): ?>
										<?php $lo_variant = $lo_im->variant('header-finger'); ?>
										<?php $url = Stored_File::url_from_filename($lo_variant->filename); ?>
										<a href="<?= $newsroom->url('manage/publish') ?>">
											<div class="image-container flex"><img src="<?= $url ?>" /></div>
										</a>
										<?php else: ?>
										<a href="<?= $newsroom->url('manage/publish') ?>">
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
												<li><a href="<?= $newsroom->url('manage/publish/pr') ?>">Press Releases</a></li>
												<li><a href="<?= $newsroom->url('manage/publish/news') ?>">News</a></li>
												<li><a href="<?= $newsroom->url('manage/publish/event') ?>">Events</a></li>
												<li><a href="<?= $newsroom->url('manage/publish/image') ?>">Images</a></li>
												<li><a href="<?= $newsroom->url('manage/publish/audio') ?>">Audio</a></li>
												<li><a href="<?= $newsroom->url('manage/publish/video') ?>">Video</a></li>
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
									<a href="view/<?= $content->slug ?>">View</a>
									<a href="<?= $newsroom->url("manage/publish/pr/edit/{$content->id}") ?>">Edit</a>
									<a href="view/<?= $content->slug ?>"><?= $vd->esc($vd->cut($content->title, 45)) ?></a>
								</li>
								<?php endforeach ?>
								<?php else: ?>
								<li>No Press Releases</li>
								<?php endif ?>
							</ul>
						</td>
						<td class="content-date">
							<ul class="td-list td-list-center">
								<?php foreach ((array) $newsroom->content as $content): ?>
								<li>
									<?php if ($content->is_published): ?>
									<span class="status"><img class="tl" title="Published"
										src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>
									<?php elseif ($content->is_under_review): ?>
									<span class="status"><img class="tl" title="Under Review"
										src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
									<?php elseif ($content->is_draft): ?>
									<span class="status"><img class="tl" title="Draft"
										src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
									<?php else: ?>
									<span class="status"><img class="tl" title="Scheduled"
										src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
									<?php endif ?>
									<?php $dt_publish = Date::out($content->date_publish, $newsroom->timezone); ?>
									<?= $dt_publish->format('d M Y') ?>
								</li>
								<?php endforeach ?>								
							</ul>
						</td>
						<td class="td-list-last">
							<ul class="td-list td-list-center">
								<?php foreach ((array) $newsroom->content as $content): ?>
								<?php if ($content->is_published): ?>
								<li><a href="<?= $newsroom->url("manage/analyze/content/view/{$content->id}") ?>">
									<span><?= (int) $content->hits ?></span></li>
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
			</div>
			<div class="pull-right grid-report">			
				Displaying <?= count($vd->results) ?> 
					of <?= $vd->chunkination->total() ?> Companies</div>
			</div>
		</div>
			
		<?= $vd->chunkination->render() ?>
		
	</div>
</div>