<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit Video</h1>
					<?php else: ?>
					<h1>Add New Video</h1>
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
			<form class="tab-content required-form has-premium" method="post" action="manage/publish/video/edit/save" id="content-form">
				
				<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<h2>Basic Information</h2>

						<section class="form-section basic-information row-fluid">
							<input value="<?= $vd->esc(@$vd->m_content->external_author) ?>"
								type="hidden" id="external-author" name="external_author" />
							<input value="<?= $vd->esc(@$vd->m_content->external_duration) ?>"
								type="hidden" id="external-duration" name="external_duration" />
							<div class="clearfix">
								<div class="span4 image-container image-upload-left">
									<input type="hidden" name="image_id" id="image-id" class="image_id required" 
										value="<?= @$image->id ?>" data-required-name="Video" />
									<?php if ($image): ?>
									<img id="image-thumb"
										src="<?= Stored_Image::url_from_filename($image->variant('thumb')->filename) ?>" />
									<?php else: ?>
									<img id="image-thumb" class="loader blank" />
									<?php endif ?>
								</div>
								<div class="span8 image-upload-right">
									<ul>
										<li id="select-video" class="clearfix">
											<div class="span4">
												<select class="show-menu-arrow span12" name="external_provider">
													<?php foreach ($providers as $provider): ?>
													<option value="<?= $vd->esc($provider) ?>"
														<?= value_if_test((@$vd->m_content->external_provider === $provider), 'selected') ?>>
														<?= $vd->esc(Video::get_provider_name($provider)) ?>
													</option>
													<?php endforeach ?>
												</select>
											</div>
											<div class="span8">
												<input class="in-text span12 required" type="text" name="external_video_id" 
													id="video-id" placeholder="Enter Video URL"
													value="<?= $vd->esc(@$vd->m_content->external_video_id) ?>" 
													data-required-name="Video Source" />
											</div>
										</li>
										<li>
											<input class="in-text span12 required" type="text" name="title" 
												id="title" placeholder="Enter Title of Video"
												maxlength="<?= $ci->conf('title_max_length') ?>"
												value="<?= $vd->esc(@$vd->m_content->title) ?>" 
												data-required-name="Title" />
										</li>
									</ul>
								</div>
								<script>
			
								$(function() {
									
									var select_video = $("#select-video");
									var video_id_input = $("#video-id");
									var provider_select = select_video.find("select");
									var video_props = video_id_input.add(provider_select);
									
									var title_input = $("input#title");
									var summary_ta = $("textarea#summary");
									
									var external_author_input = $("#external-author");
									var external_width_input = $("#external-width");
									var external_height_input = $("#external-height");
									var external_duration_input = $("#external-duration");					
									
									provider_select.on_load_select();
									
									video_props.on("change", function() {
										
										// not entered id so wait
										if (!video_id_input.val())
											return;
										
										var post_data = video_props.serialize();
										var new_image = $("img#image-thumb")
										var image_id_input = $("input#image-id");
										new_image.removeClass("blank");
										new_image.addClass("loader has-loader");
										new_image.removeAttr("src");
										image_id_input.val("");	
										
										external_author_input.val("");
										external_width_input.val("");
										external_height_input.val("");
										external_duration_input.val("");
										
										$(".required-error").remove();
										
										var on_upload = function(res) {
											
											if (res === null) {
			
												var required_error = $.create("div");
												required_error.addClass("alert alert-error");
												required_error.addClass("required-error");
												
												error_html = "<strong>Error!<\/strong> The " 
													+ "video information is not correct.";
													
												required_error.html(error_html);
												select_video.parent().before(required_error);
												
											} else {
												
												new_image.removeClass("loader has-loader");
												new_image.attr("src", res.image_url);
												image_id_input.val(res.image_id);
												video_id_input.val(res.video_id);
												if (!res.video_data) return;
												
												if (!title_input.val()) {
													title_input.val(res.video_data.title);
													title_input.trigger("change");
												}
												
												if (!summary_ta.val()) {
													summary_ta.val(res.video_data.description);
													summary_ta.trigger("change");
												}
												
												external_author_input.val(res.video_data.author);
												external_width_input.val(res.video_data.width);
												external_height_input.val(res.video_data.height);
												external_duration_input.val(res.video_data.duration);
												
											}
											
										};
										
										$.post("manage/publish/video/resolve_video", 
											post_data, on_upload);
										
									});
									
								});
								
								</script>
							</div>
							<ul>
								<li>
									<textarea class="in-text span12 required" id="summary" name="summary"
										data-required-name="Summary" placeholder="Enter Summary of Video"
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
											placeholder="Source / Videographer" />
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