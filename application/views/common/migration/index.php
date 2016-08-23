<p class="pre-checklist">
	This process will migrate your account and existing press releases to our new system. 
	You will be given access to our new control panel and features such as company newsrooms, 
	improved analytics and email campaigns. Please <strong>be aware that the new system is in beta</strong> 
	and should only be used by those wanting to try out the new functionality. 
</p>

<hr />

<ul class="checklist">
	
	<?php if ($vd->user_ready): ?>
	<li class="s-ok">
		<div class="checklist-head">Your account is active and able to transfer.</div>
		<p>
			Your account is active and verified. You are a normal user 
			<?php if ($vd->package > 0): ?>
				on the <strong><?= $vd->access_level ?></strong> package.
			<?php else: ?>
				without a subscription plan.
			<?php endif ?>
		</p>
	</li>	
	<?php elseif ($vd->is_reseller): ?>	
	<li class="s-fail">
		<div class="checklist-head">Your account is used as a reseller.</div>
		<p>
			It is not possible to migrate your account at this time. 
			Our new reseller features will be available soon.
		</p>
	</li>
	<?php elseif ($vd->is_migrated): ?>	
	<li class="s-fail">
		<div class="checklist-head">Your account has been migrated already.</div>
		<p>
			It is not possible to migrate your account at this time. 
			Contact <a href="helpdesk">support</a> if you are unable to access your account.
		</p>
	</li>
	<?php else: ?>
	<li class="s-fail">
		<div class="checklist-head">Your account is not active. Contact <a href="helpdesk">support</a>.</div>
		<p>
			Your account is not active or has not been verified. 
		</p>
	</li>
	<?php endif ?>
	
	<?php if ($vd->pr_within_2_days): ?>	
	<li class="s-fail">
		<div class="checklist-head">You have recent press releases.</div>
		<p>
			You have press releases submitted or approved within the last 48 hours
			(or a release scheduled). You should wait a few days and then try again.
		</p>
	</li>
	<?php elseif ($vd->pr_within_7_days): ?>
	<li class="s-warn">
		<div class="checklist-head">You have recent press releases.</div>
		<p>
			You have press releases that were submitted or approved within the last 7 days. 
			Make sure that you have downloaded the PDF report(s). Your press releases will be 
			migrated but old reports may not be available. 
		</p>
	</li>
	<?php else: ?>
	<li class="s-ok">
		<div class="checklist-head">You have no recent press releases.</div>
		<p>
			Any existing press releases will be migrated over to our new system but old PDF reports may not be available. 
			Make sure you have downloaded reports that you wish to keep. 
		</p>
	</li>
	<?php endif ?>
	
	<?php if ($vd->server_busy): ?>	
	<li class="s-fail">
		<div class="checklist-head">The server is too busy.</div>
		<p>
			We are already in the process of migrating several users. 
			Please try again in a few minutes. 
		</p>
	</li>
	<?php else: ?>
	<!-- <li class="s-ok">
		<div class="checklist-head">Server resources available.</div>
		<p>
			The server is not too busy. We are able to process your migration now.
		</p>
	</li> -->
	<?php endif ?>
	
</ul>

<div class="post-checklist hidden">
	
	<hr />
	
	<form action="common/migration/exec" method="post">		
	
		<label class="checkbox-container checkbox-block marbot-20 clearfix">
			<input type="checkbox" name="confirm" id="confirm" value="1" />
			<span class="checkbox"></span>
			<div class="block">
				I have read all the information above and confirm that 
				I want to continue with the migration.
			</div>
		</label>
		
		<div class="pull-right">
			<button class="btn btn-primary" disabled id="submit" type="submit">Continue</button>
			<button class="btn" type="button" id="cancel">Cancel</button>
		</div>
	
	</form>
	
</div>

<script>

$(function() {
	
	var failed = $("li.s-fail").size();
	if (!failed) $(".post-checklist").removeClass("hidden");
	
	var cancel = $("#cancel");
	cancel.on("click", function() {
		window.location = "MyAccount";
	});
	
	var confirm = $("#confirm");
	var submit = $("#submit");
	confirm.on("change", function() {
		submit.prop("disabled", !confirm.is(":checked"));
	});
	
});
	
</script>