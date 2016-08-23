<?php $error_level = error_reporting(E_ALL ^ E_NOTICE); ?>

<style>

.main .container {
	margin: 0;
	padding: 0;
}

table.settings_billing {
	border: none;
	border-collapse: collapse;
	text-align: left;
	width: 100%;
}

table.settings_billing th span {
	border-bottom: 1px dashed #333;
	display: inline-block;
	padding: 0px 3px 4px 3px;
}

table.settings_billing th,
table.settings_billing td {
	padding: 6px 12px;
}

table.settings_billing tr :last-child {
	padding-right: 0px;
}

table.settings_billing tr :first-child {
	padding-left: 0px;
}

table.settings_billing tr.totals td {
	padding: 1px 10px;
}

div.cancel {  
	margin: 10px 20px 20px 20px;
	text-align: right;
}

div.cancel .cancel-hidden {
	padding: 5px 0px;
}

div.cancel .cancel-hidden * {
	vertical-align: middle;
}

div.cancel .cancel-hidden input {
	margin-right: 5px;
	margin-bottom: 0;
	width: 180px;
}

div.cancel .cancel-hidden div {
	color: #900;
	font-size: 14px;
	margin-bottom: 8px;
}

div.cancel .cancel-hidden input:focus,
div.cancel .cancel-hidden input:hover {
	border: 1px solid #aaa;
}

div.code {
	border: 1px dashed #aaa;
	display: block;
	font-family: monospace;
	margin: 20px;
	padding: 20px;
}

</style>

<div class="code">
	Order ID: <?= $vd->esc($order['ultra_id']) ?><br />
	Order Date: <?= date('j M Y H:i:s', $order['paid_ts']) ?><br />
	<?php if (isset($order['is_auto_order'])): ?>
	<?php if ($order['is_active']): ?>
		Last Payment: <?= date('j M Y H:i:s', $order['last_paid_ts']) ?><br />
		Next Payment: <?= date('j M Y H:i:s', $order['next_paid_ts']) ?><br />
		Status: Active [<a class="cancel-button" href="<?= current_url() ?>#cancel">Cancel</a>]<br />
	<?php else: ?>
		Last Payment: <?= date('j M Y H:i:s', $order['last_paid_ts']) ?><br />
		Status: Inactive<br />
	<?php endif ?>
	<?php endif ?>
	<br />
	Bill To<br />
	---------<br />
	Company: <?= $vd->esc($order['bill_to']['company']) ?><br />
	Name: <?= $vd->esc($order['bill_to']['title']) ?> 
		<?= $vd->esc($order['bill_to']['first_name']) ?>
		<?= $vd->esc($order['bill_to']['last_name']) ?><br />
	Address: <?= $vd->esc($order['bill_to']['address1']) ?> 
		<?= $vd->esc($order['bill_to']['address2']) ?><br />
	City: <?= $vd->esc($order['bill_to']['city']) ?><br />
	State: <?= $vd->esc($order['bill_to']['state']) ?><br />
	Zip: <?= $vd->esc($order['bill_to']['zip']) ?><br />
	Country: <?= $vd->esc($order['bill_to']['country']) ?><br />
	<br />
	Card Type: <?= $vd->esc($order['card_type']) ?><br />
	Card Number: <span class="muted">xxxx xxxx xxxx</span>
		<?= substr($vd->esc($order['card_number']), -4) ?><br />
	<br />
	
	<table class="settings_billing">
		<thead>
			<tr>
				<th><span>Description</span></th>
				<th><span>Quantity</span></th>
				<th><span>Amount</span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($order['items'] as $item): ?>
			<tr>
				<td><?= $vd->esc($item['description']) ?></td>
				<td><?= $vd->esc($item['quantity']) ?></td>
				<td>$<?= $vd->esc($item['amount']) ?></td>
			</tr>
			<?php endforeach ?>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr class="totals">
				<td colspan="1"></td>
				<td class="ta-right">Subtotal:</td>
				<td>$<?= $vd->esc($order['subtotal']) ?></td>
			</tr>
			<tr class="totals">
				<td colspan="1"></td>
				<td class="ta-right">Tax:</td>
				<td>$<?= $vd->esc($order['tax']) ?></td>
			</tr>
			<tr class="totals">
				<td colspan="1"></td>
				<td class="ta-right">Total:</td>
				<td>$<?= $vd->esc($order['total']) ?></td>
			</tr>              
		</tbody>
	</table>          
</div>

<?php if (isset($order['is_auto_order']) && $order['is_active']): ?>
<div class="cancel">
	<a name="cancel" />          
	<a class="btn btn-normal cancel-button">Cancel Subscription</a>          
	<div class="cancel-hidden" style="display: none">
		<form action="" method="post">
			<div>Enter account password to cancel order:</div>
			<input type="password" name="password" class="cancel-input" />
			<input type="hidden" name="cancel" value="1" />
			<input type="submit" style="display: none" />
			<a class="cancel-submit btn btn-normal">Cancel</a>
		</form>
	</div>
	<script>
	
	(function($, undefined) {
		
		$(function() {
			
			$("a.cancel-button").click(function() {
				$("div.cancel a.cancel-button").hide();
				$("div.cancel div.cancel-hidden").show();
				$("div.cancel input.cancel-input").focus();
				$("div.cancel a.cancel-submit").click(function() {
					$("div.cancel form").submit();
				});
			});
			
			if (window.location.hash === "#cancel") {
				$("a.cancel-button").click();
			}
			
		});
		
	})(jQuery);          
	
	</script>
</div>
<?php endif ?>

<?php error_reporting($error_level); ?>