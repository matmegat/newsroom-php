<?php

if ($ci->is_common_host)
	return;

if (!$ci->newsroom->is_active)
	return;

$custom = $ci->newsroom->custom();
if (!$custom || !$custom->ganal)
	return;

?>
<script>

var _gaq = _gaq || [];

(function() {
	
	var account = <?= json_encode($custom->ganal) ?>;
	
	_gaq.push(['_setAccount', account]);
	_gaq.push(['_trackPageview']);

	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	
})();

</script>