<section class="aside-block aside-pr-views">
	<h3>Press Release Views</h3>			
	<div class="aside-content aside-content-border">				
		<ul class="aside-pr-list">
			<li>
				<a href="manage/analyze/content/pr/published">
					<span class="aside-pr-content-value"><?= $vd->pr_hits_week ?></span></a>
				<span class="aside-pr-content-label">This Week</span>
			</li>
			<li>
				<a href="manage/analyze/content/pr/published">
					<span class="aside-pr-content-value"><?= $vd->pr_hits_month ?></span></a>
				<span class="aside-pr-content-label">This Month</span>
			</li>
		</ul>
		<?php if ($ci->use_overview): ?>
		<a href="manage/overall/analyze/content" class="aside-pr-link-stats">View Stats »</a>
		<?php else: ?>
		<a href="manage/analyze/content/pr/published" class="aside-pr-link-stats">View Stats »</a>
		<?php endif ?>
	</div>
</section>