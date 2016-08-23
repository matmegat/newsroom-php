<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Video Guide</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<?php if (@$vd->is_delete): ?>
<?= $ci->load->view('admin/settings/partials/guide_delete_before'); ?>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content required-form" method="post" action="<?= $ci->uri->uri_string ?>">
				<div class="row-fluid">
					
					<div class="span8 information-panel">
						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12 required" type="text" 
												name="title" placeholder="Guide Name"
												data-required-name="Guide Name"
												value="<?= $vd->esc(@$vd->guide->title) ?>" />
										</div>
									</div>
								</li>
								<li class="marbot-20">
									<textarea class="in-text in-content span12" id="content"
										name="content" placeholder="Content"><?= 
											$vd->esc(@$vd->guide->content) 
									?></textarea>
									<script> 
									
									window.init_editor($("#content"), { height: 400 });
									
									</script>
								</li>
							</ul>
							<ul>
								<li id="select-video" class="clearfix">
									<div class="span4">
										<select class="show-menu-arrow span12" name="external_video_provider">
											<?php $providers = Video::providers(); ?>
											<?php foreach ($providers as $provider): ?>
											<option value="<?= $vd->esc($provider) ?>"
												<?= value_if_test((@$vd->guide->external_video_provider 
													=== $provider), 'selected') ?>>
												<?= $vd->esc(Video::get_provider_name($provider)) ?>
											</option>
											<?php endforeach ?>
										</select>
									</div>
									<div class="span8">
										<input class="in-text span12" type="text" name="external_video_id" 
											id="video-id" placeholder="Enter Video URL"
											value="<?= $vd->esc(@$vd->guide->external_video_id) ?>" />
									</div>
								</li>
								<script>

								$(function() {
									
									var select_video = $("#select-video");
									var video_id_input = $("#video-id");
									var provider_select = select_video.find("select");
									var video_props = video_id_input.add(provider_select);
									
									provider_select.on_load_select({
										container: "body"
									});
									
									video_props.on("change", function() {
										
										// not entered id so wait
										if (!video_id_input.val())
											return;
										
										var post_data = video_props.serialize();
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
												
												video_id_input.val(res.video_id);
												if (!res.video_data) return;
												
											}
											
										};
										
										$.post("common/resolve_video", 
											post_data, on_upload);
										
									});
									
								});
								
								</script>
							</ul>
						</section>					
					</div>

					<aside class="span4 aside aside-fluid">
						<div id="locked_aside">
							<div class="aside-properties">
								<section class="ap-block">
									<ul>
										<li class="pad-10v">
											<select class="show-menu-arrow span12 selectpicker" name="section">
												<option value="publish">Publish</option>
												<option value="contact">Contact</option>
												<option value="analyze">Analyze</option>
												<option value="newsroom">Newsroom</option>
											</select>
										</li>
										<li>
											<div class="row-fluid">
												<button type="submit" name="save" value="1" 
													class="span12 bt-orange">Save</button>
											</div>
										</li>
									</ul>
								</section>
							</div>
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