<!doctype html>
<html lang="en" class="
	<?= value_if_test($ci->is_common_host,   'is-common-host') ?>
	<?= value_if_test($ci->is_detached_host, 'is-detached-host') ?>">
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
		
		<script src="<?= $vd->assets_base ?>lib/jquery.js"></script>
		<script src="<?= $vd->assets_base ?>lib/jquery.create.js"></script>
		<script src="<?= $vd->assets_base ?>js/base.js?<?= $vd->version ?>"></script>
		<script src="<?= $vd->assets_base ?>js/browse.js?<?= $vd->version ?>"></script>
		
		<!--[if lt IE 9]><script src="<?= $vd->assets_base ?>lib/html5shiv.js"></script><![endif]-->
		<!--[if IE]><script src="<?= $vd->assets_base ?>lib/formdata.js"></script><![endif]-->
		
		<?php if (!$ci->is_common_host): ?>
		<?php $back_image = Model_Image::find(@$vd->nr_custom->back_image_id); ?>
		
		<style>
		
		body {
			
			<?php if (@$vd->nr_custom->back_image_repeat === 'repeat'): ?>
			background-position: top left !important;
			<?php else: ?>
			background-position: center center !important;
			<?php endif ?>
			
			<?php if (@$vd->nr_custom->back_color): ?>
			background-color: <?= $vd->nr_custom->back_color ?> !important;
			<?php endif ?>
			
			<?php if ($back_image): ?>
			<?php $bi_variant = $back_image->variant('original'); ?>
			<?php $bi_url = Stored_File::url_from_filename($bi_variant->filename); ?>
			background-image: url("<?= $bi_url ?>") !important;
			<?php endif ?>
			
			background-repeat: <?= @$vd->nr_custom->back_image_repeat ?> !important;
			background-attachment: fixed;
			
		}
		
		.share-side {
			
			<?php if ($back_image || @$vd->nr_custom->back_color): ?>
			left: -77px;
			<?php endif ?>
			
		}
		
		#content-container {
			
			<?php if (@$vd->nr_custom->back_color === 'transparent'): ?>
			background-color: transparent !important;
			<?php endif ?>
			
		}
		
		#content-container *:not(.no-custom) {
			
			<?php if (@$vd->nr_custom->text_color): ?>
			color: <?= $vd->nr_custom->text_color ?> !important;
			<?php endif ?>
			
		}
		
		#content-container a:not(.no-custom) {
			
			<?php if (@$vd->nr_custom->link_color): ?>
			color: <?= $vd->nr_custom->link_color ?> !important;
			<?php endif ?>
			
		}
		
		#content-container a:not(.no-custom):hover {
			
			<?php if (@$vd->nr_custom->link_hover_color): ?>
			color: <?= $vd->nr_custom->link_hover_color ?> !important;
			<?php endif ?>
			
		}
		
		.top-panel {
			
			<?php if (@$vd->nr_custom->header_color): ?>
			background-color: <?= $vd->nr_custom->header_color ?> !important;
			<?php endif ?>
			
		}
		
		.brand-login input,
		.brand-login button {
			
			<?php if (@$vd->nr_custom->header_color): ?>
			color: <?= $vd->nr_custom->header_color ?> !important;
			<?php endif ?>			
			
		}
		
		.ln-press-contact {
			
			<?php if (@$vd->nr_custom->header_color): ?>
			border-color: <?= $vd->nr_custom->header_color ?> !important;
			<?php endif ?>
			
		}
		
		.ln-press-contact .ln-contact-details {
			
			<?php if (@$vd->nr_custom->header_color): ?>
			background-color: <?= $vd->nr_custom->header_color ?> !important;
			<?php endif ?>
			
		}
		
		</style>
		
		<?php endif ?>
		
		<?php if (isset($vd->m_content)): ?>
		
		<link rel="canonical" href="<?= $ci->website_url($vd->m_content->url()) ?>" />
		<meta property="og:title" content="<?= $vd->esc($vd->m_content->title) ?>" />
		<meta property="og:description" content="<?= $vd->esc(@$vd->m_content->summary) ?>" />		
		
		<?php endif ?>
		
		<link href="<?= $vd->assets_base ?>im/favicon.ico?<?= $vd->version ?>" type="image/x-icon" rel="shortcut icon" />
		<link href="<?= $vd->assets_base ?>im/favicon.ico?<?= $vd->version ?>" type="image/x-icon" rel="icon" />
		
	</head>
	
	<body class="<?= value_if_test($ci->is_own_domain, 'is-own-domain') ?>">
		
		<!--[if lt IE 7]><p class="chromeframe">You are using an <strong>outdated</strong> browser. 
		Please <a href="http://browsehappy.com/">upgrade your browser</a> or 
		<a href="http://www.google.com/chromeframe/?redirect=true">activate Google 
		Chrome Frame</a> to improve your experience.</p><![endif]-->
		
		<section class="top-panel <?= value_if_test($ci->is_common_host, 'marbot') ?>">
			<div class="container">
				<div class="row-fluid">
					<div class="span6">
						<a class="brand brand-logo" href="<?= $ci->conf('website_url') ?>" accesskey="1">iNews<b>Wire</b></a>
					</div>
					<?php if (!Auth::is_user_online()): ?>
					<div class="span6">
						<div class="pull-right brand-login">
							<form action="shared/login" method="post">
								<input type="email" name="email" />
								<input type="password" name="password" />
								<button type="submit">
									SIGN IN
								</button>
							</form>
						</div>
					</div>
					<?php endif ?>
				</div>
			</div>
		</section>
		
		<?php if (!$this->is_common_host): ?>
		<?php $lo_im = Model_Image::find(@$vd->nr_custom->logo_image_id); ?>
		<?php $lo_height = 0; ?>
		<?php if ($lo_im) $lo_variant = $lo_im->variant('header'); ?>
		<?php if ($lo_im) $lo_url = Stored_File::url_from_filename($lo_variant->filename); ?>
		<?php if ($lo_im) $lo_height = $lo_variant->height; ?>
		<header class="org-header 
			<?= value_if_test(@$vd->nr_custom->use_white_header, 'white') ?>
			<?= value_if_test($lo_height < 50, 'slim') ?>">
			<div class="row-fluid">
				<div class="span9">
					<?php if ($lo_im): ?>
					<div class="org-header-logo">
						<a href="<?= $ci->newsroom->url(null, true) ?>">
							<img src="<?= $lo_url ?>" alt="<?= $vd->esc($ci->newsroom->company_name) ?>" />
						</a>
					</div>
					<?php endif ?>
					<div class="org-header-text">
						<span>
							<?php if (@$vd->nr_custom->headline_prefix): ?>
							<span class="prefix">
								<?= $vd->esc($vd->nr_custom->headline_prefix) ?>
							</span>
							<?php else: ?>
							<span class="prefix">
								the official newsroom of
							</span>
							<?php endif ?>
							<br />
							<h1>								
								<?php if ($ci->is_common_host): ?>
								<a href="<?= $vd->esc(@$vd->nr_profile->website) ?>">
									<?= $vd->esc($ci->newsroom->company_name) ?>
								</a>
								<?php else: ?>
								<a href="<?= $ci->newsroom->url(null, true) ?>">
									<?php if (@$vd->nr_custom->headline_h1): ?>
									<?= $vd->esc($vd->nr_custom->headline_h1) ?>
									<?php else: ?>
									<?= $vd->esc($ci->newsroom->company_name) ?>
									<?php endif ?>
								</a>
								<?php endif ?>
							</h1>
						</span>
					</div>
				</div>
				<div class="span3 org-header-search">
					<form action="browse/search" method="get">
						<input type="text" name="terms"
							placeholder="Search Newsroom" 
							value="<?= $vd->esc($this->input->get('terms')) ?>" />
						<button type="submit"><i class="icon-search"></i></button>
					</form>
				</div>
			</div>		
		</header>
		<?php endif ?>
		
		<div class="container" id="content-container">
			<div class="row-fluid">
				
				<?php if (@$vd->is_news_center): ?>
				<section class="news-center-main">
				<?php else: ?>
				<section class="span8 main" role="main">
				<?php endif ?>
						
					<div id="feedback">
					<?php if ($ci->feedback): ?>
					<?php $ci->clear_feedback(); ?>
					<?php foreach ($ci->feedback as $feedback): ?>
					<div class="feedback"><?= $feedback ?></div>
					<?php endforeach ?>
					<?php endif ?>
					</div>

					<div class="row-fluid
						<?= value_if_test($vd->wide_view, 'wide-view', 'normal-view') ?>">
						
						<?php if (!$vd->wide_view): ?>
						
						<aside class="span4 aside aside-left">
							
							<?php if (!$ci->is_common_host): ?>
							<?= $ci->load->view('browse/partials/content_types') ?>
							<?php endif ?>

							<?php if (!$ci->is_common_host): ?>
							<?php if (@$vd->nr_custom->rel_res_pri_link ||
							          @$vd->nr_custom->rel_sec_pri_link ||
							          @$vd->nr_custom->rel_ter_pri_link): ?>		
							
							<section class="al-block">
								<h3>Relevant Links</h3>
								<ul class="links-list">
									<?php if (@$vd->nr_custom->rel_res_pri_link): ?>
									<li>
										<a href="<?= $vd->esc($vd->nr_custom->rel_res_pri_link) ?>">
											<?= $vd->esc($vd->nr_custom->rel_res_pri_title) ?>
										</a>
									</li>
									<?php endif ?>
									<?php if (@$vd->nr_custom->rel_res_sec_link): ?>
									<li>
										<a href="<?= $vd->esc($vd->nr_custom->rel_res_sec_link) ?>">
											<?= $vd->esc($vd->nr_custom->rel_res_sec_title) ?>
										</a>
									</li>
									<?php endif ?>
									<?php if (@$vd->nr_custom->rel_res_ter_link): ?>
									<li>
										<a href="<?= $vd->esc($vd->nr_custom->rel_res_ter_link) ?>">
											<?= $vd->esc($vd->nr_custom->rel_res_ter_title) ?>
										</a>
									</li>
									<?php endif ?>
								</ul>
							</section>
							
							<?php endif ?>						
							<?php endif ?>

							<?= $this->load->view('browse/partials/address') ?>

							<?php if (!$ci->is_common_host && count($vd->nr_listed_archives)): ?>
							<section class="al-block">
								<h3>Archive by Date</h3>
								<ul class="links-list nav-activate">
									<?php foreach ($vd->nr_listed_archives as $date): ?>									
									<li>										
										<a data-on="^browse/month/<?= $date->format('Y/m') ?>"
											href="browse/month/<?= $date->format('Y/m') ?>">
											<i class="icon-hand-right"></i> 
											<?= $date->format('F Y') ?>
										</a>
									</li>
									<?php endforeach ?>
								</ul>
							</section>
							<?php endif ?>
							
						</aside>						
						
						<?php endif ?>
						