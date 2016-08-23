<section class="form-section tags">
	<h2>
		<span>Tags</span>
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::TAGS ?>">
			<i class="icon-question-sign"></i>
		</a>
		<span class="help-inline">(Add your keywords, separated with a comma)</span>
	</h2>
	<ul>
		<li>
			<input class="in-text span12 required-callback" type="text" name="tags" id="tags"
				data-required-name="Tags" data-required-callback="tags-count"
				<?php if (@$vd->m_content): ?>
				value="<?= $vd->esc(implode(', ', $vd->m_content->get_tags())) ?>"
				<?php endif ?>
				placeholder="Lorem, Ipsum, Dolor, Tags" />
			<?php if (count($vd->recent_tags)): ?>
			<p class="help-block" id="suggested-tags">
				Suggested Tags: 
				<?php foreach ($vd->recent_tags as $tag): ?>
				<a><?= $tag ?></a>	
				<span class="vertical-bar">|</span>
				<?php endforeach ?>
			</p>
			<script>
			
			$(function() {
				
				var tags = $("#tags");
				
				$("#suggested-tags a").on("click", function() {
					var exploded = $.parse_comma_delim(tags.val());
					var new_tag = $(this).text();
					if (exploded.indexOf(new_tag) == -1)
						exploded.push(new_tag);
					tags.val(exploded.join(", "));
				});
				
				var content_form = $("#content-form");
				var is_pr_form = content_form.hasClass("pr-form");
			
				required_js.add_callback("tags-count", function(value) {
					var response = { valid: false, text: "must have between 3 and 12 tags" };
					var exploded = $.parse_comma_delim(tags.val());
					response.valid = (!is_pr_form || exploded.length >= 3)
					response.valid = response.valid && exploded.length <= 12;
					tags.val(exploded.join(", "));
					return response;
				});
				
			});
			
			</script>
			<?php endif ?>
		</li>
	</ul>
</section>