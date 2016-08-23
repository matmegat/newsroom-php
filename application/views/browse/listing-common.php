<div class="main-content news-center-listing">
	<section class="latest-news">	
		
		<header class="ln-header">
			
			<?php if (isset($vd->ln_header)): ?>
			<h2><?= $vd->esc($vd->ln_header) ?></h2>
			<?php elseif (isset($vd->ln_header_html)): ?>
			<h2><?= $vd->ln_header_html ?></h2>
			<?php endif ?>
			
		</header>
		
		<div id="ln-container" class="masonry">
			
			<?php foreach ($vd->results as $result): ?>
			<?= $ci->load->view("browse/listing/{$result->type}", 
				array('content' => $result)); ?>
			<?php endforeach ?>
			
		</div>
	</section>
</div>