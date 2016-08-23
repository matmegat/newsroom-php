<div><?= $content ?></div>
<?php if (isset($unsubscribe)): ?>
<div style="color: #999999;">
	<br /><br />
	<a href="<?= $unsubscribe ?>" style="color: #7BABC1;">Unsubscribe</a> 
	from these emails. 
</div>
<?php endif ?>
<?php if (isset($pixel)): ?>
<img src="<?= $pixel ?>" width="1" height="1" />
<?php endif ?>