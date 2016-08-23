<form action="<?= $ci->uri->uri_string() ?>" method="post" class="required-form">
	
	<div class="row-fluid">
		<div class="span12">
			<header class="page-header">
				<div class="row-fluid">
					<div class="span6">
						<h1>Account Details</h1>
					</div>
					
					<div class="span6">
						<div class="pull-right">
							<input type="hidden" name="save" value="1" />
							<?php if (!$this->session->get('assume_account_owner')): ?>
							<input type="password" placeholder="Verify Password" name="password" />
							<?php endif ?>
							<button type="submit" class="bt-publish bt-orange">
								Save
							</button>
						</div>
					</div>
				</div>
			</header>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="content content-no-tabs">
				<div class="row-fluid">
					<div class="span12">
						
						<section class="form-section basic-information">
							<h2>User Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span4">
											<input type="text" placeholder="First Name" class="in-text span12 required"
												name="first_name" value="<?= $vd->esc($vd->user->first_name) ?>"
												data-required-name="First Name" />
										</div>
										<div class="span4">
											<input type="text" placeholder="Last Name" class="in-text span12 required" 
												name="last_name" value="<?= $vd->esc($vd->user->last_name) ?>" 
												data-required-name="Last Name" />
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<input type="email" placeholder="Email Address" class="in-text span12 required" 
												name="email" value="<?= $vd->esc($vd->user->email) ?>" 
												data-required-name="Email Address" />
										</div>
									</div>
								</li>
							</ul>
						</section>

						<section class="form-section basic-information">
							<h2>Change Password</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span4">
											<input type="password" placeholder="New Password" id="new-password" 
												name="new_password" class="in-text span12" />
											<input type="password" placeholder="Confirm New Password" 
												id="new-password-confirm" name="new_password_confirm" 
												class="in-text span12" />
										</div>
										<div class="span5">
											<script>
											
											$(function() {
												
												var hidden = true;
												var pw_boxes = $("#new-password, #new-password-confirm");
												pw_boxes.on("focus", function() {
													if (!hidden) return;
													$("#secure-hint").fadeIn();
													hidden = false;
												});
												
											});
											
											</script>
											<div class="alert alert-info hidden" id="secure-hint">
												<strong>Be Secure!</strong> We recommend a password at least 8 characters in length. 
												You should include letters, numbers and symbols for added security. 
											</div>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script src="<?= $vd->assets_base ?>js/required.js?<?= $vd->version ?>"></script>
	
</form>