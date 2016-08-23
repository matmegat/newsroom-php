<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Company Profile</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">		
		
		<div class="content">
			<form class="tab-content required-form" method="post" action="manage/newsroom/company/save">
				<div class="row-fluid">
					<div class="span8 information-panel">

						<section class="form-section basic-information">
							<h2>Basic Information</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<input class="in-text span12 required" type="text" 
												name="company_name" placeholder="Company Name"
												data-required-name="Company Name"
												value="<?= $vd->esc(@$vd->name) ?>" />
										</div>
									</div>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 required" name="website" 
												data-required-name="Website"
												placeholder="Company Website" type="url"
												value="<?= $vd->esc(@$vd->profile->website) ?>" />
										</div>
										<div class="span6">
											<input class="in-text span12 required" name="email" 
												data-required-name="Email Address"
												placeholder="Company Email" type="email"
												value="<?= $vd->esc(@$vd->profile->email) ?>" />
										</div>
									</div>
								</li>
							</ul>
						</section>

						<section class="form-section company-address">
							<h2>Company Address</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span8">
											<input class="in-text span12" name="address_street" 
											placeholder="Street Address" type="text"
											value="<?= $vd->esc(@$vd->profile->address_street) ?>" />
										</div>
										<div class="span4">
											<input class="in-text span12"  name="address_apt_suite"
												type="text" placeholder="Apt / Suite" 
												value="<?= $vd->esc(@$vd->profile->address_apt_suite) ?>" />
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span4">
											<input class="in-text span12" type="text" 
												name="address_city" placeholder="City"
												value="<?= $vd->esc(@$vd->profile->address_city) ?>" />
										</div>
										<div class="span4">
											<input class="in-text span12" type="text" 
												name="address_state" placeholder="State / Region"
												value="<?= $vd->esc(@$vd->profile->address_state) ?>" />
										</div>
										<div class="span4">
											<input class="in-text span12" type="text" 
												name="address_zip" placeholder="Zip Code"
												value="<?= $vd->esc(@$vd->profile->address_zip) ?>" />
										</div>
									</div>
								</li>
								<li id="select-country">
									<div class="row-fluid">
										<div class="span6">
											<select class="show-menu-arrow span12" name="address_country_id">
												<option class="selectpicker-default" title="Select Country" value=""
													<?= value_if_test(!@$vd->profile->address_country_id, 'selected') ?>>None</option>
												<?php foreach ($common_countries as $country): ?>
												<option value="<?= $country->id ?>"
													<?= value_if_test((@$vd->profile->address_country_id == $country->id), 'selected') ?>>
													<?= $vd->esc($country->name) ?>
												</option>
												<?php endforeach ?>
												<option data-divider="true"></option>
												<?php foreach ($countries as $country): ?>
												<option value="<?= $country->id ?>"
													<?= value_if_test((@$vd->profile->address_country_id == $country->id && 
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
										</div>
										<div class="span6">
											<input class="in-text span12" type="text" 
												name="phone" placeholder="Phone Number"
												value="<?= $vd->esc(@$vd->profile->phone) ?>" />
										</div>
									</div>
								</li>
							</ul>
						</section>
						
						<section class="form-section company-description">
							<h2>Company Summary</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12">
											<textarea class="in-text span12 required-callback" name="summary" id="summary"
												placeholder="Short Company Description" data-required-name="Summary"
												data-required-callback="summary-min-words"><?= 
												$vd->esc(@$vd->profile->summary) 
											?></textarea>
											<p class="help-block" id="summary_countdown_text">
												<span id="summary_countdown">250</span> Characters Left. 
												This will be visible on the newsroom sidebar.</p>
											<script>
									
											$(function() {
												
												$("#summary").limit_length(250, 
													$("#summary_countdown_text"), 
													$("#summary_countdown"));
												
												required_js.add_callback("summary-min-words", function(value) {
													var response = { valid: false, text: "must have at least 10 words" };
													response.valid = /([^\s]*[a-z][^\s]*(\s+|$)){10,}/i.test(value);
													return response;
												});
												
											});											
											
											</script>
										</div>
									</div>
								</li>
							</ul>
						</section>

						<section class="form-section company-description">
							<h2>Company Description</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span12 marbot-20 cke-container">
											<textarea class="in-text span12" name="description" 
												placeholder="Company Description" id="description"><?= 
												$vd->esc(@$vd->profile->description) 
											?></textarea>
											<p class="help-block">
												Add a description of your company.
											</p>
											<script>
									
											window.init_editor($("#description"), { height: 400 });
											
											</script>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
						<section class="form-section social-services">
							<h2>Social Accounts</h2>
							<ul>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 facebook-profile-id" type="text" 
												name="soc_facebook" placeholder="Facebook" 
												value="<?= $vd->esc(@$vd->profile->soc_facebook) ?>" />
											<p class="help-block">Facebook Username or Page</p>
										</div>
										<div class="span6">
											<input class="in-text span12 twitter-profile-id" type="text" 
												name="soc_twitter" placeholder="Twitter" 
												value="<?= $vd->esc(@$vd->profile->soc_twitter) ?>" />
											<p class="help-block">Twitter Username</p>
										</div>
									</div>
								</li>
								<li>
									<div class="row-fluid">
										<div class="span6">
											<input class="in-text span12 gplus-profile-id" type="text" 
												name="soc_gplus" placeholder="Google Plus" 
												value="<?= $vd->esc(@$vd->profile->soc_gplus) ?>" />
											<p class="help-block">Google Plus ID</p>
										</div>
										<div class="span6">
											<input class="in-text span12 youtube-profile-id" type="text" 
												name="soc_youtube" placeholder="YouTube Username" 
												value="<?= $vd->esc(@$vd->profile->soc_youtube) ?>" />
											<p class="help-block">YouTube Username</p>
										</div>
									</div>
								</li>
							</ul>
						</section>
						
					</div>

					<aside class="span4 aside aside-fluid">
						<div class="aside-properties padding-top" id="locked_aside">
							<ul>
								<li id="select-type">
									<select class="show-menu-arrow span12" name="type">
										<option class="selectpicker-default" title="Select Type" value=""
											<?= value_if_test(!@$vd->profile->type, 'selected') ?>>None</option>
										<option value="private"
											<?= value_if_test(@$vd->profile->type == 'private', 'selected') ?>>
											Privately Held</option>
										<option value="public"
											<?= value_if_test(@$vd->profile->type == 'public', 'selected') ?>>
											Publicly Held</option>
									</select>
									<script>

									$(function() {
										
										$("#select-type select").on_load_select();
										
									});
									
									</script>
								</li>
								<li id="select-industry" class="select-right">
									<select class="show-menu-arrow span12 category" name="beat_id">
										<option class="selectpicker-default" title="Select Industry" value=""
											<?= value_if_test(!@$vd->profile->beat_id, 'selected') ?>>None</option>
										<?php foreach ($vd->beats as $group): ?>
										<optgroup label="<?= $vd->esc($group->name) ?>">
											<?php foreach ($group->beats as $beat): ?>
											<option value="<?= $beat->id ?>"
												<?= value_if_test((@$vd->profile->beat_id == $beat->id), 'selected') ?>>
												<?= $vd->esc($beat->name) ?>
											</option>
											<?php endforeach ?>
										</optgroup>
										<?php endforeach ?>
									</select>
									<script>

									$(function() {
										
										$("#select-industry select")
											.on_load_select({ size: 10 });
										
									});
									
									</script>
								</li>
								<li id="select-year">
									<select class="show-menu-arrow span12" name="year">
										<option class="selectpicker-default" title="Year Founded" value=""
											<?= value_if_test(!@$vd->profile->year, 'selected') ?>>None</option>
										<?php for ($i = (int) date('Y'); $i >= 1800; $i--): ?>
										<option value="<?= $i ?>"
											<?= value_if_test((@$vd->profile->year == $i), 'selected') ?>>
											<?= $i ?>
										</option>
										<?php endfor ?>
									</select>
									<script>

									$(function() {
										
										$("#select-year select")
											.on_load_select({ size: 10 });
										
									});
									
									</script>
								</li>
								<li id="select-timezone">
									<select class="span12 show-menu-arrow" name="timezone">
										<?php $timezone_selected = false; ?>
										<option class="selectpicker-default" title="Select Timezone" value=""
											<?= value_if_test(!$ci->newsroom->timezone, 'selected') ?>>Default</option>
										<optgroup label="Common Timezones">
											<?php foreach ($vd->common_timezones as $value => $timezone): ?>
											<option value="<?= $vd->esc($timezone) ?>"												
												<?= value_if_test(!$timezone_selected && 
													$ci->newsroom->timezone == $timezone, 'selected') ?>>
												<?php if ($ci->newsroom->timezone == $timezone) 
													$timezone_selected = true; ?>
												<?= $vd->esc($value); ?>
											</option>
											<?php endforeach ?>	
										</optgroup>
										<optgroup label="Local Timezones">
											<?php foreach ($vd->timezones as $timezone): ?>
											<option value="<?= $vd->esc($timezone) ?>"
												<?= value_if_test(!$timezone_selected && 
													$ci->newsroom->timezone == $timezone, 'selected') ?>>
												<?php if ($ci->newsroom->timezone == $timezone) 
													$timezone_selected = true; ?>
												<?php 
												
												$timezone = str_replace('_', ' ', $timezone);
												$timezone = str_replace('/', ' - ', $timezone);
												echo $vd->esc($timezone);
												
												?>
											</option>
											<?php endforeach ?>	
										</optgroup>
									</select>
									<p class="help-block marbot-10">Statistics data will use UTC.</p>
									<script>

									$(function() {
										
										$("#select-timezone select").on_load_select({ size: 10 });
										
									});
									
									</script>
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