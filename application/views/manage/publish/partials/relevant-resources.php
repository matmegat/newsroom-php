<section class="form-section relevant_resources section-requires-premium" id="relevant-resources">
	<h2>
		Additional Links
		<a data-toggle="tooltip" class="tl" href="#" 
			title="<?= Help::WEB_LINKS ?>">
			<i class="icon-question-sign"></i>
		</a>	
	</h2>
	<div class="header-help-block">Include additional links related to your company.</div>
	<?= $ci->load->view('manage/publish/partials/requires-premium') ?>
	<ul>
		<li class="rr-pri">
			<div class="row-fluid">
				<div class="span5 rr-title">
					<input class="in-text span12" type="text" 
						value="<?= $vd->esc(@$vd->m_content->rel_res_pri_title) ?>"
						name="rel_res_pri_title" 
						placeholder="Resource Title" />
				</div>
				<div class="span7 rr-link">
					<input class="in-text span12" type="url" 
						value="<?= $vd->esc(@$vd->m_content->rel_res_pri_link) ?>"
						name="rel_res_pri_link" 
						placeholder="Resource Link" />
				</div>
			</div>
		</li>
		<li class="rr-sec disabled">
			<div class="row-fluid">
				<div class="span5 rr-title">
					<input class="in-text span12" type="text"
						value="<?= $vd->esc(@$vd->m_content->rel_res_sec_title) ?>" 
						name="rel_res_sec_title" 
						placeholder="Resource Title" disabled />
				</div>
				<div class="span7 rr-link">
					<input class="in-text span12" type="url" 
						value="<?= $vd->esc(@$vd->m_content->rel_res_sec_link) ?>"
						name="rel_res_sec_link" 
						placeholder="Resource Link" disabled />
				</div>
			</div>
		</li>
	</ul>
</section>
<script>
	
$(function() {
	
	var rr_boxes = $("#relevant-resources input");
	var rr_sec = $("#relevant-resources .rr-sec");
	
	if (rr_boxes.val()) {
		// already have a value => enable all
		rr_boxes.attr("disabled", false);		
		rr_sec.removeClass("disabled");
	}
		
	// a value is provided => enable all
	rr_boxes.on("change", function() {
		if ($(this).val()) {
			rr_boxes.attr("disabled", false);
			rr_sec.removeClass("disabled");
		}
	});
	
});
	
</script>