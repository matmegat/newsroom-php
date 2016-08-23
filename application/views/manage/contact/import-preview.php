<h2>Data Preview</h2>

<table class="grid" id="selectable-results">
	<thead>
		
		<tr>
			<th class="left">Contact</th>
			<th>Company</th>
			<th>Title</th>
		</tr>
		
	</thead>
	<tbody>
		
		<?php foreach ($vd->results as $result): ?>
		<tr>
			<td class="left">
				<h3 class="contact-name">
					<?php if ($result->first_name || $result->last_name): ?>
					<div class="marbot-5">
						<?= $vd->esc($result->first_name) ?>
						<?= $vd->esc($result->last_name) ?>
					</div>
					<div class="muted">
						<?= $vd->esc($result->email) ?>
					</div>
					<?php else: ?>
					<div>
						<?= $vd->esc($result->email) ?>
					</div>
					<?php endif ?>
				</h3>		
			</td>
			<td>
				<?php if ($result->company_name): ?>
				<?= $vd->esc($result->company_name) ?>
				<?php else: ?>
				<span>-</span>
				<?php endif ?>
			</td>
			<td>
				<?php if ($result->title): ?>
				<?= $vd->esc($result->title) ?>
				<?php else: ?>
				<span>-</span>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>

	</tbody>
</table>