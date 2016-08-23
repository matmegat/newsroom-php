<section class="ap-block ap-status">
	<h3>
		Status: 
		<?php if (!@$vd->m_content): ?>
		<span>Not Saved</span>
		<?php elseif ($vd->m_content->is_published): ?>
		<span>Published</span>
		<?php elseif ($vd->m_content->is_under_review): ?>
		<span>Under Review</span>
		<?php elseif ($vd->m_content->is_draft): ?>
		<span>Not Published (Draft)</span>
		<?php else: ?>
		<span>Scheduled</span>
		<?php endif ?>
	</h3>
</section>