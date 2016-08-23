<section class="membership-level-agency">
	<h2>
		Membership Level 
		<strong><?= Auth::user()->package_name() ?></strong>
	</h2>
	
	<ul class="mla-list">
		<li>
			<span class="mla-label">Premium PR Credits</span>
			<span class="mla-value">				
				<a href="#" class="tl info-hover-a" title="<?= $vd->pr_credits_premium->available ?> Available">
					<img src="<?= $vd->assets_base ?>im/fugue-icons/external.png" /></a>
				<?= $vd->pr_credits_premium->used ?>
				of <?= $vd->pr_credits_premium->total ?>
		</li>
		<li>
			<span class="mla-label">Basic PR Credits</span>
			<span class="mla-value">
				<a href="#" class="tl info-hover-a" title="<?= $vd->pr_credits_basic->available ?> Available">
					<img src="<?= $vd->assets_base ?>im/fugue-icons/external.png" /></a>
				<?= $vd->pr_credits_basic->used ?>
				of <?= $vd->pr_credits_basic->total ?>
			</span>
		</li>
		<li>
			<span class="mla-label">Email Credits</span>
			<span class="mla-value">
				<a href="#" class="tl info-hover-a" title="<?= $vd->email_credits->available ?> Available">
					<img src="<?= $vd->assets_base ?>im/fugue-icons/external.png" /></a>
				<?= $vd->email_credits->used ?>
				of <?= $vd->email_credits->total ?>
			</span>
		</li>
	</ul>
</section>