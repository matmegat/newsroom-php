<div class="span8 main-content">
	<section class="latest-news">
		
		<header class="ln-header">
			<h2>Company Contacts</h2>
		</header>

		<div id="ln-container" class="masonry">
			
			<?php foreach ($vd->results as $result): ?>
			<?= $ci->load->view('browse/listing/contact', 
				array('contact' => $result)); ?>
			<?php endforeach ?>
			
		</div>
		
	</section>
</div>