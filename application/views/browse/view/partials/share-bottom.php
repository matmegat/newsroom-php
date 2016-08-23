<div class="share-bottom marbot-20">

	<a class="share-facebook share-window no-custom" href="http://www.facebook.com/share.php?u=<?= 
		urlencode($ci->website_url($vd->m_content->url())) ?>" target="_blank"
		data-width="640" data-height="400">
		<i class="icon-facebook-sign no-custom"></i>
		Share on Facebook
	</a>

	<a class="share-twitter share-window no-custom" href="http://twitter.com/intent/tweet?text=<?= 
		urlencode($vd->m_content->title) ?>+<?= 
		urlencode($ci->website_url($vd->m_content->url())) ?>" target="_blank"
		data-width="640" data-height="440">
		<i class="icon-twitter no-custom"></i>
		Share on Twitter
	</a>
		
	<a class="share-google-plus share-window no-custom" href="https://plus.google.com/share?url=<?=
		urlencode($ci->website_url($vd->m_content->url())) ?>" target="_blank"
		data-width="500" data-height="600">
		<i class="icon-google-plus no-custom"></i>
	</a>

	<a class="share-linkedin share-window no-custom" 
		href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?=
		urlencode($ci->website_url($vd->m_content->url())) ?>&amp;title=<?=
		urlencode($vd->m_content->title) ?>&amp;summary=<?=
		urlencode(@$vd->m_content->summary) ?>&amp;source=<?=
		urlencode($ci->newsroom->company_name) ?>" target="_blank"
		data-width="520" data-height="570">
		<i class="icon-linkedin-sign no-custom"></i>
	</a>
	
</div>