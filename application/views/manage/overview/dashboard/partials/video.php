<section class="aside-block aside-overview-video">
	<h3>Control Panel Overview</h3>
	<a id="overview-video" href="#<?= $vd->video_modal_id ?>" data-toggle="modal"></a>
	<script>
	
	$(function() {
		
		var overview_video = $("#overview-video");
		var video_html = <?php $vid = new Video_Youtube($vd->external_video_id); ?>
			<?= json_encode($vid->render(853, 480, array('autoplay' => true))); ?>;
		var modal = $("#<?= $vd->video_modal_id ?>");
		var modal_content = modal.find(".modal-content");
		
		modal.on("shown", function() {
			modal_content.html(video_html);	
		});
		
		modal.on("hide", function() {
			modal_content.empty();
		});
		
	});
	
	</script>
</section>