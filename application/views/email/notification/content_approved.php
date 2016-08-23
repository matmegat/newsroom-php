Your <?= strtolower(Model_Content::full_type($content->type)) ?> submission has been approved! 

<br><br><code><b><a href="<?= $ci->website_url($content->url()) ?>"><?= 
	$vd->esc($content->title) ?></a></b></code>