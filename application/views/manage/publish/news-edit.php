<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit News Content</h1>
					<?php else: ?>
					<h1>Add News Content</h1>
					<?php endif ?>
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
		<div class="content">
			<form class="tab-content required-form has-premium" method="post" action="manage/publish/news/edit/save" id="content-form">
				
				<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<h2>Basic Information</h2>

						<section class="form-section basic-information">
							<ul>
								<li>
									<input class="in-text span12 required" type="text" name="title" 
										id="title" placeholder="Enter Title of News Content"
										maxlength="<?= $ci->conf('title_max_length') ?>"
										value="<?= $vd->esc(@$vd->m_content->title) ?>" data-required-name="Title" />
								</li>
								<li>
									<textarea class="in-text span12 required" id="summary" name="summary" 
										data-required-name="Summary" placeholder="Enter Summary of News Content"
										><?= $vd->esc(@$vd->m_content->summary) ?></textarea>
									<p class="help-block" id="summary_countdown_text">
										<span id="summary_countdown"></span> Characters Left</p>
									<script>
									
									$("#summary").limit_length(<?= $ci->conf('summary_max_length') ?>, 
										$("#summary_countdown_text"), 
										$("#summary_countdown"));
									
									</script>
								</li>
								<li class="marbot-20">
									<textarea class="in-text in-content span12 required" id="content"
										data-required-name="Content Body" name="content" 
										placeholder="News Content Body"><?= 
										$vd->esc(@$vd->m_content->content) 
									?></textarea>
									<script>
									
									window.init_editor($("#content"), { height: 400 });
									
									</script>
								</li>
							</ul>
						</section>

						<?= $ci->load->view('manage/publish/partials/tags') ?>
						<?= $ci->load->view('manage/publish/partials/web-images') ?>						
						<?= $ci->load->view('manage/publish/partials/relevant-resources') ?>
						
						<?php if (!@$vd->m_content->is_published): ?>
						<?= $ci->load->view('manage/publish/partials/social-media') ?>
						<?php endif ?>
						
					</div>
						
					<aside class="span4 aside aside-fluid">
						<div class="aside-properties" id="locked_aside">

							<?= $this->load->view('manage/publish/partials/status') ?>

							<section class="ap-block ap-properties">
								<ul>
									<?= $this->load->view('manage/publish/partials/select-category') ?>
									<?php if (!@$vd->m_content->is_published): ?>
									<?= $this->load->view('manage/publish/partials/publish-date') ?>
									<?php endif ?>
									<script>
									
									$(function() {
										
										var selects = $("#locked_aside select.category");
										selects.on_load_select();
											
										$(window).load(function() {
											selects.eq(0).addClass("required");
										});
										
									});
									
									</script>
									<li>										
										<?php if (@$vd->m_content->is_published || @$vd->m_content->is_under_review): ?>
										<div class="row-fluid">
											<div class="span5 offset3">
												<button type="submit" name="is_preview" value="1" 
													class="span11 bt-silver">Preview</button>
											</div>
											<div class="span4">
												<button type="submit" name="publish" value="1" 
													class="span12 bt-silver bt-orange">Save</button>
											</div>											
										</div>
										<?php else: ?>
										<div class="row-fluid marbot">
											<div class="span7">
												<button type="submit" name="is_draft" value="1" 
													class="span12 bt-silver">Save Draft</button>
											</div>
											<div class="span5">
												<button type="submit" name="is_preview" value="1" 
													class="span12 bt-silver pull-right">Preview</button>
											</div>
										</div>										
										<div class="row-fluid">											
											<div class="span12">
												<button type="submit" name="publish" value="1" 
													class="span12 bt-orange pull-right">Publish</button>
											</div>
										</div>
										<?php endif ?>
									</li>
								</ul>
							</section>
							
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