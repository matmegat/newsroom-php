<!doctype html>
<html lang="en">
	<head>
		
		<title>
			<?php if (isset($ci->title) && $ci->title): ?>
				<?= $vd->esc($ci->title); ?> |
			<?php endif ?>
			<?php foreach(array_reverse($vd->title) as $title): ?>
				<?= $vd->esc($title); ?> |
			<?php endforeach ?>
			<?php if (@$vd->nr_custom->headline): ?>
			<?= $vd->esc($vd->nr_custom->headline) ?>
			<?php else: ?>
			<?= $vd->esc($ci->newsroom->company_name) ?>
			<?php endif ?>
		</title>
		
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<base href="<?= $ci->config->item('base_url') ?>" />
		
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/base.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/browse.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/raw.css?<?= $vd->version ?>" />
		
		<script src="<?= $vd->assets_base ?>lib/jquery.js"></script>
		
	</head>
	
	<body>
		
		<div id="cv-container" class="content-type-<?= $vd->m_content->type ?> wide-view">
			
			<article class="article">

				<header class="article-header">
					<?php $lo_im = Model_Image::find(@$vd->nr_custom->logo_image_id); ?>
					<?php if ($lo_im) $lo_variant = $lo_im->variant('header'); ?>
					<?php if ($lo_im) $lo_url = Stored_File::url_from_filename($lo_variant->filename); ?>
					<?php if ($lo_im) $lo_width = $lo_variant->width; ?>
					<?php if ($lo_im): ?>
						<?php if ($lo_width <= 100): ?>							
						<h2 class="clearfix">
							<a href="<?= $vd->esc(@$vd->nr_profile->website) ?>" class="raw-company-logo">
								<img src="<?= $lo_url ?>" alt="<?= $vd->esc($ci->newsroom->company_name) ?>" />
							</a>
							<?= $vd->esc($vd->m_content->title) ?>
						</h2>
						<?php else: ?>
						<a href="<?= $vd->esc(@$vd->nr_profile->website) ?>" class="raw-company-logo">
							<img src="<?= $lo_url ?>" alt="<?= $vd->esc($ci->newsroom->company_name) ?>" />
						</a>
						<h2><?= $vd->esc($vd->m_content->title) ?></h2>
						<?php endif ?>					
					<?php else: ?>
					<h2><?= $vd->esc($vd->m_content->title) ?></h2>
					<?php endif ?>
				</header>

				<section class="article-details">
					<div class="row-fluid">
						<?php if ($vd->m_content->is_premium): ?>
							<?php $cover_image = Model_Image::find($vd->m_content->cover_image_id); ?>
							<?php if ($cover_image): ?>
								<div class="span3">
									<?php $orig_variant = $cover_image->variant('original'); ?>
									<?php $ci_variant = $cover_image->variant('view-cover'); ?>
									<?php $ci_filename = $ci_variant->filename; ?>
									<a href="<?= Stored_File::url_from_filename($orig_variant->filename) ?>" class="use-lightbox">
									<img src="<?= Stored_File::url_from_filename($ci_filename) ?>" 
									alt="<?= $vd->esc($vd->m_content->title) ?>" class="add-border" /></a>
								</div>
								<div class="span9">
							<?php else: ?>
								<div class="span12">
							<?php endif; ?>
						<?php else: ?>
							<div class="span12">
						<?php endif ?>
							<?= $ci->load->view('browse/view/partials/article_info') ?>
							<p><?php echo $vd->esc($vd->m_content->summary) ?></p>
						</div>
					</div>
				</section>

				<section class="article-content">
					<?php echo $ci->load->view('browse/view/partials/supporting_quote') ?>
					<div class="marbot-15 html-content"><?php echo $vd->m_content->content ?></div>					
				</section>
				
				<?php if ($vd->m_content->is_premium): ?>
				<?= $ci->load->view('browse/view/partials/related_resources'); ?>
				<?= $ci->load->view('browse/view/partials/additional_images'); ?>
				<?php endif ?>
				
				<?= $ci->load->view('browse/view/partials/tags_categories') ?>
				
				<section class="resources-block company-contact-information">
					<h3>
						Company <strong>Contact Information</strong>
					</h3>
					<div class="clearfix al-block aside-press-contact raw-press-contact">
						<?php $contact_image = Model_Image::find(@$vd->nr_contact->image_id); ?>								
						<?php if (@$contact_image): ?>			
							<?php $ci_variant = $contact_image->variant('contact'); ?>
							<?php $ci_url = Stored_File::url_from_filename($ci_variant->filename); ?>
							<img src="<?= $ci_url ?>" alt="<?= $vd->esc($vd->nr_contact->name) ?>" />			
						<?php else: ?>
							<img src="<?= $vd->assets_base ?>im/contact_image.png" />
						<?php endif ?>
						<address class="adr al-adr aside-content-block">															
							<div class="aside-pc-name">
								<?= $vd->esc($vd->nr_contact->name) ?>
								<?php if ($vd->nr_contact->email): ?>
								<a href="mailto:<?= $vd->esc($vd->nr_contact->email) ?>" 
									target="_blank" class="raw-email-icon">
									<i class="icon-envelope-alt"></i>
								</a>
								<?php endif ?>				
							</div>
							<div class="adr-org">
								<a href="<?= $vd->esc(@$vd->nr_profile->website) ?>">
									<?= $vd->esc($ci->newsroom->company_name) ?>
								</a>
							</div>							
							<span class="street-address">
								<?= $vd->esc(@$vd->nr_profile->address_apt_suite) ?>
								<?= $vd->esc(@$vd->nr_profile->address_street) 
									?><?= value_if_test(@$vd->nr_profile->address_street, ',') ?>									
								<?= $vd->esc(@$vd->nr_profile->address_city) ?>
							</span>
							<span class="postal-region">
								<?= $vd->esc(@$vd->nr_profile->address_state) 
									?><?= value_if_test(@$vd->nr_profile->address_state, ',') ?>
								<?= $vd->esc(@$vd->nr_profile->address_zip) ?>
							</span>
							<?php if (@$vd->nr_profile->phone): ?>
							<span class="adr-tel">
								<a href="tel:<?= preg_replace('#[^0-9\+\-]#is', '', @$vd->nr_profile->phone) ?>">
									<?= $vd->esc(@$vd->nr_profile->phone) ?></a>
							</span>	
							<?php endif ?>
						</address>
					</div>
				</section>
				
				<div class="view-original-source">
					<hr />Original Source: <a href="<?= $ci->website_url($vd->m_content->url()) ?>">
						<?= $ci->conf('website_host') ?></a>
				</div> 

			</article>
			
		</div>		
		
		<script>
		
		$(function() {
			
			var base = $("base").attr("href");
			$("a").each(function() {
				var _this = $(this);
				var href = _this.attr("href")
				if (href.indexOf("://") < 0)
					_this.attr("href", base + href);
			});
			
		});
		
		</script>
		
	</body>
</html>