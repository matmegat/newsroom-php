<?php

// do not count stats for the owner of the content
if (Auth::is_user_online() && 
    $ci->newsroom->user_id == Auth::user()->id)
	return;

$cam = $ci->input->get('cam');
$puid = $ci->input->get('puid');

if ($cam && $puid)
{
	$stats = new Statistics();
	$stats->set_newsroom($ci->newsroom->name);
	$stats->set_campaign($cam);
	$email_click_pixel = $stats->email_click_pixel($puid);
}

?>

<script> 

var _paq = _paq || []; 

_piwik_base_url = <?= json_encode($ci->conf('piwik_base_url')) ?>;
 
_paq.push(["setSiteId", 1]); 
_paq.push(["setTrackerUrl", _piwik_base_url + "/piwik.php"]); 
_paq.push(["setCustomVariable", <?= Statistics::CV_NEWSROOM ?>, "newsroom", <?= json_encode($ci->newsroom->name) ?>, "page"]);
_paq.push(["setCustomVariable", <?= Statistics::CV_OWNER ?>, "owner", <?= json_encode($ci->newsroom->user_id) ?>, "page"]);

<?php if (isset($vd->m_content)): ?>
_paq.push(["setCustomVariable", <?= Statistics::CV_CONTENT ?>, "content", <?= json_encode($vd->m_content->id) ?>, "page"]);
_paq.push(["setCustomVariable", <?= Statistics::CV_CONTENT_TYPE ?>, "content-type", <?= json_encode($vd->m_content->type) ?>, "page"]);
<?php endif ?>

<?php if ($cam): ?>
_paq.push(["setCustomVariable", <?= Statistics::CV_CAMPAIGN ?>, "campaign", <?= json_encode($cam) ?>, "page"]);
<?php endif ?>

// we don't set cookie domain because different newsroom => different visitor
// _paq.push(["setCookieDomain", <?= json_encode($ci->env['session_domain']) ?>]);
_paq.push(["trackPageView"]); 

$("script").first().before($.create("script", {
	async: true,
	defer: true,	
	src: _piwik_base_url + "/piwik.js"
}));

</script>

<?php if (isset($email_click_pixel)): ?>
<img src="<?= $email_click_pixel ?>" width="1" height="1" />
<?php endif ?>