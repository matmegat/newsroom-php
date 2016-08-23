<?= $ci->load->view('manage/publish/partials/breadcrumbs') ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<?php if (@$vd->m_content): ?>
					<h1>Edit Event</h1>
					<?php else: ?>
					<h1>Add Event</h1>
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
			<form class="tab-content required-form has-premium" method="post" action="manage/publish/event/edit/save" id="content-form">
				
				<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<section class="form-section event-date marbot-20">
							<div class="row-fluid">
								<div class="span5">
									<h2>Event Start Date</h2>
									<div class="input-append in-text-date in-text-add-on">
										<input class="in-text required" id="date-start" name="date_start"
											data-date-format="yyyy-mm-dd" type="text" 
											data-required-name="Start Date"
											value="<?= $vd->esc(@$vd->m_content->date_start_str) ?>" />
										<span class="add-on"><i class="icon-calendar"></i></span>
									</div>
									<div class="input-append in-text-time in-text-add-on bootstrap-timepicker">
										<input class="in-text" id="time-start" name="time_start" type="text" 
											value="<?= $vd->esc(@$vd->m_content->time_start_str) ?>" />
										<span class="add-on"><i class="icon-time"></i></span>
									</div>
								</div>
								<div class="span5">
									<h2>Event End Date</h2>
									<div class="input-append in-text-date in-text-add-on">
										<input class="in-text" id="date-finish" name="date_finish"
											data-date-format="yyyy-mm-dd" type="text"
											value="<?= $vd->esc(@$vd->m_content->date_finish_str) ?>" />
										<span class="add-on"><i class="icon-calendar"></i></span>
									</div>
									<div class="input-append in-text-time in-text-add-on bootstrap-timepicker">
										<input class="in-text" id="time-finish" name="time_finish" type="text"
											value="<?= $vd->esc(@$vd->m_content->time_finish_str) ?>" />
										<span class="add-on"><i class="icon-time"></i></span>
									</div>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span12">
									<label class="checkbox-container">
										<input type="checkbox" name="is_all_day" id="is-all-day"
											<?= value_if_test(@$vd->m_content->is_all_day, 'checked') ?> />
										<span class="checkbox"></span>
										Runs All Day
									</label>
								</div>
							</div>
							<script>
	
							$(function() {
								
								var extra_fields_disabled = false;
								var nowTemp = new Date();
								var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 
									nowTemp.getDate(), 0, 0, 0, 0);
								
								var date_start = $("#date-start");
								var date_finish = $("#date-finish");
								var date_start_i = date_start.next("span");
								var date_finish_i = date_finish.next("span");
								
								var time_start = $("#time-start");
								var time_finish = $("#time-finish");
								var time_start_i = time_start.next("span");
								var time_finish_i = time_finish.next("span");
								
								var date_fields = $();
								date_fields = date_fields.add(date_start);
								date_fields = date_fields.add(date_finish);
								
								var date_icons = $();
								date_icons = date_icons.add(date_start_i);
								date_icons = date_icons.add(date_finish_i);
								
								var time_icons = $();
								time_icons = time_icons.add(time_start_i);
								time_icons = time_icons.add(time_finish_i);
								
								$(date_fields).datepicker({
									onRender: function(date) {
										if (date.valueOf() < now.valueOf())
											return 'disabled';
									}
								});
								
								$(time_start).timepicker({
									defaultTime: '09:00 AM',
									minuteStep: 5,
									showMeridian: true,
									showInputs: false,
									showSeconds: true
								});
								
								$(time_finish).timepicker({
									defaultTime: '05:00 PM',
									minuteStep: 5,
									showMeridian: true,
									showInputs: false,
									showSeconds: true
								});
									
								$(date_icons).on("click", function() {
									if (extra_fields_disabled) return;
									$(this).prev("input").datepicker("show");
								});
								
								$(time_icons).on("click", function() {
									if (extra_fields_disabled) return;
									$(this).prev("input").timepicker("showWidget");
								});
								
								var is_all_day = $("#is-all-day");
								is_all_day.on("change", function() {
									extra_fields_disabled = is_all_day.is(":checked");
									date_finish.attr("disabled", extra_fields_disabled);
									time_start.attr("disabled", extra_fields_disabled);
									time_finish.attr("disabled", extra_fields_disabled);
								}).trigger("change");
								
							});
							
							</script>
						</section>
						
						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<input class="in-text span12 required" type="text" name="title" 
										id="title" placeholder="Enter Title of Event"
										value="<?= $vd->esc(@$vd->m_content->title) ?>" 
										maxlength="<?= $ci->conf('title_max_length') ?>"
										data-required-name="Title" />
								</li>								
								<li>
									<textarea class="in-text span12 required" id="summary" name="summary"
										data-required-name="Summary" placeholder="Enter Summary of Event"
										><?= $vd->esc(@$vd->m_content->summary) ?></textarea>
									<p class="help-block" id="summary_countdown_text">
										<span id="summary_countdown"></span> Characters Left</p>
									<script>
									
									$("#summary").limit_length(<?= $ci->conf('summary_max_length') ?>, 
										$("#summary_countdown_text"), 
										$("#summary_countdown"));
									
									</script>
								</li>
								<li class="marbot-20">
									<textarea class="in-text in-content span12 required" id="content"
										data-required-name="Event Description" name="content" 
										placeholder="Event Description"><?= 
										$vd->esc(@$vd->m_content->content) 
									?></textarea>
									<script>
									
									window.init_editor($("#content"), { height: 400 });
									
									</script>
								</li>
								<li>
									<input class="in-text span12" type="text" name="address" 
										placeholder="Enter Address / Location"
										value="<?= $vd->esc(@$vd->m_content->address) ?>"  />
								</li>
							</ul>
						</section>
						
						<section class="form-section">
							<h2>Event Pricing</h2>
							<div class="row-fluid">
								<div class="span6">
									<div class="input-prepend in-text-price in-text-add-on">
										<span class="add-on">$</span>
										<input class="in-text" id="price" type="text" name="price"
											placeholder="Price" min="0" pattern="^(\d+(\.\d{2})?)?$"
											value="<?= value_if_test(@$vd->m_content->price, 
												sprintf('%.2f', @$vd->m_content->price)) ?>" />
									</div>
								</div>
								<div class="span6">
									<input class="span12 in-text" name="discount_code" 
										type="text" placeholder="Discount Code"
										value="<?= $vd->esc(@$vd->m_content->discount_code) ?>"  />
								</div>
							</div>
						</section>

						<?= $ci->load->view('manage/publish/partials/tags') ?>
						<?= $ci->load->view('manage/publish/partials/web-images') ?>						
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
									<li id="select-event-type">
										<select class="show-menu-arrow span12" name="event_type_id" 
											data-required-name="Event Type">
											<option class="selectpicker-default" title="Select Event Type" value=""
												<?= value_if_test(!@$vd->m_content->event_type_id, 'selected') ?>>None</option>
											<?php foreach ($event_types as $et): ?>
											<option value="<?= $et->id ?>"
												<?= value_if_test((@$vd->m_content->event_type_id == $et->id), 'selected') ?>>
												<?= $vd->esc($et->name) ?>
											</option>
											<?php endforeach ?>
										</select>
										<script>

										$(function() {
											
											var select = $("#select-event-type select")
											select.on_load_select();
												
											$(window).load(function() {
												select.addClass("required");
											});
											
										});
										
										</script>
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
					
					<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-timepicker.css" />					
					<script src="<?= $vd->assets_base ?>lib/bootstrap-timepicker.js"></script>
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