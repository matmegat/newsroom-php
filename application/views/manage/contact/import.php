<ul class="breadcrumb no-print">
	<li><a href="manage/contact">iContact</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/contact/contact">Contacts</a> <span class="divider">&raquo;</span></li>
	<li class="active">Import</li>
</ul>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Import Contacts</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="<?= $vd->assets_base ?>other/template.csv" class="bt-publish bt-silver">Download Template</a>
						<a href="<?= $vd->assets_base ?>other/example.csv" class="bt-publish bt-silver">Example</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content" method="post" id="import-form" action="manage/contact/import/save">				
				<div class="row-fluid">
					
					<div class="span8 information-panel">
						
						<section class="form-section company-logo">
							<h2>Select CSV File</h2>
							<input type="hidden" class="required" id="stored-file-id"
								data-required-name="CSV File" name="stored_file_id" />
							<input type="hidden" id="filename" name="filename" />
							<div class="row-fluid" id="csv-please-wait">
								<div class="span10">
									<div class="alert alert-warning">
										Please wait while the file is uploaded.
									</div>
								</div>
							</div>
							<div id="csv-upload">
								<div class="row-fluid">
									<div class="span12 file-upload-faker">
										<div class="fake row-fluid">
											<div class="span10 text-input">
												<input type="text" placeholder="Select File" class="in-text span12 fake-text" />
											</div>
											<div class="span2">
												<button class="btn span12 fake-button" type="button">Browse</button>
											</div>
										</div>
										<div class="real row-fluid">
											<input class="in-text span12 real-file" type="file" name="csv" />
										</div>
									</div>
								</div>
							</div>
							<script>

							$(function() {
								
								var import_button = $("#import-button");
								var csv_upload = $("#csv-upload");
								var preview = $("#preview");
								
								preview.hide();
								
								csv_upload.find(".real-file").on("change", function() {
									
									var real_file = $(this);
									var fake_text = csv_upload.find(".fake-text");
									var please_wait = $("#csv-please-wait");
									
									fake_text.removeClass("error");
									fake_text.addClass("loader");
									fake_text.val(real_file.val());
									real_file.attr("disabled", true);
									please_wait.slideDown();
									please_wait.addClass("enabled");
									
									var id_input = $("input#stored-file-id");
									var filename_input = $("input#filename");
									
									var on_upload = function(res) {
										
										please_wait.hide();
										please_wait.removeClass("enabled");
										
										if (res.stored_file_id)
										{
											real_file.attr("disabled", false);
											fake_text.removeClass("loader");
											id_input.val(res.stored_file_id);
											filename_input.val(res.filename);
											import_button.attr("disabled", false);
											import_button.addClass("bt-orange");
											import_button.removeClass("btn");
											
											preview.empty();
											preview.html(res.preview);
											preview.show();
											
											window.location.hash = "preview";
										}
										else
										{
											fake_text.addClass("error");
											real_file.attr("disabled", false);
											fake_text.removeClass("loader");
											import_button.attr("disabled", true);
											import_button.removeClass("bt-orange");
											import_button.addClass("btn");
										}
										
									};
									
									setTimeout(function() {
										real_file.ajax_upload({
											callback: on_upload,
											url: "manage/contact/import/store_csv"
										})}, 1000);
									
								});
								
							});
							
							</script>
						</section>
						
						<?= $ci->load->view('manage/contact/partials/tags', null, true) ?>
						<?= $ci->load->view('manage/contact/partials/contact_lists', null, true) ?>
						
					</div>
					
					<aside class="span4 aside aside-fluid">
						<div class="alert alert-info">
							The uploaded CSV file must be in the correct format. 
							Each line should have an <strong>email address</strong> and can 
							have optional <strong>first name</strong>, <strong>last name</strong>,
							<strong>company name</strong> and <strong>title</strong> fields.
						</div>
						<button class="btn span6 pull-right" 
							type="submit" id="import-button" disabled>
							Process File
						</button>
					</aside>
					
				</div>	
				
				<a name="preview"></a>
				<div id="preview" class="form-section marbot-20"></div>
				
			</form>			
			<script>
			
			$(function() {
			
				var import_button = $("#import-button");
				
				var update_progress = function() {
					$.get("manage/contact/import/progress", function(res) {
						import_button.text(res + " Processed");
						setTimeout(update_progress, 250);
					});
				};
				
				$("#import-form").on("submit", function() {
					import_button.prop("disabled", true);
					import_button.removeClass("bt-orange");
					import_button.removeClass("span6");
					import_button.addClass("span8");
					import_button.addClass("btn");
					import_button.text("0 Processed");
					setTimeout(update_progress, 250);					
				});
				
			});			
			
			</script>
		</div>
	</div>
</div>