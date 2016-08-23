<!doctype html>
<!--[if lt IE 9]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gte IE 9]><!--><html lang="en"><!--<![endif]-->
	<head>
		<title>
			<?php foreach(array_reverse($vd->title) as $title): ?>
				<?= $vd->esc($title); ?> |
			<?php endforeach ?>
			iNewsWire
		</title>
		<meta charset="utf-8" />		
		<meta name="viewport" content="width=device-width" />
		<base href="<?= $base = $ci->config->item('base_url') ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-select.min.css" 
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/base.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/reseller.css?<?= $vd->version ?>" />
		<script src="<?= $vd->assets_base ?>lib/jquery.js"></script>
		<!--[if lt IE 9]><script src="<?= $vd->assets_base ?>lib/html5shiv.js"></script><![endif]-->
		<script src="<?= $vd->assets_base ?>js/base.js?<?= $vd->version ?>"></script>
		<script src="<?= $vd->assets_base ?>js/reseller.js?<?= $vd->version ?>"></script>
	</head>
	<body>
		<header class="header">
			<div class="container">
				<div class="row-fluid">					
					<div class="span5">
						<h1 class="logo"><a href="manage" accesskey="1">iNewsWire</a></h1>						
					</div>
					<div class="span7">
						<div class="login-panel">
							<ul>
								<li class="welcome">
									Welcome 
									<?= $vd->esc(Auth::user()->first_name) ?>
									<?= $vd->esc(substr(Auth::user()->last_name, 0, 1)) ?>.
								</li>
								<li>
									<a href="reseller/account">
										<i class="icon-cog"></i> Account Settings
									</a>
								</li>
								<li>
									<a href="<?= $ci->conf('website_url') ?>helpdesk/">
										<i class="icon-comment"></i> Helpdesk
									</a>
								</li>
								<li>
									<a href="shared/logout">
										<i class="icon-signout"></i> Logout
									</a>
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
								<ul id="nav-main" class="nav-activate">
									<li>
										<a href="manage/dashboard" data-on="^manage/(dashboard|$)" 
											class="menu-icons menu-icons-dashboard">
											<span></span>Dashboard
										</a>
									</li>
									<li>
										<a href="manage/publish" data-on="^manage/publish" 
											class="menu-icons menu-icons-ipublish">
											<span></span>iPublish
										</a>
									</li>
								</ul>
							</nav>
						</div>
						<?= $this->load->view('manage/partials/search', null, true); ?>
					</div>
				</div>
			</section>