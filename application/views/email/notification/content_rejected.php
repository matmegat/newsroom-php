Your <?= strtolower(Model_Content::full_type($content->type)) ?> submission has been rejected.

<br><br><code>
<b><?= $vd->esc($content->title) ?></b>
<br><?= str_repeat('-', strlen($content->title)) ?>
<?php if ($feedback['comments']): ?>
	<br><br><?= $feedback['comments'] ?>	
<?php endif ?>
<?php if (!empty($feedback['canned'])): ?>
	<?php foreach ((array) $feedback['canned'] as $canned): ?>
		<?php $canned = Model_Canned::find($canned); ?>
		<br><br><b><?= $vd->esc($canned->title) ?></b><br>
		<?= $vd->esc($canned->content) ?>	
	<?php endforeach ?>
<?php endif ?>
</code>