<article class="article">

	<header class="article-header">
		<h2><?php echo $vd->esc($vd->m_content->title) ?></h2>
	</header>

	<section class="article-details">
		<div class="row-fluid">
			<?php $cover_image = Model_Image::find($vd->m_content->cover_image_id); ?>
			<?php if($cover_image) : ?>
				<div class="span4">
					<?php $orig_variant = $cover_image->variant('original'); ?>
					<?php $ci_variant = $cover_image->variant('view-cover'); ?>
					<?php $ci_filename = $ci_variant->filename; ?>
					<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
					<img src="<?= Stored_File::url_from_filename($ci_filename) ?>" 
					alt="<?= $vd->esc($vd->m_content->title) ?>" class="add-border" /></a>
				</div>
				<div class="span8">
			<?php else: ?>
				<div class="span12">
			<?php endif; ?>
				<?= $ci->load->view('browse/view/partials/article_info') ?>
				<p><?php echo $vd->esc($vd->m_content->summary) ?></p>
			</div>
		</div>
	</section>

	<section class="article-content clearfix">
		<?php echo $ci->load->view('browse/view/partials/supporting_quote') ?>
		<div class="marbot-15 html-content"><?php echo $vd->m_content->content ?></div>
		<?= $ci->load->view('browse/view/partials/share-bottom') ?>
	</section>

	<section class="resources-block event-block event-details">	
		<h3>Event <strong>Details</strong></h3>
		<?php if ($vd->m_content->address) : ?>
			<p>
				<i class="icon-globe"></i><!-- 
			--><span>Place:</span> <strong><?= $vd->esc($vd->m_content->address) ?></strong>
			</p>
		<?php endif ?>
		<p>
			
			<?php $event_start_date = Date::out($vd->m_content->date_start); ?>
			<?php $event_end_date = Date::out($vd->m_content->date_finish); ?>
			<?php $show_year = $event_start_date < Date::$now || $event_start_date > Date::months(3); ?>
		
			<?php if ($vd->m_content->is_all_day): ?>
			<i class="icon-calendar"></i><span>Date:</span>
			<?php else: ?>
			<i class="icon-calendar"></i><span>Starts:</span> 
			<?php endif ?>
			
			<strong>
			<?php if ($show_year): ?>
				<?php if ($event_start_date->format("G:i") === "0:00" && $vd->m_content->is_all_day): ?>
					<?php echo $event_start_date->format("jS F Y") ?>
				<?php else: ?>
					<?php echo $event_start_date->format("jS F Y g:i A") ?>
				<?php endif; ?>
			<?php else: ?>
				<?php if ($event_start_date->format("G:i") === "0:00" && $vd->m_content->is_all_day): ?>
					<?php echo $event_start_date->format("jS F") ?>
				<?php else: ?>
					<?php echo $event_start_date->format("jS F g:i A") ?>
				<?php endif; ?>
			<?php endif ?>
			</strong>
			
			<?php if (!$vd->m_content->is_all_day): ?>
				<br /><i class="icon-blank"></i><span>Ends:</span> 
				<strong>
				<?php if ($show_year): ?>
					<?php echo $event_end_date->format("jS F Y g:i A") ?>
				<?php else: ?>
					<?php echo $event_end_date->format("jS F g:i A") ?>
				<?php endif ?>
				</strong>
			<?php endif ?>
			
		</p>
		<p>
			<i class="icon-shopping-cart"></i><span>Price:</span> 
			<?php if((float) $vd->m_content->price > 0): ?>
				<strong>$<?php echo $vd->esc($vd->m_content->price) ?></strong>
				<?php if($vd->m_content->discount_code): ?>
					(Discount Code: <i><?php echo $vd->esc(
						$vd->m_content->discount_code) ?></i>)
				<?php endif; ?>
			<?php else: ?>
				<strong>Free</strong>
			<?php endif ?>
		</p>
		<?php $event_type = Model_Event::find($vd->m_content->event_type_id) ?>
		<?php if ($event_type) : ?>
			<p>
				<i class="icon-briefcase"></i><!-- 
			--><span>Type:</span> <strong><?php echo $vd->esc($event_type->name) ?></strong>
			</p>
		<?php endif; ?>
	</section> 

	<?php echo $ci->load->view('browse/view/partials/additional_images') ?>
	<?php echo $ci->load->view('browse/view/partials/links_tags') ?>

</article>