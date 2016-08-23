<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit Image</h1>
					<?php else: ?>
					<h1>Add New Image</h1>
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
			<form class="tab-content required-form has-premium" method="post" action="manage/publish/image/edit/save" id="content-form">
				
				<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<h2>Basic Information</h2>

						<section class="form-section basic-information row-fluid">
							<div class="clearfix">
								<div class="span4 image-container image-upload-left">
									<input type="hidden" name="image_id" id="image-id" class="image_id required" 
										value="<?= @$image->id ?>" data-required-name="Image" />
									<?php if ($image): ?>
									<img id="image-thumb"
										src="<?= Stored_Image::url_from_filename($image->variant('thumb')->filename) ?>" />
									<?php else: ?>
									<img id="image-thumb" class="loader blank" />
									<?php endif ?>
								</div>
								<div class="span8 image-upload-right">
									<ul>
										<li>
											<div class="row-fluid" id="content-image-upload">
												<div class="span12 file-upload-faker">
													<div class="fake row-fluid">
														<div class="span9 text-input">
															<input type="text" placeholder="Select Image" class="in-text span12 fake-text" />
														</div>
														<div class="span3">
															<button class="btn span12 fake-button" type="button">Browse</button>
														</div>
													</div>
													<div class="real row-fluid">
														<input class="in-text span12 real-file" type="file" name="image" />
													</div>
												</div>
											</div>
										</li>
										<li>
											<input class="in-text span12 required" type="text" name="title" 
												id="title" placeholder="Enter Title of Image"
												value="<?= $vd->esc(@$vd->m_content->title) ?>" 
												maxlength="<?= $ci->conf('title_max_length') ?>"
												data-required-name="Title" />
										</li>
									</ul>
								</div>
								<script>
			
								$(function() {
									
									var ci_upload = $("#content-image-upload");
									
									ci_upload.find(".real-file").on("change", function() {
										
										var real_file = $(this);
										var fake_text = ci_upload.find(".fake-text");
										
										fake_text.removeClass("error");
										fake_text.val(real_file.val());
										real_file.attr("disabled", true);
										
										var new_image = $("img#image-thumb")
										var image_id_input = $("input#image-id");
										new_image.removeClass("blank");
										new_image.addClass("loader");
										new_image.removeAttr("src");
										image_id_input.val("");
										
										var on_upload = function(res) {
											
											if (res.status)
											{
												fake_text.val("");
												real_file.attr("disabled", false);
												new_image.removeClass("loader");
												new_image.attr("src", res.files.thumb);
												image_id_input.val(res.image_id);
											}
											else
											{
												fake_text.addClass("error");
												real_file.attr("disabled", false);
											}
											
										};
										
										var variants = ["finger", "thumb", 
											"view-cover", "view-full", "cover"];
																					
										real_file.ajax_upload({
											callback: on_upload,
											url: "manage/image/upload",
											data: { variants: variants }
										});
										
									});
									
								});
								
								</script>
							</div>
							<ul>
								<li>
									<textarea class="in-text span12 required" id="summary" name="summary"
										data-required-name="Summary" placeholder="Enter Summary of Image"
										><?= $vd->esc(@$vd->m_content->summary) ?></textarea>
									<p class="help-block" id="summary_countdown_text">
										<span id="summary_countdown"></span> Characters Left</p>
									<script>
									
									$("#summary").limit_length(<?= $ci->conf('summary_max_length') ?>, 
										$("#summary_countdown_text"), 
										$("#summary_countdown"));
									
									</script>
								</li>
							</ul>
						</section>
						
						<?= $ci->load->view('manage/publish/partials/tags') ?>					
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
									<?= $this->load->view('manage/publish/partials/license') ?>
									<li>
										<input class="span12 in-text" type="text" name="source" 
											value="<?= $vd->esc(@$vd->m_content->source) ?>" 
											placeholder="Source / Photographer" />
									</li>
									<?php if (!@$vd->m_content->is_published): ?>
									<?= $this->load->view('manage/publish/partials/publish-date') ?>
									<?php endif ?>
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