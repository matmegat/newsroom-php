<?php if (Auth::is_admin_controlled()) return; ?>
<?php if (Auth::$is_from_secret) return; ?>
<div id="video_guide" class="modal hide fade modal-autoheight modal-video" 
	tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="close_modal">
			<i class="icon-remove"></i>
		</button>
		<label for="cb_modal_show" class="checkbox sm-checkbox">
			<?php if ($vd->auto_show) : ?>
			 <input type="checkbox" name="cb_modal_show" id="cb_modal_show"> Don’t show this dialog again
			<?php endif; ?>
		</label>
		<h3 id="modalLabel">What is i<?= ucwords($vd->section) ?>?</h3>
	</div>
	<div class="modal-body">
		<div class="row-fluid">
			<aside class="span5 modal-tabs-aside">
				<ul class="vertical-tabs">
				<?php foreach ($vd->videos as $i => $video): ?>
					<li class="<?= value_if_test($i === 0, 'active') ?>">
						<a href="#video_<?= $video->id ?>" class="clearfix">
							<?php if ($video->stored_image_id): ?>
								<?php $image = Stored_Image::from_db($video->stored_image_id); ?>
								<img src="<?= $image->url() ?>" 
									alt="<?= $vd->esc($video->title) ?>" />
							<span class="vt-block video-img-margin">
							<?php $summary_limit = 50; ?>
							<?php else : ?>
							<span class="vt-block">
							<?php $summary_limit = 80; ?>
							<?php endif; ?>
								<span class="vt-title"><?= $vd->esc($video->title) ?></span>
								<span class="vt-content"><?= $vd->cut(strip_tags($video->content), $summary_limit) ?></span>
							</span>
						</a>
					</li>
				<?php endforeach ?>
				</ul>
			</aside>
			<div class="span7 no-margin">
				<div class="tab-content">
					<?php foreach ($vd->videos as $i => $video) : ?>
						<section class="tab-pane html-content
							<?= value_if_test($i === 0, 'active') ?>" 
							id="video_<?= $video->id ?>">
							<?php if ($video->external_video_id): ?>
								<?php $provider = Video::get_instance(
									$video->external_video_provider, 
									$video->external_video_id); ?>
								<div class="fake-video-box"></div>
								<span class="media-block needs-video">
									<?= $vd->esc($provider->render(448, 252)); ?>
								</span>
							<?php endif; ?>
							<h4><?= $vd->esc($video->title) ?></h4>
							<?= $video->content ?>
						</section>
					<?php endforeach; ?>
				</div>
			</div>
		</div>	
	</div>
</div>

<script>

$(function() {

	var video_guide = $("#video_guide");	
	
	var max_height = (0.9 * $(window).height()) - 40;	
	if (max_height > 600) max_height = 600;
	video_guide.find(".row-fluid .span7").css("min-height", max_height);
	video_guide.find(".modal-body").css("height", max_height);
	
	var convert_video_html = function(media_block) {
		video_guide.find(".has-video").each(function() {
			var _this = $(this);
			_this.text(_this.html());
			_this.removeClass("has-video");
			_this.prev(".fake-video-box")
				.addClass("basic").show();
		});		
		if (!media_block || !media_block.size()) return;
		setTimeout(function() {
			media_block.html(media_block.text());
			media_block.addClass("has-video");
			media_block.prev(".fake-video-box").hide();
		}, 500);		
	};
	
	video_guide.on("show", function() {
		var active = video_guide.find(".tab-content .active");
		var mb = active.find(".media-block");
		convert_video_html(mb);
	});
	
	video_guide.on("hide", function() {
		convert_video_html();
	});

	var vertical_tabs = $(".vertical-tabs a");
	vertical_tabs.on("click", function(e) {
		e.preventDefault();
		var _this = $(this);
		_this.tab("show");
		var mb = $(_this.attr("href")).find(".media-block");
		convert_video_html(mb);
	}); 
	
	<?php if ($vd->auto_show): ?>
	setTimeout(function() {
		video_guide.modal({ show: true });
	}, 1500);
	<?php else: ?>
	video_guide.modal({ show: false });
	<?php endif ?>

	// close the modal and check the do not show
	$("#close_modal").on("click", function() {	
		if ($("#cb_modal_show").is(":checked")) 
			$.get("manage/video_guide_record/<?= $ci->uri->segment(2) ?>");	
	});

});

</script>