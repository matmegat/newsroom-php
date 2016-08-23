<div style="width: 500px; margin: 20px auto;">
	<?php if ($vd->result): ?>
	<div class="alert alert-success">
		<strong>Success!</strong> Your subscription has been removed. 
	</div>
	<?php else: ?>
	<div class="alert alert-success">
		<strong>Error!</strong> Invalid subscription data. 
	</div>
	<?php endif ?>
</div>