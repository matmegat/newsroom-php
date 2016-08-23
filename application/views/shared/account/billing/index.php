<?php if (count($autoOrders)): ?>
	
<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Subscriptions</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content content-no-tabs">
			
			<table class="grid marbot-20">
				<thead>
					<tr>
						<th class="left">Reference Order</th>
						<th>Package</th>
						<th>Amount</th>
						<th>Last Paid</th>
						<th>Cancel</th>
						<th>View</th>
					</tr>
				</thead>
				<tbody>
					
					<?php foreach ($autoOrders as $order): ?>
					<tr>
						<td class="left">
							<a href="<?= $ci->uri->uri_string() ?>/autoOrder?ultra_id=<?= 
								$vd->esc($order['ultra_id']) ?>"><?= 
								$vd->esc($order['ultra_id']) ?></a>
						</td>
						<td>
							<?php if ($order['package']): ?>
							<?= $vd->esc($order['package']) ?>
							<?php else: ?>
							<span>-</span>
							<?php endif ?>
						</td>
						<td>
							$<?= $vd->esc($order['amount']) ?>
						</td>												
						<td>
							<?= $order['paid_dt']->format('jS M Y') ?>
						</td>
						<td>
							<a href="<?= $ci->uri->uri_string() ?>/autoOrder?ultra_id=<?= 
								$vd->esc($order['ultra_id']) ?>#cancel">Cancel</a>
						</td>
						<td>
							<a href="<?= $ci->uri->uri_string() ?>/autoOrder?ultra_id=<?= 
								$vd->esc($order['ultra_id']) ?>">View</a>
						</td>
					</tr>
					<?php endforeach ?>
					
				</tbody>
			</table>
			
		</div>
	</div>
</div>
			
<?php endif ?>

<div class="row-fluid">
	<div class="span12">
		<header class="page-header">
			<div class="row-fluid">
				<div class="span6">
					<h1>Order History</h1>
				</div>
			</div>
		</header>
	</div>
</div>

<div class="row-fluid">
	<div class="span12">
		<div class="content content-no-tabs">
			
			<table class="grid">
				<thead>
					<tr>
						<th class="left">Order ID</th>
						<th>Card</th>
						<th>Amount</th>
						<th>Paid</th>
						<th>View</th>
					</tr>
				</thead>
				<tbody>
					
					<?php foreach ($dbOrders as $order): ?>
					<tr>
						<td class="left">
							<a href="<?= $ci->uri->uri_string() ?>/dbOrder?id=<?= 
								$vd->esc($order['id']) ?>"><?= 
								$vd->esc($order['ultra_id']) ?></a>
						</td>
						<td>
							<?php if ($order['card_number']): ?>								
								<?= $vd->esc($order['card_type']) ?>
								<span class="muted">xxx</span> 
								<?= $vd->esc($order['card_number']) ?>
							<?php else: ?>
								<?= $vd->esc($order['card_type']) ?>
							<?php endif ?>							
						</td>
						<td>
							$<?= $vd->esc($order['amount']) ?>
						</td>
						<td>
							<?= $order['paid_dt']->format('jS M Y') ?>
						</td>
						<td>
							<a href="<?= $ci->uri->uri_string() ?>/dbOrder?id=<?= 
								$vd->esc($order['id']) ?>">View</a>
						</td>
					</tr>
					<?php endforeach ?>
					
				</tbody>
			</table>

			<div class="grid-report">Note: We only show the last 6 months of billing history.</div>
					
		</div>
	</div>
</div>