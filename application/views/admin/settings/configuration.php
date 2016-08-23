<form action="" method="post">
	
	<div class="row-fluid">
		<div class="span12">
			<header class="page-header">
				<div class="row-fluid">
					<div class="span6">
						<h1>Configuration</h1>
					</div>
					<div class="span6">
						<button class="btn bt-silver pull-right">Save</button>
					</div>
				</div>
			</header>
		</div>
	</div>

	<div class="row-fluid">
		<div class="span12">
			<div class="pad-20">
				
				<div class="row-fluid">
					<div class="span4">
						<?php foreach ($vd->col_1 as $k => $setting): ?>
						<?= $ci->load->view('admin/settings/configuration-setting',
							array('setting' => $setting)); ?>
						<?php endforeach ?>
					</div>
					<div class="span4">
						<?php foreach ($vd->col_2 as $k => $setting): ?>
						<?= $ci->load->view('admin/settings/configuration-setting',
							array('setting' => $setting)); ?>
						<?php endforeach ?>
					</div>
					<div class="span4">
						<?php foreach ($vd->col_3 as $k => $setting): ?>
						<?= $ci->load->view('admin/settings/configuration-setting',
							array('setting' => $setting)); ?>
						<?php endforeach ?>
					</div>
				</div>
			
			</div>
		</div>
	</div>
	
	<script>
	
	$(function() {
		
		var editor = $("#<?= $vd->editor_modal_id ?>");
		var editor_ta = editor.find("#configuration-editor");
		var source_ta = null;
		
		editor.on("hide", function() {
			source_ta.val(editor_ta.val());
		});
		
		$(".source-ta").on("focus", function() {
			var setting = $(this).parents(".configuration-setting");
			source_ta = setting.find(".source-ta-data");
			var value = source_ta.val();
			editor_ta.val(value);
			editor.modal("show");
		});
		
	});
	
	</script>
	
</form>