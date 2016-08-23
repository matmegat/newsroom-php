<section class="form-section tags">
	<h2>
		<span>Tags</span>
		<span class="help-inline">(Add keywords, separated with a comma)</span>
	</h2>
	<ul>
		<li>
			<input class="in-text span12" type="text" name="tags" id="tags"
				<?php if (@$vd->contact): ?>
				value="<?= $vd->esc(implode(', ', $vd->contact->get_tags())) ?>"
				<?php endif ?>
				placeholder="Lorem, Ipsum, Dolor, Tags" />
			<?php if (count($vd->recent_tags)): ?>
			<p class="help-block" id="suggested-tags">
				Suggested Tags: 
				<?php foreach ($vd->recent_tags as $tag): ?>
				<a><?= $vd->esc($tag) ?></a>	
				<span class="vertical-bar">|</span>
				<?php endforeach ?>
			</p>
			<script>
			
			$(function() {
				
				var tags = $("#tags");
				
				$("#suggested-tags a").on("click", function() {
					var existing = tags.val();
					var exploded = $.parse_comma_delim(existing);					
					var new_tag = $(this).text();
					if (exploded.indexOf(new_tag) == -1)
						exploded.push(new_tag);
					tags.val(exploded.join(", "));
				});
				
			});
			
			</script>
			<?php endif ?>
		</li>
	</ul>
</section>