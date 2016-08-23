<header class="ln-block-header">
	<span class="ln-category">
		<?php if ($this->is_common_host): ?>
		<?= Model_Content::full_type($content->type) ?>
		<?php else: ?>
		<a href="browse/<?= $content->type ?>">
			<?= Model_Content::full_type($content->type) ?>
		</a>
		<?php endif ?>
	</span> -
	<span class="ln-date">
		<?php $dt_date_publish = Date::out($content->date_publish); ?>
		<?= $dt_date_publish->format('M j, Y') ?>
	</span>
</header>