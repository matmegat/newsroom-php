<?php 

$args = array();
$args[] = 'autohide=1';
$args[] = 'showinfo=0';
$args[] = 'theme=light';
$args[] = 'rel=0';

if (@$options['autoplay']) 
	$args[] = 'autoplay=1';

$args = implode('&amp;', $args);

?>
<div class="has-flash-content">
	<iframe class="video-youtube" 
		width="<?= $width ?>" height="<?= $height ?>" 
		src="<?= "//www.youtube.com/embed/{$id}?{$args}" ?>"
		frameborder="0" allowfullscreen></iframe>
</div>