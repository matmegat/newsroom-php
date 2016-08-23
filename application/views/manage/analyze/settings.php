<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Report Settings</h1>
				</div>
				<div class="span6">
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content required-form" method="post" action="manage/analyze/settings/save">
				<div class="row-fluid">
					<div class="span8 information-panel">

						<?php if ($this->newsroom->is_active): ?>
						<section class="form-section basic-information">
							<h2>Newsroom Stats Report</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="overall_email" 
												placeholder="Email Addresses (Comma Separated)"
												value="<?= $vd->esc(@$vd->settings->overall_email) ?>" />
										</div>
									</div>
									<div class="row-fluid">
										<div class="span12 marbot-20">
											<label class="radio-container">
												<input type="radio" name="overall_when" value="weekly" 
													<?= value_if_test((@$vd->settings->overall_when == 'weekly'), 'checked') ?> /> 
												<span class="radio"></span>
												<span class="muted">Once a</span><span> week</span>
											</label>
											<label class="radio-container">
												<input type="radio" name="overall_when" value="monthly" 
													<?= value_if_test((@$vd->settings->overall_when == 'monthly'), 'checked') ?> /> 
												<span class="radio"></span>
												<span class="muted">Once a</span><span> month</span>
											</label>
											<label class="radio-container">
												<input type="radio" name="overall_when" value="" 
													<?= value_if_test(!@$vd->settings->overall_when, 'checked') ?> /> 
												<span class="radio"></span>
												<span>Never</span>
											</label>
										</div>
									</div>
								</li>
							</ul>
						</section>
						<?php endif ?>
						
						<section class="form-section basic-information">
							<h2>Press Release Stats Report</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="pr_email" 
												placeholder="Email Addresses (Comma Separated)"
												value="<?= $vd->esc(@$vd->settings->pr_email) ?>" />
										</div>
									</div>
									<div class="row-fluid">
										<div class="span12 marbot-20">
											<label class="radio-container">
												<input type="radio" name="pr_when" value="7" 
													<?= value_if_test((@$vd->settings->pr_when == '7'), 'checked') ?> /> 
												<span class="radio"></span>
												<span class="muted">After </span><span>7 days</span>
											</label>
											<label class="radio-container">
												<input type="radio" name="pr_when" value="30" 
													<?= value_if_test((@$vd->settings->pr_when == '30'), 'checked') ?> /> 
												<span class="radio"></span>
												<span class="muted">After </span><span>30 days</span>
											</label>
											<label class="radio-container">
												<input type="radio" name="pr_when" value="" 
													<?= value_if_test(!@$vd->settings->pr_when, 'checked') ?> /> 
												<span class="radio"></span>
												<span>Never</span>
											</label>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div class="alert alert-info">
							<strong>Remember!</strong> You can view stats at any time
							using the control panel.
						</div>
						<div class="aside-properties padding-top" id="locked_aside">
							<ul>
								<li class="nomarbot">
									<div class="row-fluid">
										<div class="span8">
											<button type="submit" name="test" value="1" 
												class="span12 bt-silver">Save and Test</button>
										</div>
										<div class="span4">
											<button type="submit" name="publish" value="1" 
												class="span12 bt-orange">Save</button>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</aside>
					
					<script src="<?= $vd->assets_base ?>js/required.js?<?= $vd->version ?>"></script>
					<script>
					
					$(function() {
						
						var options = { offset: { top: 20 } };
						$.lockfixed("#locked_aside", options);
						
					});
					
					</script>
					
				</div>
			</form>
		</div>
	</div>
</div>