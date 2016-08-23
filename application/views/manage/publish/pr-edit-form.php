<input type="hidden" name="id" value="<?= @$vd->m_content->id ?>" />
				
<div class="row-fluid">
	<div class="span8 information-panel">
		
		<section class="form-section marbot-20">
			<h2>Basic or Premium</h2>
			<?php if (!@$vd->m_content || !$vd->m_content->is_consume_locked()): ?>
			<div class="alert alert-warning hidden" id="no-credits-warning">
				<strong>Warning!</strong> You do not currently have enough credits to 
				publish the press release. You can still save the press release as a draft 
				or schedule it for a later date but you will need to have credit available 
				24 hours before the desired publish date. 
			</div>
			<?php endif ?>
			<ul>
				<li class="radio-container-box marbot">
					<label class="radio-container louder">
						<input type="radio" name="is_premium" value="0" class="is-premium-radio"  
							<?= value_if_test(@$vd->m_content && @$vd->m_content->is_consume_locked(), 'disabled') ?>
							<?= value_if_test(!@$vd->m_content->is_premium, 'checked') ?> />
						<span class="radio"></span>
						Basic Press Release 
						<?php if (!@$vd->m_content || !$vd->m_content->is_consume_locked()): ?>
						<?php if (Auth::user()->pr_credits_basic()): ?>
						(<?= Auth::user()->pr_credits_basic() ?> Credits Remaining)
						<?php else: ?>
						(<a href="manage/upgrade">Get Credits</a>)
						<?php endif ?>
						<?php endif ?>
					</label>
					<p class="muted">
						A basic release with limited features and distribution. 
					</p>
				</li>
				<li class="radio-container-box">
					<label class="radio-container louder">
						<input type="radio" name="is_premium" class="is-premium-radio" id="is-premium" value="1" 
							<?= value_if_test(@$vd->m_content && @$vd->m_content->is_consume_locked(), 'disabled') ?>
							<?= value_if_test(@$vd->m_content->is_premium, 'checked') ?> />
						<span class="radio"></span>
						Premium Press Release
						<?php if (!@$vd->m_content || !$vd->m_content->is_consume_locked()): ?>
						<?php if (Auth::user()->pr_credits_premium()): ?>
						(<?= Auth::user()->pr_credits_premium() ?> Credits Remaining)
						<?php else: ?>
						(<a href="manage/upgrade">Get Credits</a>)
						<?php endif ?>
						<?php endif ?>
					</label>
					<p class="muted">
						A premium release with support for additional features such as additional 
						images, external links, embedded video and attached files. Premium releases
						are distributed to a wider range of news organizations.
					</p>
				</li>
			</ul>
			<script>
			
			$(function() {
				
				var content_form = $("#content-form");						
				var switch_pr_type = function(is_premium) {
					content_form.toggleClass("has-premium", is_premium);
					content_form.toggleClass("has-basic", !is_premium);
				};

				var pr_credits_basic = <?= json_encode(Auth::user()->pr_credits_basic()) ?>;
				var pr_credits_premium = <?= json_encode(Auth::user()->pr_credits_premium()) ?>;
				
				var is_premium_radio = $("#is-premium");
				var is_premium_radios = $(".is-premium-radio");
				var no_credits_warning = $("#no-credits-warning");
				var section_premium = $(".section-requires-premium");
				
				var prevent_default = function(ev) {
					$(document.activeElement).blur();
					ev.preventDefault();
					return false;
				};
				
				var disable_features = function() {
					section_premium.find("input")
						.on("mousedown.feature-lock", prevent_default)
						.on("click.feature-lock", prevent_default)
						.on("focus.feature-lock", prevent_default)
						.prop("readonly", true);
					section_premium.find("select")
						.on("mousedown.feature-lock", prevent_default)
						.on("click.feature-lock", prevent_default)
						.on("focus.feature-lock", prevent_default)
						.prop("readonly", true);
					section_premium.find("button")
						.on("mousedown.feature-lock", prevent_default)
						.on("click.feature-lock", prevent_default)
						.on("focus.feature-lock", prevent_default)
						.prop("readonly", true);
				};
				
				var enable_features = function() {
					section_premium.find("input")
						.off("mousedown.feature-lock")
						.off("click.feature-lock")
						.off("focus.feature-lock")
						.prop("readonly", false);
					section_premium.find("select")
						.off("mousedown.feature-lock")
						.off("click.feature-lock")
						.off("focus.feature-lock")
						.prop("readonly", false);
					section_premium.find("button")
						.off("mousedown.feature-lock")
						.off("click.feature-lock")
						.off("focus.feature-lock")
						.prop("readonly", false);
				};
				
				var handle_premium_mod = function() {
					var is_premium = is_premium_radio.is(":checked");
					var available = is_premium ? pr_credits_premium : pr_credits_basic;
					no_credits_warning.toggle(available <= 0);
					switch_pr_type(is_premium);
					if (is_premium) enable_features();
					else disable_features();
				};
				
				is_premium_radios.on("change", handle_premium_mod);
				handle_premium_mod();
				
				content_form.on("click", "div.requires-premium a", function() {
					is_premium_radio.prop("checked", true).trigger("change");
				});
				
			});
			
			</script>
		</section>
				
		<section class="form-section basic-information">
			<h2>
				Basic Information
				<a data-toggle="tooltip" class="tl" href="#" 
					title="<?= Help::PR_BASIC ?>">
					<i class="icon-question-sign"></i>
				</a>
			</h2>
			<ul>	
				<li>
					<input class="in-text span12 required required-callback" type="text" name="title" 
						id="title" placeholder="Enter Title of Press Release" 
						maxlength="<?= $ci->conf('title_max_length') ?>"
						value="<?= $vd->esc(@$vd->m_content->title) ?>" data-required-name="Title"
						data-required-callback="title-min-words title-max-chars" />
					<script>
					
					$(function() {
						
						required_js.add_callback("title-min-words", function(value) {
							var response = { valid: false, text: "must have at least 4 words" };
							response.valid = /([^\s]*[a-z][^\s]*(\s+|$)){4,}/i.test(value);
							return response;
						});
						
					});
					
					</script>
				</li>
				<li>
					<textarea class="in-text span12 required required-callback" id="summary" name="summary"
						data-required-name="Summary" placeholder="Enter Summary of Press Release"
						data-required-callback="summary-min-words" 
						><?= $vd->esc(@$vd->m_content->summary) ?></textarea>
					<p class="help-block ta-right" id="summary_countdown_text">
						<span id="summary_countdown"></span> Characters Left</p>
					<script>
					
					$(function() {
						
						$("#summary").limit_length(<?= $ci->conf('summary_max_length') ?>, 
							$("#summary_countdown_text"), 
							$("#summary_countdown"));
						
						required_js.add_callback("summary-min-words", function(value) {
							var response = { valid: false, text: "must have at least 10 words" };
							response.valid = /([^\s]*[a-z][^\s]*(\s+|$)){10,}/i.test(value);
							return response;
						});
						
					});
					
					</script>
				</li>
				<li class="marbot-20 cke-container" id="content-container">
					<textarea class="in-text in-content span12 required required-callback" id="content"
						data-required-name="Content Body" name="content" 
						data-required-callback="content-min-words content-max-chars 
							content-max-links-free content-max-links-premium"
						placeholder="Press Release Body"><?= 
						$vd->esc(@$vd->m_content->content) 
					?></textarea>									
					<script>
					
					$(function() {
						
						var min_word_count = <?= $ci->conf('press_release_min_words') ?>;
						
						var is_premium_radio = $("#is-premium");
						var convert_to_text_format = function(value) {
							value = value.replace(/<[^>]*>/g, " ");
							value = value.replace(/&nbsp;/g, " ");
							return value;
						};
						
						// the word regex used for counting words
						var word_count = /([a-z0-9]+([^\s]*[\s]+[^a-z0-9]*|$))/ig;
						
						required_js.add_callback("content-min-words", function(value) {
							value = convert_to_text_format(value);
							var response = { valid: false, text: "must have at least <?= 
								$ci->conf('press_release_min_words') ?> words" };
							var match = value.match(word_count);
							var count = match ? match.length : 0;
							response.valid = count >= min_word_count;
							return response;
						});
						
						required_js.add_callback("content-max-chars", function(value) {
							value = convert_to_text_format(value);
							var response = { valid: false, text: "must not exceed <?= 
								$ci->conf('press_release_max_length') ?> characters" };
							response.valid = value.length <= <?= 
								$ci->conf('press_release_max_length') ?>;
							return response;
						});
						
						required_js.add_callback("content-max-links-free", function(value) {
							if (is_premium_radio.is(":checked")) return { valid: true };
							var response = { valid: false, text: "can have at most <?= 
								$ci->conf('press_release_links_basic') ?> external links" };
							var a_links = value.match(/(<a[^>]*>)/gi);
							response.valid = !a_links || a_links.length <= <?= 
								$ci->conf('press_release_links_basic') ?>;
							return response;
						});
						
						required_js.add_callback("content-max-links-premium", function(value) {
							if (!is_premium_radio.is(":checked")) return { valid: true };
							var response = { valid: false, text: "can have at most <?= 
								$ci->conf('press_release_links_premium') ?> external links" };
							var a_links = value.match(/(<a[^>]*>)/gi);
							response.valid = !a_links || a_links.length <= <?= 
								$ci->conf('press_release_links_premium') ?>;
							return response;
						});
						
						window.init_editor($("#content"), { height: 400 }, function() {
						
							var _this = this;		
							var content_word_text = $("#content_word_text");
							var content_word_count = $("#content_word_count");
							var show_word_count = function() {
								var text = convert_to_text_format(_this.getData());
								var match = text.match(word_count);
								var count = match ? match.length : 0;
								content_word_text.toggleClass("status-true", count >= min_word_count);
								content_word_count.text(count);
							};
							
							_this.on("contentDom", function() {
								_this.document.on("keyup", function(ev) {
									window.rate_limit(show_word_count, min_word_count);
								});
							});
							
							_this.on("instanceReady", function() {
								var link_button = $("#content-container .cke_button__link");
								link_button_handler = link_button[0].onclick;
								link_button.removeAttr("onclick");
								link_button.on("click", function(ev) {
									// max number of links is 3 for premium, 0 otherwise
									var is_premium = is_premium_radio.is(":checked");
									var max = is_premium ? <?= 
										$ci->conf('press_release_links_premium') ?> : <?= 
										$ci->conf('press_release_links_basic') ?>;
									var value = _this.getData();
									var a_links = value.match(/(<a[^>]*>)/gi);
									var count = a_links ? a_links.length : 0;
									if (count < max) return link_button_handler.call(this);
									// show an alert about reaching limit
									bootbox.alert("You are limited to <strong>" + 
										max + "<\/strong> embedded links with a " + 
										(is_premium ? "premium" : "basic") +
										" press release.");
								});
							});
							
							show_word_count();
							
						});
						
					});
					
					</script>
					<p class="help-block ta-right" id="content_word_text">
						<span id="content_word_count">0</span> Words (<?= 
							$ci->conf('press_release_min_words') ?> Required)</p>
				</li>
			</ul>
		</section>

		<?= $ci->load->view('manage/publish/partials/supporting-quote') ?>
		<?= $ci->load->view('manage/publish/partials/tags') ?>						
		<?= $ci->load->view('manage/publish/partials/web-images') ?>
		<?= $ci->load->view('manage/publish/partials/web-files') ?>						
		<?= $ci->load->view('manage/publish/partials/relevant-resources') ?>
		
		<?php if (!@$vd->m_content->is_published): ?>
		<?= $ci->load->view('manage/publish/partials/social-media') ?>
		<?php endif ?>
		
		<?= $ci->load->view('manage/publish/partials/web-video') ?>
		
	</div>
		
	<aside class="span4 aside aside-fluid">
		<div class="aside-properties" id="locked_aside">

			<?= $this->load->view('manage/publish/partials/status') ?>

			<section class="ap-block ap-properties">
				<ul>
					<?= $this->load->view('manage/publish/partials/select-category') ?>									
					<script>
					
					$(function() {
						
						var selects = $("#locked_aside select.category");
						selects.on_load_select();
							
						$(window).load(function() {
							selects.eq(0).addClass("required");
						});
						
					});
					
					</script>
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
	
	<script src="<?= $vd->assets_base ?>lib/bootbox.min.js?<?= $vd->version ?>"></script>
	<script src="<?= $vd->assets_base ?>js/required.js?<?= $vd->version ?>"></script>
	<script>
	
	$(function() {
		
		var options = { offset: { top: 20 } };
		$.lockfixed("#locked_aside", options);
		
	});
	
	</script>
	
</div>