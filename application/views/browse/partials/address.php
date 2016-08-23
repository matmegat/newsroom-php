<?php if (@$vd->nr_profile->address_street ||
			 @$vd->nr_profile->address_city ||
			 @$vd->nr_profile->address_state ||
			 @$vd->nr_profile->address_zip ||
			 @$vd->nr_profile->address_phone): ?>	
       						          
<section class="al-block al-adr">
	<h3>Address</h3>
	<address class="adr">
		<span class="adr-org"><?= $vd->esc($ci->newsroom->company_name) ?></span>
		<span class="street-address">
			<?= $vd->esc(@$vd->nr_profile->address_apt_suite) ?>
			<?= $vd->esc(@$vd->nr_profile->address_street) ?>
		</span>
		<span class="postal-region">
			<?php if (@$vd->nr_profile->address_city): ?>
			<?= $vd->esc(@$vd->nr_profile->address_city) ?><br />
			<?php endif ?>
			<?= $vd->esc(@$vd->nr_profile->address_state) ?>
			<?php if (strlen(@$vd->nr_profile->address_state) > 4): ?><br /><?php endif ?>
			<?= $vd->esc(@$vd->nr_profile->address_zip) ?>
		</span>
		<span class="adr-tel">
			<?= $vd->esc(@$vd->nr_profile->phone) ?>
		</span>
	</address>
</section>		
				
<?php endif ?>