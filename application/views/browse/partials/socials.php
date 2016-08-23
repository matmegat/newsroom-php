<div class="aside-social-list clearfix">
	<ul>
		<?php if (@$vd->nr_profile->soc_twitter): ?>
		<li><a target="_blank" href="http://www.twitter.com/<?= 
			$vd->esc($vd->nr_profile->soc_twitter)
			?>"><i class="icon-twitter-sign"></i></a></li>
		<?php endif ?>
		<?php if (@$vd->nr_profile->soc_facebook): ?>
		<li><a target="_blank" href="http://www.facebook.com/<?= 
			$vd->esc($vd->nr_profile->soc_facebook)
			?>"><i class="icon-facebook-sign"></i></a></li>
		<?php endif ?>
		<?php if (@$vd->nr_profile->soc_gplus): ?>
		<li><a target="_blank" href="http://plus.google.com/<?= 
			$vd->esc($vd->nr_profile->soc_gplus)
			?>"><i class="icon-google-plus-sign"></i></a></li>
		<?php endif ?>
		<?php if (@$vd->nr_profile->soc_youtube): ?>
		<li><a target="_blank" href="http://www.youtube.com/user/<?= 
			$vd->esc($vd->nr_profile->soc_youtube)
			?>"><i class="icon-youtube-sign"></i></a></li>
		<?php endif ?>
	</ul>
</div>