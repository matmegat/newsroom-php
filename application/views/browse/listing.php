<div class="span8 main-content">
	<section class="latest-news">
		
		<header class="ln-header">
			
			<?php if (isset($vd->ln_header)): ?>
			<h2><?= $vd->esc($vd->ln_header) ?></h2>
			<?php elseif (isset($vd->ln_header_html)): ?>
			<h2><?= $vd->ln_header_html ?></h2>
			<?php endif ?>
			
		</header>
		
		<div id="ln-container" class="masonry">
			
			<?php if(count($vd->results) == 0) : ?>
			
				<div class="simple-content-area inner-content">
					<h4>No Data</h4><hr />
					<p>No content was found in this section. 
						Why not try another?</p>
				</div>
				
			<?php else : ?>
			
				<?php foreach ($vd->results as $result): ?>
				<?= $ci->load->view("browse/listing/{$result->type}", 
					array('content' => $result)); ?>
				<?php endforeach ?>
				
			<?php endif; ?>
			
		</div>
		
	</section>	
</div>