<ul class="breadcrumb">
	<li><a href="manage/newsroom">iNewsroom</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/newsroom/contact">Company Contacts</a> <span class="divider">&raquo;</span></li>
	<?php if (@$vd->contact): ?>
	<li class="active"><?= $vd->esc($vd->contact->name) ?></li>
	<?php else: ?>
	<li class="active">New Contact</li>
	<?php endif ?>
</ul>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->contact): ?>
					<h1>Edit Contact</h1>
					<?php else: ?>
					<h1>Add Contact</h1>
					<?php endif ?>
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
			<form class="tab-content required-form" method="post" action="manage/newsroom/contact/edit/save">
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<input type="hidden" name="contact_id" value="<?= @$vd->contact->id ?>" />

						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12 required" type="text" 
												name="name" placeholder="Contact Name"
												data-required-name="Contact Name"
												value="<?= $vd->esc(@$vd->contact->name) ?>" />
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="title" placeholder="Contact Title"
												value="<?= $vd->esc(@$vd->contact->title) ?>" />
										</div>
									</div>
								</li>
							</ul>
							<h2>Contact Bio</h2>
							<ul>
								<li class="marbot-20 cke-container">
									<textarea class="in-text in-content span12" id="description"
										name="description" placeholder="Contact Description"><?= 
										$vd->esc(@$vd->contact->description) 
									?></textarea>									
									<script>
									
									window.init_editor($("#description"), { height: 400 });
									
									</script>
									<p class="help-block">Describe or talk about this contact.</p>
								</li>
							</ul>
						</section>
						
						<section class="form-section contact-picture">
							<h2>Contact Picture</h2>
							<input type="hidden" id="contact-image-id" name="image_id" 
								value="<?= @$vd->contact->image_id ?>" />
							<div class="row-fluid">
								<div class="span4 image-upload-left image-container scaled">
									<?php if (@$vd->contact->image_id): ?>
									<?php $lo_im = Model_Image::find($vd->contact->image_id); ?>
									<?php $lo_variant = $lo_im->variant('thumb'); ?>
									<?php $lo_url = Stored_Image::url_from_filename($lo_variant->filename); ?>
									<img id="contact-image-thumb" src="<?= $lo_url ?>" />
									<?php else: ?>
									<img id="contact-image-thumb" class="loader blank" />
									<?php endif ?>
								</div>
								<div class="span8">
									<div class="row-fluid">
										<div class="span12">
											<div class="row-fluid no-overflow marbot-5" id="contact-image-upload">
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
												We recommend a <span class="darker">minimum size of
												200x200</span>. The image will be resized if needed. 
												A head shot will work best within the newsroom interface.
											</p>
										</div>
									</div>
								</div>
							</div>
							<script>
		
							$(function() {
								
								var ci_upload = $("#contact-image-upload");
								
								ci_upload.find(".real-file").on("change", function() {
									
									var real_file = $(this);
									var fake_text = ci_upload.find(".fake-text");
									var ci_thumb = $("#contact-image-thumb");
									
									fake_text.removeClass("error");
									fake_text.val(real_file.val());
									real_file.attr("disabled", true);
									ci_thumb.removeClass("blank");
									ci_thumb.addClass("loader");
									ci_thumb.removeAttr("src");
									
									var image_id_input = $("input#contact-image-id");
									
									var on_upload = function(res) {
										
										if (res.status)
										{
											real_file.attr("disabled", false);
											image_id_input.val(res.image_id);
											ci_thumb.removeClass("loader");
											ci_thumb.attr("src", res.files["thumb"]);
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
										data: { variants: ["contact", "contact-cover", 
											"finger", "thumb"] }
									});
									
								});

								ci_upload.find(".remove-button").on("click", function() {
									
									$("input#contact-image-id").val("");
									ci_upload.find(".fake-text").val("");
									
									var ci_thumb = $("#contact-image-thumb");
									ci_thumb.addClass("loader blank");
									ci_thumb.removeAttr("src");
									
								});
								
							});
							
							</script>
						</section>
						
						<section class="form-section contact-information">
							<h2>Contact Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12" type="email" 
												name="email" placeholder="Email Address"
												value="<?= $vd->esc(@$vd->contact->email) ?>" />
											<p class="help-block">Email Address</p>
										</div>
										<div class="span6">
											<input class="in-text span12" type="url" 
												name="website" placeholder="Website"
												value="<?= $vd->esc(@$vd->contact->website) ?>" />
											<p class="help-block">Website Address</p>
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12" type="text" 
												name="phone" placeholder="Phone Number"
												value="<?= $vd->esc(@$vd->contact->phone) ?>" />
											<p class="help-block">Phone Number</p>
										</div>
										<div class="span6">
											<input class="in-text span12" type="text" 
												name="skype" placeholder="Skype"
												value="<?= $vd->esc(@$vd->contact->skype) ?>" />
											<p class="help-block">Skype Username</p>
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 facebook-profile-id" type="text" 
												name="facebook" placeholder="Facebook" 
												value="<?= $vd->esc(@$vd->contact->facebook) ?>" />
											<p class="help-block">Facebook Username or Page</p>
										</div>
										<div class="span6">
											<input class="in-text span12 twitter-profile-id" type="text" 
												name="twitter" placeholder="Twitter" 
												value="<?= $vd->esc(@$vd->contact->twitter) ?>" />
											<p class="help-block">Twitter Username</p>
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 linkedin-profile-id" type="text" 
												name="linkedin" placeholder="LinkedIn"
												value="<?= $vd->esc(@$vd->contact->linkedin) ?>" />
											<p class="help-block">LinkedIn Profile ID</p>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div class="aside-properties padding-top" id="locked_aside">
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<div class="alert alert-info" id="main-contact-alert">
												<strong>Notice!</strong> You can have at most one press contact. 
												Selecting press contact below will convert all other contacts to normal.
											</div>
											<?php 
											
											$is_main_contact = !$ci->newsroom->company_contact_id && !$vd->contact; 
											$is_main_contact = $is_main_contact || ($vd->contact && 
												$vd->contact->id == $ci->newsroom->company_contact_id); 
											
											?>
											<select class="show-menu-arrow span12" name="is_main_contact" id="is-main-contact">
												<option <?= value_if_test($is_main_contact, 'selected') ?> value="1">
													Press Contact
												</option>
												<option <?= value_if_test(!$is_main_contact, 'selected') ?> value="0">
													Normal Contact
												</option>
											</select>
											<script>

											$(function() {
												
												var is_main_contact = $("#is-main-contact");
												is_main_contact.on_load_select();
												
												<?php if (!$is_main_contact): ?>
													
												var alert = $("#main-contact-alert");												
												is_main_contact.on("change", function() {
													alert.toggleClass("enabled", $(this).val());
												});
												
												<?php endif ?>
												
											});
											
											</script>
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span5 offset3">
											<button type="submit" name="is_preview" value="1" 
												class="span12 bt-silver">Preview</button>
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