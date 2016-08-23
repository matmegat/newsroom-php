<div class="article-info">
	<?php if ($ci->is_common_host): ?>
	<span class="ai-category"><?= 
		Model_Content::full_type($vd->m_content->type) ?></span> -
	<?php else: ?>
	<a href="browse/<?= $vd->m_content->type ?>">
		<span class="ai-category"><?= 
			Model_Content::full_type($vd->m_content->type) ?></span>
	</a> -
	<?php endif ?>
	<span class="ai-date">
		<?php $dt_date_publish = Date::out($vd->m_content->date_publish); ?>
		<?php if ($dt_date_publish > Date::days(-2) && 
			$dt_date_publish->format('H:i') != '00:00'): ?>
		<?= $dt_date_publish->format('M j, Y H:i') ?>
		<?php else: ?>
		<?= $dt_date_publish->format('M j, Y') ?>
		<?php endif ?>
	</span>
</div>