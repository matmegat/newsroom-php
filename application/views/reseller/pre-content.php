<section class="main">
	<div class="container">
		<?php if ($ci->feedback): ?>
		<?php $ci->clear_feedback(); ?>
		<div id="feedback">
			<?php foreach ($ci->feedback as $feedback): ?>
			<div class="feedback"><?= $feedback ?></div>
			<?php endforeach ?>
		</div>
		<?php endif ?>