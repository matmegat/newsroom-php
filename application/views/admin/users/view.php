<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>User Details</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<form class="row-fluid" action="<?= $ci->uri->uri_string ?>" method="post">
	<div class="span12">		
		<div class="content content-no-tabs">
			
			<div class="span8 information-panel">
				
				<section class="form-section user-details">
					<h2 class="marbot-5">Basic Information</h2>
					<div class="row-fluid">
						<div class="span6 relative">							
							<input type="text" required name="first_name"
								class="span12 in-text has-placeholder"
								value="<?= $vd->user->first_name ?>"
								placeholder="First Name" />
							<strong class="placeholder">First Name</strong>
						</div>
						<div class="span6 relative">							
							<input type="text" required name="last_name" 
								class="span12 in-text has-placeholder"
								value="<?= $vd->user->last_name ?>"
								placeholder="Last Name" />
							<strong class="placeholder">Last Name</strong>
						</div>
					</div>
					<div class="relative">
						<input type="email" name="email" required
							class="span12 in-text has-placeholder" 
							value="<?= $vd->user->email ?>"
							placeholder="Email Address" />
						<strong class="placeholder">Email Address</strong>
					</div>
					<div class="relative">
						<textarea name="notes"
							class="span12 in-text has-placeholder user-notes" 
							placeholder="Additional Notes" /><?= 
								$vd->user->notes ?></textarea>
						<strong class="placeholder">Additional Notes</strong>
					</div>	
				</section>	
				
				<section class="form-section give-credits marbot-10">
					<h2>Give Credits</h2>					
					<div class="row-fluid give-credits-row">
						<div class="span4">
							<select name="ac_class[]" class="selectpicker show-menu-arrow span12 marbot-15">
								<option value="pr_premium">Premium PR</option>
								<option value="pr_basic">Basic PR</option>
								<option value="email">Email</option>
								<option value="newsroom">Newsroom</option>
							</select>
						</div>
						<div class="span3">
							<input type="text" class="in-text span12 marbot-15" 
								name="ac_amount[]" placeholder="Amount" />
						</div>
						<div class="span4">
							<div class="input-append give-credits-add-on in-text-add-on marbot-15">
								<input class="in-text ac-expires" name="ac_expires[]"
									data-date-format="yyyy-mm-dd" type="text" 
									value="<?= Date::days(30)->format('Y-m-d') ?>" />
								<span class="add-on ac-expires-icon"><i class="icon-calendar"></i></span>
							</div>
						</div>
						<div class="span1">
							<button type="button" class="span12 add-more btn">+</button>
						</div>
					</div>	
					<script>
					
					$(function() {
						
						var nowTemp = new Date();
						var tomorrow = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), 
							nowTemp.getDate(), 0, 0, 0, 0);
						tomorrow.setDate(tomorrow.getDate() + 1);
						
						var add_datetime = function(row) {
							
							var expires_date = row.find(".ac-expires");	
							var expires_icon = row.find(".ac-expires-icon");
													
							expires_date.datepicker({
								onRender: function(date) {
									if (date.valueOf() < tomorrow.valueOf())
										return 'disabled';
								}
							});
							
							expires_icon.on("click", function() {
								expires_date.datepicker("show");
							});
							
						};
						
						$(document).on("click", ".add-more", function() {
							
							var row = $(this).parents(".give-credits-row");
							var new_row = $.create(row[0].tagName);
							new_row.attr("class", row.attr("class"));
							new_row.html(row.html());
							new_row.find(".bootstrap-select").remove();
							new_row.find("select.selectpicker").on_load_select();
							add_datetime(new_row);
							row.after(new_row);
							
						});
						
						add_datetime($(".give-credits-row"));
						$(".add-more").click();						
						
					});
					
					</script>
				</section>
				
				<?php if (isset($vd->credit_data)): ?>				
				<section class="form-section user-credits">
					<h2>Active Credits</h2>					
					<table class="credit-data grid marbot-20">
						<thead>
							<tr>
								<th class="left">Class</th>
								<th>Expiration</th>
								<th>Used</th>
								<th>Available</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php $stat = $vd->credit_data->pr_premium; ?>
								<td class="left"><span class="status-package">Plan</span> Premium PR</td>
								<td>
									<?php $dt_expires = Date::out($vd->user->package_expires()); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td><?= $stat->rollover_used ?></td>
								<td><?= $stat->rollover_available ?></td>
								<td><?= $stat->rollover_total ?></td>
							</tr>
							<?php foreach ($vd->credit_data->pr_premium->held as $k => $held): ?>
							<tr>
								<td class="left"><span class="status-held">Held</span> Premium PR</td>
								<td>
									<?php $dt_expires = Date::out($held->date_expires); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>										
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td><?= $held->used() ?></td>
								<td><?= $held->available() ?></td>
								<td><?= $held->total() ?></td>
							</tr>
							<?php endforeach ?>
							<tr>
								<?php $stat = $vd->credit_data->pr_basic; ?>
								<td class="left"><span class="status-package">Plan</span> Basic PR</td>
								<td>
									<span class="muted">Periodic</span>
								</td>
								<td><?= $stat->rollover_used ?></td>
								<td><?= $stat->rollover_available ?></td>
								<td><?= $stat->rollover_total ?></td>
							</tr>
							<?php foreach ($vd->credit_data->pr_basic->held as $k => $held): ?>
							<tr>
								<td class="left"><span class="status-held">Held</span> Basic PR</td>
								<td>
									<?php $dt_expires = Date::out($held->date_expires); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>										
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td><?= $held->used() ?></td>
								<td><?= $held->available() ?></td>
								<td><?= $held->total() ?></td>
							</tr>
							<?php endforeach ?>
							<tr>
								<?php $stat = $vd->credit_data->email; ?>
								<td class="left"><span class="status-package">Plan</span> Email</td>
								<td>
									<?php $dt_expires = Date::out($vd->user->package_expires()); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td><?= $stat->rollover_used ?></td>
								<td><?= $stat->rollover_available ?></td>
								<td><?= $stat->rollover_total ?></td>
							</tr>
							<?php foreach ($vd->credit_data->email->held as $k => $held): ?>
							<tr>
								<td class="left"><span class="status-held">Held</span> Email</td>
								<td>
									<?php $dt_expires = Date::out($held->date_expires); ?>
									<?php if ($dt_expires > Date::months(11)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td><?= $held->used() ?></td>
								<td><?= $held->available() ?></td>
								<td><?= $held->total() ?></td>
							</tr>
							<?php endforeach ?>
							<tr>
								<?php $stat = $vd->credit_data->newsroom; ?>
								<td class="left"><span class="status-package">Plan</span> Newsroom</td>
								<td>
									<?php $dt_expires = Date::out($vd->user->package_expires()); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td>-</td>
								<td>-</td>
								<td><?= $stat->rollover ?></td>
							</tr>
							<?php foreach ($vd->credit_data->newsroom->held as $k => $held): ?>
							<tr>
								<td class="left"><span class="status-held">Held</span> Newsroom</td>
								<td>
									<?php $dt_expires = Date::out($held->date_expires); ?>
									<?php if ($dt_expires > Date::months(9)): ?>
										<?= $dt_expires->format('Y-m-d'); ?>
									<?php else: ?>										
										<?= $dt_expires->format('M jS'); ?>
									<?php endif ?>
								</td>
								<td>-</td>
								<td>-</td>
								<td><?= $held->total() ?></td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
				</section>
				<?php endif ?>
				
			</div>
			
			<aside class="span4 aside aside-fluid">
				<div id="locked_aside">
					
					<div class="aside-properties padding-top marbot-20">
						<section class="ap-block marbot-5">
							<select class="show-menu-arrow span12 selectpicker" name="is_active">
								<option <?= value_if_test(!$vd->user->id || $vd->user->is_active, 'selected')
									?> value="1">Account Enabled</option>
								<option <?= value_if_test($vd->user->id && !$vd->user->is_active, 'selected')
									?> value="0">Account Disabled</option>								
							</select> 
						</section>
						<section class="ap-block marbot-5">
							<select class="show-menu-arrow span12 selectpicker" name="is_admin">
								<option <?= value_if_test(!@$vd->user->is_admin, 'selected')
									?> value="0">Standard User</option>
								<option <?= value_if_test(@$vd->user->is_admin, 'selected')
									?> value="1">Admin User</option>								
							</select>
						</section>
						<section class="ap-block row-fluid marbot-10">
							<div class="row-fluid marbot-5">
								<div class="span12">
									<a class="span12 ta-center btn" id="reset-password"
										<?= value_if_test(!$vd->user->id, 'disabled') ?>
										target="_blank">Reset Password</a>
									<script>
									
									$(function() {
										
										bootbox.animate(false);
										var message = 'This action will reset the new password.';
										$("#reset-password").on("click", function() {
											if ($(this).is(":disabled")) return;
											bootbox.confirm(message, function(confirm) {
												if (!confirm) return;
												var url = "admin/users/view/reset/<?= $vd->user->id ?>";
												$.post(url, { confirm: true }, function(res) {
													var e = $.create("input").addClass("password-text");
													e.val(res.password);
													bootbox.alert(e);
													e.focus().select();
												});
											});
										});
										
									});
									
									</script>
								</div>
							</div>
							<div class="row-fluid">
								<div class="span8">
									<a class="span12 ta-center btn btn-no-ts btn-danger"
										<?= value_if_test(!$vd->user->id, 'disabled') ?>
										<?php if ($vd->user->id): ?>
										href="<?= Admo::url('manage', $vd->user->id) ?>" 
										<?php endif ?>
										target="_blank">Admin Session</a>
								</div>
								<div class="span4">
									<button type="submit" name="save" value="1" 
										class="span12 bt-orange pull-right">Save</button>
								</div>
							</div>
							
						</section>
					</div>
					
					<?php if ($vd->user->remote_addr): ?>
					<?php $is_blocked = Model_Blocked::find($vd->user->remote_addr); ?>
					<div class="aside-properties aside-compact marbot-20">
						<section class="ap-block row-fluid">
							<div class="input-append remote-addr-block <?= 
								value_if_test($is_blocked, 'remote-addr-blocked') ?>">
								<input type="text" id="remote-addr" name="remote_addr" readonly
									class="nomarbot" value="<?= $vd->user->remote_addr ?>" />
								<?php if ($is_blocked): ?>
								<span class="add-on">BLOCKED</span>
								<?php else: ?>
								<a class="btn nomarbot" target="_blank" id="remote-addr-btn"
									href="admin/settings/ip_block/add?user=<?= $vd->user->id ?>">
									<i class="icon-ban-circle status-false"></i>
								</a>
								<?php endif ?>								
							</div>
						</section>
					</div>
					<script>
					
					$(function() {
						
						$("#remote-addr").on("focus", function() {
							$(this).select();
						});
						
						$("#remote-addr-btn").on("click", function() {
							var _this = $(this);
							$.get(_this.attr("href"));
							_this.parent().addClass("remote-addr-blocked");
							var text_add_on = $.create("span");
							text_add_on.addClass("add-on");
							text_add_on.text("BLOCKED");
							_this.before(text_add_on);
							_this.remove();
							return false;
						});
						
					});
					
					</script>
					<?php endif ?>	
					
					<?php if ($vd->user->id): ?>
					<div class="aside-properties padding-top marbot-20">
						<section class="ap-block marbot-5">
							<table class="grid user-details" id="user-details">
								<tbody>
									<tr>
										<th>Package</th>
										<td><?= $vd->user->package_name() ?></td>
									</tr>
									<tr>
										<th>iPublish</th>
										<td>
											<?= (int) @$vd->published_count ?>
											<a href="admin/publish/pr/published?filter_user=<?= $vd->user->id ?>" 
												class="add-filter-icon" target="_blank"></a>
										</td>
									</tr>
									<tr>
										<th>iContact</th>
										<td>
											<?= (int) @$vd->campaign_count ?>
											<a href="admin/contact/campaign/all?filter_user=<?= $vd->user->id ?>" 
												class="add-filter-icon" target="_blank"></a>
										</td>
									</tr>
									<tr>
										<th>Companies</th>
										<td>
											<?= (int) @$vd->companies_count ?>
											<a href="admin/companies/all?filter_user=<?= $vd->user->id ?>" 
												class="add-filter-icon" target="_blank"></a>
										</td>
									</tr>
									<tr>
										<th>Newsrooms</th>
										<td>
											<?= (int) @$vd->credit_data->newsroom->used ?>
											<a href="admin/companies/newsroom?filter_user=<?= $vd->user->id ?>" 
												class="add-filter-icon" target="_blank"></a>
										</td>
									</tr>
								</tbody>
							</table>
						</section>
					</div>	
					<?php endif ?>			
					
				</div>
			</aside>
			
			<link rel="stylesheet" href="<?= $vd->assets_base ?>lib/bootstrap-datepicker.css" />					
					<script src="<?= $vd->assets_base ?>lib/bootstrap-datepicker.js"></script>
			<script src="<?= $vd->assets_base ?>lib/bootbox.min.js"></script>
			<script src="<?= $vd->assets_base ?>js/required.js?<?= $vd->version ?>"></script>
			<script>
			
			$(function() {
				
				var options = { offset: { top: 20 } };
				$.lockfixed("#locked_aside", options);
				
			});
			
			</script>
					
		</div>
	</div>
</form>