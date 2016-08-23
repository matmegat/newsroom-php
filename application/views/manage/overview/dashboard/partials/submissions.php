<section class="agency-overview">			
	<section class="ao-content">
		<div class="rm-tabs-block">
			<ul class="inline tabs-header">
				<li class="active"><a data-toggle="tab" href="#rm_press_releases"><span>Press Releases</span></a></li>
				<li><a data-toggle="tab" href="#rm_campaigns"><span>Email Campaigns</span></a></li>
			</ul>
			<div class="tab-content">
				<div id="rm_press_releases" class="tab-pane active">
					<ul class="rm-list">
						<?php if (!count($vd->prs)): ?>
						<li><span class="rm-title">None</span></li>
						<?php endif ?>
						<?php foreach ($vd->prs as $pr): ?>
						<li>
							<span class="rm-title">
								<?php if ($pr->is_published): ?>
								<span class="status"><img alt="Published"
									src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>
								<?php elseif ($pr->is_under_review): ?>
								<span class="status"><img alt="Under Review"
									src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
								<?php elseif ($pr->is_draft): ?>
								<span class="status"><img alt="Draft"
									src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
								<?php else: ?>
								<span class="status"><img alt="Scheduled"
									src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
								<?php endif ?>
								<a href="<?= $pr->mock_nr->url() ?>manage/publish/pr/edit/<?= $pr->id ?>">
									<?= $vd->esc($vd->cut($pr->title, 40)) ?>
								</a>
							</span>
							<span class="rm-link">											
								<?php if ($pr->is_published): ?>
								<a href="<?= $pr->mock_nr->url() ?>manage/analyze/content/view/<?= $pr->id ?>">Stats</a> | 											
								<?php endif ?>
								<?php if (!Auth::user()->is_free_user() || ($pr->is_premium && $pr->is_published)): ?>
								<a href="<?= $pr->mock_nr->url() ?>manage/contact/campaign/edit/from/<?= $pr->id ?>">Email</a> |
								<?php endif ?>
								<a href="<?= $ci->common()->url($pr->url()) ?>" target="_blank">View</a>
							</span>
						</li>
						<?php endforeach ?>
					</ul>
				</div>
				<div id="rm_campaigns" class="tab-pane">
					<ul class="rm-list">
						<?php if (!count($vd->emails)): ?>
						<li><span class="rm-title">None</span></li>
						<?php endif ?>
						<?php foreach ($vd->emails as $email): ?>
						<li>
							<span class="rm-title">
								<?php if ($email->is_sent): ?>
								<span class="status"><img alt="Sent"
									src="<?= $vd->assets_base ?>im/tick-circle.png" /></span>
								<?php elseif ($email->is_send_active): ?>
								<span class="status"><img alt="Send Active"
									src="<?= $vd->assets_base ?>im/hourglass.png" /></span>
								<?php elseif ($email->is_draft): ?>
								<span class="status"><img alt="Draft"
									src="<?= $vd->assets_base ?>im/pencil-button.png" /></span>
								<?php else: ?>
								<span class="status"><img alt="Scheduled"
									src="<?= $vd->assets_base ?>im/clock-select-remain.png" /></span>
								<?php endif ?>
								<a href="<?= $email->mock_nr->url() ?>manage/contact/campaign/edit/<?= $email->id ?>">
									<?= $vd->esc($vd->cut($email->name, 40)) ?>
								</a>
							</span>
							<span class="rm-link">
								<?php if ($email->is_sent): ?>
								<a href="<?= $email->mock_nr->url() ?>manage/analyze/email/view/<?= $email->id ?>">Stats</a> |
								<?php endif ?>
								<a href="<?= $email->mock_nr->url() ?>manage/contact/campaign/edit/<?= $email->id ?>">Edit</a>
							</span>
						</li>
						<?php endforeach ?>
					</ul>
				</div>
			</div>
		</div>
	</section>
</section>