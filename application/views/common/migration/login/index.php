<!DOCTYPE html>
<!--[if IE]><html class="ie" lang="en" ><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9" lang="en"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js" lang="en"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title>Login</title>
	
	<base href="<?= $base = $ci->config->item('base_url') ?>" />
	
<!-- CSS Static Files-->	
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,300italic,400italic,600italic' rel='stylesheet' type='text/css'>

<!-- Custom CSS -->	
	<link rel="stylesheet" href="application/views/common/migration/login/css/main.css">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<!-- Pre-empt IE9 into quirks mode --><!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" /><![endif]-->

	<script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
<!--[if lt IE 8]><p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p> <![endif]-->

<!-- Header Section -->
	<header class="header navbar-fixed-top">

<!-- Navigation -->
		<nav class="navbar">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="common/migration"><img src="application/views/common/migration/login/images/logo-inewswire.svg" alt="Logo | iNewsWire"></a>
				</div>
			</div>
		</nav>
	</header>

<!-- Main -->
	<main class="main login-section form-page" role="main">
		<div class="container">
			<div class="row">
				<div class="col-sm-3">
				</div>
				<div class="col-sm-6">
					<header class="main-header">
						<h1>Login</h1>
					</header>
					
					<?php if (isset($vd->error)): ?>
					<div class="alert alert-danger">
						<?= $vd->esc($vd->error) ?>
					</div>	
					<?php endif ?>
					
					<form class="login-form" method="post" action="" role="form">
						<ul>
							<li class="form-group">
								<label for="email">Email Address</label>
								<input class="form-control" type="email" name="email" id="email" tabindex="1">
								<script>
								
								$(function() {
									$("#email").focus();
								});
								
								</script>
							</li>
							<li class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label for="pass">Password</label>
									</div>
									<div class="col-xs-6">
										<a href="Login/Forgot" class="help-form-link">Forgot Password?</a>
									</div>
								</div>
								<input class="form-control" type="password" name="password" id="pass" tabindex="2">
							</li>
							<li>
								<button type="submit" class="btn btn-success btn-lg" tabindex="3">Login</button>
							</li>
						</ul>
						
						<footer class="form-footer">
							<menu class="form-footer-menu">
								<li><i class="fa fa-user"></i> Need an account? <a href="Register">Sign up today!</a></li>
							</menu>
						</footer>
					</form>
				</div>
				<div class="col-sm-3">
				</div>	
			</div>
		</div>
	</main>

<!-- Footer -->
	<footer class="footer">
		<div class="container">
			<div class="row">
				

<!-- Copyright -->
				<div class="col-sm-12">
					<section class="copy">
						<span class="tel">
							<i class="fa fa-phone"></i> (800) 713-7278
						</span>
						
						<address class="adr">
							<span><strong>iNewsWire.com</strong> LLC 5 Penn Plaza, 23rd Floor </span>
							<span>New York, NY 10001 (800) 713-7278</span>
						</address>
						
					</section>
				</div>
			</div>
		</div>
	</footer>
	
	
<!--JS Libraries -->
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
<!--[if lt IE 9]><script src="js/respond.min.js"></script><![endif]-->
	<script src="application/views/common/migration/login/js/retina.js"></script>
	<script src="application/views/common/migration/login/js/masonry.pkgd.min.js"></script>
	
<!-- Initialize JS Plugins -->
	<script src="application/views/common/migration/login/js/config.js"></script>
</body>
</html>