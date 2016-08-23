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
			iNewswire
		</title>
		<meta charset="utf-8" />		
		<meta name="viewport" content="width=device-width" />
		<base href="<?= $base = $ci->config->item('base_url') ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-select.min.css" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/base.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/manage.css?<?= $vd->version ?>" />
		<link rel="stylesheet" href="<?= $vd->assets_base ?>css/admin.css?<?= $vd->version ?>" />
		<script src="<?= $vd->assets_base ?>lib/jquery.js"></script>
		<script src="<?= $vd->assets_base ?>lib/jquery.create.js"></script>
		<!--[if lt IE 9]><script src="<?= $vd->assets_base ?>lib/html5shiv.js"></script><![endif]-->
		<script src="<?= $vd->assets_base ?>js/base.js?<?= $vd->version ?>"></script>
		<script src="<?= $vd->assets_base ?>js/manage.js?<?= $vd->version ?>"></script>
		<script src="<?= $vd->assets_base ?>js/admin.js?<?= $vd->version ?>"></script>
		<script> 

		CKEDITOR_BASEPATH = <?= json_encode("{$vd->assets_base}lib/ckeditor/") ?>;
		NR_COMPANY_ID = <?= json_encode($ci->newsroom->company_id) ?>;

		</script>
	</head>
	<body>
		<header class="header">
			<div class="container">
				<div class="row-fluid">					
					<div class="span7">
						<h1 class="logo"><a href="manage" accesskey="1">iNewsWire</a></h1>
						<div class="newsroom-panel">
							<a class="btn" href="<?= $ci->conf('website_url') ?>AdminHome">Classic Admin</a>
						</div>
					</div>
					<div class="span5">
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
											<li><a href="manage">
												<i class="icon-user"></i> User Panel</a></li>
											<li><a href="manage/account">
												<i class="icon-cog"></i> Account Details</a></li>
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
								<ul id="nav-main" class="nav-activate nav-main-compact">
									<li>
										<a href="admin/publish<?= $vd->esc(gstring()) ?>" data-on="^admin/publish">
											iPublish
										</a>
									</li>
									<li>
										<a href="admin/contact<?= $vd->esc(gstring()) ?>" data-on="^admin/contact">
											iContact
										</a>
									</li>
									<li>
										<a href="admin/companies<?= $vd->esc(gstring()) ?>" data-on="^admin/companies">
											Companies
										</a>
									</li>
									<li>
										<a href="admin/users" data-on="^admin/users">
											Users
										</a>
									</li>
									<li>
										<a href="admin/settings" data-on="^admin/settings">
											Settings
										</a>
									</li>
								</ul>
							</nav>
						</div>
						<?= $this->load->view('admin/partials/search', null, true); ?>
					</div>
				</div>
			</section>