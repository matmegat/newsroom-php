<div class="alert alert-info">
	You have activated <strong class="newsroom-credits-used"><?= 
	$credits_used = Auth::user()->newsroom_credits_used() ?></strong> 
	newsroom(s) and have <strong class="newsroom-credits-available"><?= 
	(($credits_total = Auth::user()->newsroom_credits_total())
	 - $credits_used) ?></strong> credits left.
</div>