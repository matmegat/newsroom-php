<div class="alert alert-error clearfix" id="delete-confirm">
	<form method="post" action="">
		<input type="hidden" name="delete" value="1" />
		<?php foreach ($vd->selected as $contact_list_id => $v): ?>
		<input type="hidden" name="selected[<?= $contact_list_id ?>]" value="1" />
		<?php endforeach ?>
		<button class="pull-left btn btn-danger" 
			type="submit" name="confirm" value="1">
			Confirm Delete
		</button>
		<strong>Caution!</strong> You are about to delete the contacts selected below.
		<br />Be aware that this action cannot be reversed.
	</form>
</div>

<script>
	
$(function() {
	
	var fields = $();
	fields = fields.add(".content input:not([type=checkbox])");
	fields = fields.add(".content select");
	fields = fields.add(".content button:not(.checkbox)");
	fields = fields.add(".content a");
	fields.remove();
	
	$(".content input[type=checkbox]")
		.attr("disabled", true)
		.addClass("disabled");
		
	$("#selectable-results th .checkbox-container").remove();
	$("#selectable-results tbody tr:not(.checked)").remove();
	$(".container .page-header").parent().parent().remove();
	$("#tabs").parent().parent().remove();	
	$(".chunkination").empty();
	$(".grid-report").empty();
	
});
	
</script>

<div class="marbot"></div>