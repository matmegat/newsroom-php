<div class="span8 main-content">
	<section class="content-view">
			
		<div id="login-form" class="simple-content-area inner-content">

			<h4>Login</h4><hr />
			<form action="" method="post">

				<?php if (!empty($vd->error_text)): ?>
				<div class="control-group">
					<label class="control-label"></label>
					<div class="controls">
						<div id="login-error" class="alert alert-error">
							<?= $vd->error_text ?>
						</div>
					</div>
				</div>
				<?php endif ?>

				<div class="input-prepend input-block-level">
					<span class="add-on icon-padding-fix"><i class="icon-user"></i></span>
					<input id="prependedInput" class="span3" name="email" type="email" placeholder="email@example.com" required />
				</div>
				<div class="input-prepend input-block-level">
					<span class="add-on icon-padding-fix"><i class="icon-lock"></i></span>
					<input id="prependedInput" class="span3" name="password" type="password" placeholder="password" required />
				</div>

				<div class="control-group">
					<div class="controls" id="login-form-links">
						<a href="<?= $ci->conf('website_url') ?>Register">Register</a>
						<span class="vertical-bar">|</span>
						<a href="<?= $ci->conf('website_url') ?>Login/Forgot">Forgot Password</a>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<button type="submit" class="btn btn-success no-custom">Login</button>
					</div>
				</div>
			</form>
		</div>
		
	</section>	
</div>