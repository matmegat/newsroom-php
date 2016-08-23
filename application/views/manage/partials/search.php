<?php 
								
$context_uri = $ci->uri->uri_string();

$search_classes = array(
   // contacts within iContact
	'#^manage/contact/campaign#' => array(
		'label' => 'iContact Campaigns', 
		'uri' => 'manage/contact/campaign/all'),
	// contacts within iContact
	'#^manage/contact/(list|contact|import)#' => array(
		'label' => 'iContact Contacts', 
		'uri' => 'manage/contact/contact/search'),
	// content within iAnalyze
	'#^manage/analyze/email#' => array(
		'label' => 'iAnalyze Email Stats', 
		'uri' => 'manage/analyze/email'),
	// content within iAnalyze
	'#^manage/analyze#' => array(
		'label' => 'iAnalyze Content', 
		'uri' => 'manage/analyze/content/search'),
	// contacts within iNewsroom
	'#^manage/newsroom#' => array(
		'label' => 'iNewsroom Contacts', 
		'uri' => 'manage/newsroom/contact/search'),
	// content within iPublish
	'#^manage/(dashboard|publish)#' => array(
		'label' => 'iPublish Content', 
		'uri' => 'manage/publish/search'),
	// content within overview iPublish
	'#^manage/overview/(dashboard|publish)#' => array(
		'label' => 'iPublish Content', 
		'uri' => 'manage/overview/publish/search'),
	// campaigns within overview iContact
	'#^manage/overview/contact#' => array(
		'label' => 'iContact Campaigns', 
		'uri' => 'manage/overview/contact/search'),
	// campaigns within overview iContact
	'#^manage/companies#' => array(
		'label' => 'Companies', 
		'uri' => 'manage/companies'),
	// campaigns within overview iContact
	'#^manage/companies/archive#' => array(
		'label' => 'Companies', 
		'uri' => 'manage/companies/archive'),
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
				<input class="span12" id="search-box" type="search" name="terms" 
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