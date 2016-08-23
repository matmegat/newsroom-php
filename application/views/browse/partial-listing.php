<?php foreach ($vd->results as $result): ?>
<?= $ci->load->view("browse/listing/{$result->type}", 
	array('content' => $result)); ?>
<?php endforeach ?>