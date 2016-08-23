<ul class="breadcrumb">
	<li><a href="manage/contact">iContact</a> <span class="divider">&raquo;</span></li>
	<li><a href="manage/contact/list">Lists</a> <span class="divider">&raquo;</span></li>
	<li class="active"><?= $vd->esc($vd->list->name) ?></li>
</ul>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Contacts Manager</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<a href="manage/contact/contact/edit/from/<?= $vd->list->id ?>" class="bt-silver bt-publish">
							Add Contact
						</a>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		
		<div id="list-name-editable">
			<div class="normal">
				<h2>
					<span class="text"><?= $vd->esc($vd->list->name) ?></span>
					<a><i class="icon-edit"></i></a>
				</h2>
			</div>
			<div class="edit" style="display: none">
				<input type="text" value="<?= $vd->esc($vd->list->name) ?>" />
			</div>
		</div>
		
		<?= $this->load->view('manage/contact/partials/contact_listing', null, true) ?>	
		
		<script>
		
		$(function() {
			
			var container = $("#list-name-editable");
			var normal = container.find(".normal");
			var edit = container.find(".edit");
			var input = edit.find("input");
			var h2_text = normal.find("h2 .text");
			
			var do_edit = function() {
				normal.hide();
				edit.show();
				input.focus();
			};
			
			var do_after_save = function() {
				h2_text.text(input.val());
				input.attr("disabled", false);
				normal.show();
				edit.hide();
			};
			
			var do_save = function() {					
				var value = $.trim(input.val());
				if (!value) return after_save(input.val(h2_text.text()));
				input.attr("disabled", true);
				var data = { name: value };
				$.post("manage/contact/list/rename/<?= $vd->list->id ?>", 
					data, do_after_save);
			};
			
			normal.find("a").on("click", do_edit);
			input.on("blur", do_save);
			input.on("keypress", function(ev) {
				if (ev.which == 13) do_save();
			});
			
		});
		
		</script>
		
	</div>
</div>