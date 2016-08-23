<section class="form-section web-files row-fluid section-requires-premium">
	<h2>
		Related Files
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_FILES ?>">
			<i class="icon-question-sign"></i>
		</a>	
	</h2>
	<div class="header-help-block">Attach other files related to the content.</div>
	<?= $ci->load->view('manage/publish/partials/requires-premium') ?>
	<ul id="content-file-upload">
		<li class="web-file">
			<div class="alert alert-warning file-upload-status">
				<strong>Patience!</strong>
				The upload process can take several minutes. <br />
				You can continue to fill out the form while you wait.
			</div>
			<div class="alert alert-error file-upload-error">
				<strong>Error!</strong>
				<span></span>
			</div>
			<div class="row-fluid">
				<div class="span10 file-upload-faker">
					<input type="hidden" class="stored-file-id" name="stored_file_id_1" 
						value="<?= $vd->esc(@$vd->m_content->stored_file_id_1) ?>" />
					<input type="hidden" class="stored-file-name" name="stored_file_name_1" 
						value="<?= $vd->esc(@$vd->m_content->stored_file_name_1) ?>" />
					<div class="fake row-fluid">
						<div class="span10 text-input">
							<input type="text" placeholder="Select File" class="in-text span12 fake-text"
								value="<?= $vd->esc(@$vd->m_content->stored_file_name_1) ?>" />
						</div>
						<div class="span2">
							<button class="btn span12 fake-button" type="button">Browse</button>
						</div>
					</div>
					<div class="real row-fluid">
						<input class="in-text span12 real-file" type="file" name="file" />
					</div>
				</div>
				<div class="span2">
					<button type="button" class="file-upload-faker-button btn span12 remove-button">
						Remove
					</button>
				</div>
			</div>
		</li>
		<li class="web-file">
			<div class="alert alert-warning file-upload-status">
				<strong>Patience!</strong>
				The upload process can take several minutes. <br />
				You can continue to fill out the form while you wait.
			</div>
			<div class="alert alert-error file-upload-error">
				<strong>Error!</strong>
				<span></span>
			</div>
			<div class="row-fluid">
				<div class="span10 file-upload-faker">
					<input type="hidden" class="stored-file-id" name="stored_file_id_2" 
						value="<?= $vd->esc(@$vd->m_content->stored_file_id_2) ?>" />
					<input type="hidden" class="stored-file-name" name="stored_file_name_2" 
						value="<?= $vd->esc(@$vd->m_content->stored_file_name_2) ?>" />
					<div class="fake row-fluid">
						<div class="span10 text-input">
							<input type="text" placeholder="Select File" class="in-text span12 fake-text"
								value="<?= $vd->esc(@$vd->m_content->stored_file_name_2) ?>" />
						</div>
						<div class="span2">
							<button class="btn span12 fake-button" type="button">Browse</button>
						</div>
					</div>
					<div class="real row-fluid">
						<input class="in-text span12 real-file" type="file" name="file" />
					</div>
				</div>
				<div class="span2">
					<button type="button" class="file-upload-faker-button btn span12 remove-button">
						Remove
					</button>
				</div>
			</div>
		</li>
		<script>

		$(function() {
			
			var cf_upload = $("#content-file-upload");
			var upload_player = $("#uploaded-audio-player");
			
			cf_upload.find(".real-file").on("change", function() {
				
				var real_file = $(this);
				var container = real_file.parents("li.web-file");
				var fake_text = container.find(".fake-text");
				var upload_status = container.find(".file-upload-status");
				var upload_error = container.find(".file-upload-error");
				var stored_file_id_input = container.find(".stored-file-id");
				var stored_file_name_input = container.find(".stored-file-name");
				var remove_button = container.find(".remove-button");
				
				fake_text.removeClass("error");
				fake_text.val(real_file.val());
				real_file.attr("disabled", true);
				remove_button.attr("disabled", true);
				
				fake_text.addClass("progress");
				upload_error.removeClass("enabled");
				upload_status.addClass("enabled");
				stored_file_id_input.val("");
				stored_file_name_input.val("");
				
				var on_upload = function(res) {
					
					remove_button.attr("disabled", false);
					upload_status.removeClass("enabled");
					fake_text.removeClass("progress");
					
					if (res && res.status) {
						
						real_file.attr("disabled", false);
						stored_file_id_input.val(res.stored_file_id);
						stored_file_name_input.val(real_file.val());
						
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
					url: "manage/publish/common/upload_file",
					progress: function(ev) {
						var fraction = ev.loaded / ev.total;
						var width_of_box = fake_text.outerWidth();
						var position = Math.round(-1000 + (width_of_box * fraction));
						fake_text.css("background-position", position + "px 0px");
					}
				});
				
			});

			cf_upload.find(".remove-button").on("click", function() {
				var container = $(this).parents("li.web-file");
				var fake_text = container.find(".fake-text");
				var stored_file_id_input = container.find(".stored-file-id");
				var stored_file_name_input = container.find(".stored-file-name");
				var upload_status = container.find(".file-upload-status");
				var upload_error = container.find(".file-upload-error");
				stored_file_name_input.val("");
				stored_file_id_input.val("");
				fake_text.removeClass("error");
				fake_text.val("");
			});
			
		});
		
		</script>
	</ul>
</section>