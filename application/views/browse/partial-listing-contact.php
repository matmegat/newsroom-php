<?php foreach ($vd->results as $result): ?>			
<?= $ci->load->view('browse/listing/contact', 
	array('contact' => $result)); ?>
<?php endforeach ?>