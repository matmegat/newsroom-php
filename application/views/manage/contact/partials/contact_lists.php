<section class="form-section contact-lists">
	<?php if (@$lists_all): ?>
	<h2>Select Contacts</h2>
	<div class="marbot-15">
		<label class="checkbox-container">
			<input type="checkbox" name="all_contacts" id="all-contacts" 
				value="1" <?= value_if_test(@$vd->campaign->all_contacts, 'checked') ?> />
			<span class="checkbox"></span>
			Send to all contacts.
		</label>
	</div>
	<script>
	
	$(function() {
		
		window.__update_contact_lists_ui = function() {
			var is_checked = all_contacts.is(":checked");
			$("#contact-lists select").prop("disabled", is_checked);
			$("#contact-lists .bootstrap-select .btn").prop("disabled", is_checked);
			$("#add-list").prop("disabled", is_checked);
			$("#create-list").prop("disabled", is_checked);			
		};
		
		var all_contacts = $("#all-contacts");
		all_contacts.on("change", window.__update_contact_lists_ui);
		
	});
	
	</script>
	<?php else: ?>
	<h2>Contact Lists</h2>
	<?php endif ?>
	<ul id="contact-lists">
		<?php if (!@$vd->related_lists && @$vd->from_m_contact_list): ?>
		<?php $vd->related_lists = array($vd->from_m_contact_list); ?>
		<?php endif ?>
		<?php $in_lists_count = count(@$vd->related_lists); ?>
		<?php for ($i = 0; $i < max($in_lists_count, 1); $i += 2): ?>
		<li>
			<div class="row-fluid">
				<?php for ($o = 0; $o < 2 && ($i + $o) < max($in_lists_count, 1); $o++): ?>
				<div class="span5 list-container list-select-container">
					<select class="show-menu-arrow span12" name="lists[]">
						<option class="selectpicker-default" 
							title="Select List" value="">None</option>
						<?php if (isset($vd->related_lists[$i+$o])): ?>
							<?php $in_list = $vd->related_lists[$i+$o]; ?>
							<?php foreach ($lists as $list): ?>
							<option value="<?= $list->id ?>"
								<?= value_if_test($in_list->id == $list->id, 'selected') ?>>
								<?= $vd->esc($list->name) ?>
							</option>
							<?php endforeach ?>
						<?php else: ?>
							<?php foreach ($lists as $list): ?>
							<option value="<?= $list->id ?>">
								<?= $vd->esc($list->name) ?>
							</option>
							<?php endforeach ?>
						<?php endif ?>
					</select>
				</div>
				<?php endfor ?>
			</div>
		</li>
		<?php endfor ?>
	</ul>
	<?php if (@$lists_allow_create): ?>
	<div class="marbot-20 btn-group">	
		<button id="add-list" class="btn btn-small" type="button">
			Add
		</button>		
		<button id="create-list" class="btn btn-small" type="button">
			Create
		</button>		
	</div>
	<?php else: ?>
	<div class="marbot-20 btn-group">	
		<button id="add-list" class="btn btn-small" type="button">
			Add List
		</button>	
	</div>
	<?php endif ?>
	<script>
	
	$(function() {
	
		var conf = { size: 5, container: 'body' };		
		var contact_lists = $("#contact-lists");
		
		contact_lists.find("select").on_load_select(conf);
		contact_lists.addClass("added-selectpicker");
		
		$(window).load(function() {
			if (window.__update_contact_lists_ui !== undefined)
				window.__update_contact_lists_ui();
		});
		
		$("#add-list").on("click", function() {
			var source_list = contact_lists.find(".list-select-container").eq(0);
			var last_list = contact_lists.find(".list-container").last();
			var new_list = source_list.clone();
			new_list.find(".bootstrap-select").remove();
			new_list.find("select").val("").on_load_select(conf);
			if (last_list.parent().children().size() === 1)
				return last_list.after(new_list);
			var new_row = $.create("li");
			var new_fluid = $.create("div").addClass("row-fluid");
			new_row.append(new_fluid);
			new_fluid.append(new_list);
			contact_lists.append(new_row);
		});
		
		<?php if (@$lists_allow_create): ?>
		
		$("#create-list").on("click", function() {
			var last_list = contact_lists.find(".list-container").last();
			var new_list = $.create("div").addClass("span5 list-container");
			var in_text = $.create("input");
			in_text.addClass("in-text span12");
			in_text.attr("name", "create_lists[]");
			in_text.attr("placeholder", "List Name");
			in_text.attr("type", "text");
			new_list.append(in_text);
			if (last_list.parent().children().size() === 1)
				return last_list.after(new_list);
			var new_row = $.create("li");
			var new_fluid = $.create("div").addClass("row-fluid");
			new_row.append(new_fluid);
			new_fluid.append(new_list);
			contact_lists.append(new_row);
		});
		
		<?php endif ?>
	
	});
	
	</script>
</section>