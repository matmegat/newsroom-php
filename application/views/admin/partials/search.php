<?php
								
$context_uri = $ci->uri->uri_string();
$search_classes = array(
	// content within iPublish
	'#^admin/publish/pr#' => array(
		'label' => 'iPublish Press Release', 
		'uri' => 'admin/publish/pr/all'),
	// content within iPublish
	'#^admin/publish/news#' => array(
		'label' => 'iPublish News', 
		'uri' => 'admin/publish/news/all'),
	// content within iPublish
	'#^admin/publish/event#' => array(
		'label' => 'iPublish Event', 
		'uri' => 'admin/publish/event/all'),
	// content within iPublish
	'#^admin/publish/image#' => array(
		'label' => 'iPublish Image', 
		'uri' => 'admin/publish/image/all'),
	// content within iPublish
	'#^admin/publish/audio#' => array(
		'label' => 'iPublish Audio', 
		'uri' => 'admin/publish/audio/all'),
	// content within iPublish
	'#^admin/publish/video#' => array(
		'label' => 'iPublish Video', 
		'uri' => 'admin/publish/video/all'),
	// content within iContact
	'#^admin/contact/campaign#' => array(
		'label' => 'iContact Campaign', 
		'uri' => 'admin/contact/campaign/all'),
	// content within iContact
	'#^admin/contact/list#' => array(
		'label' => 'iContact List', 
		'uri' => 'admin/contact/list/all'),
	// content within iContact
	'#^admin/contact/contact#' => array(
		'label' => 'iContact Contact', 
		'uri' => 'admin/contact/contact/all'),
	// companies list
	'#^admin/companies#' => array(
		'label' => 'Companies', 
		'uri' => 'admin/companies'),
	// content within iContact
	'#^admin/users#' => array(
		'label' => 'Users', 
		'uri' => 'admin/users'),
	// content within iContact
	'#^admin/settings/ip_block#' => array(
		'label' => 'IP Addresses', 
		'uri' => 'admin/settings/ip_block'),
	// content within iContact
	'#^admin/settings/fc_sites#' => array(
		'label' => 'FC Sites', 
		'uri' => 'admin/settings/fc_sites'),
);

foreach ($search_classes as $k => $v)
{
	if (!preg_match($k, $context_uri))
		continue;
	
	?>
	<div class="span4">
		<section class="search-form-panel">
			<form method="get" 
				action="<?= $v['uri'] ?>">
				<?php foreach ((array) $ci->input->get() as $gk => $gv): ?>
				<?php if ($gk == 'filter_search') continue; ?>
				<input type="hidden" name="<?= $vd->esc($gk) ?>" 
					value="<?= $vd->esc($gv) ?>" />
				<?php endforeach ?>
				<input class="span12" id="search-box" type="search" name="filter_search" 
					placeholder="Search for <?= $v['label'] ?>"
					value="<?= $vd->esc($ci->input->get('terms')) ?>" />
				<button type="submit">Search</button>
			</form>
		</section>
	</div>
	<script>

	$(function() {
		$("#search-box").focus();
	});

	</script>
	<?php
	
	return;
}

?>