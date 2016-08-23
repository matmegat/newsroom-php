<div class="alert alert-error with-btn">
	<?php if ($vd->m_content->is_under_review): ?>
	<strong>Not Published!</strong> 	
	This content is being reviewed by our staff. 
	<?php if (Auth::is_admin_online()): ?>
	<span class="pull-right">
		<a class="btn btn-mini btn-success" 
			href="admin/publish/approve/<?= $vd->m_content->id ?>/view">Approve</a>
		<a class="btn btn-mini btn-danger" 
			href="admin/publish/reject/<?= $vd->m_content->id ?>/view">Reject</a>
	</span>
	<?php endif ?>
	<?php elseif (($vd->m_content->type != Model_Content::TYPE_PR || $vd->m_content->is_approved) 
	              && new DateTime($vd->m_content->date_publish) < Date::$now
	              && !$vd->m_content->is_draft): ?>
	<strong>Scheduled!</strong> 	
	This content will be released within a few seconds.
	<?php elseif (!$vd->m_content->is_draft): ?>
	<strong>Scheduled!</strong> 	
	This content is scheduled for release. 
	<?php else: ?>
	<strong>Not Published!</strong> 	
	This content is only visible to the content creator. 
	<?php endif ?>
</div>