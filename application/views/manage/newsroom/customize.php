<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Design / Customization</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content required-form" method="post" action="manage/newsroom/customize/save">
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<?php if (!$vd->custom): ?>
						<section class="form-section use-defaults pad-15v">
							<div class="alert alert-success with-btn-left nomarbot">
								Want to use the default customization? Just press the button.
								<span class="pull-left">
									<a class="btn btn-mini btn-primary" 
										href="manage/newsroom/customize/defaults">
										Use Defaults</a>
								</span>
							</div>
						</section>
						<?php endif ?>

						<section class="form-section newsroom-name" id="newsroom-url">
							<h2>Newsroom Name</h2>
							<div class="row-fluid">
								<div class="span12 input-append in-text-add-on">
									<input class="in-text has-loader" type="text" id="newsroom-name"
										name="name" placeholder="Newsroom Name"
										value="<?= $vd->esc($ci->newsroom->name) ?>" />
									<span class="add-on"><?= $ci->conf('host_suffix') ?></span>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<p class="help-block">
										This will change the newsroom URL. 
										The existing URL will no longer function. 
									</p>
								</div>
							</div>
							<?php if (Auth::is_admin_controlled()): ?>
							<div class="row-fluid">
								<div class="span12">
									<input class="in-text span12" type="text" 
										placeholder="Newsroom Domain" name="newsroom_domain"
										value="<?= $vd->esc($ci->newsroom->domain) ?>" />
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<p class="help-block">
										This will set a custom domain for accessing the newsroom. 
										The DNS for the domain must be set such that an the host
										has an A record with our IP address 
										(<span class="status-info"><?= 
											$ci->conf('ip_address') ?></span>).
									</p>
								</div>
							</div>
							<?php else: ?>
							<div class="row-fluid">
								<div class="span12">
									<input class="in-text span12" type="text" 
										disabled placeholder="Newsroom Domain"
										value="<?= $vd->esc($ci->newsroom->domain) ?>" />
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<p class="help-block">
										Please contact us if you would like to use your own domain. 
									</p>
								</div>
							</div>
							<?php endif ?>
						</section>
						
						<script>
	
						$(function() {
							
							var test_field = $("#newsroom-name");
							var current_value = null;	
							
							var perform_test_render = function(res) {
								
								test_field.removeClass("loader");
								test_field.toggleClass("success", res.available);
								test_field.toggleClass("error", !res.available);
								
							};
							
							var perform_test = function(value) {
								
								var post_data = {};
								post_data.name = value;
								test_field.addClass("loader");
								test_field.removeClass("success");
								test_field.removeClass("error");
								$.post("manage/newsroom/customize/name_test", 
									post_data, perform_test_render);
								
							};
							
							var schedule_test_check = function() {
								
								var value = test_field.val();
								if (current_value == value) return;
								perform_test(value);
								current_value = value;
								
							};
							
							var schedule_test = function() {
								
								var value = test_field.val();
								if (current_value != value) {
									test_field.removeClass("success");
									test_field.removeClass("error");	
								}
								
								setTimeout(schedule_test_check, 250);
								
							};
							
							test_field.on("keypress", schedule_test);
							test_field.on("change", schedule_test);
							
						});
							
						</script>
						
						<section class="form-section company-logo">
							<h2>Company Logo</h2>
							<input type="hidden" id="logo-image-id" name="logo_image_id" 
								value="<?= @$vd->custom->logo_image_id ?>" />
							<div class="row-fluid">
								<div class="span4 image-upload-left image-container scaled">
									<?php if (@$vd->custom->logo_image_id): ?>
									<?php $lo_im = Model_Image::find($vd->custom->logo_image_id); ?>
									<?php $lo_variant = $lo_im->variant('header-thumb'); ?>
									<?php $lo_url = Stored_Image::url_from_filename($lo_variant->filename); ?>
									<img id="logo-image-thumb" src="<?= $lo_url ?>" />
									<?php else: ?>
									<img id="logo-image-thumb" class="loader blank" />
									<?php endif ?>
								</div>
								<div class="span8">
									<div class="row-fluid">
										<div class="span12">
											<div class="row-fluid no-overflow marbot-5" id="logo-image-upload">
												<div class="span9 file-upload-faker">
													<div class="fake row-fluid">
														<div class="span8 text-input">
															<input type="text" placeholder="Select Image" class="in-text span12 fake-text" />
														</div>
														<div class="span4">
															<button class="btn span12 fake-button" type="button">Browse</button>
														</div>
													</div>
													<div class="real row-fluid">
														<input class="in-text span12 real-file" type="file" name="image" />
													</div>
												</div>
												<div class="span3">
													<button type="button" class="file-upload-faker-button btn span12 remove-button">
														Remove
													</button>
												</div>
											</div>
											<p class="help-block">
												<?php $v_header = $ci->conf('v_sizes', 'header') ?>
												We enforce a <span class="darker">maximum size of 
												<?= $v_header->width ?>x<?= $v_header->height ?></span>.
												The image will be resized if it exceeds that. The PNG32
												format is recommended and any transparency will be preserved.
											</p>
										</div>
									</div>
								</div>
							</div>
							<div>
								<label class="checkbox-container">
									<input type="checkbox" name="use_white_header" value="1" 
										<?= value_if_test(@$vd->custom->use_white_header, 'checked') ?> /> 
									<span class="checkbox"></span>
									<span>Optimize for a logo with a white (non-transparent) background. </span>
									
								</label>
								<p class="help-block">For best results, please use an image with a 
									transparent background and then untick this box.</p>
							</div>
							<script>
		
							$(function() {
								
								var li_upload = $("#logo-image-upload");
								
								li_upload.find(".real-file").on("change", function() {
									
									var real_file = $(this);
									var fake_text = li_upload.find(".fake-text");
									var li_thumb = $("#logo-image-thumb");
									
									fake_text.removeClass("error");
									fake_text.val(real_file.val());
									real_file.attr("disabled", true);
									li_thumb.removeClass("blank");
									li_thumb.addClass("loader");
									li_thumb.removeAttr("src");
									
									var image_id_input = $("input#logo-image-id");
									
									var on_upload = function(res) {
										
										if (res.status)
										{
											real_file.attr("disabled", false);
											image_id_input.val(res.image_id);
											li_thumb.removeClass("loader");
											li_thumb.attr("src", res.files["header-thumb"]);
										}
										else
										{
											fake_text.addClass("error");
											real_file.attr("disabled", false);
										}
										
									};
									
									real_file.ajax_upload({
										callback: on_upload,
										url: "manage/image/upload",
										data: { variants: ["header", "header-thumb",
											"header-finger", "header-sidebar"] }
									});
									
								});

								li_upload.find(".remove-button").on("click", function() {
									
									$("input#logo-image-id").val("");
									li_upload.find(".fake-text").val("");
									
									var li_thumb = $("#logo-image-thumb");
									li_thumb.addClass("loader blank");
									li_thumb.removeAttr("src");
									
								});
								
							});
							
							</script>
						</section>
						
						<section class="form-section headline">
							<h2>Newsroom Title and Header</h2>
							<div class="row-fluid">
								<div class="span12">
									<input class="in-text span12" type="text" 
										name="headline" placeholder="Newsroom Title"
										value="<?= $vd->esc(@$vd->custom->headline) ?>" />
									<p class="help-block">
										Add a title to your newsroom to help describe your company or organization.
									</p>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span6">
									<input class="in-text span12" type="text" 
										name="headline_prefix" placeholder="Header (Line 1)"
										value="<?= $vd->esc(@$vd->custom->headline_prefix) ?>" />
									<p class="help-block">
										Change the first line of text in the newsroom header.
									</p>
								</div>
								<div class="span6">
									<input class="in-text span12" type="text" 
										name="headline_h1" placeholder="Header (Line 2)"
										value="<?= $vd->esc(@$vd->custom->headline_h1) ?>" />
									<p class="help-block">
										Change the H1 title in the newsroom header.
										Defaults to the company name.
									</p>
								</div>
							</div>
						</section>
						
						<section class="form-section" id="relevant-links">
							<h2>Newsroom Links</h2>
							<ul>
								<li class="rr-pri">
									<div class="row-fluid">
										<div class="span5 rr-title">
											<input class="in-text span12" type="text" 
												value="<?= $vd->esc(@$vd->custom->rel_res_pri_title) ?>"
												name="rel_res_pri_title" 
												placeholder="Link Title" />
										</div>
										<div class="span7 rr-link">
											<input class="in-text span12" type="url" 
												value="<?= $vd->esc(@$vd->custom->rel_res_pri_link) ?>"
												name="rel_res_pri_link" 
												placeholder="Link URL" />
										</div>
									</div>
								</li>
								<li class="rr-sec">
									<div class="row-fluid">
										<div class="span5 rr-title">
											<input class="in-text span12" type="text"
												value="<?= $vd->esc(@$vd->custom->rel_res_sec_title) ?>" 
												name="rel_res_sec_title" 
												placeholder="Link Title" />
										</div>
										<div class="span7 rr-link">
											<input class="in-text span12" type="url" 
												value="<?= $vd->esc(@$vd->custom->rel_res_sec_link) ?>"
												name="rel_res_sec_link" 
												placeholder="Link URL" />
										</div>
									</div>
								</li>
								<li class="rr-ter">
									<div class="row-fluid">
										<div class="span5 rr-title">
											<input class="in-text span12" type="text"
												value="<?= $vd->esc(@$vd->custom->rel_res_ter_title) ?>" 
												name="rel_res_ter_title" 
												placeholder="Link Title" />
										</div>
										<div class="span7 rr-link">
											<input class="in-text span12" type="url" 
												value="<?= $vd->esc(@$vd->custom->rel_res_ter_link) ?>"
												name="rel_res_ter_link" 
												placeholder="Link URL" />
										</div>
									</div>
								</li>
							</ul>
						</section>
						
						<section class="form-section background">
							<h2>Background</h2>
							<input type="hidden" id="back-image-id" name="back_image_id" 
								value="<?= @$vd->custom->back_image_id ?>" />
							<div class="row-fluid">
								<div class="span4 image-upload-left image-container">
									<?php if (@$vd->custom->back_image_id): ?>
									<?php $ba_im = Model_Image::find($vd->custom->back_image_id); ?>
									<?php $ba_variant = $ba_im->variant('thumb'); ?>
									<?php $ba_url = Stored_Image::url_from_filename($ba_variant->filename); ?>
									<img id="back-image-thumb" src="<?= $ba_url ?>" />
									<?php else: ?>
									<img id="back-image-thumb" class="loader blank" />
									<?php endif ?>
								</div>
								<div class="span8 image-upload-right">
									<ul>
										<li>											
											<div class="row-fluid">
												<div class="span12">
													<div class="row-fluid" id="back-image-upload">
														<div class="span9 file-upload-faker">
															<div class="fake row-fluid">
																<div class="span8 text-input">
																	<input type="text" placeholder="Select Image" class="in-text span12 fake-text" />
																</div>
																<div class="span4">
																	<button class="btn span12 fake-button" type="button">Browse</button>
																</div>
															</div>
															<div class="real row-fluid">
																<input class="in-text span12 real-file" type="file" name="image" />
															</div>
														</div>
														<div class="span3">
															<button type="button" class="file-upload-faker-button btn span12 remove-button">
																Remove
															</button>
														</div>
													</div>
												</div>
											</div>
										</li>
										<li id="select-back-repeat">
											<select class="show-menu-arrow span12" name="back_image_repeat">
												<option value="repeat"
													<?= value_if_test(@$vd->custom->back_image_repeat == 'repeat', 'selected') ?>>
													Tiled
												</option>
												<option value="no-repeat"
													<?= value_if_test(@$vd->custom->back_image_repeat == 'no-repeat', 'selected') ?>>
													Fixed Position
												</option>
											</select>
											<script>

											$(function() {
												
												$("#select-back-repeat select").on_load_select();
												
											});
											
											</script>
										</li>
									</ul>
								</div>
							</div>
							<script>
		
							$(function() {
								
								var bi_upload = $("#back-image-upload");
								
								bi_upload.find(".real-file").on("change", function() {
									
									var real_file = $(this);
									var fake_text = bi_upload.find(".fake-text");
									var ba_thumb = $("#back-image-thumb");
									
									fake_text.removeClass("error");
									fake_text.val(real_file.val());
									real_file.attr("disabled", true);
									ba_thumb.removeClass("blank");
									ba_thumb.addClass("loader");
									ba_thumb.removeAttr("src");
									
									var image_id_input = $("input#back-image-id");
									
									var on_upload = function(res) {
										
										if (res.status)
										{
											real_file.attr("disabled", false);
											image_id_input.val(res.image_id);
											ba_thumb.removeClass("loader");
											ba_thumb.attr("src", res.files["thumb"]);
										}
										else
										{
											fake_text.addClass("error");
											real_file.attr("disabled", false);
										}
										
									};
									
									real_file.ajax_upload({
										callback: on_upload,
										url: "manage/image/upload",
										data: { variants: ["thumb"], size_limit: 524288 }
									});
									
								});

								bi_upload.find(".remove-button").on("click", function() {
									
									$("input#back-image-id").val("");
									bi_upload.find(".fake-text").val("");
									
									var ba_thumb = $("#back-image-thumb");
									ba_thumb.addClass("loader blank");
									ba_thumb.removeAttr("src");		
									
								});
								
							});
							
							</script>							
						</section>
						
						<section class="form-section">
							<h2>Google Analytics</h2>
							<div class="row-fluid">
								<div class="span12">
									<input class="in-text span12" type="text" pattern="^UA\-\d+\-\d+$"
										name="ganal" placeholder="Tracking ID" 
										value="<?= $vd->esc(@$vd->custom->ganal) ?>" />
									<p class="help-block">
										Use Google Analytics for detailed newsroom statistics. 
									</p>
								</div>
							</div>
						</section>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div class="aside-properties padding-top" id="locked_aside">
							<ul class="color-labels">								
								<li class="customize-colors row-fluid">
									<div class="span12 input-append in-text-add-on">
										<div class="color-label"><div>Link Color</div></div>
										<input type="text" placeholder="Link Color" class="in-text span10 color" name="link_color"
											value="<?= $vd->esc(@$vd->custom->link_color) ?>" pattern="^#[A-Fa-f0-9]{6}$" />
										<span class="color-pick add-on"><i class="icon-screenshot"></i></span>
									</div>
								</li>
								<li class="customize-colors row-fluid">
									<div class="span12 input-append in-text-add-on">
										<div class="color-label"><div>Link Hover Color</div></div>
										<input type="text" placeholder="Link Hover Color" class="in-text span10 color" name="link_hover_color"
											value="<?= $vd->esc(@$vd->custom->link_hover_color) ?>" pattern="^#[A-Fa-f0-9]{6}$" />
										<span class="color-pick add-on"><i class="icon-screenshot"></i></span>
									</div>
								</li>
								<li class="customize-colors row-fluid">
									<div class="span12 input-append in-text-add-on">
										<div class="color-label"><div>Text Color</div></div>
										<input type="text" placeholder="Text Color" class="in-text span10 color" name="text_color"
											value="<?= $vd->esc(@$vd->custom->text_color) ?>" pattern="^#[A-Fa-f0-9]{6}$" />
										<span class="color-pick add-on"><i class="icon-screenshot"></i></span>
									</div>
								</li>
								<li class="customize-colors row-fluid">
									<div class="span12 input-append in-text-add-on">
										<div class="color-label"><div>Header Color</div></div>
										<input type="text" placeholder="Header Color" class="in-text span10 color" name="header_color"
											value="<?= $vd->esc(@$vd->custom->header_color) ?>" pattern="^#[A-Fa-f0-9]{6}$" />
										<span class="color-pick add-on"><i class="icon-screenshot"></i></span>
									</div>
								</li>
								<li class="customize-colors row-fluid">
									<div class="span12 input-append in-text-add-on" data-colorpicker-trans="1">
										<div class="color-label"><div>Background Color</div></div>
										<input type="text" placeholder="Background Color" class="in-text span10 color" name="back_color"
											value="<?= $vd->esc(@$vd->custom->back_color) ?>" pattern="^(transparent|#[A-Fa-f0-9]{6})$" />
										<span class="color-pick add-on"><i class="icon-screenshot"></i></span>
									</div>
								</li>
								<li>
									<div class="row-fluid marbot-20">
										<div class="span7 offset5">
											<button type="button" id="reset-defaults"
												class="span12 ta-center bt-silver">Reset Colors</a>
											<script>
											
											$(function() {
												
												var reset = $("#reset-defaults");
												reset.on("click", function() {
													$("input.color").val("");
												});
												
											});
											
											</script>
										</div>
									</div>
								</li>		
								<li>
									<div class="row-fluid">
										<div class="span8 offset1">
											<button type="submit" name="is_preview" value="1" 
												class="span12 bt-silver">Preview Newsroom</button>
										</div>
										<div class="span3">
											<button type="submit" name="publish" value="1" 
												class="span12 bt-orange">Save</button>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</aside>
					
					<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-colorpicker/css.css" />
					<script src="<?= $vd->assets_base ?>lib/bootstrap-colorpicker/js.js"></script>
					<script src="<?= $vd->assets_base ?>js/required.js?<?= $vd->version ?>"></script>
					<script>
					
					$(function() {
						
						var options = { offset: { top: 20 } };
						$.lockfixed("#locked_aside", options);
						
						$("input.color").each(function() {
							var _this = $(this);
							var container = _this.parent();
							container.colorpicker({ format: "hex" });
							container.colorpicker("setValue", _this.val());
						});
											
						$("span.color-pick").on("click", function() {
							$(this).prev().colorpicker("show");
						});
						
						$(".color-labels .color").on("focus", function() {
							$(this).prev().addClass("focus");
						}).on("blur", function() {
							$(this).prev().removeClass("focus");
						})
						
					});
					
					</script>
					
				</div>
			</form>
		</div>
	</div>
</div>