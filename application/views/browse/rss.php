<?= '<?xml version="1.0" encoding="utf-8" ?>' ?>

<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	
	<title>
		<?php if (isset($ci->title) && $ci->title): ?>
			<?= $vd->esc($ci->title); ?> |
		<?php endif ?>
		<?php foreach(array_reverse($vd->title) as $title): ?>
			<?= $vd->esc($title); ?> |
		<?php endforeach ?>
		<?php if ($ci->is_common_host): ?>
		iNewswire
		<?php else: ?>
		<?= $vd->esc($ci->newsroom->company_name) ?>
		<?php endif ?>
	</title>
	<link><?= $ci->newsroom->url(null, true) ?></link>
	<description>Latest news from <?= 
		$vd->esc($ci->newsroom->company_name) ?></description>
		
	<atom:link href="<?= $ci->config->item('base_url') ?><?= 
		$vd->esc(gstring($ci->uri->uri_string())) ?>" 
		rel="self" type="application/rss+xml" />
		
	<?php foreach ($vd->results as $result): ?>
	<item>
		<title><?= $vd->esc(@$result->title) ?></title>
		<link><?= $ci->website_url($result->url()) ?></link>
		<guid><?= $ci->website_url($result->url()) ?></guid>
		<description><?= $vd->esc(@$result->summary) ?></description>
	</item>
	<?php endforeach ?>
	
</channel>
</rss>