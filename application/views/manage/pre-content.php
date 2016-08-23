<section class="main">
	<div class="container">
		<div id="feedback">
		<?php if ($ci->feedback): ?>
		<?php $ci->clear_feedback(); ?>
		<?php foreach ($ci->feedback as $feedback): ?>
		<div class="feedback"><?= $feedback ?></div>
		<?php endforeach ?>
		<?php endif ?>
		</div>