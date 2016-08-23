<?php if ($vd->nr_listed_types): ?>
<section class="al-block">
	<h3>
		Newsroom
		<a href="browse/rss" class="pull-right">
			<i class="icon-rss"></i>
		</a>
	</h3>	
	<ul class="links-list nav-activate">
		<?php if ($vd->nr_listed_types->pr): ?>
		<li><a data-on="^browse/pr" href="browse/pr">
			<i class="icon-hand-right"></i> Press Releases</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->news): ?>
		<li><a data-on="^browse/news" href="browse/news">
			<i class="icon-hand-right"></i> News</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->event): ?>
		<li><a data-on="^browse/event" href="browse/event">
			<i class="icon-hand-right"></i> Events</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->image): ?>
		<li><a data-on="^browse/image" href="browse/image">
			<i class="icon-hand-right"></i> Images</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->audio): ?>
		<li><a data-on="^browse/audio" href="browse/audio">
			<i class="icon-hand-right"></i> Audio</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->video): ?>
		<li><a data-on="^browse/video" href="browse/video">
			<i class="icon-hand-right"></i> Video</a></li>
		<?php endif ?>
		<?php if ($vd->nr_listed_types->contact): ?>
		<li><a data-on="^browse/contact" href="browse/contact">
			<i class="icon-hand-right"></i> Contacts</a></li>
		<?php endif ?>
	</ul>
</section>
<?php endif ?>