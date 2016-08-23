<td>
	<?php if ($result->o_user_id): ?>
	<div>								
		<a data-gstring="&amp;filter_user=<?= $result->o_user_id ?>"
			href="#" class="add-filter-icon"></a>							
		<a href="admin/users/view/<?= $result->o_user_id ?>" class="black">
			<?php if ($result->o_user_first_name && $result->o_user_last_name): ?>
			<?= $vd->esc($result->o_user_first_name) ?>
			<?= $vd->esc($result->o_user_last_name) ?>
			<?php else: ?>
			<?= $vd->esc($vd->cut($result->o_user_email, 30)) ?>									
			<?php endif ?>
		</a>
	</div>
	<?php endif ?>
	<?php if ($result->o_company_id): ?>		
	<div>
		<a data-gstring="&amp;filter_company=<?= $result->o_company_id ?>"
			href="#" class="add-filter-icon"></a>
		<a href="admin/companies/view/<?= $result->o_company_id ?>" class="status-muted smaller">
			<?= $vd->esc($vd->cut($result->o_company_name, 20)) ?>
		</a>
	</div>
	<?php endif ?>
</td>