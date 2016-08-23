<?php if ($vd->is_search): ?>
<div class="marbot-20"></div>
<?php else: ?>
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li>
				<a data-on="^manage/analyze/content/pr/published" 
					href="<?= gstring('manage/analyze/content/pr/published') ?>">
					Press Releases
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/news/published" 
					href="<?= gstring('manage/analyze/content/news/published') ?>">
					News
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/event/published" 
					href="<?= gstring('manage/analyze/content/event/published') ?>">
					Events
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/image/published" 
					href="<?= gstring('manage/analyze/content/image/published') ?>">
					Images
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/audio/published" 
					href="<?= gstring('manage/analyze/content/audio/published') ?>">
					Audio
				</a>
			</li>
			<li>
				<a data-on="^manage/analyze/content/video/published" 
					href="<?= gstring('manage/analyze/content/video/published') ?>">
					Video
				</a>
			</li>
		</ul>
	</div>
</div>
<?php endif ?>