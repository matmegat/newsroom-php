<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Social Services</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content social-services">		
		
			<div class="row-fluid">
				<div class="span8">
					
					<ul id="social-services-list">
						<li>
							<h2>Facebook Authorization</h2>
							<?php if ($vd->facebook_auth && $vd->facebook_auth->is_valid()): ?>
							<div>
								<div class="social-auth-text">
									Facebook authorization is enabled for
									<strong><?= $vd->facebook_name ?></strong>.
								</div>
								<div>
									<form action="manage/newsroom/social/facebook_page" method="post">
										<select id="select-facebook-page" class="show-menu-arrow smaller" name="page">
											<option value="">Personal Timeline</option>
											<?php foreach ($vd->facebook_pages as $page): ?>
											<option value="<?= $page->id ?>" 
												<?= value_if_test($page->id == $vd->facebook_auth->page, 'selected') ?>>
												<?= $vd->esc($page->name) ?></option>
											<?php endforeach ?>
										</select>
										<button id="set-page" type="submit"
											class="btn btn-small">Set</button>
										<script> 
										
										$(function() {
											
											var select = $("#select-facebook-page");
											select.on_load_select();
											
											var set_page = $("#set-page");
											var bs_select = select.siblings(".bootstrap-select");
											
											set_page.detach();
											bs_select.children("button").after(set_page);
											
										});
										
										</script>
									</form>
								</div>
								<div>
									<form action="manage/newsroom/social/facebook_delete">
										<button class="btn btn-small btn-danger" type="submit">
											Remove Authorization
										</button>
									</form>
								</div>
							</div>
							<?php else: ?>
							<div>
								<div class="social-auth-text">
									Facebook authorization not found.
								</div>
								<div>
									<form action="manage/newsroom/social/facebook_start">
										<button class="btn btn-small" type="submit">
											Start Authorization
										</button>
									</form>
								</div>
							</div>
							<?php endif ?>
						</li>
						<li>
							<h2>Twitter Authorization</h2>			
							<?php if ($vd->twitter_auth && $vd->twitter_auth->is_valid()): ?>
							<div>
								<div class="social-auth-text">
									Twitter authorization is enabled for
									<strong><?= $vd->twitter_name ?></strong>.
								</div>
								<div>
									<form action="manage/newsroom/social/twitter_delete">
										<button class="btn btn-small btn-danger" type="submit">
											Remove Authorization
										</button>
									</form>
								</div>
							</div>
							<?php else: ?>
							<div>
								<div class="social-auth-text">
									Twitter authorization not found.
								</div>
								<div>
									<form action="manage/newsroom/social/twitter_start">
										<button class="btn btn-small" type="submit">
											Start Authorization
										</button>
									</form>
								</div>
							</div>							
							<?php endif ?>
						</li>
					</ul>
					
				</div>
				<aside class="span4 aside">
					
					<div class="alert alert-info" style="margin-top: 10px">
						This process will authorize the newsroom to publish content to 
						facebook and twitter on your behalf. We recommend that you 
						login to facebook or twitter before starting.
					</div>
					
				</aside>
			</div>
			
		</div>
	</div>
</div>