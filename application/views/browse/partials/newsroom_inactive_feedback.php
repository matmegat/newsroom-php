<div class="alert alert-warning">
	<strong>Warning!</strong> This newsroom is not visible to the public. 
	It is not active 
	<?php if (Auth::user()->newsroom_credits_available()): ?>
	(<a href="manage/companies/activate_and_return/<?= 
		$ci->newsroom->company_id ?>">activate</a> now).
	<?php else: ?>
	(<a href="manage/upgrade">upgrade</a> now).
	<?php endif ?> 
</div>