<section class="form-section web-images section-requires-premium">
	<h2>
		Add Related Images
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_IMAGES ?>">
			<i class="icon-question-sign"></i>
		</a>	
	</h2>
	<div class="header-help-block">Attach images related to the content.</div>
	<?= $ci->load->view('manage/publish/partials/requires-premium') ?>
	<ul class="images-uploader-list" id="web-images-list">
		<?php $image_item_count = 8; ?>
		<?php if (@$vd->m_content): ?>
			<?php foreach ($vd->m_content->get_images() as $image): ?>
				<?php if ($vd->m_content->cover_image_id == $image->id): ?>
				<?php $has_cover_image = true; $image_item_count--; ?>
				<?= $ci->load->view('manage/publish/partials/web-images-item.php', 
					array('image' => $image, 'featured' => true)); ?>
				<?php endif ?>
			<?php endforeach ?>
			<?php if (!@$has_cover_image): ?>
			<?php $image_item_count--; ?>
			<?= $ci->load->view('manage/publish/partials/web-images-item.php', 
				array('image' => null, 'featured' => true)); ?>
			<?php endif ?>
			<?php foreach ($vd->m_content->get_images() as $image): ?>
				<?php if ($vd->m_content->cover_image_id != $image->id): ?>
				<?php $image_item_count--; ?>
				<?= $ci->load->view('manage/publish/partials/web-images-item.php', 
					array('image' => $image, 'featured' => false)); ?>
				<?php endif ?>
			<?php endforeach ?>
		<?php endif ?>
		<?php for ($i = 0; $i < $image_item_count; $i++): ?>
			<?= $ci->load->view('manage/publish/partials/web-images-item.php', 
				array('image' => null, 'featured' => ($i === 0 && !@$vd->m_content))); ?>
		<?php endfor ?>
	</ul>
		
	<script>
			
	$(function() {
		
		var ci_upload = $("#web-images-list");
		
		var update_visible = function() {
			
			var non_selects = ci_upload.find(".images-list-item:not(.s-select)");
			if (non_selects.size()) non_selects = non_selects.filter(":not(.featured)");
			var count = non_selects.size();
			
			if (count < 3) {
				var selects = ci_upload.find(".images-list-item.s-select");
				if (selects.size()) selects = selects.filter(":not(.featured)");
				var after_first_3 = selects.slice(3 - count);
				after_first_3.hide();
				return;
			}
			
			var all = ci_upload.find(".images-list-item");
			all.fadeIn(1000);
			return;
			
		};
		
		update_visible();
		
		ci_upload.find(".real-file").on("change", function() {
			
			var real_file = $(this);
			var container = real_file.parents(".images-list-item");
			var new_image = container.find(".s-existing img");
			var image_id_input = container.find("input.image_id");
			var progress_value = container.find(".progress-value");
			
			var variants = ["finger", "web", "view-web"];
			if (container.hasClass("featured")) {
				variants.push("cover");
				variants.push("view-cover");
			}
			
			container.removeClass("s-select-error");
			container.removeClass("s-select");
			container.addClass("s-progress");			
			image_id_input.val("");
			
			update_visible();
			
			var on_upload = function(res) {
				
				if (res.status)
				{
					container.removeClass("s-progress");
					container.addClass("s-existing");
					new_image.attr("src", res.files.web);
					image_id_input.val(res.image_id);
				}
				else
				{
					container.addClass("s-select-error");
					container.addClass("s-select");
					real_file.attr("disabled", false);
				}
				
			};
			
			real_file.ajax_upload({
				callback: on_upload,
				url: "manage/image/upload",
				data: { variants: variants },
				progress: function(ev) {
					var percent = Math.round((ev.loaded / ev.total) * 100);
					progress_value.css("width", percent + "%");
				}
			});
			
		});
		
		ci_upload.find(".images-list-item-remove").on("click", function() {
			
			var container = $(this).parents(".images-list-item");
			var image_id_input = container.find("input.image_id");
			
			container.removeClass("s-existing");
			container.addClass("s-select");
			image_id_input.val("");
			
		});
		
	});
	
	</script>
	
</section>