<?php if ($report->type === Report_Email::TYPE_PR): ?>
<p><strong>Press Release Report</strong></p>
<?php else:  ?>
<p><strong>Newsroom Report</strong></p>
<?php endif ?>
<p>
	For: <em><?= $vd->esc($report->context) ?></em><br />
	Date: <em><?= date('M j, Y') ?></em><br />
	See attached file.
</p>