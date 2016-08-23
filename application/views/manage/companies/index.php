<?php $credits_total = Auth::user()->newsroom_credits_total(); ?>
<?php $credits_available = Auth::user()->newsroom_credits_available(); ?>

<?php if ($credits_total): ?>
<div id="out-of-credits" class="below-header-feedback
	<?= value_if_test($credits_available, 'hidden') ?>">	
	<div class="alert alert-warning">
		<strong>Attention!</strong>
		You've used all available newsroom credits. 
		<a href="manage/upgrade">Purchase</a> more credits.
	</div>
</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Manage Companies</h1>
				</div>
				<div class="span6">
					<div class="pull-right">
						<form action="manage/companies/create" method="post">
							<input type="text" placeholder="Company Name" name="company_name" />
							<button type="submit" class="bt-publish bt-orange">
								Create
							</button>
						</form>
					</div>
				</div>
			</div>
		</header>
	</div>
</div>

<?php if ($vd->has_archived || $vd->is_archived_list): ?>
<div class="row-fluid">
	<div class="span12">
		<ul class="nav nav-tabs nav-activate" id="tabs">
			<li><a data-on="^manage/companies(/[0-9]+)?$" href="<?= gstring('manage/companies') ?>">Companies</a></li>
			<li><a data-on="^manage/companies/archived" href="<?= gstring('manage/companies/archived') ?>">Archived</a></li>
		</ul>
	</div>
</div>
<?php endif ?>

<div class="row-fluid">
	<div class="span12">		
		<div class="content listing">
			
			<table class="grid newsroom-list 
				<?= value_if_test(!$credits_available, 'locked') ?>">
				<thead>
					
					<tr>
						<th class="left">Company Name</th>
						<th>Press Contact</th>
						<th>Newsroom</th>
					</tr>
					
				</thead>
				<tbody>
					
					<?php foreach ($vd->results as $k => $result): ?>
					<tr class="newsroom-activation-status <?= value_if_test(
						$result->is_active, 'active', 'inactive') ?>">
						<td class="left">
							<h3>
								<a href="<?= $result->url('manage/dashboard') ?>">
									<?= $vd->esc($result->company_name) ?></a>
								<?php if (!$k && !$vd->chunkination->offset() 
										&& $result->order_default >= 0 
										&& !$vd->is_archived_list): ?>
								<span>&nbsp;(Default)</span>
								<?php endif ?>
								<?php if ($result->is_archived): ?>
								<span>&nbsp;(Archived)</span>
								<?php endif ?>
							</h3>
							<ul>
								<?php if ($result->is_archived): ?>
								<li><a href="manage/companies/archive/<?= $result->company_id ?>">Restore</a></li>
								<?php else: ?>
								<li><a href="<?= $result->url('manage/newsroom/company') ?>">Edit</a></li>
								<li><a href="manage/companies/set_default/<?= $result->company_id ?>">Set Default</a></li>								
								<li><a href="manage/companies/archive/<?= $result->company_id ?>">Archive</a></li>
								<?php endif ?>
							</ul>
						</td>						
						<td>
							<?php if (@$result->m_contact): ?>
							<div><?= $result->m_contact->name ?></div>
							<div class="muted"><?= $result->m_contact->email ?></div>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
						</td>
						<td>
							<span class="active-text">Active</span>
							<span class="inactive-text">Inactive</span>
							<?php if (!$result->is_archived): ?>
							(<a class="newsroom-activation" href="#"><!-- 
							--><input type="hidden" name="company_id" value="<?= $result->company_id ?>" /><!--
						   --><span class="inactive-text">Activate</span><!--
							--><span class="active-text">Deactivate</span><!--
							--></a>)
							<?php endif ?>
							<?php if ($result->is_archived): ?>
							<span class="active-text">
								(<a href="<?= $result->url() ?>">View</a>)
							</span>
							<?php else: ?>
							<div><a href="<?= $result->url() ?>">View Newsroom</a></div>
							<?php endif ?>
						</td>
					</tr>
					<?php endforeach ?>

				</tbody>
			</table>
			
			<div class="grid-report">Displaying <?= count($vd->results) ?> 
				of <?= $vd->chunkination->total() ?> Companies</div>
			
			<?= $vd->chunkination->render() ?>
		
		</div>
	</div>
</div>

<script>
	
$(function() {
	
	var buttons = $(".newsroom-activation");
	var out_of_credits = $("#out-of-credits");
	var newsroom_list = $(".newsroom-list");
	var activate_current_newsroom = $(".activate-current-newsroom");
	
	buttons.on("click", function() {
		
		var _this = $(this);
		var container = _this.parents(".newsroom-activation-status");
		var data = _this.find("input").serialize();
		container.addClass("has-loader");
		
		$.post("manage/companies/activation", data, function(res) {
			
			container.removeClass("has-loader");
			out_of_credits.toggleClass("hidden", !res.is_at_limit);
			container.toggleClass("active", res.is_active);
			container.toggleClass("inactive", !res.is_active);
			newsroom_list.toggleClass("locked", res.is_at_limit);
			_this.blur();
			
		});
		
		return false;
		
	});
	
	activate_current_newsroom.on("click", function() {
		
		data = { company_id: NR_COMPANY_ID };
		$.post("manage/companies/activation", data, function(res) {
			window.location = activate_current_newsroom.data("redirect");
		});
		
		return false;
		
	});
	
});
	
</script>