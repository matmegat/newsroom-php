<div id="login-form">
	
	<form class="form-horizontal" action="" method="post">
			
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
			
			<div class="control-group">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input id="email" name="email" type="email"
						placeholder="email@example.com" required />
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input id="password" name="password" type="password" 
						placeholder="Password" required />
				</div>
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
					<button type="submit" class="btn btn-success">Login</button>
				</div>
			</div>
		
	</form>
	
</div>