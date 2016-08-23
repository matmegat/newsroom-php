<ul class="breadcrumb">
	<li><a href="manage/contact">iContact</a> <span class="divider">&raquo;</span></li>
	<?php if (@$vd->from_m_contact_list): ?>
	<li><a href="manage/contact/list">Lists</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/contact/list/edit/<?= $vd->from_m_contact_list->id ?>">
		<?= $vd->esc($vd->from_m_contact_list->name) ?></a> 
		<span class="divider">&raquo;</span></li>
	<?php else: ?>
	<li><a href="manage/contact/contact">Contacts</a> <span class="divider">&raquo;</span></li>
	<?php endif ?>
	<?php if (@$vd->contact): ?>
	<li class="active">Edit Contact</li>
	<?php else: ?>
	<li class="active">Add Contact</li>
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
			<form class="tab-content required-form" method="post" action="manage/contact/contact/edit/save">
				<div class="row-fluid">
					<div class="span8 information-panel">
						
						<input type="hidden" id="contact-id" name="contact_id" value="<?= @$vd->contact->id ?>" />

						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 required" type="text" 
												name="first_name" placeholder="First Name"
												data-required-name="First Name"
												value="<?= $vd->esc(@$vd->contact->first_name) ?>" />
										</div>
										<div class="span6">
											<input class="in-text span12 required" type="text" 
												name="last_name" placeholder="Last Name"
												data-required-name="Last Name"
												value="<?= $vd->esc(@$vd->contact->last_name) ?>" />
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid" id="email-error">
										<div class="span12">
											<div class="alert alert-error">
												Contact already exists with this email address.
											</div>
										</div>
									</div>
									<div class="row-fluid">
										<div class="span12">
											<input class="required" type="hidden" name="email" 
												id="email" data-required-name="Email Address"
												value="<?= $vd->esc(@$vd->contact->email) ?>" />
											<input class="in-text has-loader span12" type="email" 
												name="email-visible" id="email-visible" 
												placeholder="Email Address"
												value="<?= $vd->esc(@$vd->contact->email) ?>" />
										</div>
									</div>
									<script>
									
									$(function() {
										
										var email = $("#email");
										var email_visible = $("#email-visible");
										var email_error = $("#email-error");
										var contact_id = $("#contact-id");
										
										email_visible.on("change", function() {
											
											email_to_check = email_visible.val();
											email_visible.addClass("loader");
											email.addClass("loader");
											email_visible.removeClass("error");
											email_error.slideUp();
											email.val("");
											
											var data = {};
											data.contact_id = contact_id.val();
											data.email = email_to_check;
											
											$.post("manage/contact/contact/email_check", data, function(res) {
												
												email_visible.removeClass("loader");
												email.removeClass("loader");
												if (res.available) return email.val(email_to_check);
												email_visible.addClass("error");
												email_error.slideDown();
												
											});
											
										});
										
									});
									
									</script>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="company_name" placeholder="Company Name"
												value="<?= $vd->esc(@$vd->contact->company_name) ?>" />
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="title" placeholder="Title"
												value="<?= $vd->esc(@$vd->contact->title) ?>" />
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12" type="text" 
												name="twitter" placeholder="Twitter"
												value="<?= $vd->esc(@$vd->contact->twitter) ?>" />
										</div>
									</div>
								</li>
								<li>
									<textarea class="in-text span12" id="notes" name="notes"
										placeholder="Notes"><?= $vd->esc(@$vd->contact->notes) 
										?></textarea>
									<p class="help-block" id="notes_countdown_text">
										<span id="notes_countdown">250</span> Characters Left</p>
									<script>
									
									$("#notes").limit_length(250, 
										$("#notes_countdown_text"), 
										$("#notes_countdown"));
									
									</script>
								</li>
							</ul>
						</section>
						
						<?= $ci->load->view('manage/contact/partials/contact_lists', null, true) ?>
						<?= $ci->load->view('manage/contact/partials/tags', null, true) ?>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div class="aside-properties padding-top" id="locked_aside">
							<ul>
								<li id="select-country" class="select-right">
									<select class="show-menu-arrow span12" name="country_id">
										<option class="selectpicker-default" title="Select Country" value=""
											<?= value_if_test(!@$vd->contact->country_id, 'selected') ?>>None</option>
										<?php foreach ($common_countries as $country): ?>
										<option value="<?= $country->id ?>"
											<?= value_if_test((@$vd->contact->country_id == $country->id), 'selected') ?>>
											<?= $vd->esc($country->name) ?>
										</option>
										<?php endforeach ?>
										<option data-divider="true"></option>
										<?php foreach ($countries as $country): ?>
										<option value="<?= $country->id ?>"
											<?= value_if_test((@$vd->contact->country_id == $country->id && 
												!$country->is_common), 'selected') ?>>
											<?= $vd->esc($country->name) ?>
										</option>
										<?php endforeach ?>
									</select>
									<script>

									$(function() {
										
										$("#select-country select")
											.on_load_select({ size: 10 });
										
									});
									
									</script>
								</li>
								<li class="select-right select-beat">
									<select class="show-menu-arrow span12 category" name="beat_1_id">
										<option class="selectpicker-default" title="Select Beat" value=""
											<?= value_if_test(!@$vd->contact->beat_1_id, 'selected') ?>>None</option>
										<?php foreach ($beats as $group): ?>
										<optgroup label="<?= $vd->esc($group->name) ?>">
											<?php foreach ($group->beats as $beat): ?>
											<option value="<?= $beat->id ?>"
												<?= value_if_test((@$vd->contact->beat_1_id == $beat->id), 'selected') ?>>
												<?= $vd->esc($beat->name) ?>
											</option>
											<?php endforeach ?>
										</optgroup>
										<?php endforeach ?>
									</select>									
								</li>	
								<li class="select-right select-beat">
									<select class="show-menu-arrow span12 category" name="beat_2_id">
										<option class="selectpicker-default" title="Select Beat" value=""
											<?= value_if_test(!@$vd->contact->beat_2_id, 'selected') ?>>None</option>
										<?php foreach ($beats as $group): ?>
										<optgroup label="<?= $vd->esc($group->name) ?>">
											<?php foreach ($group->beats as $beat): ?>
											<option value="<?= $beat->id ?>"
												<?= value_if_test((@$vd->contact->beat_2_id == $beat->id), 'selected') ?>>
												<?= $vd->esc($beat->name) ?>
											</option>
											<?php endforeach ?>
										</optgroup>
										<?php endforeach ?>
									</select>
								</li>
								<li class="select-right select-beat">
									<select class="show-menu-arrow span12 category" name="beat_3_id">
										<option class="selectpicker-default" title="Select Beat" value=""
											<?= value_if_test(!@$vd->contact->beat_3_id, 'selected') ?>>None</option>
										<?php foreach ($beats as $group): ?>
										<optgroup label="<?= $vd->esc($group->name) ?>">
											<?php foreach ($group->beats as $beat): ?>
											<option value="<?= $beat->id ?>"
												<?= value_if_test((@$vd->contact->beat_3_id == $beat->id), 'selected') ?>>
												<?= $vd->esc($beat->name) ?>
											</option>
											<?php endforeach ?>
										</optgroup>
										<?php endforeach ?>
									</select>									
								</li>	
								<li>
									<div class="row-fluid">
										<div class="span4 offset8">
											<button type="submit" name="publish" value="1" 
												class="span12 bt-orange">Save</button>
										</div>
									</div>
								</li>
							</ul>
							<script>

							$(function() {
								
								$("li.select-beat select")
									.on_load_select({ size: 10 });
								
							});
							
							</script>
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