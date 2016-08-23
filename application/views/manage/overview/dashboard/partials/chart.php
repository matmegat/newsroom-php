<?php if ($ci->use_overview): ?>
<section class="home-chart-panel">
	<div class="home-chart" id="home_chart">
		<a href="manage/overview/analyze/overall" class="stats-loader"
			style="width: 460px; height: 100px;">
			<img src="manage/overview/dashboard/chart" />
		</a>
	</div>
</section>
<?php elseif ($this->newsroom->is_active): ?>
<section class="home-chart-panel">
	<div class="home-chart" id="home_chart">
		<a href="manage/analyze/overall" class="stats-loader"
			style="width: 460px; height: 100px;">
			<img src="manage/dashboard/chart" />
		</a>
	</div>
</section>
<?php else: ?>
<div class="marbot-10"></div>
<?php endif ?>