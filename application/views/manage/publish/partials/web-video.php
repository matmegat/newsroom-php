<?php if (!isset($providers)) $providers = Video::providers(); ?>

<section class="form-section web-video section-requires-premium">
	<h2>
		Embed Video
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_VIDEO ?>">
			<i class="icon-question-sign"></i>
		</a>
	</h2>
	<div class="header-help-block">Include a video related to the content.</div>
	<?= $ci->load->view('manage/publish/partials/requires-premium') ?>
	<div class="row-fluid">
		<div class="span12">
			<ul>
				<li id="select-video" class="clearfix">
					<div class="span4">
						<select class="show-menu-arrow span12" name="web_video_provider">
							<?php foreach ($providers as $provider): ?>
							<option value="<?= $vd->esc($provider) ?>"
								<?= value_if_test((@$vd->m_content->web_video_provider === $provider), 'selected') ?>>
								<?= $vd->esc(Video::get_provider_name($provider)) ?>
							</option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="span8">
						<input class="in-text span12" type="text" name="web_video_id" 
							id="video-id" placeholder="Enter Video URL"
							value="<?= $vd->esc(@$vd->m_content->web_video_id) ?>" />
					</div>
				</li>
			</ul>
		</div>
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
				
				$.post("manage/publish/common/resolve_video", 
					post_data, on_upload);
				
			});
			
		});
		
		</script>
	</div>
</section>