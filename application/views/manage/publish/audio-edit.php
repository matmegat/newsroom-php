<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit Audio</h1>
					<?php else: ?>
					<h1>Add New Audio</h1>
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
			<form class="tab-content required-form has-premium" method="post" action="manage/publish/audio/edit/save" id="content-form">
				
				<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<h2>Basic Information</h2>
						
						<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/mediaelement/mediaelementplayer.css" />
						<script src="<?= $vd->assets_base ?>lib/mediaelement/mediaelement-and-player.min.js"></script>

						<section class="form-section basic-information row-fluid">							
							<ul>
								<li id="uploaded-audio-player" class="<?= value_if_test(@$vd->m_content, 'enabled') ?>">
									<?php if (@$audio): ?>
									<audio src="<?= $vd->esc(Stored_File::url_from_filename($audio->filename)) ?>" />
									<script>
									
									$(function() {
										
										var audio = $("#uploaded-audio-player audio");
										audio.mediaelementplayer({
											audioWidth: 590
										});
										
									});
									
									</script>
									<?php endif ?>
								</li>
								<li>
									<input type="hidden" id="stored-file-id" name="stored_file_id" 
										value="<?= $vd->esc(@$vd->m_content->stored_file_id) ?>"
										class="required" data-required-name="Audio File" />
								</li>
								<li id="audio-upload-status">
									<div class="alert alert-warning">
										<strong>Patience!</strong>
										The upload process can take several minutes. <br />
										You can continue to fill out the form while you wait.
									</div>
								</li>
								<li id="audio-upload-error">
									<div class="alert alert-error">
										<strong>Error!</strong>
										<span></span>
									</div>
								</li>
								<li>
									<div class="row-fluid" id="content-audio-upload">
										<div class="span12 file-upload-faker">
											<div class="fake row-fluid">
												<div class="span10 text-input">
													<input type="text" placeholder="Select Audio File (MP3)" class="in-text span12 fake-text" />
												</div>
												<div class="span2">
													<button class="btn span12 fake-button" type="button">Browse</button>
												</div>
											</div>
											<div class="real row-fluid">
												<input class="in-text span12 real-file" type="file" name="audio" 
													accept="<?= $vd->esc(implode(',', $ci->supported_mime_types())) ?>" />
											</div>
										</div>
									</div>
									<script>
			
									$(function() {
										
										var ci_upload = $("#content-audio-upload");
										var upload_status = $("#audio-upload-status");
										var upload_error = $("#audio-upload-error");
										var upload_player = $("#uploaded-audio-player");
										var stored_file_id_input = $("input#stored-file-id");
										
										ci_upload.find(".real-file").on("change", function() {
											
											var real_file = $(this);
											var fake_text = ci_upload.find(".fake-text");
											
											fake_text.removeClass("error");
											fake_text.val(real_file.val());
											real_file.attr("disabled", true);
											
											upload_player.removeClass("enabled");
											upload_error.removeClass("enabled");
											upload_status.addClass("enabled");
											stored_file_id_input.val("");
											
											var on_upload = function(res) {
												
												upload_status.removeClass("enabled");
												
												if (res && res.status) {
													
													fake_text.val("");
													real_file.attr("disabled", false);
													stored_file_id_input.val(res.stored_file_id);
													stored_file_id_input.trigger("change");
													upload_player.addClass("enabled");
													
													var audio = $.create("audio");
													audio.attr("src", res.audio_url);
													upload_player.empty().append(audio);
													audio.mediaelementplayer({
														audioWidth: 590
													});
													
												} else {
													
													error_text = ((!res || !res.error) 
														? "Upload Failed"
														: res.error);
														
													fake_text.addClass("error");
													real_file.attr("disabled", false);
													upload_error.find("span").text(error_text);
													upload_error.addClass("enabled");
													
												}
												
											};
											
											real_file.ajax_upload({
												callback: on_upload,
												url: "manage/publish/audio/upload"
											});
											
										});
										
									});
									
									</script>		
								</li>
								<li>
									<input class="in-text span12 required" type="text" name="title" 
										id="title" placeholder="Enter Title of Audio"
										value="<?= $vd->esc(@$vd->m_content->title) ?>" 
										maxlength="<?= $ci->conf('title_max_length') ?>"
										data-required-name="Title" />
								</li>
								<li>
									<textarea class="in-text span12 required" id="summary" name="summary"
										data-required-name="Summary" placeholder="Enter Summary of Audio"
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
											placeholder="Source / Audiographer" />
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