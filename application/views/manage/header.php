<!doctype html>
<html lang="en" class="is-user-panel
	<?= value_if_test($ci->use_overview,                    'use-overview') ?>
	<?= value_if_test($ci->is_common_host,                  'is-common-host') ?>
	<?= value_if_test($ci->is_detached_host,                'is-detached-host') ?>
	<?= value_if_test(Auth::user()->has_platinum_access(),  'has-platinum-access') ?>
	<?= value_if_test(Auth::user()->has_gold_access(),      'has-gold-access') ?>
	<?= value_if_test(Auth::user()->has_silver_access(),    'has-silver-access') ?>
	<?= value_if_test(Auth::user()->is_free_user(),         'is-free-user') ?>">
	<head>
		<title>
			<?php if (isset($ci->title) && $ci->title): ?>
				<?= $vd->esc($ci->title); ?> |
			<?php endif ?>
			<?php foreach(array_reverse($vd->title) as $title): ?>
				<?= $vd->esc($title); ?> |
			<?php endforeach ?>
			<?php if ($ci->is_common_host): ?>
			iNewswire
			<?php else: ?>
			<?= $vd->esc($ci->newsroom->company_name) ?>
			<?php endif ?>		
		</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width" />
		<base href="<?= $base = $ci->config->item('base_url') ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-select.min.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-datepicker.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/base.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/manage.css?<?= $vd->version ?>" />
		<script src="<?= $vd->assets_base ?>lib/jquery.js"></script>
		<script src="<?= $vd->assets_base ?>lib/jquery.create.js"></script>
		<!--[if lt IE 9]><script src="<?= $vd->assets_base ?>lib/html5shiv.js"></script><![endif]-->
		<!--[if IE]><script src="<?= $vd->assets_base ?>lib/formdata.js"></script><![endif]-->
		<script src="<?= $vd->assets_base ?>js/base.js?<?= $vd->version ?>"></script>
		<script src="<?= $vd->assets_base ?>js/manage.js?<?= $vd->version ?>"></script>
		<script> 

		CKEDITOR_BASEPATH = <?= json_encode("{$vd->assets_base}lib/ckeditor/") ?>;
		NR_COMPANY_ID = <?= json_encode($ci->newsroom->company_id) ?>;

		</script>
	</head>
	<body>
			
		<?php if (Auth::is_admin_controlled() && Auth::is_admin_mode()): ?>
		<div id="admin-bar" class="alert alert-error">
			<strong>Admin Session:</strong> Your current session is for the 
			<?= value_if_test(Auth::user()->is_reseller, 'reseller', 'user') ?>
			<strong><?= $vd->esc(Auth::user()->email) ?></strong>.
			<strong class="pull-right">
				<a href="<?= $ci->common()->url('admin') ?>">Leave Session</a>
			</strong>
		</div>
		<?php endif ?>
		<header class="header">
			<div class="container">
				<div class="row-fluid">					
					<div class="span8">
						<h1 class="logo"><a href="manage" accesskey="1">iNewsWire</a></h1>
						<?php if ($vd->user_newsrooms): ?>
						<div class="newsroom-panel">
							<div class="btn-group">
								<a class="btn dropdown-toggle" data-toggle="dropdown">									
									<?php if ($ci->is_common_host): ?>
										<?php if (Auth::user()->is_free_user()): ?>
										Select Company
										<?php else: ?>
										Account Overview
										<?php endif ?>
									<?php else: ?>
									<?= $vd->esc($ci->newsroom->company_name) ?>
									<?php endif ?>
									<span class="caret"></span>
								</a>
								<ul class="dropdown-menu">
									<?php if (Auth::user()->has_platinum_access() || 
										(count($vd->user_newsrooms) > 1 && !Auth::user()->is_free_user())): ?>
									<li class="overview-link"><a href="manage/overview">
										Account Overview</a></li>
									<li class="divider"></li>
									<?php endif ?>
									<?php foreach ($vd->user_newsrooms as $newsroom): ?>
									<li>
										<?php if ($ci->is_common_host): ?>
										<a href="<?= $newsroom->url('manage/dashboard') ?>">
										<?php else: ?>
										<a href="<?= gstring($newsroom->url($ci->uri->uri_string())) ?>">
										<?php endif ?>
										<?= $vd->esc($newsroom->company_name) ?>
										</a>
									</li>
									<?php endforeach ?>
								</ul>
							</div>
							<?php if (!$ci->is_common_host): ?>
							<a class="btn btn-view-newsroom" href="browse" target="_blank">View Newsroom</a>
							<?php endif ?>
						</div>
						<?php endif ?>
					</div>
					<div class="span4">
						<div class="login-panel">
							<ul>
								<li class="welcome">
									Welcome 
									<?= $vd->esc(Auth::user()->first_name) ?>
									<?= $vd->esc(substr(Auth::user()->last_name, 0, 1)) ?>.
								</li>
								<li>
									<div class="btn-group dd-menu-nav">
										<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">
											Account <span class="caret"></span>
										</a>
										<ul class="dropdown-menu">
											<?php if (Auth::user()->is_admin): ?>
											<li><a href="<?= $ci->common()->url('admin') ?>">
												<i class="icon-lock"></i> Admin Panel</a></li>
											<?php endif ?>
											<li><a href="manage/account">
												<i class="icon-cog"></i> Account Details</a></li>
											<li><a href="manage/companies">
												<i class="icon-briefcase"></i> Manage Companies</a></li>
											<li><a href="<?= $ci->conf('website_url') ?>helpdesk/">
												<i class="icon-comment"></i> Helpdesk</a></li>
											<li><a href="shared/logout">
												<i class="icon-signout"></i> Logout</a></li>
										</ul>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</header>

		<div class="wrapper container">
			<section class="main-menu-bar">
				<div class="container">
					<div class="row-fluid">
						<div class="span8">
							<nav class="main-menu">
								<ul id="nav-main" class="nav-activate
									<?= value_if_test(!Auth::user()->has_platinum_access() &&
											count($vd->user_newsrooms) <= 1, 'disable-overview') ?>">
									<li class="relative menu-dashboard">
										<a href="manage<?= value_if_test($ci->is_common_host, '/overview') ?>/dashboard" 
											data-on="^manage/(overview/)?dashboard" 
											class="menu-icons menu-icons-dashboard">
											<span></span>Dashboard<strong class="use-overview">Overview</strong>
										</a>
										<a class="use-overview overview-link" href="manage/overview/dashboard">Overview</a>
									</li>
									<li class="relative">
										<a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/publish" 
											data-on="^manage/(overview/)?publish"
											class="menu-icons menu-icons-ipublish">
											<span></span>iPublish<strong>Overview</strong>
										</a>
										<a class="overview-link" href="manage/overview/publish">Overview</a>
									</li>
									<li class="relative">
										<a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/contact" 
											data-on="^manage/(overview/)?contact" 
											class="menu-icons menu-icons-icontacts">
											<span></span>iContact<strong>Overview</strong>
										</a>
										<a class="overview-link" href="manage/overview/contact">Overview</a>
									</li>
									<li class="relative">
										<a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/analyze" 
											data-on="^manage/(overview/)?analyze" 
											class="menu-icons menu-icons-ianalyze">
											<span></span>iAnalyze<strong>Overview</strong>
										</a>
										<a class="overview-link" href="manage/overview/analyze">Overview</a>
									</li>
									<li class="relative">
										<a href="manage<?= value_if_test($ci->use_overview, '/overview') ?>/newsroom" 
											<?php if ($ci->use_overview): ?>
											data-on="^manage/(companies|overview/newsroom)" 
											<?php else: ?>
											data-on="^manage/newsroom" 
											<?php endif ?>
											class="menu-icons menu-icons-inewsroom">
											<span></span>iNewsroom<strong>Overview</strong>
										</a>
										<a class="overview-link" href="manage/overview/newsroom">Overview</a>
									</li>
								</ul>
							</nav>
						</div>
						<?= $this->load->view('manage/partials/search', null, true); ?>
					</div>
				</div>
			</section>