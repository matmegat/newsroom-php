<ul class="breadcrumb">
	<li><a href="manage/contact">iContact</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/contact/campaign">Email Campaigns</a> <span class="divider">&raquo;</span></li>
	<?php if (@$vd->campaign): ?>
	<li class="active"><?= $vd->esc($vd->campaign->name) ?></li>
	<?php else: ?>
	<li class="active">New Campaign</li>
	<?php endif ?>
</ul>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->campaign): ?>
					<h1>Edit Email Campaign</h1>
					<?php else: ?>
					<h1>Add Email Campaign</h1>
					<?php endif ?>
				</div>
				<div class="span6">
				</div>
			</div>
		</header>
	</div>
</div>

<?php if (Auth::user()->is_free_user()): ?>
<div class="below-header-feedback">
	<div class="alert alert-info">
		<strong>Attention!</strong> 
		You can use at most <?= Model_Setting::value('bundled_email_credits') ?>
		credits and 1 campaign for each premium release. 
		Subscribe to remove this restriction.
	</div>	
</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content required-form" method="post" action="manage/contact/campaign/edit/save">
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<input type="hidden" id="campaign-id" name="campaign_id" value="<?= @$vd->campaign->id ?>" />

						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12 required" type="text" 
												name="name" placeholder="Campaign Name"
												data-required-name="Campaign Name"
												value="<?= $vd->esc(@$vd->campaign->name) ?>" />
											<p class="help-block">
												Only you will see this. 
												None of your recipients will see the campaign name.
											</p>
										</div>
									</div>
								</li>
							</ul>
						</section>

						<section class="form-section select-content marbot">
							<h2>
								Select Content
								<?php if (!Auth::user()->is_free_user()): ?>
								<p class="help-inline">(Optional)</p>
								<?php endif ?>
							</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<?php $related = @$vd->from_m_content; ?>
										<?php if (!$related) $related = @$vd->campaign->m_content; ?>
										<input type="hidden" id="content-id" name="content_id" 
											<?php if (Auth::user()->is_free_user()): ?>
											class="required" data-required-name="Content" 
											<?php endif ?>
											value="<?= @$related->id ?>" />
										<ul id="select-content-switch" class="<?= value_if_test($related, 'selected') ?>">
											<li id="active-content">
												<div class="row-fluid input-append">	
													<input type="text" class="span9 in-text marbot-10" 
														disabled id="content-title"
														value="<?= $vd->esc(@$related->title) ?> (<?= 
														$vd->esc(Model_Content::short_type(@$related->type)) ?>)" />
													<button type="button" class="btn">Change</button>
												</div>
											</li>
											<li class="content-search">
												<ul id="content-results" class="marbot"></ul>
											</li>
											<li class="content-search">
												<button id="load-more" type="button" class="btn btn-small marbot">
													Load More
												</button>
												<img src="<?= $vd->assets_base ?>im/loader-line.gif" />
											</li>
										</ul>
										<script>
											
										$(function() {
											
											var select_content_switch = $("#select-content-switch");
											var active_content = $("#active-content");
											var content_results = $("#content-results");
											var content_title = $("#content-title");
											var content_id = $("#content-id");
											var change_button = active_content.find("button");
											var load_more_button = $("#load-more");
											var subject_field = $("#subject");
											
											var post_data = {};
											post_data.limit = 5;
											post_data.offset = 0;
																						
											var perform_render = function(results) {
												
												load_more_button.removeClass("loader");
												if (results.data.length == 0 || 
												    results.data.length % post_data.limit != 0)
													load_more_button.addClass("disabled");
												
												for (var idx in results.data) {
													
													var result = results.data[idx];
													var row = $.create("li");
													var button = $.create("button");
													
													var type_span = $.create("span");
													type_span.addClass("pull-right");
													type_span.text(" (" + result.type + ")");
													
													row.data("content-id", result.id);
													row.data("subject", result.subject);
													row.data("content", result.content);
													
													// we esc() on the server side before send
													row.html(result.title);
													row.append(type_span);
													button.attr("type", "button");
													button.addClass("btn btn-mini");
													button.text("Select");
													row.prepend(button);
													
													content_results.append(row);
													
												}
												
												if (!content_results.children().size())
													content_results.text("None Available");
												
											};
											
											var perform_load = function(value) {
												
												load_more_button.addClass("loader");
												$.post("manage/contact/campaign/related_content", 
													post_data, perform_render);
												post_data.offset += post_data.limit;
												
											};
											
											content_results.on("click", "li", function() {
												
												var _this = $(this);
												_this.find("button").remove();
												content_id.val(_this.data("content-id"));
												content_title.val(_this.text());
												
												// set campaign default content
												var editor = CKEDITOR.instances.content;
												var text = $.trim($(editor.getData()).text());
												if (!text) editor.setData(_this.data("content"));												
												if (!$.trim(subject_field.val()))
													subject_field.val(_this.data("subject"));
												
												select_content_switch.addClass("selected");
												content_results.empty();
												
											});
											
											change_button.on("click", function() {
												
												content_id.val("");
												select_content_switch.removeClass("selected");
												load_more_button.removeClass("disabled");
												content_results.empty();	
												post_data.offset = 0;
												perform_load();
												
											});
											
											load_more_button.on("click", perform_load);											
											perform_load();
											
										});
											
										</script>
									</div>
								</li>
							</ul>
						</section>
						
						<section class="form-section">
							<h2>Email Content</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12 required" type="text" 
												name="subject" placeholder="Subject Line" id="subject"
												data-required-name="Subject"
												value="<?= @$vd->from_m_content ? 
													$vd->esc(@$vd->from_m_content->title) :
													$vd->esc(@$vd->campaign->content) 
												?>" />
										</div>
									</div>
								</li>
								<li class="marbot-20">
									<div id="marker-buttons" class="btn-group">
										<?php foreach ($markers as $marker => $label): ?>
											<button class="btn btn-small btn-marker" 
												value="((<?= $vd->esc($marker) ?>))" type="button">
												<?= $vd->esc($label) ?>
											</button>
										<?php endforeach ?>
									</div>
									<textarea class="in-text in-content span12 required" id="content"
										data-required-name="Content Body" name="content" 
										data-link-default-url="((tracking-link))"
										placeholder="Email Body"><?= 
										@$vd->from_m_content ? 
											$vd->esc(@$vd->default_content) :
											$vd->esc(@$vd->campaign->content) 
									?></textarea>
									<script>
									
									window.init_editor($("#content"), { height: 400 });
									
									$(function() { 
										
										$("#marker-buttons .btn-marker").on("click", function() {
											var editor = CKEDITOR.instances["content"];
											var create = CKEDITOR.plugins.placeholder.createPlaceholder;
											var text = $(this).val();
											create(editor, undefined, text);
										});
										
									});
									
									</script>
								</li>
							</ul>
						</section>
						
						<section class="form-section">
							<h2>Sender Details</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 required" type="text" 
												name="sender_name" placeholder="Sender Name"
												<?php if ($vd->campaign): ?>
												value="<?= $vd->esc(@$vd->campaign->sender_name) ?>" 
												<?php else: ?>
												value="<?= $vd->esc($ci->newsroom->company_name) ?>" 
												<?php endif ?>
												data-required-name="Sender Name" />
												<p class="help-block">
													Send the email from this name.
												</p>
										</div>
										<div class="span6">
											<input class="in-text span12 required" type="text" 
												name="sender_email" placeholder="Sender Email"
												<?php if ($vd->campaign): ?>
												value="<?= $vd->esc(@$vd->campaign->sender_email) ?>" 
												<?php elseif (@$vd->company_profile->email): ?>
												value="<?= $vd->esc($vd->company_profile->email) ?>" 
												<?php else: ?>
												value="<?= $vd->esc(Auth::user()->email) ?>" 
												<?php endif ?>
												data-required-name="Sender Email" />
												<p class="help-block">
													Send the email from this address.
												</p>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
						<?= $ci->load->view('manage/contact/partials/contact_lists', null, true) ?>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div id="locked_aside">
							<div class="aside-properties marbot-20">
								<section class="ap-block ap-status">
									<h3>
										Status: 
										<?php if (!@$vd->campaign): ?>
										<span>Not Saved</span>
										<?php elseif ($vd->campaign->is_sent): ?>
										<span>Sent</span>
										<?php elseif ($vd->campaign->is_draft): ?>
										<span>Saved (Draft)</span>
										<?php else: ?>
										<span>Scheduled</span>
										<?php endif ?>
									</h3>
								</section>
								<section class="ap-block">
									<ul>
										<li>
											<input class="span12 in-text datepicker" id="send-date" type="text" 
												data-date-format="yyyy-mm-dd hh:ii" name="date_send" 
												value="<?= @$vd->campaign->date_send_str ?>"
												placeholder="Send Date" />
											<script>
											
											$(function() {
												
												var nowTemp = new Date();
												var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 
													nowTemp.getDate(), 0, 0, 0, 0);
												
												var send_date = $("#send-date")
												
												send_date.datetimepicker({
													startDate: now,
													autoclose: true,
													todayBtn: true,
													minView: 1,
												});
												
												send_date.on("changeDate", function(ev) {
													ev.date.setMinutes(0);
												});
												
											});
											
											</script>											
											<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-datetimepicker.css" />	
											<script src="<?= $vd->assets_base ?>lib/bootstrap-datetimepicker.js"></script>
										</li>
										<li>
											<div class="row-fluid">
												<?php if (@$vd->campaign->is_sent): ?>
												<div class="span6">
													<button type="submit" name="publish" value="1" 
														class="span11 bt-silver">Save</button>
												</div>
												<div class="span6">
													<button type="submit" name="resend" value="1" 
														class="span12 bt-orange pull-right">Resend</button>
												</div>
												<?php else: ?>
												<div class="span6">
													<button type="submit" name="is_draft" value="1" 
														class="span12 bt-silver">Save Draft</button>
												</div>
												<div class="span6">
													<button type="submit" name="publish" value="1" 
														class="span11 bt-orange pull-right">Send</button>
												</div>
												<?php endif ?>
											</div>
										</li>
									</ul>
								</section>
							</div>
							<div class="alert alert-info">
								<strong>Attention!</strong>
								You should test the email with the form 
								shown below	to ensure it is correct.
							</div>
							<div class="aside-properties">
								<section class="ap-block ap-test-email">
									<h3 class="marbot">Test Email</h3>
									<ul>
										<li>
											<input type="text" placeholder="First Name" 
												name="test_first_name" class="span12 in-text" />
										</li>
										<li>
											<input type="text" placeholder="Last Name" 
												name="test_last_name" class="span12 in-text" />
										</li>
										<li>
											<input type="email" placeholder="Email Address" 
												name="test_email" class="span12 in-text">
										</li>
										<li>
											<div class="row-fluid">
												<button type="submit" name="test" 
													value="1" class="btn span7 pull-right">
													Save and Send
												</button>
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